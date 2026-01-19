<?php require_once '../app/views/layouts/header.php'; ?>

<div class="auth-form">
    <h2>Login</h2>
    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger">
            <?= $data['error'] ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/auth/login" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-block">Login</button>
    </form>
    <p style="margin-top: 10px; text-align: center;">
        Don't have an account? <a href="<?= BASE_URL ?>/auth/register">Register</a>
    </p>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>