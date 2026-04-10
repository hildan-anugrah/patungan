<?php $this->load->view('layouts/header'); ?>

<div class="navbar">
    <div class="container">
        <h1><?php echo htmlspecialchars($patungan['nama_grup']); ?></h1>
        <div>
            <a href="<?php echo base_url('index.php?/dashboard'); ?>" class="btn btn-primary">← Kembali</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2 class="card-title">Detail Patungan</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <strong>Total Biaya:</strong> Rp <?php echo number_format($patungan['total_biaya'], 0, ',', '.'); ?>
            </div>
            <div>
                <strong>Biaya per Orang:</strong> Rp <?php echo number_format($patungan['biaya_per_orang'], 0, ',', '.'); ?>
            </div>
            <div>
                <strong>Jumlah Anggota:</strong> <?php echo count($patungan['anggota']); ?> orang
            </div>
            <div>
                <strong>Dibuat:</strong> <?php echo date('d/m/Y H:i', strtotime($patungan['created_at'])); ?>
            </div>
        </div>

        <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Daftar Pembayaran Anggota</h3>

        <table>
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
                            <span class="card-status status-<?php echo str_replace(' ', '-', $anggota['status']); ?>">
                                <?php echo ucfirst($anggota['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $new_status = ($anggota['status'] === 'belum bayar') ? 'sudah bayar' : 'belum bayar';
                                $button_class = ($anggota['status'] === 'belum bayar') ? 'btn-success' : 'btn-primary';
                                $button_text = ($anggota['status'] === 'belum bayar') ? 'Tandai Sudah Bayar' : 'Reset ke Belum Bayar';
                            ?>
                            <form action="<?php echo base_url('index.php?/patungan_ctrl/updateStatus'); ?>" method="POST" style="display: inline;">
                                <input type="hidden" name="patungan_id" value="<?php echo $patungan['id']; ?>">
                                <input type="hidden" name="anggota_index" value="<?php echo $index; ?>">
                                <input type="hidden" name="status" value="<?php echo $new_status; ?>">
                                <button type="submit" class="btn <?php echo $button_class; ?>" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                                    <?php echo $button_text; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 1.5rem;">
            <a href="<?php echo base_url('index.php?/patungan_ctrl/edit/' . $patungan['id']); ?>" class="btn btn-primary">Edit</a>
            <a href="<?php echo base_url('index.php?/patungan_ctrl/delete/' . $patungan['id']); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus patungan ini?')">Hapus</a>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
