<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        $this->addIndexIfPossible('users', 'idx_users_role_regsrc_created', ['role', 'registration_source', 'created_at']);
        $this->addIndexIfPossible('document_requests', 'idx_docreq_status_created_user', ['status', 'created_at', 'user_id']);
        $this->addIndexIfPossible('complaints', 'idx_complaints_status_created_user', ['status', 'created_at', 'user_id']);
    }

    public function down()
    {
        $this->dropIndexIfExists('users', 'idx_users_role_regsrc_created');
        $this->dropIndexIfExists('document_requests', 'idx_docreq_status_created_user');
        $this->dropIndexIfExists('complaints', 'idx_complaints_status_created_user');
    }

    private function addIndexIfPossible(string $table, string $indexName, array $columns): void
    {
        if (! $this->db->tableExists($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! $this->db->fieldExists($column, $table)) {
                return;
            }
        }

        if ($this->indexExists($table, $indexName)) {
            return;
        }

        $safeColumns = implode(', ', array_map(static fn ($column): string => '`' . $column . '`', $columns));
        $this->db->query("CREATE INDEX `{$indexName}` ON `{$table}` ({$safeColumns})");
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! $this->db->tableExists($table)) {
            return;
        }

        if (! $this->indexExists($table, $indexName)) {
            return;
        }

        $this->db->query("DROP INDEX `{$indexName}` ON `{$table}`");
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $database = (string) $this->db->getDatabase();
        $builder = $this->db->table('information_schema.statistics');
        $row = $builder
            ->select('INDEX_NAME')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->get()
            ->getRowArray();

        return is_array($row) && $row !== [];
    }
}

