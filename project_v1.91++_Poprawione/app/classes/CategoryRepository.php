<?php
declare(strict_types=1);

class CategoryRepository
{
    public function __construct(private Database $db) {}

    /** @return array<int, array<string, mixed>> */
    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT id, nazwa, matka FROM categories ORDER BY matka ASC, nazwa ASC");
    }

    /** @return array<int, array<string, mixed>> */
    public function getChildren(int $parentId): array
    {
        return $this->db->fetchAll(
            "SELECT id, nazwa, matka FROM categories WHERE matka = ? ORDER BY nazwa ASC",
            "i",
            [$parentId]
        );
    }

    public function create(string $name, int $parentId): void
    {
        $name = trim($name);
        $parentId = max(0, $parentId);
        if ($name === '') {
            throw new RuntimeException("Pusta nazwa kategorii.");
        }

        // Optional: check parent exists (except root)
        if ($parentId !== 0) {
            $p = $this->db->fetchOne("SELECT id FROM categories WHERE id = ? LIMIT 1", "i", [$parentId]);
            if (!$p) {
                throw new RuntimeException("Nie znaleziono kategorii nadrzędnej.");
            }
        }

        $this->db->execute("INSERT INTO categories (nazwa, matka) VALUES (?, ?)", "si", [$name, $parentId]);
    }

    /**
     * Deletes category and all its descendants (safe for nested trees).
     */
    public function deleteRecursive(int $id): void
    {
        $id = (int)$id;
        if ($id <= 0) return;

        foreach ($this->getChildren($id) as $child) {
            $this->deleteRecursive((int)$child['id']);
        }

        // Products: either move to root or keep FK? safest: set NULL/0 if column allows.
        // Here: set category_id = 0 for products that used this category (works if you allow 0 as "brak").
        $this->db->execute("UPDATE products SET category_id = 0 WHERE category_id = ?", "i", [$id]);

        $this->db->execute("DELETE FROM categories WHERE id = ?", "i", [$id]);
    }

    /** @return array<int, int> */
    public function getDescendantIds(int $parentId): array
    {
        $out = [];
        $this->collectDescendants($parentId, $out);
        return $out;
    }

    /** @param array<int,int> $out */
    private function collectDescendants(int $parentId, array &$out): void
    {
        foreach ($this->getChildren($parentId) as $c) {
            $cid = (int)$c['id'];
            $out[] = $cid;
            $this->collectDescendants($cid, $out);
        }
    }

    /**
     * Returns HTML <option> list with indentation, for a <select>.
     */
    public function renderOptions(int $selectedId = 0, int $parentId = 0, int $depth = 0): string
    {
        $html = '';
        $children = $this->getChildren($parentId);
        foreach ($children as $c) {
            $id = (int)$c['id'];
            $name = (string)$c['nazwa'];
            $pad = str_repeat('— ', $depth);
            $sel = ($id === $selectedId) ? ' selected' : '';
            $html .= "<option value=\"{$id}\"{$sel}>".$pad.htmlspecialchars($name)."</option>";
            $html .= $this->renderOptions($selectedId, $id, $depth + 1);
        }
        return $html;
    }

    public function renderTree(int $parentId = 0): string
    {
        $children = $this->getChildren($parentId);
        if (!$children) return '';

        $html = "<ul>";
        foreach ($children as $c) {
            $id = (int)$c['id'];
            $name = htmlspecialchars((string)$c['nazwa']);
            $html .= "<li>";
            $html .= "<span class='cat-name'>{$name}</span>";
            $html .= "<div class='category-actions'><a class='btn danger' href='?delete={$id}' onclick='return confirm(\'Usunąć kategorię wraz z podkategoriami?\')'>Usuń</a></div>";
            $html .= $this->renderTree($id);
            $html .= "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}
