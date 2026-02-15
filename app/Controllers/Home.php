<?php

namespace App\Controllers;

use App\Models\ProgramPostModel;

class Home extends BaseController
{
    public function index(): string
    {
        $programModel = new ProgramPostModel();
        $posts        = $programModel->orderBy('published_at', 'DESC')->findAll();
        $programs     = array_values(array_filter($posts, static fn (array $item): bool => ($item['post_type'] ?? '') === 'program'));
        $articles     = array_values(array_filter($posts, static fn (array $item): bool => ($item['post_type'] ?? 'artikel') === 'artikel'));
        $activities   = array_values(array_filter($posts, static fn (array $item): bool => ($item['post_type'] ?? '') === 'kegiatan'));

        return view('public/home', [
            'posts'      => $posts,
            'programs'   => $programs,
            'articles'   => $articles,
            'activities' => $activities,
        ]);
    }

    public function posts(): string
    {
        $programModel = new ProgramPostModel();
        $type         = (string) ($this->request->getGet('type') ?? '');
        $builder      = $programModel->orderBy('published_at', 'DESC');
        if (in_array($type, ['program', 'artikel', 'kegiatan'], true)) {
            $builder->where('post_type', $type);
        }

        return view('public/posts', [
            'posts' => $builder->findAll(),
            'type'  => $type,
        ]);
    }

    public function show(string $slug): string
    {
        $programModel = new ProgramPostModel();
        $post         = $programModel->where('slug', $slug)->first();

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Program tidak ditemukan.');
        }

        return view('public/post', [
            'post' => $post,
        ]);
    }

    public function sitemapXml()
    {
        $programModel = new ProgramPostModel();
        $posts        = $programModel->select('slug, updated_at, published_at')->orderBy('published_at', 'DESC')->findAll();

        return response()
            ->setContentType('application/xml')
            ->setBody(view('public/sitemap', ['posts' => $posts]));
    }

    public function robotsTxt()
    {
        $content = "User-agent: *\nAllow: /\nSitemap: " . site_url('sitemap.xml') . "\n";
        return response()->setContentType('text/plain')->setBody($content);
    }
}
