<main class="login-page">
    <section class="login-card">
        <span class="kicker">HP BUSINESS</span>

        <?php if ($hasUser): ?>
            <h1 style="text-align: center;">MASUK</h1>

            <?php if ($message = flash()): ?>
                <p class="flash"><?= e($message) ?></p>
            <?php endif; ?>

            <form method="post" action="login">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <label>
                    Email
                    <input name="email" type="email" required>
                </label>

                <label>
                    Kata sandi
                    <input name="password" type="password" required>
                </label>

                <button class="button blue" type="submit" style="text-align: center;">Masuk dengan email →</button>
            </form>
        <?php else: ?>
            <h1 style="text-align: center;">BUAT AKUN</h1>
            <p style="text-align: center;">Mulai dengan data bisnis Anda sendiri</p>

            <?php if ($message = flash()): ?>
                <p class="flash"><?= e($message) ?></p>
            <?php endif; ?>

            <form method="post" action="setup">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <label>
                    Nama
                    <input name="name" required>
                </label>

                <label>
                    Email
                    <input name="email" type="email" required>
                </label>

                <label>
                    Kata sandi
                    <input name="password" type="password" minlength="8" required>
                </label>

                <button class="button blue" type="submit" style="text-align: center;">BUAT AKUN DAN MULAI →</button>
            </form>
        <?php endif; ?>

        <p class="muted">Google Sign-In dapat ditambahkan setelah Client ID OAuth tersedia.</p>
    </section>
</main>