<?php $this->load->view('layouts/header'); ?>

<div class="container mt-5 mb-5 flex-grow-1 d-flex align-items-center">
    <div class="auth-container w-100">
        <div class="auth-card">
            <h2>Daftar Akun Baru</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo base_url('index.php?/auth/register'); ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Ulangi password" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Daftar</button>
                </div>

            </form>

            <div class="auth-link">
                Sudah punya akun? <a href="<?php echo base_url('index.php?/auth/login'); ?>">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
