<?php
declare(strict_types=1);

class Database
{
    private mysqli $conn;

    public function __construct(string $host, string $user, string $pass, string $name, string $charset = 'utf8')
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conn = new mysqli($host, $user, $pass, $name);
        $this->conn->set_charset($charset);
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }

    /** @return array<int, array<string, mixed>> */
    public function fetchAll(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->prepareAndBind($sql, $types, $params);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    /** @return array<string, mixed>|null */
    public function fetchOne(string $sql, string $types = '', array $params = []): ?array
    {
        $rows = $this->fetchAll($sql, $types, $params);
        return $rows[0] ?? null;
    }

    public function execute(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->prepareAndBind($sql, $types, $params);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function lastInsertId(): int
    {
        return (int)$this->conn->insert_id;
    }

    private function prepareAndBind(string $sql, string $types, array $params): mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if ($types !== '' && !empty($params)) {
            // PHP < 8.1 needs references for bind_param
            $refs = [];
            foreach ($params as $k => $v) {
                $refs[$k] = &$params[$k];
            }
            $stmt->bind_param($types, ...$refs);
        }
        return $stmt;
    }
}
