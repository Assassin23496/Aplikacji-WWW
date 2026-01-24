<?php
declare(strict_types=1);

class PageRepository
{
    public function __construct(private Database $db) {}

    /** @return array<string,mixed>|null */
    public function getActiveById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM page_list WHERE id = ? AND status = 1 LIMIT 1",
            "i",
            [$id]
        );
    }

    /** @return array<string,mixed>|null */
    public function getById(int $id): ?array
    {
        return $this->db->fetchOne("SELECT * FROM page_list WHERE id = ? LIMIT 1", "i", [$id]);
    }

    public function update(int $id, string $title, string $content): void
    {
        $this->db->execute(
            "UPDATE page_list SET page_title = ?, page_content = ? WHERE id = ?",
            "ssi",
            [trim($title), $content, $id]
        );
    }

    public function updateStatus(int $id, int $status): void
    {
        $this->db->execute(
            "UPDATE page_list SET status = ? WHERE id = ?",
            "ii",
            [($status === 1) ? 1 : 0, $id]
        );
    }

    public function create(string $title, string $content, int $status = 1): void
    {
        $this->db->execute(
            "INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)",
            "ssi",
            [trim($title), $content, ($status === 1) ? 1 : 0]
        );
    }

    public function delete(int $id): void
    {
        $this->db->execute("DELETE FROM page_list WHERE id = ?", "i", [$id]);
    }

    /** @return array<int,array<string,mixed>> */
    public function listAll(): array
    {
        return $this->db->fetchAll("SELECT id, page_title, status FROM page_list ORDER BY id ASC");
    }
}
