<?php $this->load->view('layouts/header'); ?>

<nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid">
        <h1 class="navbar-brand mb-0">Edit Patungan</h1>
        <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-primary">← Kembali</a>
    </div>
</nav>

<div class="container mt-4 flex-grow-1">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo base_url('index.php?/patungan_ctrl/edit/' . $patungan_id); ?>">

                <div class="mb-3">
                    <label for="nama_grup" class="form-label">Nama Grup</label>
                    <input type="text" class="form-control" id="nama_grup" name="nama_grup" value="<?php echo htmlspecialchars($patungan['nama_grup']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="total_biaya" class="form-label">Total Biaya (Rp)</label>
                    <input type="number" class="form-control" id="total_biaya" name="total_biaya" value="<?php echo $patungan['total_biaya']; ?>" min="1" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="anggota" class="form-label">Daftar Anggota (satu nama per baris)</label>
                    <textarea class="form-control" id="anggota" name="anggota" rows="6" required><?php echo htmlspecialchars($anggota_text); ?></textarea>
                </div>

                <div class="d-grid gap-2 d-sm-flex">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
