<?php
declare(strict_types=1);

class ProductRepository
{
    public function __construct(private Database $db) {}

    /** @return array<string,mixed>|null */
    public function getById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT p.*, c.nazwa AS kategoria
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? LIMIT 1",
            "i",
            [$id]
        );
    }

    /**
     * @param array<string,mixed> $filters
     *  q: string
     *  category_id: int (includes descendants)
     *  price_min: float
     *  price_max: float
     *  sort: newest|price_asc|price_desc|title
     * @return array<int, array<string,mixed>>
     */
    public function search(array $filters = []): array
    {
        $where = ["p.status = 1"];
        $types = '';
        $params = [];

        if (!empty($filters['q'])) {
            $q = '%' . trim((string)$filters['q']) . '%';
            $where[] = "(p.title LIKE ? OR p.description LIKE ?)";
            $types .= 'ss';
            $params[] = $q;
            $params[] = $q;
        }

        if (!empty($filters['category_ids']) && is_array($filters['category_ids'])) {
            $ids = array_values(array_filter(array_map('intval', $filters['category_ids']), fn($v) => $v >= 0));
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $where[] = "p.category_id IN ($placeholders)";
                $types .= str_repeat('i', count($ids));
                $params = array_merge($params, $ids);
            }
        } elseif (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $cid = (int)$filters['category_id'];
            $where[] = "p.category_id = ?";
            $types .= 'i';
            $params[] = $cid;
        }

        if ($filters['price_min'] ?? '' !== '') {
            $min = (float)$filters['price_min'];
            $where[] = "p.price_netto >= ?";
            $types .= 'd';
            $params[] = $min;
        }
        if ($filters['price_max'] ?? '' !== '') {
            $max = (float)$filters['price_max'];
            $where[] = "p.price_netto <= ?";
            $types .= 'd';
            $params[] = $max;
        }

        $order = "p.id DESC";
        switch ((string)($filters['sort'] ?? 'newest')) {
            case 'price_asc':  $order = "p.price_netto ASC"; break;
            case 'price_desc': $order = "p.price_netto DESC"; break;
            case 'title':      $order = "p.title ASC"; break;
            default:           $order = "p.id DESC";
        }

        $sql = "
            SELECT p.*, c.nazwa AS kategoria
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY {$order}
        ";

        return $this->db->fetchAll($sql, $types, $params);
    }

    /**
     * Admin create
     * @param array<string,mixed> $data
     */
    public function create(array $data): void
    {
        $title = trim((string)($data['title'] ?? ''));
        $desc  = trim((string)($data['description'] ?? ''));
        $price = (float)($data['price_netto'] ?? 0);
        $vat   = (int)($data['vat'] ?? 0);
        $qty   = (int)($data['quantity'] ?? 0);
        $status= (int)($data['status'] ?? 0);
        $catId = (int)($data['category_id'] ?? 0);
        $image = trim((string)($data['image'] ?? ''));

        $this->db->execute(
            "INSERT INTO products (title, description, created_at, price_netto, vat, quantity, status, category_id, image)
             VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?)",
            // title(s), description(s), price(d), vat(i), qty(i), status(i), category_id(i), image(s)
            "ssdiiiis",
            [$title, $desc, $price, $vat, $qty, $status, $catId, $image]
        );
    }

    public function delete(int $id): void
    {
        $this->db->execute("DELETE FROM products WHERE id = ?", "i", [$id]);
    }
}
