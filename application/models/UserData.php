<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserData extends CI_Model {

    private $data_dir;
    private $users_file;

    public function __construct() {
        parent::__construct();
        $this->data_dir = APPPATH . 'data/';
        $this->users_file = $this->data_dir . 'users_index.json';
    }

    /**
     * Get next user ID
     */
    private function getNextUserId() {
        if (!file_exists($this->users_file)) {
            return 1;
        }

        $content = file_get_contents($this->users_file);
        $users = json_decode($content, true);
        
        if (empty($users)) {
            return 1;
        }

        $max_id = 0;
        foreach ($users as $user) {
            if ($user['user_id'] > $max_id) {
                $max_id = $user['user_id'];
            }
        }

        return $max_id + 1;
    }

    /**
     * Register user baru
     */
    public function register($username, $password) {
        // Cek apakah username sudah ada
        if ($this->getUserByUsername($username)) {
            return array('success' => false, 'message' => 'Username sudah digunakan');
        }

        $user_id = $this->getNextUserId();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Buat data user
        $user_data = array(
            'user_id' => $user_id,
            'username' => $username,
            'password' => $hashed_password,
            'patungan' => array()
        );

        // Simpan file user_{user_id}.json
        $user_file = $this->data_dir . 'user_' . $user_id . '.json';
        $this->writeFileAtomic($user_file, json_encode($user_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Update users index
        $user_index = array(
            'user_id' => $user_id,
            'username' => $username,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->addToUsersIndex($user_index);

        return array('success' => true, 'user_id' => $user_id, 'message' => 'Register berhasil');
    }

    /**
     * Get user by username
     */
    public function getUserByUsername($username) {
        $users = $this->getAllUsers();
        
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $user_id = $user['user_id'];
                $user_file = $this->data_dir . 'user_' . $user_id . '.json';
                if (file_exists($user_file)) {
                    $content = file_get_contents($user_file);
                    return json_decode($content, true);
                }
            }
        }

        return false;
    }

    /**
     * Login user
     */
    public function login($username, $password) {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return array('success' => false, 'message' => 'Username tidak ditemukan');
        }

        if (!password_verify($password, $user['password'])) {
            return array('success' => false, 'message' => 'Password salah');
        }

        return array('success' => true, 'user_id' => $user['user_id'], 'username' => $user['username']);
    }

    /**
     * Get semua users dari index
     */
    private function getAllUsers() {
        if (!file_exists($this->users_file)) {
            return array();
        }

        $content = file_get_contents($this->users_file);
        $users = json_decode($content, true);
        
        return $users ? $users : array();
    }

    /**
     * Add user ke index
     */
    private function addToUsersIndex($user_index) {
        $users = $this->getAllUsers();
        $users[] = $user_index;
        
        $this->writeFileAtomic($this->users_file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get user data by user_id
     */
    public function getUserData($user_id) {
        $user_file = $this->data_dir . 'user_' . $user_id . '.json';
        
        if (!file_exists($user_file)) {
            return false;
        }

        $content = file_get_contents($user_file);
        return json_decode($content, true);
    }

    /**
     * Save user data with atomic write
     */
    public function saveUserData($user_id, $data) {
        $user_file = $this->data_dir . 'user_' . $user_id . '.json';
        return $this->writeFileAtomic($user_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Atomic write file
     */
    private function writeFileAtomic($filepath, $content) {
        $temp_file = $filepath . '.tmp';
        
        // Write ke temp file
        if (file_put_contents($temp_file, $content) === false) {
            return false;
        }

        // Rename temp file ke file asli (atomic operation)
        if (!rename($temp_file, $filepath)) {
            unlink($temp_file);
            return false;
        }

        return true;
    }
}
