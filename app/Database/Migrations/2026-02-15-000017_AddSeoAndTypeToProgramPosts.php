<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSeoAndTypeToProgramPosts extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('post_type', 'program_posts')) {
            $this->db->query("ALTER TABLE `program_posts` ADD `post_type` VARCHAR(20) NOT NULL DEFAULT 'artikel' AFTER `title`");
        }

        if (! $this->db->fieldExists('seo_title', 'program_posts')) {
            $this->db->query("ALTER TABLE `program_posts` ADD `seo_title` VARCHAR(191) NULL AFTER `content`");
        }

        if (! $this->db->fieldExists('seo_description', 'program_posts')) {
            $this->db->query("ALTER TABLE `program_posts` ADD `seo_description` TEXT NULL AFTER `seo_title`");
        }

        if (! $this->db->fieldExists('seo_keywords', 'program_posts')) {
            $this->db->query("ALTER TABLE `program_posts` ADD `seo_keywords` VARCHAR(255) NULL AFTER `seo_description`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('seo_keywords', 'program_posts')) {
            $this->forge->dropColumn('program_posts', 'seo_keywords');
        }
        if ($this->db->fieldExists('seo_description', 'program_posts')) {
            $this->forge->dropColumn('program_posts', 'seo_description');
        }
        if ($this->db->fieldExists('seo_title', 'program_posts')) {
            $this->forge->dropColumn('program_posts', 'seo_title');
        }
        if ($this->db->fieldExists('post_type', 'program_posts')) {
            $this->forge->dropColumn('program_posts', 'post_type');
        }
    }
}

