<div style="max-width:420px;margin:60px auto">
    <div class="card">
        <div class="card-header" style="text-align:center;font-size:1.1rem">
            <i class="fa fa-sign-in-alt"></i> Đăng nhập
        </div>
        <div class="card-body">
            <form method="POST" action="<?= APP_URL ?>/auth/login">
                <div class="form-group">
                    <label for="email"><i class="fa fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="you@example.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fa fa-lock"></i> Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px">
                    <i class="fa fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>

            <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <p style="color:#888;font-size:.9rem">Chưa có tài khoản?
                    <a href="<?= APP_URL ?>/auth/register" style="color:var(--primary);font-weight:600">Đăng ký ngay</a>
                </p>
            </div>

            <!-- Tài khoản demo -->
            <div style="background:#f8f9fa;border-radius:8px;padding:14px;margin-top:16px;font-size:.82rem;color:#555">
                <strong>🔑 Tài khoản demo:</strong><br>
                <span>Admin: admin@shop.com / 123456</span><br>
                <span>User: user@shop.com / 123456</span>
            </div>
        </div>
    </div>
</div>
