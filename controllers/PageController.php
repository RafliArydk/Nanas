<?php
declare(strict_types=1);

function route_config(string $page): array
{
    return [
        'home' => ['view' => 'public/home.php'],
        'catalog' => ['view' => 'public/catalog.php'],
        'login' => ['view' => 'public/login.php'],
        'register' => ['view' => 'public/register.php'],
        'cart' => ['view' => 'pembeli/cart.php', 'role' => 'buyer'],
        'checkout' => ['view' => 'pembeli/checkout.php', 'role' => 'buyer'],
        'tracking' => ['view' => 'pembeli/tracking.php', 'role' => 'buyer'],
        'seller' => ['view' => 'penjual/dashboard.php', 'role' => 'seller'],
        'seller_products' => ['view' => 'penjual/products.php', 'role' => 'seller'],
        'seller_orders' => ['view' => 'penjual/orders.php', 'role' => 'seller'],
        'admin' => ['view' => 'admin/dashboard.php', 'role' => 'admin'],
        'admin_users' => ['view' => 'admin/users.php', 'role' => 'admin'],
        'admin_categories' => ['view' => 'admin/categories.php', 'role' => 'admin'],
    ][$page] ?? ['view' => 'errors/404.php', 'status' => 404];
}

function page_data(PDO $pdo, string $page): array
{
    if ($page === 'home') {
        return ['products' => $pdo->query('SELECT p.*, c.name category FROM products p JOIN categories c ON c.id=p.category_id WHERE p.status="active" ORDER BY p.id DESC LIMIT 4')->fetchAll()];
    }
    if ($page === 'catalog') {
        $q = '%' . ($_GET['q'] ?? '') . '%';
        $cat = (int) ($_GET['category'] ?? 0);
        $sql = 'SELECT p.*, c.name category FROM products p JOIN categories c ON c.id=p.category_id WHERE p.status="active" AND p.name LIKE ?';
        $params = [$q];
        if ($cat) {
            $sql .= ' AND p.category_id=?';
            $params[] = $cat;
        }
        $stmt = $pdo->prepare($sql . ' ORDER BY p.created_at DESC');
        $stmt->execute($params);
        return ['products' => $stmt->fetchAll(), 'categories' => $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll(), 'cat' => $cat];
    }
    if ($page === 'cart') {
        $stmt = $pdo->prepare('SELECT c.id cart_id,c.qty,p.* FROM carts c JOIN products p ON p.id=c.product_id WHERE c.buyer_id=?');
        $stmt->execute([current_user()['id']]);
        return ['items' => $stmt->fetchAll()];
    }
    if ($page === 'tracking') {
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE buyer_id=? ORDER BY id DESC');
        $stmt->execute([current_user()['id']]);
        return ['orders' => $stmt->fetchAll()];
    }
    if ($page === 'seller') {
        $stmt = $pdo->prepare('SELECT COUNT(*) products, COALESCE(SUM(stock),0) stock FROM products WHERE seller_id=?');
        $stmt->execute([current_user()['id']]);
        return ['stats' => $stmt->fetch()];
    }
    if ($page === 'seller_products') {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id=? ORDER BY id DESC');
        $stmt->execute([current_user()['id']]);
        return ['products' => $stmt->fetchAll(), 'categories' => $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll()];
    }
    if ($page === 'seller_orders') {
        $stmt = $pdo->prepare('SELECT DISTINCT o.* FROM orders o JOIN order_items oi ON oi.order_id=o.id JOIN products p ON p.id=oi.product_id WHERE p.seller_id=? ORDER BY o.id DESC');
        $stmt->execute([current_user()['id']]);
        return ['orders' => $stmt->fetchAll()];
    }
    if ($page === 'admin') {
        return ['stats' => $pdo->query("SELECT (SELECT COUNT(*) FROM users) users,(SELECT COUNT(*) FROM users WHERE role='seller') sellers,(SELECT COUNT(*) FROM orders) orders,(SELECT COALESCE(SUM(total),0) FROM orders) revenue")->fetch()];
    }
    if ($page === 'admin_users') {
        return ['users' => $pdo->query('SELECT * FROM users ORDER BY id DESC')->fetchAll()];
    }
    if ($page === 'admin_categories') {
        return ['categories' => $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll()];
    }
    return [];
}

function render_view(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/' . $view;
}
