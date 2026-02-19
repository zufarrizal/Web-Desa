<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\AnnouncementModel;
use App\Models\ArticleModel;
use App\Models\LetterSettingModel;
use App\Models\ProgramModel;

class Home extends BaseController
{
    public function index(): string
    {
        $cache = cache();
        $cacheKey = 'public_home_html_v1';
        $cachedHtml = $cache->get($cacheKey);
        if (is_string($cachedHtml) && $cachedHtml !== '') {
            return $cachedHtml;
        }

        $programModel = new ProgramModel();
        $articleModel = new ArticleModel();
        $activityModel = new ActivityModel();
        $announcementModel = new AnnouncementModel();
        $settingModel = new LetterSettingModel();
        $programs = $programModel->orderBy('published_at', 'DESC')->findAll();
        $articles = $articleModel->orderBy('published_at', 'DESC')->findAll();
        $activities = $activityModel->orderBy('published_at', 'DESC')->findAll();
        $announcements = $announcementModel->orderBy('published_at', 'DESC')->findAll();
        $posts = array_merge($programs, $articles, $activities, $announcements);
        usort($posts, static function (array $a, array $b): int {
            $left = strtotime((string) ($a['published_at'] ?? '1970-01-01 00:00:00'));
            $right = strtotime((string) ($b['published_at'] ?? '1970-01-01 00:00:00'));
            return $right <=> $left;
        });
        $setting      = $settingModel->first() ?: [];
        $villageName  = trim((string) ($setting['village_name'] ?? ''));

        $html = view('public/home', [
            'posts'      => $posts,
            'programs'   => $programs,
            'articles'   => $articles,
            'activities' => $activities,
            'announcements' => $announcements,
            'setting'    => $setting,
            'villageName'=> $villageName !== '' ? $villageName : 'Desa',
        ]);

        // Cache render homepage for 3 minutes to reduce first-hit load.
        $cache->save($cacheKey, $html, 180);
        return $html;
    }

    public function posts(): string
    {
        $type         = (string) ($this->request->getGet('type') ?? '');

        if ($type === 'program') {
            $posts = (new ProgramModel())->orderBy('published_at', 'DESC')->findAll();
        } elseif ($type === 'artikel') {
            $posts = (new ArticleModel())->orderBy('published_at', 'DESC')->findAll();
        } elseif ($type === 'kegiatan') {
            $posts = (new ActivityModel())->orderBy('published_at', 'DESC')->findAll();
        } elseif ($type === 'pengumuman') {
            $posts = (new AnnouncementModel())->orderBy('published_at', 'DESC')->findAll();
        } else {
            $posts = array_merge(
                (new ProgramModel())->orderBy('published_at', 'DESC')->findAll(),
                (new ArticleModel())->orderBy('published_at', 'DESC')->findAll(),
                (new ActivityModel())->orderBy('published_at', 'DESC')->findAll(),
                (new AnnouncementModel())->orderBy('published_at', 'DESC')->findAll()
            );
            usort($posts, static function (array $a, array $b): int {
                $left = strtotime((string) ($a['published_at'] ?? '1970-01-01 00:00:00'));
                $right = strtotime((string) ($b['published_at'] ?? '1970-01-01 00:00:00'));
                return $right <=> $left;
            });
        }

        return view('public/posts', [
            'posts' => $posts,
            'type'  => $type,
        ]);
    }

    public function show(string $slug): string
    {
        $post = (new ProgramModel())->where('slug', $slug)->first();
        if (! $post) {
            $post = (new ArticleModel())->where('slug', $slug)->first();
        }
        if (! $post) {
            $post = (new ActivityModel())->where('slug', $slug)->first();
        }
        if (! $post) {
            $post = (new AnnouncementModel())->where('slug', $slug)->first();
        }

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Program tidak ditemukan.');
        }

        return view('public/post', [
            'post' => $post,
        ]);
    }

    public function sitemapXml()
    {
        $posts = array_merge(
            (new ProgramModel())->select('slug, updated_at, published_at')->orderBy('published_at', 'DESC')->findAll(),
            (new ArticleModel())->select('slug, updated_at, published_at')->orderBy('published_at', 'DESC')->findAll(),
            (new ActivityModel())->select('slug, updated_at, published_at')->orderBy('published_at', 'DESC')->findAll(),
            (new AnnouncementModel())->select('slug, updated_at, published_at')->orderBy('published_at', 'DESC')->findAll()
        );
        usort($posts, static function (array $a, array $b): int {
            $left = strtotime((string) ($a['published_at'] ?? '1970-01-01 00:00:00'));
            $right = strtotime((string) ($b['published_at'] ?? '1970-01-01 00:00:00'));
            return $right <=> $left;
        });

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
