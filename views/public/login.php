<section style="min-height:calc(100vh - 140px);display:flex;align-items:center;justify-content:center;padding:34px 18px">
    <div class="auth-modal" style="transform:none">
        <div class="auth-top">
            <div class="auth-logo">Masuk ke RubbyBooks</div>
            <div class="auth-tagline">Gunakan akun buyer, seller, atau admin.</div>
        </div>
        <form class="auth-body" method="post">
            <input type="hidden" name="action" value="login">
            <div class="form-group"><label>Email</label><input class="form-input" type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input class="form-input" type="password" name="password" required></div>
            <button class="auth-submit">Login</button>
            <p class="auth-switch">Demo: admin@rubbybooks.test / seller@rubbybooks.test / buyer@rubbybooks.test, password: password</p>
        </form>
    </div>
</section>
