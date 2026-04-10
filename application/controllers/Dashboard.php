<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserData');
        $this->load->model('Patungan');
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
     * Halaman dashboard
     */
    public function index() {
        $this->checkAuth();

        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

        // Get semua patungan user
        $patungan_list = $this->Patungan->getAllByUser($user_id);

        $data = array(
            'username' => $username,
            'patungan_list' => $patungan_list
        );

        $this->load->view('dashboard/index', $data);
    }
}
