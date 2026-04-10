<?php $this->load->view('layouts/header'); ?>

<nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid">
        <h1 class="navbar-brand mb-0"><?php echo htmlspecialchars($patungan['nama_grup']); ?></h1>
        <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-primary">← Kembali</a>
    </div>
</nav>

<div class="container mt-4 flex-grow-1">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title mb-4">Detail Patungan</h2>
            <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                    
                        <div class="col">
                            <div class="stat-card stat-card-primary">
                                <div class="stat-card-title">Total Biaya</div>
                                <div class="stat-card-value">Rp <?php echo number_format($patungan['total_biaya'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-card-success">
                                <div class="stat-card-title">Biaya per Orang</div>
                                <div class="stat-card-value">Rp <?php echo number_format($patungan['biaya_per_orang'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-card-purple">
                                <div class="stat-card-title">Member Count</div>
                                <div class="stat-card-value"><?php echo count($patungan['anggota']); ?></div>
                            </div>
                        </div>
                    
                </div>

            <h3 class="mb-3">Daftar Pembayaran Anggota</h3>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patungan['anggota'] as $index => $anggota): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                                <td>
                                    <span class="badge status-badge status-<?php echo str_replace(' ', '-', $anggota['status']); ?>">
                                        <?php echo ucfirst($anggota['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $new_status = ($anggota['status'] === 'belum bayar') ? 'sudah bayar' : 'belum bayar';
                                        $button_class = ($anggota['status'] === 'belum bayar') ? 'btn-success' : 'btn-warning';
                                        $button_text = ($anggota['status'] === 'belum bayar') ? 'Bayar' : 'Reset';
                                    ?>
                                    <form action="<?php echo base_url('index.php?/patungan_ctrl/updateStatus'); ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="patungan_id" value="<?php echo $patungan['id']; ?>">
                                        <input type="hidden" name="anggota_index" value="<?php echo $index; ?>">
                                        <input type="hidden" name="status" value="<?php echo $new_status; ?>">
                                        <button type="submit" class="btn btn-sm <?php echo $button_class; ?>">
                                            <?php echo $button_text; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="<?php echo base_url('index.php?/patungan_ctrl/edit/' . $patungan['id']); ?>" class="btn btn-warning">Edit</a>
                <a href="<?php echo base_url('index.php?/patungan_ctrl/delete/' . $patungan['id']); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus patungan ini?')">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
