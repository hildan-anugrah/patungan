<?php $this->load->view('layouts/header'); ?>

<div class="navbar">
    <div class="container">
        <h1>Patungan Ku</h1>
        <div>
            <span>Hai, <?php echo $username; ?>!</span>
            <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>" class="btn btn-success">+ Buat Patungan</a>
            <a href="<?php echo base_url('index.php?/auth/logout'); ?>" class="btn logout">Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2 class="card-title">Daftar Patungan Anda</h2>

        <?php if (empty($patungan_list)): ?>
            <p>Anda belum membuat patungan apapun. <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>">Buat patungan sekarang</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Grup</th>
                        <th>Total Biaya</th>
                        <th>Biaya per Orang</th>
                        <th>Jumlah Anggota</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patungan_list as $patungan): ?>
                        <tr>
                            <td>
                                <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" style="color: #3498db; text-decoration: none;">
                                    <?php echo htmlspecialchars($patungan['nama_grup']); ?>
                                </a>
                            </td>
                            <td>Rp <?php echo number_format($patungan['total_biaya'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($patungan['biaya_per_orang'], 0, ',', '.'); ?></td>
                            <td><?php echo count($patungan['anggota']); ?> orang</td>
                            <td><?php echo date('d/m/Y H:i', strtotime($patungan['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">Lihat</a>
                                <a href="<?php echo base_url('index.php?/patungan_ctrl/edit/' . $patungan['id']); ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">Edit</a>
                                <a href="<?php echo base_url('index.php?/patungan_ctrl/delete/' . $patungan['id']); ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
