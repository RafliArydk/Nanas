<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah(int|float $value): string
{
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

function redirect(string $page): never
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_role(string $role): bool
{
    return (current_user()['role'] ?? null) === $role;
}

function require_login(): void
{
    if (!current_user()) {
        flash('Silakan login terlebih dahulu.', 'error');
        redirect('login');
    }
}

function require_role(array|string $roles): void
{
    require_login();
    $roles = (array) $roles;
    if (!in_array(current_user()['role'], $roles, true)) {
        flash('Akses halaman tidak sesuai role akun.', 'error');
        redirect('home');
    }
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name));
    return strtoupper(substr($parts[0] ?? 'U', 0, 1) . substr($parts[1] ?? '', 0, 1));
}

function upload_file(string $field, string $dir): ?string
{
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        return null;
    }
    $name = uniqid('rb_', true) . '.' . $ext;
    $targetDir = __DIR__ . '/../uploads/' . trim($dir, '/');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }
    move_uploaded_file($_FILES[$field]['tmp_name'], $targetDir . '/' . $name);
    return 'uploads/' . trim($dir, '/') . '/' . $name;
}

function shipping_cost(string $city): int
{
    $map = ['jakarta' => 10000, 'bandung' => 15000, 'surabaya' => 20000];
    return $map[strtolower(trim($city))] ?? 25000;
}

function next_invoice(PDO $pdo): string
{
    $prefix = 'INV-' . date('Ymd') . '-';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE invoice_number LIKE ?');
    $stmt->execute([$prefix . '%']);
    return $prefix . str_pad((string) ($stmt->fetchColumn() + 1), 3, '0', STR_PAD_LEFT);
}

function notify_user(PDO $pdo, int $userId, string $message): void
{
    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)');
    $stmt->execute([$userId, $message]);
}

function log_activity(PDO $pdo, string $activity): void
{
    $stmt = $pdo->prepare('INSERT INTO system_logs (activity) VALUES (?)');
    $stmt->execute([$activity]);
}

function cart_count(PDO $pdo): int
{
    if (!is_role('buyer')) {
        return 0;
    }
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(qty),0) FROM carts WHERE buyer_id = ?');
    $stmt->execute([current_user()['id']]);
    return (int) $stmt->fetchColumn();
}
