<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationSourceToUsersTable extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('registration_source', 'users')) {
            $this->forge->addColumn('users', [
                'registration_source' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 32,
                    'null'       => true,
                    'after'      => 'role',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('registration_source', 'users')) {
            $this->forge->dropColumn('users', 'registration_source');
        }
    }
}

