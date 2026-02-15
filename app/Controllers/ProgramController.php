<?php

namespace App\Controllers;

use App\Models\ProgramPostModel;
use CodeIgniter\I18n\Time;

class ProgramController extends BaseController
{
    private const POST_TYPES = [
        'program'  => 'Program Desa',
        'artikel'  => 'Artikel',
        'kegiatan' => 'Kegiatan Desa',
    ];

    public function index(string $type = 'program')
    {
        $type = $this->normalizeType($type);
        $programModel = new ProgramPostModel();

        return view('programs/index', [
            'posts'     => $programModel->where('post_type', $type)->orderBy('id', 'DESC')->findAll(),
            'type'      => $type,
            'typeLabel' => self::POST_TYPES[$type],
        ]);
    }

    public function create(string $type = 'program')
    {
        $type = $this->normalizeType($type);
        return view('programs/form', [
            'mode'      => 'create',
            'post'      => null,
            'type'      => $type,
            'typeLabel' => self::POST_TYPES[$type],
        ]);
    }

    public function store(string $type = 'program')
    {
        $type = $this->normalizeType($type);
        $rules = [
            'title'   => 'required|min_length[5]',
            'excerpt' => 'permit_empty|max_length[500]',
            'content' => 'required|min_length[20]',
            'seo_title' => 'permit_empty|max_length[191]',
            'seo_description' => 'permit_empty|max_length[320]',
            'seo_keywords' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $programModel = new ProgramPostModel();
        $title        = (string) $this->request->getPost('title');
        $slug         = $this->generateSlug($title);
        $imagePath    = $this->handleImageUpload();
        if ($imagePath === false) {
            return redirect()->back()->withInput()->with('errors', ['Gambar wajib format JPG/JPEG/PNG/WEBP dengan ukuran maksimal 1 MB.']);
        }

        $programModel->insert([
            'user_id'      => (int) session()->get('user_id'),
            'title'        => $title,
            'post_type'    => $type,
            'slug'         => $slug,
            'excerpt'      => (string) $this->request->getPost('excerpt'),
            'image_path'   => $imagePath,
            'content'      => (string) $this->request->getPost('content'),
            'seo_title'       => (string) $this->request->getPost('seo_title'),
            'seo_description' => (string) $this->request->getPost('seo_description'),
            'seo_keywords'    => (string) $this->request->getPost('seo_keywords'),
            'published_at' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to('/programs/' . $type)->with('success', self::POST_TYPES[$type] . ' berhasil dipublikasikan.');
    }

    public function edit(int $id)
    {
        $programModel = new ProgramPostModel();
        $post         = $programModel->find($id);

        if (! $post) {
            return redirect()->to('/programs')->with('error', 'Data program tidak ditemukan.');
        }

        return view('programs/form', [
            'mode'      => 'edit',
            'post'      => $post,
            'type'      => $this->normalizeType((string) ($post['post_type'] ?? 'program')),
            'typeLabel' => self::POST_TYPES[$this->normalizeType((string) ($post['post_type'] ?? 'program'))],
        ]);
    }

    public function update(int $id)
    {
        $programModel = new ProgramPostModel();
        $post         = $programModel->find($id);

        if (! $post) {
            return redirect()->to('/programs')->with('error', 'Data program tidak ditemukan.');
        }

        $rules = [
            'title'   => 'required|min_length[5]',
            'excerpt' => 'permit_empty|max_length[500]',
            'content' => 'required|min_length[20]',
            'seo_title' => 'permit_empty|max_length[191]',
            'seo_description' => 'permit_empty|max_length[320]',
            'seo_keywords' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = (string) $this->request->getPost('title');
        $slug  = $post['slug'];
        $postType = $this->normalizeType((string) ($post['post_type'] ?? 'program'));
        $payload = [
            'title'   => $title,
            'slug'    => $slug,
            'excerpt' => (string) $this->request->getPost('excerpt'),
            'content' => (string) $this->request->getPost('content'),
            'seo_title'       => (string) $this->request->getPost('seo_title'),
            'seo_description' => (string) $this->request->getPost('seo_description'),
            'seo_keywords'    => (string) $this->request->getPost('seo_keywords'),
        ];

        if ($title !== $post['title']) {
            $slug = $this->generateSlug($title);
            $payload['slug'] = $slug;
        }

        $newImagePath = $this->handleImageUpload();
        if ($newImagePath === false) {
            return redirect()->back()->withInput()->with('errors', ['Gambar wajib format JPG/JPEG/PNG/WEBP dengan ukuran maksimal 1 MB.']);
        }

        if ($newImagePath !== null) {
            $payload['image_path'] = $newImagePath;
            $this->removeImageFile($post['image_path'] ?? null);
        }

        if ($this->request->getPost('remove_image') === '1') {
            $payload['image_path'] = null;
            $this->removeImageFile($post['image_path'] ?? null);
        }

        $programModel->update($id, $payload);

        return redirect()->to('/programs/' . $postType)->with('success', self::POST_TYPES[$postType] . ' berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $programModel = new ProgramPostModel();
        $post         = $programModel->find($id);

        if (! $post) {
            return redirect()->to('/programs')->with('error', 'Data program tidak ditemukan.');
        }

        $this->removeImageFile($post['image_path'] ?? null);
        $postType = $this->normalizeType((string) ($post['post_type'] ?? 'program'));
        $programModel->delete($id);

        return redirect()->to('/programs/' . $postType)->with('success', self::POST_TYPES[$postType] . ' berhasil dihapus.');
    }

    private function normalizeType(string $type): string
    {
        $type = strtolower(trim($type));
        return array_key_exists($type, self::POST_TYPES) ? $type : 'program';
    }

    private function generateSlug(string $title): string
    {
        helper('url');
        $baseSlug = strtolower(url_title($title, '-', true));
        $slug     = $baseSlug;

        $programModel = new ProgramPostModel();
        $counter      = 1;
        while ($programModel->where('slug', $slug)->first()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function handleImageUpload()
    {
        $file = $this->request->getFile('image');
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $file->isValid() || $file->hasMoved()) {
            return false;
        }

        $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (! in_array((string) $file->getMimeType(), $allowedMime, true)) {
            return false;
        }

        if ((int) $file->getSizeByUnit('kb') > 1024) {
            return false;
        }

        $dir = FCPATH . 'uploads/programs';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower((string) $file->getExtension());
        $newName = 'program-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $file->move($dir, $newName, true);

        return 'uploads/programs/' . $newName;
    }

    private function removeImageFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $full = FCPATH . ltrim($path, '/\\');
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
