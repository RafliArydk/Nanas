<?php
declare(strict_types=1);

function route_config(string $page): array
{
    return [
        'home' => ['view' => 'public/home.php'],
        'catalog' => ['view' => 'public/catalog.php'],
        'login' => ['view' => 'public/home.php'],
        'register' => ['view' => 'public/home.php'],
        'buyer' => ['view' => 'pembeli/dashboard.php', 'role' => 'buyer'],
        'buyer_account' => ['view' => 'pembeli/account.php', 'role' => 'buyer'],
        'buyer_wishlist' => ['view' => 'pembeli/wishlist.php', 'role' => 'buyer'],
        'buyer_cart' => ['view' => 'pembeli/cart_page.php', 'role' => 'buyer'],
        'buyer_orders' => ['view' => 'pembeli/orders.php', 'role' => 'buyer'],
        'buyer_reviews' => ['view' => 'pembeli/reviews.php', 'role' => 'buyer'],
        'buyer_notifications' => ['view' => 'pembeli/notifications.php', 'role' => 'buyer'],
        'cart' => ['view' => 'pembeli/cart_page.php'],
        'checkout' => ['view' => 'pembeli/checkout.php'],
        'tracking' => ['view' => 'pembeli/orders.php'],
        'seller' => ['view' => 'penjual/dashboard.php', 'role' => 'seller'],
        'seller_products' => ['view' => 'penjual/products.php', 'role' => 'seller'],
        'seller_orders' => ['view' => 'penjual/orders.php', 'role' => 'seller'],
        'seller_notifications' => ['view' => 'penjual/notifications.php', 'role' => 'seller'],
        'admin' => ['view' => 'admin/dashboard.php', 'role' => 'admin'],
        'admin_users' => ['view' => 'admin/users.php', 'role' => 'admin'],
        'admin_categories' => ['view' => 'admin/categories.php', 'role' => 'admin'],
        'admin_notifications' => ['view' => 'admin/notifications.php', 'role' => 'admin'],
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
    if (in_array($page, ['buyer', 'buyer_account', 'buyer_wishlist', 'buyer_cart', 'buyer_orders', 'buyer_reviews', 'buyer_notifications', 'cart', 'tracking'], true)) {
        $base = ['buyerSidebar' => buyer_sidebar_data($pdo)];
        $uid = current_user()['id'];

        if ($page === 'buyer') {
            $stmt = $pdo->prepare('SELECT * FROM orders WHERE buyer_id=? ORDER BY id DESC LIMIT 5');
            $stmt->execute([$uid]);
            return array_merge($base, [
                'stats' => buyer_stats($pdo),
                'recentOrders' => $stmt->fetchAll(),
            ]);
        }
        if (in_array($page, ['buyer_cart', 'cart'], true)) {
            $stmt = $pdo->prepare('SELECT c.id cart_id,c.qty,p.* FROM carts c JOIN products p ON p.id=c.product_id WHERE c.buyer_id=?');
            $stmt->execute([$uid]);
            return array_merge($base, ['items' => $stmt->fetchAll()]);
        }
        if (in_array($page, ['buyer_orders', 'tracking'], true)) {
            $stmt = $pdo->prepare('SELECT * FROM orders WHERE buyer_id=? ORDER BY id DESC');
            $stmt->execute([$uid]);
            return array_merge($base, ['orders' => $stmt->fetchAll()]);
        }
        if ($page === 'buyer_reviews') {
            $stmt = $pdo->prepare('SELECT r.*, p.name product_name FROM reviews r JOIN products p ON p.id=r.product_id WHERE r.buyer_id=? ORDER BY r.id DESC');
            $stmt->execute([$uid]);
            return array_merge($base, ['reviews' => $stmt->fetchAll()]);
        }
        if ($page === 'buyer_notifications') {
            $stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC');
            $stmt->execute([$uid]);
            return array_merge($base, ['notifications' => $stmt->fetchAll()]);
        }

        return $base;
    }
    if ($page === 'seller') {
        $sellerId = current_user()['id'];
        
        // Total revenue (from completed/paid orders)
        $stmtRev = $pdo->prepare('SELECT COALESCE(SUM(oi.subtotal), 0) as total_revenue FROM order_items oi JOIN products p ON p.id = oi.product_id JOIN orders o ON o.id = oi.order_id WHERE p.seller_id = ? AND o.status IN ("paid", "processing", "shipped", "delivered")');
        $stmtRev->execute([$sellerId]);
        $revenue = $stmtRev->fetch()['total_revenue'];

        // Total orders
        $stmtOrd = $pdo->prepare('SELECT COUNT(DISTINCT oi.order_id) as total_orders FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE p.seller_id = ?');
        $stmtOrd->execute([$sellerId]);
        $totalOrders = $stmtOrd->fetch()['total_orders'];

        // Active products & Low stock
        $stmtProd = $pdo->prepare('SELECT COUNT(*) as active_products FROM products WHERE seller_id = ? AND status = "active"');
        $stmtProd->execute([$sellerId]);
        $activeProducts = $stmtProd->fetch()['active_products'];

        $stmtLowStock = $pdo->prepare('SELECT * FROM products WHERE seller_id = ? AND stock <= 10 AND status = "active" ORDER BY stock ASC LIMIT 5');
        $stmtLowStock->execute([$sellerId]);
        $lowStockProducts = $stmtLowStock->fetchAll();

        // Average rating
        $stmtRating = $pdo->prepare('SELECT COALESCE(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as total_reviews FROM reviews r JOIN products p ON p.id = r.product_id WHERE p.seller_id = ?');
        $stmtRating->execute([$sellerId]);
        $ratingData = $stmtRating->fetch();

        // Recent orders
        $stmtRecent = $pdo->prepare('SELECT DISTINCT o.*, u.name as buyer_name, (SELECT GROUP_CONCAT(p2.name SEPARATOR ", ") FROM order_items oi2 JOIN products p2 ON p2.id = oi2.product_id WHERE oi2.order_id = o.id AND p2.seller_id = ?) as product_names, (SELECT SUM(oi3.subtotal) FROM order_items oi3 JOIN products p3 ON p3.id = oi3.product_id WHERE oi3.order_id = o.id AND p3.seller_id = ?) as seller_total FROM orders o JOIN order_items oi ON oi.order_id = o.id JOIN products p ON p.id = oi.product_id JOIN users u ON u.id = o.buyer_id WHERE p.seller_id = ? ORDER BY o.created_at DESC LIMIT 5');
        $stmtRecent->execute([$sellerId, $sellerId, $sellerId]);
        $recentOrders = $stmtRecent->fetchAll();

        return [
            'revenue' => $revenue,
            'totalOrders' => $totalOrders,
            'activeProducts' => $activeProducts,
            'lowStockProducts' => $lowStockProducts,
            'ratingData' => $ratingData,
            'recentOrders' => $recentOrders
        ];
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
    if ($page === 'seller_notifications') {
        $stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC');
        $stmt->execute([current_user()['id']]);
        return ['notifications' => $stmt->fetchAll()];
    }
    if ($page === 'admin_notifications') {
        $stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC');
        $stmt->execute([current_user()['id']]);
        return ['notifications' => $stmt->fetchAll()];
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
