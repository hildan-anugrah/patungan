<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patungan_ctrl extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Patungan');
        $this->load->model('UserData');
        $this->load->helper('form');
    }

    /**
     * Cek apakah user sudah login
     */
    private function checkAuth() {
        if (!$this->session->userdata('user_id')) {
            redirect(base_url('index.php?/auth/login'));
        }
    }

    /**
     * Halaman membuat patungan baru
     */
    public function create() {
        $this->checkAuth();

        $user_id = $this->session->userdata('user_id');

        if ($this->input->post()) {
            $nama_grup = $this->input->post('nama_grup');
            $total_biaya = (float) $this->input->post('total_biaya');
            $anggota_input = $this->input->post('anggota');
            
            // Parse anggota dari textarea (pisah dengan newline)
            $anggota_names = array_filter(array_map('trim', explode("\n", $anggota_input)));

            $result = $this->Patungan->create($user_id, $nama_grup, $total_biaya, $anggota_names);

            if ($result['success']) {
                redirect(base_url('index.php?/dashboard'));
            } else {
                $data['error'] = $result['message'];
            }
        }

        $this->load->view('dashboard/create_patungan', isset($data) ? $data : array());
    }

    /**
     * Halaman edit patungan
     */
    public function edit($patungan_id) {
        $this->checkAuth();

        $user_id = $this->session->userdata('user_id');
        $patungan = $this->Patungan->getById($user_id, $patungan_id);

        if (!$patungan) {
            redirect(base_url('index.php?/dashboard'));
        }

        if ($this->input->post()) {
            $nama_grup = $this->input->post('nama_grup');
            $total_biaya = (float) $this->input->post('total_biaya');
            $anggota_input = $this->input->post('anggota');
            
            // Parse anggota dari textarea
            $anggota_names = array_filter(array_map('trim', explode("\n", $anggota_input)));

            $result = $this->Patungan->update($user_id, $patungan_id, $nama_grup, $total_biaya, $anggota_names);

            if ($result['success']) {
                redirect(base_url('index.php?/dashboard'));
            } else {
                $data['error'] = $result['message'];
            }
        }

        // Format anggota untuk ditampilkan di textarea
        $anggota_text = implode("\n", array_map(function($a) { return $a['nama']; }, $patungan['anggota']));

        $data = array(
            'patungan_id' => $patungan_id,
            'patungan' => $patungan,
            'anggota_text' => $anggota_text
        );

        $this->load->view('dashboard/edit_patungan', $data);
    }

    /**
     * Detail patungan
     */
    public function detail($patungan_id) {
        $this->checkAuth();

        $user_id = $this->session->userdata('user_id');
        $patungan = $this->Patungan->getById($user_id, $patungan_id);

        if (!$patungan) {
            redirect(base_url('index.php?/dashboard'));
        }

        $data = array(
            'patungan_id' => $patungan_id,
            'patungan' => $patungan
        );

        $this->load->view('dashboard/detail_patungan', $data);
    }

    /**
     * Update status pembayaran anggota (via AJAX atau form)
     */
    public function updateStatus() {
        $this->checkAuth();

        if (!$this->input->post()) {
            redirect(base_url('index.php?/dashboard'));
        }

        $user_id = $this->session->userdata('user_id');
        $patungan_id = $this->input->post('patungan_id');
        $anggota_index = $this->input->post('anggota_index');
        $status = $this->input->post('status');

        $result = $this->Patungan->updateAnggotaStatus($user_id, $patungan_id, $anggota_index, $status);

        if ($this->input->is_ajax_request()) {
            echo json_encode($result);
        } else {
            redirect(base_url('index.php?/patungan_ctrl/detail/' . $patungan_id));
        }
    }

    /**
     * Hapus patungan
     */
    public function delete($patungan_id) {
        $this->checkAuth();

        $user_id = $this->session->userdata('user_id');
        
        $result = $this->Patungan->delete($user_id, $patungan_id);

        if ($result['success']) {
            redirect(base_url('index.php?/dashboard'));
        } else {
            redirect(base_url('index.php?/dashboard'));
        }
    }
}
