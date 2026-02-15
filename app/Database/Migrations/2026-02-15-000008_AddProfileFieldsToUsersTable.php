<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'nik' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'email'],
            'birth_place' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'birth_date' => ['type' => 'DATE', 'null' => true],
            'gender' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'religion' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'occupation' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'marital_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'address' => ['type' => 'TEXT', 'null' => true],
            'rt' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'rw' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'village' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'district' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'province' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'citizenship' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'WNI', 'null' => true],
        ];

        foreach ($fields as $name => $definition) {
            if (! $this->db->fieldExists($name, 'users')) {
                $this->forge->addColumn('users', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        $columns = [
            'nik',
            'birth_place',
            'birth_date',
            'gender',
            'religion',
            'occupation',
            'marital_status',
            'address',
            'rt',
            'rw',
            'village',
            'district',
            'city',
            'province',
            'citizenship',
        ];

        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'users')) {
                $this->forge->dropColumn('users', $column);
            }
        }
    }
}
