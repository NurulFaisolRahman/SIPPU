<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan Anda sudah mengatur database di config/database.php 
        // dan memuat database di config/autoload.php ($autoload['libraries'] = array('database');)
        // Middleware: Proteksi halaman. Cek apakah session admin_logged_in ada.
        if (!$this->session->userdata('admin_logged_in')) {
            // Jika tidak ada session, kembalikan ke landing page (form login)
            redirect('SIPPU');
        }
    }

    public function logout() {
        // Hancurkan semua data session yang ada
        $this->session->sess_destroy();
        
        // Arahkan kembali ke halaman utama (SIPPU Landing Page)
        redirect('SIPPU');
    }

    public function index() {
        // Mengambil data dari tabel JenisIzin, filter data yang belum dihapus (DeleteAt IS NULL)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['jenis_izin_data'] = $this->db->order_by('id', 'DESC')->get('JenisIzin')->result();
        $this->load->view('Admin/Header');
        // Memuat view dengan mem-passing data (Sesuaikan path view dengan folder Anda)
        $this->load->view('Admin/Dashboard', $data);
    }

    public function JenisIzin() {
        // Mengambil data dari tabel JenisIzin, filter data yang belum dihapus (DeleteAt IS NULL)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['jenis_izin_data'] = $this->db->order_by('id', 'DESC')->get('JenisIzin')->result();
        $this->load->view('Admin/Header');
        // Memuat view dengan mem-passing data (Sesuaikan path view dengan folder Anda)
        $this->load->view('Admin/JenisIzin', $data);
    }

    public function save_izin() {
        // Bersihkan output buffer untuk mencegah pesan error/notice PHP merusak format JSON
        if (ob_get_length()) ob_clean(); 
        
        $id = $this->input->post('id');
        $jenis_izin = $this->input->post('JenisIzin');
        
        // Validasi input kosong
        if (empty(trim($jenis_izin))) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Nama Jenis Perizinan wajib diisi!']));
        }

        $data = [
            'JenisIzin' => trim($jenis_izin),
            'UpdateAt' => date('Y-m-d H:i:s')
        ];

        // Jika ID kosong, berarti tambah data baru (CREATE)
        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('JenisIzin', $data);
            
            $response = $insert 
                ? ['status' => 'success', 'message' => 'Data Jenis Perizinan berhasil ditambahkan.']
                : ['status' => 'error', 'message' => 'Sistem gagal menambahkan data.'];
        } 
        // Jika ID ada, berarti update data (UPDATE)
        else {
            $update = $this->db->where('id', $id)->update('JenisIzin', $data);
            
            $response = $update 
                ? ['status' => 'success', 'message' => 'Data Jenis Perizinan berhasil diperbarui.']
                : ['status' => 'error', 'message' => 'Sistem gagal memperbarui data.'];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_izin($id) {
        if (ob_get_length()) ob_clean(); 
        
        $data = $this->db->where('id', $id)->get('JenisIzin')->row();
        
        $response = $data 
            ? ['status' => 'success', 'data' => $data]
            : ['status' => 'error', 'message' => 'Data tidak ditemukan di database.'];
            
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_izin($id) {
        if (ob_get_length()) ob_clean(); 
        
        // SOFT DELETE: Update kolom DeleteAt menjadi tanggal saat ini, alih-alih menghapus permanen
        $data = ['DeleteAt' => date('Y-m-d H:i:s')];
        $delete = $this->db->where('id', $id)->update('JenisIzin', $data);
        
        $response = $delete 
            ? ['status' => 'success', 'message' => 'Data berhasil dihapus dari sistem.']
            : ['status' => 'error', 'message' => 'Gagal menghapus data.'];
            
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // ==========================================
    // MENU: SEKTOR USAHA
    // ==========================================
    
    // 1. Menampilkan Halaman Tabel Sektor Usaha
    public function SektorUsaha() {
        // Tarik data Sektor Usaha yang belum di-soft delete
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['sektor_usaha_data'] = $this->db->order_by('id', 'DESC')->get('SektorUsaha')->result();
        
        $this->load->view('Admin/Header');
        // Pastikan Anda nanti membuat file view bernama SektorUsaha.php di folder views/Admin/
        $this->load->view('Admin/SektorUsaha', $data);
    }

    // 2. Menyimpan & Mengupdate Data Sektor Usaha (Dipanggil via AJAX)
    public function save_sektor() {
        if (ob_get_length()) ob_clean(); 
        
        $id = $this->input->post('id');
        $nama_sektor = $this->input->post('NamaSektor');
        
        if (empty(trim($nama_sektor))) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Nama Sektor Usaha wajib diisi!']));
        }

        $data = [
            'NamaSektor' => trim($nama_sektor),
            'UpdateAt' => date('Y-m-d H:i:s')
        ];

        if (empty($id)) { // CREATE: Jika ID tidak ada
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('SektorUsaha', $data);
            $response = $insert ? ['status' => 'success', 'message' => 'Data Sektor Usaha berhasil ditambahkan.'] : ['status' => 'error', 'message' => 'Gagal menambah data sektor.'];
        } else { // UPDATE: Jika ID ada
            $update = $this->db->where('id', $id)->update('SektorUsaha', $data);
            $response = $update ? ['status' => 'success', 'message' => 'Data Sektor Usaha berhasil diperbarui.'] : ['status' => 'error', 'message' => 'Gagal memperbarui data sektor.'];
        }
        
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // 3. Mengambil 1 Data Sektor Spesifik untuk Modal Edit (Dipanggil via AJAX)
    public function get_sektor($id) {
        if (ob_get_length()) ob_clean(); 
        
        $data = $this->db->where('id', $id)->get('SektorUsaha')->row();
        
        $response = $data ? ['status' => 'success', 'data' => $data] : ['status' => 'error', 'message' => 'Data sektor tidak ditemukan.'];
        
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // 4. Soft Delete Sektor Usaha (Dipanggil via AJAX)
    public function delete_sektor($id) {
        if (ob_get_length()) ob_clean(); 
        
        // Update kolom DeleteAt menjadi waktu saat ini untuk menyembunyikan data
        $data = ['DeleteAt' => date('Y-m-d H:i:s')];
        $delete = $this->db->where('id', $id)->update('SektorUsaha', $data);
        
        $response = $delete ? ['status' => 'success', 'message' => 'Data Sektor Usaha berhasil dihapus.'] : ['status' => 'error', 'message' => 'Gagal menghapus data sektor.'];
        
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // ==========================================
    // MENU: PROFIL USAHA
    // ==========================================
    public function ProfilUsaha() {
        // 1. Tarik data profil usaha beserta relasinya (Join ke Sektor & Distrik)
        $this->db->select('ProfilUsaha.*, SektorUsaha.NamaSektor, Distrik.NamaDistrik');
        $this->db->from('ProfilUsaha');
        $this->db->join('SektorUsaha', 'SektorUsaha.id = ProfilUsaha.id_sektor', 'left');
        $this->db->join('Distrik', 'Distrik.id = ProfilUsaha.id_distrik', 'left');
        $this->db->where('ProfilUsaha.DeleteAt IS NULL', null, false);
        $this->db->order_by('ProfilUsaha.id', 'DESC');
        $data['profil_usaha_data'] = $this->db->get()->result();
        
        // 2. Tarik data Master untuk Dropdown Select Option di Form Modal
        // Tarik Sektor Usaha (Yang belum dihapus)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['sektor_list'] = $this->db->get('SektorUsaha')->result();
        
        // Tarik Data Distrik
        $data['distrik_list'] = $this->db->get('Distrik')->result();
        
        // Load Halaman View
        $this->load->view('Admin/Header', ['title' => 'Kelola Profil Usaha - SIPPU']);
        $this->load->view('Admin/ProfilUsaha', $data);
    }

    public function save_profil() {
        if (ob_get_length()) ob_clean(); 
        
        $id = $this->input->post('id');
        $nama_usaha = $this->input->post('NamaUsaha');
        $nib = $this->input->post('NIB');
        $nama_pemilik = $this->input->post('NamaPemilik');
        $alamat = $this->input->post('Alamat');
        $id_distrik = $this->input->post('id_distrik');
        $id_sektor = $this->input->post('id_sektor');
        
        // Validasi basic dari sisi server
        if (empty(trim($nama_usaha)) || empty(trim($nib))) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Nama Usaha dan NIB wajib diisi!']));
        }

        $data = [
            'NamaUsaha' => trim($nama_usaha),
            'NIB' => trim($nib),
            'NamaPemilik' => trim($nama_pemilik),
            'Alamat' => trim($alamat),
            'id_distrik' => trim($id_distrik),
            'id_sektor' => trim($id_sektor),
            'UpdateAt' => date('Y-m-d H:i:s')
        ];

        if (empty($id)) { // PROSES CREATE (Data Baru)
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('ProfilUsaha', $data);
            $response = $insert ? ['status' => 'success', 'message' => 'Profil Usaha berhasil ditambahkan.'] : ['status' => 'error', 'message' => 'Gagal menambah data profil.'];
        } else { // PROSES UPDATE (Edit Data Lama)
            $update = $this->db->where('id', $id)->update('ProfilUsaha', $data);
            $response = $update ? ['status' => 'success', 'message' => 'Profil Usaha berhasil diperbarui.'] : ['status' => 'error', 'message' => 'Gagal memperbarui data profil.'];
        }
        
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_profil($id) {
        if (ob_get_length()) ob_clean(); 
        // Mengambil data spesifik untuk diisi ke Modal form saat tombol Edit ditekan
        $data = $this->db->where('id', $id)->get('ProfilUsaha')->row();
        $response = $data ? ['status' => 'success', 'data' => $data] : ['status' => 'error', 'message' => 'Data profil tidak ditemukan.'];
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete_profil($id) {
        if (ob_get_length()) ob_clean(); 
        // PROSES SOFT DELETE
        $data = ['DeleteAt' => date('Y-m-d H:i:s')];
        $delete = $this->db->where('id', $id)->update('ProfilUsaha', $data);
        $response = $delete ? ['status' => 'success', 'message' => 'Data Profil Usaha berhasil disembunyikan.'] : ['status' => 'error', 'message' => 'Gagal menghapus data profil.'];
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // ==========================================
    // MENU: DATA INVESTASI (PMDN & PMA)
    // ==========================================
    
    public function DataInvestasi() {
        // 1. Tarik data Investasi beserta relasi ke ProfilUsaha (Untuk tabel)
        $this->db->select('DataInvestasi.*, ProfilUsaha.NamaUsaha, ProfilUsaha.NIB');
        $this->db->from('DataInvestasi');
        $this->db->join('ProfilUsaha', 'ProfilUsaha.id = DataInvestasi.id_profil', 'left');
        $this->db->where('DataInvestasi.DeleteAt IS NULL', null, false);
        $this->db->order_by('DataInvestasi.Tahun', 'DESC');
        $this->db->order_by('DataInvestasi.id', 'DESC');
        $data['investasi_data'] = $this->db->get()->result();
        
        // 2. Tarik data Master Profil Usaha (Untuk Dropdown di Form Tambah/Edit)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['profil_list'] = $this->db->get('ProfilUsaha')->result();
        
        $this->load->view('Admin/Header', ['title' => 'Kelola Data Investasi - SIPPU']);
        $this->load->view('Admin/DataInvestasi', $data);
    }

    public function save_investasi() {
        if (ob_get_length()) ob_clean(); 
        
        $id = $this->input->post('id');
        $id_profil = $this->input->post('id_profil');
        $tahun = $this->input->post('Tahun');
        $jenis = $this->input->post('JenisInvestasi');
        $nilai = $this->input->post('NilaiInvestasi');
        $tk_lokal = $this->input->post('TenagaKerjaLokal');
        $tk_asing = $this->input->post('TenagaKerjaAsing');
        
        if (empty(trim($id_profil)) || empty(trim($tahun)) || empty(trim($nilai))) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Perusahaan, Tahun, dan Nilai Investasi wajib diisi!']));
        }

        $data = [
            'id_profil' => $id_profil,
            'Tahun' => $tahun,
            'JenisInvestasi' => $jenis,
            'NilaiInvestasi' => $nilai,
            'TenagaKerjaLokal' => empty($tk_lokal) ? 0 : $tk_lokal,
            'TenagaKerjaAsing' => empty($tk_asing) ? 0 : $tk_asing,
            'UpdateAt' => date('Y-m-d H:i:s')
        ];

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataInvestasi', $data);
            $response = $insert ? ['status' => 'success', 'message' => 'Data Investasi berhasil dicatat.'] : ['status' => 'error', 'message' => 'Gagal menambah data.'];
        } else {
            $update = $this->db->where('id', $id)->update('DataInvestasi', $data);
            $response = $update ? ['status' => 'success', 'message' => 'Data Investasi berhasil diperbarui.'] : ['status' => 'error', 'message' => 'Gagal memperbarui data.'];
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_investasi($id) {
        if (ob_get_length()) ob_clean(); 
        $data = $this->db->where('id', $id)->get('DataInvestasi')->row();
        $response = $data ? ['status' => 'success', 'data' => $data] : ['status' => 'error', 'message' => 'Data tidak ditemukan.'];
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function delete_investasi($id) {
        if (ob_get_length()) ob_clean(); 
        $data = ['DeleteAt' => date('Y-m-d H:i:s')];
        $delete = $this->db->where('id', $id)->update('DataInvestasi', $data);
        $response = $delete ? ['status' => 'success', 'message' => 'Data Investasi berhasil dihapus.'] : ['status' => 'error', 'message' => 'Gagal menghapus data.'];
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}