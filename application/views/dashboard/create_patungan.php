<?php $this->load->view('layouts/header'); ?>

<div class="navbar">
    <div class="container">
        <h1>Buat Patungan Baru</h1>
        <div>
            <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-primary">← Kembali</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card" style="max-width: 600px;">
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>">

            <div class="form-group">
                <label for="nama_grup">Nama Grup</label>
                <input type="text" id="nama_grup" name="nama_grup" placeholder="Contoh: Makan Bersama" required>
            </div>

            <div class="form-group">
                <label for="total_biaya">Total Biaya (Rp)</label>
                <input type="number" id="total_biaya" name="total_biaya" placeholder="0" min="1" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="anggota">Daftar Anggota (satu nama per baris)</label>
                <textarea id="anggota" name="anggota" placeholder="Nama anggota 1&#10;Nama anggota 2&#10;Nama anggota 3" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Buat Patungan</button>
                <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-primary">Batal</a>
            </div>

        </form>

    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
