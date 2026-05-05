<div style="max-width:440px;margin:60px auto">
    <div class="card">
        <div class="card-header" style="text-align:center;font-size:1.1rem">
            <i class="fa fa-user-plus"></i> Đăng ký tài khoản
        </div>
        <div class="card-body">
            <form method="POST" action="<?= APP_URL ?>/auth/register">
                <div class="form-group">
                    <label for="name"><i class="fa fa-user"></i> Họ và tên</label>
                    <input type="text" id="name" name="name" class="form-control"
                           placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fa fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fa fa-lock"></i> Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Ít nhất 6 ký tự" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_confirm"><i class="fa fa-lock"></i> Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                           placeholder="Nhập lại mật khẩu" required>
                </div>
                <button type="submit" class="btn btn-success btn-block" style="margin-top:8px">
                    <i class="fa fa-user-plus"></i> Tạo tài khoản
                </button>
            </form>
            <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <p style="color:#888;font-size:.9rem">Đã có tài khoản?
                    <a href="<?= APP_URL ?>/auth/login" style="color:var(--primary);font-weight:600">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
