<?php $this->load->view('layouts/header'); ?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand">Patungan Ku</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-text ms-auto">
                <span class="me-3">Hai, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>" class="btn btn-success btn-sm me-2">+ Buat Patungan</a>
                <a href="<?php echo base_url('index.php?/auth/logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5 flex-grow-1">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Daftar Patungan Anda</h2>

            <?php if (empty($patungan_list)): ?>
                <div class="alert alert-info">
                    Anda belum membuat patungan apapun. <a href="<?php echo base_url('index.php?/patungan_ctrl/create'); ?>">Buat patungan sekarang</a>
                </div>
            <?php else: ?>
                

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Grup</th>
                                <th>Total Biaya</th>
                                <th>Biaya per Orang</th>
                                <th>Jumlah Anggota</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($patungan_list as $patungan): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" class="link-primary text-decoration-none fw-600">
                                            <?php echo htmlspecialchars($patungan['nama_grup']); ?>
                                        </a>
                                    </td>
                                    <td><strong>Rp <?php echo number_format($patungan['total_biaya'], 0, ',', '.'); ?></strong></td>
                                    <td>Rp <?php echo number_format($patungan['biaya_per_orang'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: var(--primary-blue); color: white;"><?php echo count($patungan['anggota']); ?> orang</span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($patungan['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo base_url('index.php?/patungan_ctrl/detail/' . $patungan['id']); ?>" class="btn btn-sm btn-primary">Lihat</a>
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
