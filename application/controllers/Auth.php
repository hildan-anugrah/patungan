<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserData');
        $this->load->helper('form');
    }

    /**
     * Redirect ke login jika user sudah login
     */
    private function redirectIfLoggedIn() {
        if ($this->session->userdata('user_id')) {
            redirect(base_url('index.php?/dashboard'));
        }
    }

    /**
     * Halaman login
     */
    public function login() {
        $this->redirectIfLoggedIn();

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $result = $this->UserData->login($username, $password);

            if ($result['success']) {
                // Set session
                $this->session->set_userdata('user_id', $result['user_id']);
                $this->session->set_userdata('username', $result['username']);
                
                redirect(base_url('index.php?/dashboard'));
            } else {
                $data['error'] = $result['message'];
            }
        }

        $this->load->view('auth/login', isset($data) ? $data : array());
    }

    /**
     * Halaman register
     */
    public function register() {
        $this->redirectIfLoggedIn();

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $password_confirm = $this->input->post('password_confirm');

            // Validasi
            if (empty($username) || empty($password)) {
                $data['error'] = 'Username dan password tidak boleh kosong';
            } else if ($password !== $password_confirm) {
                $data['error'] = 'Password tidak cocok';
            } else if (strlen($password) < 6) {
                $data['error'] = 'Password minimal 6 karakter';
            } else {
                $result = $this->UserData->register($username, $password);

                if ($result['success']) {
                    $data['success'] = 'Register berhasil! Silakan login.';
                    // Set session dan redirect ke dashboard
                    $this->session->set_userdata('user_id', $result['user_id']);
                    $this->session->set_userdata('username', $username);
                    
                    redirect(base_url('index.php?/dashboard'));
                } else {
                    $data['error'] = $result['message'];
                }
            }
        }

        $this->load->view('auth/register', isset($data) ? $data : array());
    }

    /**
     * Logout
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('index.php?/auth/login'));
    }
}
