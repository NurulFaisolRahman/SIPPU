<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan Anda sudah mengatur database di config/database.php 
        // dan memuat database di config/autoload.php ($autoload['libraries'] = array('database');)
    }

    public function index() {
        // Mengambil data dari tabel JenisIzin, filter data yang belum dihapus (DeleteAt IS NULL)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['jenis_izin_data'] = $this->db->order_by('id', 'DESC')->get('JenisIzin')->result();
        
        // Memuat view dengan mem-passing data (Sesuaikan path view dengan folder Anda)
        $this->load->view('Admin/Dashboard', $data);
    }

    public function JenisIzin() {
        // Mengambil data dari tabel JenisIzin, filter data yang belum dihapus (DeleteAt IS NULL)
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['jenis_izin_data'] = $this->db->order_by('id', 'DESC')->get('JenisIzin')->result();
        
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
}