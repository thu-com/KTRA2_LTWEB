
</div><!-- /.container -->

<footer style="background:var(--dark);color:#aaa;text-align:center;padding:20px;margin-top:40px;font-size:.85rem;">
    <p>&copy; <?= date('Y') ?> <strong style="color:var(--primary)">MOW Shop</strong> – Bài tập MOW: Cart + Order + Strategy Pattern</p>
    <p style="margin-top:4px;font-size:.78rem;">PHP MVC | Strategy Pattern | Repository Pattern | Singleton | Dependency Injection</p>
</footer>

<script>
//  AJAX Cart Count Updater
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

// Tự động ẩn flash sau 4s 
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

// Toast helper
function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'alert alert-' + (type === 'error' ? 'error' : 'success');
    t.style.cssText = 'position:fixed;top:70px;right:20px;z-index:9999;min-width:280px;animation:slideIn .3s ease';
    t.innerHTML = `<i class="fa fa-${type==='error'?'times':'check'}-circle"></i> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .5s'; setTimeout(()=>t.remove(),500); }, 3500);
}

// CSS animation for toast
const style = document.createElement('style');
style.textContent = '@keyframes slideIn{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}';
document.head.appendChild(style);
</script>
</body>
</html>
