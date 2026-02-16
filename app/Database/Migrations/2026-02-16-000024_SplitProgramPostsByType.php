<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SplitProgramPostsByType extends Migration
{
    public function up()
    {
        $this->createContentTable('programs');
        $this->createContentTable('articles');
        $this->createContentTable('activities');
        $this->createContentTable('announcements');

        if (! $this->db->tableExists('program_posts')) {
            return;
        }

        $programs = $this->db->table('programs');
        $articles = $this->db->table('articles');
        $activities = $this->db->table('activities');
        $announcements = $this->db->table('announcements');

        $posts = $this->db->table('program_posts')->get()->getResultArray();
        foreach ($posts as $post) {
            $payload = [
                'id' => (int) $post['id'],
                'user_id' => (int) $post['user_id'],
                'title' => (string) $post['title'],
                'post_type' => (string) ($post['post_type'] ?? 'artikel'),
                'slug' => (string) $post['slug'],
                'excerpt' => $post['excerpt'] ?? null,
                'image_path' => $post['image_path'] ?? null,
                'content' => (string) $post['content'],
                'seo_title' => $post['seo_title'] ?? null,
                'seo_description' => $post['seo_description'] ?? null,
                'seo_keywords' => $post['seo_keywords'] ?? null,
                'published_at' => $post['published_at'] ?? null,
                'created_at' => $post['created_at'] ?? null,
                'updated_at' => $post['updated_at'] ?? null,
            ];

            $type = strtolower((string) ($post['post_type'] ?? 'artikel'));

            if ($type === 'program') {
                $payload['post_type'] = 'program';
                $this->insertIfMissing($programs, $payload);
                continue;
            }

            if ($type === 'kegiatan') {
                $payload['post_type'] = 'kegiatan';
                $this->insertIfMissing($activities, $payload);
                continue;
            }

            if ($type === 'pengumuman') {
                $payload['post_type'] = 'pengumuman';
                $this->insertIfMissing($announcements, $payload);
                continue;
            }

            $payload['post_type'] = 'artikel';
            $this->insertIfMissing($articles, $payload);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('announcements')) {
            $this->forge->dropTable('announcements', true);
        }
        if ($this->db->tableExists('activities')) {
            $this->forge->dropTable('activities', true);
        }
        if ($this->db->tableExists('articles')) {
            $this->forge->dropTable('articles', true);
        }
        if ($this->db->tableExists('programs')) {
            $this->forge->dropTable('programs', true);
        }
    }

    private function createContentTable(string $table): void
    {
        if ($this->db->tableExists($table)) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
            ],
            'post_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
            ],
            'excerpt' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'content' => [
                'type' => 'LONGTEXT',
            ],
            'seo_title' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
            ],
            'seo_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'seo_keywords' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable($table);
    }

    private function insertIfMissing(\CodeIgniter\Database\BaseBuilder $table, array $payload): void
    {
        $exists = $table->select('id')->where('id', (int) $payload['id'])->get()->getRowArray();
        if ($exists) {
            return;
        }

        $table->insert($payload);
    }
}
