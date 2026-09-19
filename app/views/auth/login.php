<main class="login-page">
    <section class="login-card neo-card-animated">
        <div class="auth-brand">
            <span class="brand-mark">▣</span>
            <div>
                <b>HP BUSINESS</b>
                <small>OPERATIONS DESK</small>
            </div>
        </div>

        <span class="kicker">#WELCOME BACK</span>
        <h1>Masuk ke akun Anda.</h1>
        <p class="auth-copy">Kelola inventaris HP, transaksi pembelian, dan penjualan dari satu dashboard terpadu.</p>

        <?php if ($message = flash()): ?>
            <div class="flash-alert" role="alert">
                <span class="flash-icon">⚡</span>
                <span class="flash-text"><?= e($message) ?></span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        <?php endif; ?>

        <?php if ($currentUser): ?>
            <div class="signed-in-panel">
                <b>Anda sedang masuk sebagai <?= e($currentUser['name']) ?>.</b>
                <span>Pilih opsi di bawah untuk melanjutkan ke aplikasi atau keluar akun:</span>
                <div class="signed-in-actions">
                    <a class="button blue" href="<?= e(url()) ?>">→ Buka Dashboard</a>
                    <a class="button pink" href="<?= e(url('logout')) ?>">⎋ Keluar Akun</a>
                </div>
            </div>
        <?php else: ?>
            <form method="post" action="<?= e(url('login')) ?>" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <label>
                    <span>Email Terdaftar</span>
                    <input name="email" type="email" autocomplete="email" placeholder="nama@email.com" required>
                </label>

                <label>
                    <span>Kata Sandi (Password)</span>
                    <input name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
                </label>

                <button class="button blue submit-btn" type="submit">
                    <span>→</span> Masuk ke Dashboard
                </button>
            </form>

            <div class="auth-switch-box">
                <p>Belum memiliki akun operasional?</p>
                <a class="button yellow" href="<?= e(url('register')) ?>">＋ Buat Akun Baru Sekarang</a>
            </div>
        <?php endif; ?>

        <div class="auth-footer-note">
            <small>🔒 Sistem Dilindungi Enkripsi &amp; Validasi CSRF</small>
        </div>
    </section>
</main>
