<?php $this->load->view('layouts/header'); ?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Daftar Akun Baru</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo base_url('index.php?/auth/register'); ?>">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success" style="width: 100%;">Daftar</button>
                </div>

            </form>

            <div class="auth-link">
                Sudah punya akun? <a href="<?php echo base_url('index.php?/auth/login'); ?>">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
