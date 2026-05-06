<?php
// ============================================================
//  controllers/ProductController.php
// ============================================================

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';

class ProductController extends BaseController
{
    private ProductRepository $productRepo;

    public function __construct()
    {
        $db = \Database::getInstance()->getConnection();
        $this->productRepo = new ProductRepository($db);
    }

    // ── GET /  hoặc  /products ───────────────────────────────
    public function index(): void
    {
        $search  = $this->sanitize($this->get('search', ''));
        $catId   = (int)$this->get('cat', 0);
        $page    = max(1, (int)$this->get('page', 1));
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $filters = [
            'search'   => $search,
            'limit'    => $perPage,
            'offset'   => $offset,
            'in_stock' => false,
        ];
        if ($catId > 0) $filters['category_id'] = $catId;

        $products   = $this->productRepo->getAllProducts($filters);
        $total      = $this->productRepo->count(array_diff_key($filters, ['limit'=>0,'offset'=>0]));
        $categories = $this->productRepo->getCategories();
        $pages      = (int)ceil($total / $perPage);

        $this->view('products/index', [
            'products'   => $products,
            'categories' => $categories,
            'search'     => $search,
            'catId'      => $catId,
            'page'       => $page,
            'pages'      => $pages,
            'total'      => $total,
            'flash'      => $this->getFlash(),
            'pageTitle'  => 'Sản phẩm',
        ]);
    }

    // ── GET /products/{id} ───────────────────────────────────
    public function detail(int $id): void
    {
        $product = $this->productRepo->findProductById($id);
        if (!$product) {
            $this->setFlash('error', 'Sản phẩm không tồn tại.');
            $this->redirect('/products');
        }

        // Gợi ý sản phẩm cùng danh mục
        $related = [];
        if ($product->getCategoryId()) {
            $related = $this->productRepo->getAllProducts([
                'category_id' => $product->getCategoryId(),
                'limit'       => 4,
            ]);
            $related = array_filter($related, fn($p) => $p->getId() !== $id);
        }

        $this->view('products/detail', [
            'product'   => $product,
            'related'   => array_values($related),
            'flash'     => $this->getFlash(),
            'pageTitle' => $product->getName(),
        ]);
    }
}
