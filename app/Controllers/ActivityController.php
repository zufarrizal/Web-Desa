<?php

namespace App\Controllers;

use App\Models\ActivityModel as ProgramPostModel;
use CodeIgniter\I18n\Time;

class ActivityController extends BaseController
{
    private const TYPE = 'kegiatan';
    private const TYPE_LABEL = 'Kegiatan Desa';

    public function index()
    {
        $model = new ProgramPostModel();

        return view('programs/index', [
            'posts' => $model->where('post_type', self::TYPE)->orderBy('id', 'DESC')->findAll(),
            'type' => self::TYPE,
            'typeLabel' => self::TYPE_LABEL,
        ]);
    }

    public function create()
    {
        return view('programs/form', [
            'mode' => 'create',
            'post' => null,
            'type' => self::TYPE,
            'typeLabel' => self::TYPE_LABEL,
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[5]',
            'excerpt' => 'permit_empty|max_length[500]',
            'content' => 'required|min_length[20]',
            'seo_title' => 'permit_empty|max_length[191]',
            'seo_description' => 'permit_empty|max_length[320]',
            'seo_keywords' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ProgramPostModel();
        $title = (string) $this->request->getPost('title');
        $slug = $this->generateSlug($title);
        $imagePath = $this->handleImageUpload();
        if ($imagePath === false) {
            return redirect()->back()->withInput()->with('errors', ['Gambar wajib format JPG/JPEG/PNG/WEBP dengan ukuran maksimal 1 MB.']);
        }

        $model->insert([
            'user_id' => (int) session()->get('user_id'),
            'title' => $title,
            'post_type' => self::TYPE,
            'slug' => $slug,
            'excerpt' => (string) $this->request->getPost('excerpt'),
            'image_path' => $imagePath,
            'content' => (string) $this->request->getPost('content'),
            'seo_title' => (string) $this->request->getPost('seo_title'),
            'seo_description' => (string) $this->request->getPost('seo_description'),
            'seo_keywords' => (string) $this->request->getPost('seo_keywords'),
            'published_at' => Time::now()->toDateTimeString(),
        ]);

        return redirect()->to('/programs/' . self::TYPE)->with('success', self::TYPE_LABEL . ' berhasil dipublikasikan.');
    }

    public function edit(int $id)
    {
        $model = new ProgramPostModel();
        $post = $model->find($id);

        if (! $post) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Data posting tidak ditemukan.');
        }

        if ((string) ($post['post_type'] ?? '') !== self::TYPE) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Jenis posting tidak sesuai.');
        }

        return view('programs/form', [
            'mode' => 'edit',
            'post' => $post,
            'type' => self::TYPE,
            'typeLabel' => self::TYPE_LABEL,
        ]);
    }

    public function update(int $id)
    {
        $model = new ProgramPostModel();
        $post = $model->find($id);

        if (! $post) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Data posting tidak ditemukan.');
        }

        if ((string) ($post['post_type'] ?? '') !== self::TYPE) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Jenis posting tidak sesuai.');
        }

        $rules = [
            'title' => 'required|min_length[5]',
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
        $payload = [
            'title' => $title,
            'slug' => (string) $post['slug'],
            'excerpt' => (string) $this->request->getPost('excerpt'),
            'content' => (string) $this->request->getPost('content'),
            'seo_title' => (string) $this->request->getPost('seo_title'),
            'seo_description' => (string) $this->request->getPost('seo_description'),
            'seo_keywords' => (string) $this->request->getPost('seo_keywords'),
        ];

        if ($title !== (string) $post['title']) {
            $payload['slug'] = $this->generateSlug($title);
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

        $model->update($id, $payload);

        return redirect()->to('/programs/' . self::TYPE)->with('success', self::TYPE_LABEL . ' berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new ProgramPostModel();
        $post = $model->find($id);

        if (! $post) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Data posting tidak ditemukan.');
        }

        if ((string) ($post['post_type'] ?? '') !== self::TYPE) {
            return redirect()->to('/programs/' . self::TYPE)->with('error', 'Jenis posting tidak sesuai.');
        }

        $this->removeImageFile($post['image_path'] ?? null);
        $model->delete($id);

        return redirect()->to('/programs/' . self::TYPE)->with('success', self::TYPE_LABEL . ' berhasil dihapus.');
    }

    private function generateSlug(string $title): string
    {
        helper('url');
        $baseSlug = strtolower(url_title($title, '-', true));
        $slug = $baseSlug;
        $model = new ProgramPostModel();
        $counter = 1;
        while ($model->where('slug', $slug)->first()) {
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
