<?php

namespace App\Controllers;

use App\Models\ComplaintModel;

class ComplaintController extends BaseController
{
    private const MAX_IMAGE_BYTES = 1048576; // 1MB

    public function index()
    {
        $role   = (string) session()->get('user_role');
        $userId = (int) session()->get('user_id');
        $model  = new ComplaintModel();

        $builder = $model->select('complaints.*, users.name as user_name')
            ->join('users', 'users.id = complaints.user_id', 'left')
            ->orderBy(
                "CASE complaints.status
                    WHEN 'baru' THEN 1
                    WHEN 'ditindaklanjuti' THEN 2
                    WHEN 'selesai' THEN 3
                    WHEN 'ditolak' THEN 4
                    ELSE 99
                END",
                '',
                false
            )
            ->orderBy('complaints.created_at', 'ASC')
            ->orderBy('complaints.id', 'ASC');

        if ($role !== 'admin') {
            $builder->where('complaints.user_id', $userId);
        }

        return view('complaints/index', [
            'complaints' => $builder->findAll(),
            'role'       => $role,
        ]);
    }

    public function create()
    {
        return view('complaints/form', [
            'mode'      => 'create',
            'complaint' => null,
            'role'      => (string) session()->get('user_role'),
        ]);
    }

    public function store()
    {
        $rules = [
            'title'    => 'required|min_length[5]',
            'content'  => 'required|min_length[10]',
            'location' => 'permit_empty|max_length[191]',
            'image'    => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5120]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageResult = $this->handleUploadedImage(null);
        if (isset($imageResult['error'])) {
            return redirect()->back()->withInput()->with('error', $imageResult['error']);
        }

        $model = new ComplaintModel();
        $model->insert([
            'user_id'   => (int) session()->get('user_id'),
            'title'     => (string) $this->request->getPost('title'),
            'content'   => (string) $this->request->getPost('content'),
            'location'  => (string) $this->request->getPost('location'),
            'image_path'=> $imageResult['image_path'] ?? null,
            'status'    => 'baru',
            'response'  => null,
            'created_at'=> date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function edit(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        return view('complaints/form', [
            'mode'      => 'edit',
            'complaint' => $complaint,
            'role'      => (string) session()->get('user_role'),
        ]);
    }

    public function update(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        $role = (string) session()->get('user_role');
        $rules = [
            'title'    => 'required|min_length[5]',
            'content'  => 'required|min_length[10]',
            'location' => 'permit_empty|max_length[191]',
            'image'    => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5120]',
        ];

        if ($role === 'admin') {
            $rules['status'] = 'required|in_list[baru,ditindaklanjuti,selesai,ditolak]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageResult = $this->handleUploadedImage($complaint['image_path'] ?? null);
        if (isset($imageResult['error'])) {
            return redirect()->back()->withInput()->with('error', $imageResult['error']);
        }

        $payload = [
            'title'    => (string) $this->request->getPost('title'),
            'content'  => (string) $this->request->getPost('content'),
            'location' => (string) $this->request->getPost('location'),
        ];
        if (array_key_exists('image_path', $imageResult)) {
            $payload['image_path'] = $imageResult['image_path'];
        }

        if ($role === 'admin') {
            $payload['status']   = (string) $this->request->getPost('status');
            $payload['response'] = (string) $this->request->getPost('response');
        }

        $model = new ComplaintModel();
        $model->update($id, $payload);

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $complaint = $this->findAuthorizedComplaint($id);
        if (! $complaint) {
            return redirect()->to('/complaints')->with('error', 'Data tidak ditemukan atau tidak diizinkan.');
        }

        $model = new ComplaintModel();
        $model->delete($id);
        $this->removeImageFile((string) ($complaint['image_path'] ?? ''));

        return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil dihapus.');
    }

    private function findAuthorizedComplaint(int $id): ?array
    {
        $model     = new ComplaintModel();
        $complaint = $model->find($id);

        if (! $complaint) {
            return null;
        }

        if ((string) session()->get('user_role') !== 'admin' && (int) $complaint['user_id'] !== (int) session()->get('user_id')) {
            return null;
        }

        return $complaint;
    }

    private function handleUploadedImage(?string $oldImagePath): array
    {
        $file = $this->request->getFile('image');
        if (! $file) {
            return [];
        }

        $errorCode = $file->getError();
        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            return [];
        }
        if ($errorCode !== UPLOAD_ERR_OK) {
            return ['error' => $this->uploadErrorMessage($errorCode)];
        }

        $size = (int) $file->getSize();
        if ($size <= self::MAX_IMAGE_BYTES) {
            $saved = $this->saveOriginalUploadedImage($file, $oldImagePath);
            if (isset($saved['error'])) {
                return $saved;
            }

            return ['image_path' => $saved['image_path']];
        }

        if (! extension_loaded('gd')) {
            return ['error' => 'File di atas 1MB perlu kompresi, tetapi ekstensi GD PHP belum aktif. Aktifkan GD atau upload gambar <= 1MB.'];
        }

        $uploadDir = FCPATH . 'uploads/complaints';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newName = 'complaint-' . time() . '-' . bin2hex(random_bytes(4)) . '.jpg';
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;
        $tmpPath = $file->getTempName();

        if (! $this->compressToJpegUnderLimit($tmpPath, $targetPath, self::MAX_IMAGE_BYTES)) {
            return ['error' => 'Gagal memproses gambar. Pastikan file valid (JPG/PNG/WEBP).'];
        }

        if (is_file($targetPath) && filesize($targetPath) > self::MAX_IMAGE_BYTES) {
            @unlink($targetPath);
            return ['error' => 'Ukuran gambar setelah kompres masih di atas 1MB. Gunakan gambar yang lebih kecil.'];
        }

        if ($oldImagePath) {
            $this->removeImageFile($oldImagePath);
        }

        return ['image_path' => 'uploads/complaints/' . $newName];
    }

    private function saveOriginalUploadedImage($file, ?string $oldImagePath): array
    {
        $uploadDir = FCPATH . 'uploads/complaints';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower((string) $file->getClientExtension());
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = 'jpg';
        }

        $newName = 'complaint-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (! $file->move($uploadDir, $newName, true)) {
            return ['error' => 'Gagal menyimpan file gambar ke server.'];
        }

        if ($oldImagePath) {
            $this->removeImageFile($oldImagePath);
        }

        return ['image_path' => 'uploads/complaints/' . $newName];
    }

    private function compressToJpegUnderLimit(string $sourcePath, string $targetPath, int $maxBytes): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        $info = @getimagesize($sourcePath);
        if (! is_array($info) || ! isset($info[2])) {
            return false;
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $src) {
            return false;
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        if ($srcW < 1 || $srcH < 1) {
            imagedestroy($src);
            return false;
        }

        $maxDim = 1800;
        $scale = min(1, $maxDim / max($srcW, $srcH));
        $dstW = max(1, (int) round($srcW * $scale));
        $dstH = max(1, (int) round($srcH * $scale));

        $canvas = imagecreatetruecolor($dstW, $dstH);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
        imagedestroy($src);

        $quality = 85;
        $saved = false;

        while ($quality >= 35) {
            @imagejpeg($canvas, $targetPath, $quality);
            if (is_file($targetPath) && filesize($targetPath) <= $maxBytes) {
                $saved = true;
                break;
            }
            $quality -= 5;
        }

        if (! $saved) {
            @imagejpeg($canvas, $targetPath, 35);
        }

        imagedestroy($canvas);
        return is_file($targetPath);
    }

    private function removeImageFile(string $imagePath): void
    {
        if ($imagePath === '') {
            return;
        }

        $fullPath = FCPATH . ltrim($imagePath, '/\\');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE =>
                'Ukuran file melampaui batas server. Naikkan upload_max_filesize/post_max_size atau upload file lebih kecil.',
            UPLOAD_ERR_PARTIAL =>
                'Upload gambar tidak selesai. Coba ulangi.',
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE, UPLOAD_ERR_EXTENSION =>
                'Server tidak dapat memproses upload gambar saat ini.',
            default =>
                'Terjadi masalah saat upload gambar.',
        };
    }
}
