<?php
// ============================================================
//  repositories/ProductRepository.php
// ============================================================

require_once BASE_PATH . '/interfaces/RepositoryInterface.php';
require_once BASE_PATH . '/models/Product.php';

class ProductRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ── Tìm theo ID ─────────────────────────────────────────
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findProductById(int $id): ?Product
    {
        $row = $this->findById($id);
        return $row ? new Product($row) : null;
    }

    // ── Danh sách sản phẩm ──────────────────────────────────
    public function findAll(array $filters = []): array
    {
        $sql    = 'SELECT p.*, c.name AS category_name
                   FROM products p
                   LEFT JOIN categories c ON c.id = p.category_id
                   WHERE 1=1';
        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= ' AND p.category_id = :cat';
            $params['cat'] = $filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (p.name LIKE :q1 OR p.description LIKE :q2)';
            $params['q1'] = '%' . $filters['search'] . '%';
            $params['q2'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['in_stock'])) {
            $sql .= ' AND p.stock > 0';
        }

        $sql .= ' ORDER BY p.id DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= ' OFFSET ' . (int)$filters['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Trả về mảng Product objects */
    public function getAllProducts(array $filters = []): array
    {
        return array_map(
            fn(array $row) => new Product($row),
            $this->findAll($filters)
        );
    }

    // ── CRUD ────────────────────────────────────────────────
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (category_id, name, description, price, stock, image)
             VALUES (:cat, :name, :desc, :price, :stock, :img)'
        );
        $stmt->execute([
            'cat'   => $data['category_id'] ?? null,
            'name'  => $data['name'],
            'desc'  => $data['description'] ?? '',
            'price' => $data['price'],
            'stock' => $data['stock'],
            'img'   => $data['image'] ?? 'default.jpg',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];
        foreach (['name','description','price','stock','image','category_id'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = :$f";
                $params[$f] = $data[$f];
            }
        }
        if (empty($fields)) return false;
        $sql = 'UPDATE products SET ' . implode(', ', $fields) . ' WHERE id = :id';
        return $this->db->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare('DELETE FROM products WHERE id = :id')
                        ->execute(['id' => $id]);
    }

    /** Cập nhật tồn kho sau khi đặt hàng thành công */
    public function decreaseStock(int $productId, int $qty): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET stock = stock - :qty
             WHERE id = :id AND stock >= :qty_check'
        );
        $stmt->execute(['qty' => $qty, 'id' => $productId, 'qty_check' => $qty]);
        return $stmt->rowCount() > 0;
    }

    /** Lấy danh sách categories */
    public function getCategories(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    /** Đếm tổng sản phẩm (phân trang) */
    public function count(array $filters = []): int
    {
        $sql    = 'SELECT COUNT(*) FROM products WHERE 1=1';
        $params = [];
        if (!empty($filters['category_id'])) {
            $sql .= ' AND category_id = :cat';
            $params['cat'] = $filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (name LIKE :q1 OR description LIKE :q2)';
            $params['q1'] = '%' . $filters['search'] . '%';
            $params['q2'] = '%' . $filters['search'] . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
