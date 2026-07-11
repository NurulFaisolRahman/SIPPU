<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SIPPU extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load library dan helper yang dibutuhkan
        $this->load->library('session');
        $this->load->helper('url');
        
        // Load database (pastikan pengaturan database di application/config/database.php sudah benar)
        $this->load->database(); 
    }

    // 1. Menampilkan Halaman Utama (Frontend)
    public function index() {
        $this->load->view('index');
    }

    // 2. Fungsi Validasi Login (Dipanggil via AJAX)
    public function proses_login() {
        // Pastikan request adalah POST
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);

            // Cari user di database berdasarkan username
            $this->db->where('username', $username);
            $query = $this->db->get('AkunAdmin');
            $user = $query->row();
            // Verifikasi user ada dan password cocok dengan hash di database
            if ($user->Username == $username && password_verify($password, $user->Password)) {
                // Set Session
                $session_data = array(
                    'admin_username'  => $user->Username,
                    'admin_logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);

                // Kirim respon sukses dalam format JSON
                echo json_encode(['status' => 'success', 'message' => 'Login Berhasil']);
            } else {
                // Kirim respon error jika kombinasi salah
                echo json_encode(['status' => 'error', 'message' => 'Username atau Password yang Anda masukkan salah!']);
            }
        }
    }
}