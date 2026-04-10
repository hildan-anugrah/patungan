<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patungan extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserData');
    }

    /**
     * Get next patungan ID untuk user tertentu
     */
    private function getNextPatunganId($user_id) {
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data || empty($user_data['patungan'])) {
            return 1;
        }

        $max_id = 0;
        foreach ($user_data['patungan'] as $patungan) {
            if ($patungan['id'] > $max_id) {
                $max_id = $patungan['id'];
            }
        }

        return $max_id + 1;
    }

    /**
     * Create patungan baru
     */
    public function create($user_id, $nama_grup, $total_biaya, $anggota_names) {
        // Validasi
        if (empty($anggota_names)) {
            return array('success' => false, 'message' => 'Jumlah anggota tidak boleh kosong');
        }

        $jumlah_anggota = count($anggota_names);
        
        if ($jumlah_anggota === 0) {
            return array('success' => false, 'message' => 'Jumlah anggota tidak boleh nol');
        }

        if ($total_biaya <= 0) {
            return array('success' => false, 'message' => 'Total biaya harus lebih besar dari nol');
        }

        // Hitung biaya per orang
        $biaya_per_orang = round($total_biaya / $jumlah_anggota, 2);

        // Format anggota dengan status awal "belum bayar"
        $anggota = array();
        foreach ($anggota_names as $nama) {
            $anggota[] = array(
                'nama' => trim($nama),
                'status' => 'belum bayar'
            );
        }

        // Get user data
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data) {
            return array('success' => false, 'message' => 'User tidak ditemukan');
        }

        // Buat data patungan
        $patungan_id = $this->getNextPatunganId($user_id);
        $patungan_baru = array(
            'id' => $patungan_id,
            'nama_grup' => $nama_grup,
            'total_biaya' => $total_biaya,
            'biaya_per_orang' => $biaya_per_orang,
            'anggota' => $anggota,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Tambah ke array patungan
        $user_data['patungan'][] = $patungan_baru;

        // Save user data
        $this->UserData->saveUserData($user_id, $user_data);

        return array('success' => true, 'patungan_id' => $patungan_id, 'message' => 'Patungan berhasil dibuat');
    }

    /**
     * Get semua patungan user
     */
    public function getAllByUser($user_id) {
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data) {
            return array();
        }

        return $user_data['patungan'] ? $user_data['patungan'] : array();
    }

    /**
     * Get patungan by ID
     */
    public function getById($user_id, $patungan_id) {
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data || empty($user_data['patungan'])) {
            return false;
        }

        foreach ($user_data['patungan'] as $patungan) {
            if ($patungan['id'] == $patungan_id) {
                return $patungan;
            }
        }

        return false;
    }

    /**
     * Update status pembayaran anggota
     */
    public function updateAnggotaStatus($user_id, $patungan_id, $anggota_index, $status) {
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data) {
            return array('success' => false, 'message' => 'User tidak ditemukan');
        }

        // Cari patungan
        $patungan_found = false;
        foreach ($user_data['patungan'] as &$patungan) {
            if ($patungan['id'] == $patungan_id) {
                // Update anggota status
                if (isset($patungan['anggota'][$anggota_index])) {
                    $patungan['anggota'][$anggota_index]['status'] = $status;
                    $patungan['updated_at'] = date('Y-m-d H:i:s');
                    $patungan_found = true;
                } else {
                    return array('success' => false, 'message' => 'Anggota tidak ditemukan');
                }
                break;
            }
        }

        if (!$patungan_found) {
            return array('success' => false, 'message' => 'Patungan tidak ditemukan');
        }

        // Save user data
        $this->UserData->saveUserData($user_id, $user_data);

        return array('success' => true, 'message' => 'Status pembayaran berhasil diperbarui');
    }

    /**
     * Update patungan
     */
    public function update($user_id, $patungan_id, $nama_grup, $total_biaya, $anggota_names) {
        // Validasi
        if (empty($anggota_names)) {
            return array('success' => false, 'message' => 'Jumlah anggota tidak boleh kosong');
        }

        $jumlah_anggota = count($anggota_names);
        
        if ($jumlah_anggota === 0) {
            return array('success' => false, 'message' => 'Jumlah anggota tidak boleh nol');
        }

        if ($total_biaya <= 0) {
            return array('success' => false, 'message' => 'Total biaya harus lebih besar dari nol');
        }

        // Hitung biaya per orang
        $biaya_per_orang = round($total_biaya / $jumlah_anggota, 2);

        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data) {
            return array('success' => false, 'message' => 'User tidak ditemukan');
        }

        // Cari dan update patungan
        $patungan_found = false;
        foreach ($user_data['patungan'] as &$patungan) {
            if ($patungan['id'] == $patungan_id) {
                $patungan['nama_grup'] = $nama_grup;
                $patungan['total_biaya'] = $total_biaya;
                $patungan['biaya_per_orang'] = $biaya_per_orang;
                
                // Update anggota (reset status ke belum bayar untuk anggota baru)
                $anggota_baru = array();
                foreach ($anggota_names as $nama) {
                    $anggota_baru[] = array(
                        'nama' => trim($nama),
                        'status' => 'belum bayar'
                    );
                }
                $patungan['anggota'] = $anggota_baru;
                $patungan['updated_at'] = date('Y-m-d H:i:s');
                $patungan_found = true;
                break;
            }
        }

        if (!$patungan_found) {
            return array('success' => false, 'message' => 'Patungan tidak ditemukan');
        }

        // Save user data
        $this->UserData->saveUserData($user_id, $user_data);

        return array('success' => true, 'message' => 'Patungan berhasil diupdate');
    }

    /**
     * Delete patungan
     */
    public function delete($user_id, $patungan_id) {
        $user_data = $this->UserData->getUserData($user_id);
        
        if (!$user_data) {
            return array('success' => false, 'message' => 'User tidak ditemukan');
        }

        // Cari dan hapus patungan
        $patungan_found = false;
        foreach ($user_data['patungan'] as $index => $patungan) {
            if ($patungan['id'] == $patungan_id) {
                unset($user_data['patungan'][$index]);
                $patungan_found = true;
                break;
            }
        }

        if (!$patungan_found) {
            return array('success' => false, 'message' => 'Patungan tidak ditemukan');
        }

        // Re-index array
        $user_data['patungan'] = array_values($user_data['patungan']);

        // Save user data
        $this->UserData->saveUserData($user_id, $user_data);

        return array('success' => true, 'message' => 'Patungan berhasil dihapus');
    }
}
