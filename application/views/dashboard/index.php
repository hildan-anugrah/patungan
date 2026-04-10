<?php $this->load->view('layouts/header'); ?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand">Patungan Ku</span>
        <div class="navbar-text">
            <span class="me-3">Hai, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>" class="btn btn-success btn-sm me-2">+ Buat Patungan</a>
            <a href="<?php echo base_url('index.php?/auth/logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4 flex-grow-1">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title mb-4">Daftar Patungan Anda</h2>

            <?php if (empty($patungan_list)): ?>
                <div class="alert alert-info">
                    Anda belum membuat patungan apapun. <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>">Buat patungan sekarang</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" class="link-primary text-decoration-none">
                                            <?php echo htmlspecialchars($patungan['nama_grup']); ?>
                                        </a>
                                    </td>
                                    <td>Rp <?php echo number_format($patungan['total_biaya'], 0, ',', '.'); ?></td>
                                    <td>Rp <?php echo number_format($patungan['biaya_per_orang'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?php echo count($patungan['anggota']); ?> orang</span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($patungan['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/edit/' . $patungan['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/delete/' . $patungan['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
