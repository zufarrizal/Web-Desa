<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoKkToUsersTable extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('no_kk', 'users')) {
            $this->forge->addColumn('users', [
                'no_kk' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'null' => true,
                    'after' => 'email',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('no_kk', 'users')) {
            $this->forge->dropColumn('users', 'no_kk');
        }
    }
}
