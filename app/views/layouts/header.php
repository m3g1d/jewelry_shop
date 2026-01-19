<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= defined('APP_NAME') ? APP_NAME : 'Jewelry Shop' ?>
    </title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>
    <nav>
        <a href="<?= BASE_URL ?>" class="logo">Luxe Jewelry</a>
        <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= BASE_URL ?>/shop">Shop</a></li>
                <?php if (strtolower($_SESSION['role']) == 'admin'): ?>
                    <li><a href="<?= BASE_URL ?>/admin/dashboard">Admin</a></li>
                <?php endif; ?>
                <li><a href="<?= BASE_URL ?>/auth/logout">Logout (
                        <?= htmlspecialchars($_SESSION['name']) ?>)
                    </a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>/auth/login">Login</a></li>
                <li><a href="<?= BASE_URL ?>/auth/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">