<main class="login-page">
    <section class="login-card neo-card-animated">
        <div class="auth-brand">
            <span class="brand-mark">▣</span>
            <div>
                <b>HP BUSINESS</b>
                <small>OPERATIONS DESK</small>
            </div>
        </div>

        <span class="kicker">#START YOUR WORKSPACE</span>
        <h1>Buat akun baru.</h1>
        <p class="auth-copy">Daftarkan akun administrator untuk mulai mengelola stok HP, data supplier, dan mencatat transaksi bisnis Anda.</p>

        <?php if ($message = flash()): ?>
            <div class="flash-alert" role="alert">
                <span class="flash-icon">⚡</span>
                <span class="flash-text"><?= e($message) ?></span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('register')) ?>" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <label>
                <span>Nama Lengkap *</span>
                <input name="name" autocomplete="name" placeholder="Contoh: Budi Santoso" required>
            </label>

            <label>
                <span>Email Valid *</span>
                <input name="email" type="email" autocomplete="email" placeholder="nama@email.com" required>
            </label>

            <label>
                <span>Kata Sandi (Minimal 8 Karakter) *</span>
                <input name="password" type="password" minlength="8" autocomplete="new-password" placeholder="••••••••" required>
            </label>

            <button class="button blue submit-btn" type="submit">
                <span>＋</span> Buat Akun &amp; Mulai Sekarang
            </button>
        </form>

        <div class="auth-switch-box">
            <p>Sudah memiliki akun terdaftar?</p>
            <a class="button yellow" href="<?= e(url('login')) ?>">→ Masuk ke Akun Anda</a>
        </div>

        <div class="auth-footer-note">
            <small>🔒 Akun akan dibuat dengan status aktif langsung</small>
        </div>
    </section>
</main>
