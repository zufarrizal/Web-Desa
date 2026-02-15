<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagePathToProgramPosts extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('image_path', 'program_posts')) {
            $this->db->query("ALTER TABLE `program_posts` ADD `image_path` VARCHAR(255) NULL AFTER `excerpt`");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('image_path', 'program_posts')) {
            $this->forge->dropColumn('program_posts', 'image_path');
        }
    }
}

