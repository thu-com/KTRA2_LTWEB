</div><!-- /.container -->

<footer style="
    background: var(--primary-dark, #2a4f33);
    color: #b8d4b8;
    margin-top: 60px;
    font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif;
">
    <div style="max-width:1240px; margin:0 auto; display:grid; grid-template-columns:2fr 1fr 1fr 1.5fr; gap:48px; padding:56px 20px 40px;">

        <!-- Thương hiệu -->
        <div>
            <div style="font-family:'Playfair Display',serif; font-size:1.45rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                <i class="fa-solid fa-leaf" style="color:#8ecf9e;"></i> MOW Garden
            </div>
            <p style="font-size:.87rem; line-height:1.85; color:#93b893; max-width:270px; margin-bottom:22px;">
                Mang thiên nhiên vào không gian sống. Chúng tôi cung cấp cây cảnh,
                chậu trồng và phụ kiện làm vườn chất lượng cao trên khắp Việt Nam.
            </p>
            <div style="display:flex; gap:10px;">
                <?php foreach ([['fa-facebook-f','#'],['fa-instagram','#'],['fa-youtube','#'],['fa-tiktok','#']] as [$icon,$url]): ?>
                <a href="<?= $url ?>" style="width:36px;height:36px;border-radius:50%;border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;color:#93b893;font-size:.85rem;transition:.2s;"
                   onmouseover="this.style.background='rgba(255,255,255,.12)';this.style.color='#fff'"
                   onmouseout="this.style.background='transparent';this.style.color='#93b893'">
                    <i class="fab <?= $icon ?>"></i>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sản phẩm -->
        <div>
            <h4 style="font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;color:#fff;font-weight:600;margin-bottom:18px;">Sản phẩm</h4>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:9px;">
                <?php foreach ([
                    ['Cây trong nhà',     '/products?cat=indoor'],
                    ['Cây ngoài trời',    '/products?cat=outdoor'],
                    ['Cây phong thủy',    '/products?cat=fengshui'],
                    ['Chậu cây',          '/products?cat=pot'],
                    ['Phụ kiện làm vườn', '/products?cat=tool'],
                ] as [$label, $href]): ?>
                <li>
                    <a href="<?= APP_URL . $href ?>" style="color:#93b893;font-size:.87rem;display:inline-block;transition:.2s;"
                       onmouseover="this.style.color='#fff';this.style.paddingLeft='5px'"
                       onmouseout="this.style.color='#93b893';this.style.paddingLeft='0'">
                        <?= $label ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Hỗ trợ -->
        <div>
            <h4 style="font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;color:#fff;font-weight:600;margin-bottom:18px;">Hỗ trợ</h4>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:9px;">
                <?php foreach ([
                    ['Hướng dẫn chăm sóc cây','#'],
                    ['Chính sách vận chuyển',  '#'],
                    ['Chính sách đổi trả',     '#'],
                    ['Câu hỏi thường gặp',     '#'],
                    ['Liên hệ chúng tôi',      '#'],
                ] as [$label, $href]): ?>
                <li>
                    <a href="<?= $href ?>" style="color:#93b893;font-size:.87rem;display:inline-block;transition:.2s;"
                       onmouseover="this.style.color='#fff';this.style.paddingLeft='5px'"
                       onmouseout="this.style.color='#93b893';this.style.paddingLeft='0'">
                        <?= $label ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Liên hệ + Newsletter -->
        <div>
            <h4 style="font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;color:#fff;font-weight:600;margin-bottom:18px;">Liên hệ</h4>
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">

                <!-- Địa chỉ có link Google Maps -->
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:.86rem;color:#93b893;">
                    <i class="fa-solid fa-location-dot" style="color:#8ecf9e;margin-top:2px;width:14px;flex-shrink:0;"></i>
                    <a href="https://www.google.com/maps/place/105+Nguy%E1%BB%85n+%C4%90%E1%BB%A9c+C%E1%BA%A3nh,+T%C6%B0%C6%A1ng+Mai,+H%C3%A0+N%E1%BB%99i/@20.9868835,105.8480904,17.71z"
                       target="_blank"
                       style="color:#93b893;transition:.18s;line-height:1.5;"
                       onmouseover="this.style.color='#fff';this.style.textDecoration='underline'"
                       onmouseout="this.style.color='#93b893';this.style.textDecoration='none'">
                        105 Nguyễn Đức Cảnh, Tương Mai, Hà Nội
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:.7rem;margin-left:3px;"></i>
                    </a>
                </div>

                <?php foreach ([
                    ['fa-phone',   '0983 484 725 – 024 3568 1234'],
                    ['fa-envelope','hello@mowgarden.com'],
                    ['fa-clock',   '08:00 – 22:00 hàng ngày'],
                ] as [$icon, $text]): ?>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:.86rem;color:#93b893;">
                    <i class="fa-solid <?= $icon ?>" style="color:#8ecf9e;margin-top:2px;width:14px;flex-shrink:0;"></i>
                    <span><?= $text ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Newsletter -->
            <h4 style="font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;color:#fff;font-weight:600;margin-bottom:12px;">Nhận ưu đãi</h4>

            <!-- Thông báo thành công (ẩn mặc định) -->
            <div id="newsletter-success" style="display:none;background:rgba(142,207,158,.12);border:1px solid rgba(142,207,158,.35);border-radius:10px;padding:10px 14px;font-size:.83rem;color:#8ecf9e;margin-bottom:10px;">
                <i class="fa fa-circle-check"></i>
                Chúng tôi đã nhận được thông tin của bạn! Cảm ơn đã đăng ký
            </div>

            <div id="newsletter-form" style="display:flex;border-radius:50px;overflow:hidden;border:1px solid rgba(255,255,255,.18);">
                <input id="newsletter-email" type="email" placeholder="Email của bạn..."
                       style="flex:1;background:transparent;border:none;outline:none;padding:9px 16px;color:#fff;font-size:.83rem;font-family:inherit;">
                <button onclick="submitNewsletter()"
                        style="background:#3a6b45;border:none;padding:9px 16px;color:#fff;font-size:.83rem;font-weight:600;cursor:pointer;font-family:inherit;transition:.2s;white-space:nowrap;"
                        onmouseover="this.style.background='#2a4f33'" onmouseout="this.style.background='#3a6b45'">
                    Đăng ký
                </button>
            </div>
        </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,.08);margin:0 20px;"></div>

    <!-- ── GOOGLE MAPS – full width ── -->
    <div style="position:relative;">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d500!2d105.8499962!3d20.9874506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac6b03c262db%3A0xb388004daef96ef2!2s105%20Nguy%E1%BB%85n%20%C4%90%E1%BB%A9c%20C%E1%BA%A3nh%2C%20T%C6%B0%C6%A1ng%20Mai%2C%20H%C3%A0%20N%E1%BB%99i!5e0!3m2!1svi!2svn!4v1680000000000"
            width="100%" height="260"
            style="border:0;display:block;filter:grayscale(15%) brightness(0.85);"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <!-- Overlay nhãn địa chỉ -->
        <a href="https://www.google.com/maps/place/105+Nguy%E1%BB%85n+%C4%90%E1%BB%A9c+C%E1%BA%A3nh,+T%C6%B0%C6%A1ng+Mai,+H%C3%A0+N%E1%BB%99i/@20.9868835,105.8480904,17.71z"
           target="_blank"
           style="position:absolute;bottom:16px;left:50%;transform:translateX(-50%);background:#2a4f33;color:#fff;padding:8px 20px;border-radius:50px;font-size:.82rem;font-weight:600;display:flex;align-items:center;gap:8px;box-shadow:0 4px 16px rgba(0,0,0,.3);transition:.2s;text-decoration:none;white-space:nowrap;"
           onmouseover="this.style.background='#3a6b45';this.style.transform='translateX(-50%) translateY(-2px)'"
           onmouseout="this.style.background='#2a4f33';this.style.transform='translateX(-50%)'">
            <i class="fa-solid fa-location-dot" style="color:#8ecf9e;"></i>
            105 Nguyễn Đức Cảnh, Tương Mai, Hà Nội
            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:.72rem;opacity:.8;"></i>
        </a>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,.08);margin:0 20px;"></div>

    <div style="max-width:1240px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;padding:20px;font-size:.8rem;color:#638a63;flex-wrap:wrap;gap:12px;">
        <span>© <?= date('Y') ?> <strong style="color:#8ecf9e;">MOW Garden</strong> – Kiểm tra 2 Môn Lập trình Web PHP &amp; MySQL</span>
        <div style="display:flex;gap:20px;">
            <a href="#" style="color:#93b893;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#93b893'">Bảo mật</a>
            <a href="#" style="color:#93b893;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#93b893'">Điều khoản</a>
        </div>
        <div style="font-size:1rem;">Cảm ơn quý khách!</div>
    </div>
</footer>

<script>
// AJAX Cart Count Updater 
function updateCartBadge() {
    fetch('<?= APP_URL ?>/cart/count', {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(d => {
        const el = document.getElementById('cart-badge');
        if (el) el.textContent = d.count || 0;
    })
    .catch(() => {});
}

//Tự động ẩn flash sau 4s
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

//Toast helper
function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'alert alert-' + (type === 'error' ? 'error' : 'success');
    t.style.cssText = 'position:fixed;top:78px;right:20px;z-index:9999;min-width:280px;animation:slideIn .3s ease';
    t.innerHTML = `<i class="fa fa-${type==='error'?'times-circle':'check-circle'}"></i> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .5s'; setTimeout(()=>t.remove(),500); }, 3500);
}

const style = document.createElement('style');
style.textContent = '@keyframes slideIn{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}';
document.head.appendChild(style);

//Newsletter
function submitNewsletter() {
    const input   = document.getElementById('newsletter-email');
    const form    = document.getElementById('newsletter-form');
    const success = document.getElementById('newsletter-success');
    const email   = input ? input.value.trim() : '';

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        input.placeholder = 'Vui lòng nhập email hợp lệ!';
        input.style.color = '#e74c3c';
        setTimeout(() => {
            input.placeholder = 'Email của bạn...';
            input.style.color = '#fff';
        }, 2500);
        return;
    }

    // Ẩn form → hiện thông báo
    form.style.display    = 'none';
    success.style.display = 'block';

    // TODO: tích hợp gửi email thật
    // EmailJS: emailjs.send('service_id','template_id',{email})
    // PHPMailer: fetch('<?= APP_URL ?>/newsletter',{method:'POST',body:JSON.stringify({email})})
    console.log('Đăng ký newsletter:', email);
}
</script>
</body>
</html>