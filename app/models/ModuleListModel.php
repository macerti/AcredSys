<?php

class ModuleListModel
{
    public function fetchList(string $table, int $organizationId, int $limit = 25, int $offset = 0): array
    {
        $hasDeletedAt = $this->tableHasColumn($table, 'deleted_at');
        $sql = "SELECT * FROM {$table} WHERE organization_id = :org_id";

        if ($hasDeletedAt) {
            $sql .= ' AND deleted_at IS NULL';
        }

        $sql .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
        $stmt = db()->prepare($sql);
        $stmt->bindValue(':org_id', $organizationId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        $cfg = require __DIR__ . '/../../config/database.php';
        $stmt = db()->prepare('SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :schema_name AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name LIMIT 1');
        $stmt->execute([
            ':schema_name' => $cfg['dbname'],
            ':table_name' => $table,
            ':column_name' => $column,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
