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
            redirect();
        }
    }

    public function logout() {
        // Hancurkan semua data session yang ada
        $this->session->sess_destroy();
        
        // Arahkan kembali ke halaman utama (SIPPU Landing Page)
        redirect();
    }

    // 1. TAMPILAN UTAMA MANAJEMEN JENIS PERIZINAN & REKAP BULANAN
    public function JenisIzin($tahun_param = null) {
        // Ambil filter tahun dari URI segment (prioritas) atau GET, default tahun berjalan (Current Year)
        if ($tahun_param !== null) {
            $tahun = (int)$tahun_param;
        } else {
            $tahun_get = $this->input->get('tahun');
            $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        }
        
        // Safety check jika tahun di URL <= 2015 atau format bukan 4 digit
        if ($tahun <= 2015 || strlen((string)$tahun) !== 4) {
            $tahun = (int)date('Y');
        }

        // QUERY FILTERING: Hanya mengambil Jenis Izin yang MEMILIKI data di RekapIzinBulan pada Tahun terpilih
        $jenis_izin_list = $this->db->select('JenisIzin.*')
                                    ->from('JenisIzin')
                                    ->join('RekapIzinBulan', 'RekapIzinBulan.id_jenis_izin = JenisIzin.id')
                                    ->where('RekapIzinBulan.Tahun', $tahun)
                                    ->where('JenisIzin.DeleteAt IS NULL', null, false)
                                    ->where('RekapIzinBulan.DeleteAt IS NULL', null, false)
                                    ->group_by('JenisIzin.id')
                                    ->order_by('JenisIzin.id', 'DESC')
                                    ->get()
                                    ->result();

        foreach ($jenis_izin_list as $row) {
            $rekap_raw = $this->db->where('id_jenis_izin', $row->id)
                                  ->where('Tahun', $tahun)
                                  ->where('DeleteAt IS NULL', null, false)
                                  ->get('RekapIzinBulan')
                                  ->result();

            $rekap_bulan = array_fill(1, 12, 0);
            $total_tahun = 0;

            foreach ($rekap_raw as $r) {
                $rekap_bulan[$r->Bulan] = (int)$r->Jumlah;
                $total_tahun += (int)$r->Jumlah;
            }

            $row->rekap_bulan = $rekap_bulan;
            $row->total_tahun = $total_tahun;
        }

        $data['jenis_izin_data'] = $jenis_izin_list;
        $data['selected_tahun'] = $tahun;

        $this->load->view('Admin/Header', array('title' => 'Manajemen Jenis Perizinan'));
        $this->load->view('Admin/JenisIzin', $data);
    }

    // 2. AJAX GET DATA UNTUK EDIT MODAL
    public function get_izin($id, $tahun_param = null) {
        if (ob_get_length()) ob_clean(); 
        
        // Prioritaskan dari parameter URL segment agar tidak terpengaruh config query string CI
        if ($tahun_param !== null) {
            $tahun = (int)$tahun_param;
        } else {
            $tahun_get = $this->input->get('tahun');
            $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        }

        if ($tahun <= 2015 || strlen((string)$tahun) !== 4) {
            $tahun = (int)date('Y');
        }

        $izin = $this->db->where('id', $id)
                         ->where('DeleteAt IS NULL', null, false)
                         ->get('JenisIzin')
                         ->row();

        if ($izin) {
            $rekap = $this->db->where('id_jenis_izin', $id)
                              ->where('Tahun', $tahun)
                              ->where('DeleteAt IS NULL', null, false)
                              ->get('RekapIzinBulan')
                              ->result();

            $data_bulan = array_fill(1, 12, 0);
            foreach ($rekap as $rk) {
                $data_bulan[$rk->Bulan] = (int)$rk->Jumlah;
            }

            $izin->data_bulan = $data_bulan;
            $izin->tahun = $tahun;

            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'success', 'data' => $izin)));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(array('status' => 'error', 'message' => 'Data perizinan tidak ditemukan.')));
    }

    // 3. SIMPAN ATAU UPDATE BATCH (MULTIPLE JENIS IZIN & 12 BULAN) SEKALIGUS
    public function save_izin() {
        if (ob_get_length()) ob_clean();

        $tahun_raw = trim((string)$this->input->post('Tahun'));
        $tahun = (int)$tahun_raw;

        // VALIDASI SERVER-SIDE: Format 4 Digit & Harus > 2015
        if (empty($tahun_raw) || !preg_match('/^[0-9]{4}$/', $tahun_raw) || $tahun <= 2015) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array(
                                    'status' => 'error', 
                                    'message' => 'Tahun tidak valid! Masukkan 4 digit angka dan harus lebih besar dari 2015 (Contoh: 2026).'
                                )));
        }

        $izin_array = $this->input->post('Izin');

        if (empty($izin_array) || !is_array($izin_array)) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Tidak ada baris data perizinan yang dikirim!')));
        }

        $this->db->trans_start();

        foreach ($izin_array as $izin) {
            $id = isset($izin['id']) ? $izin['id'] : '';
            $jenis_izin_nama = isset($izin['JenisIzin']) ? trim($izin['JenisIzin']) : '';
            
            if (empty($jenis_izin_nama)) continue; 

            // Step 1: Upsert Induk (JenisIzin)
            if (empty($id)) {
                $this->db->insert('JenisIzin', array(
                    'JenisIzin' => $jenis_izin_nama,
                    'InputAt' => date('Y-m-d H:i:s')
                ));
                $id_jenis_izin = $this->db->insert_id();
            } else {
                $this->db->where('id', $id)->update('JenisIzin', array(
                    'JenisIzin' => $jenis_izin_nama,
                    'UpdatedAt' => date('Y-m-d H:i:s')
                ));
                $id_jenis_izin = $id;
            }

            // Step 2: Loop Upsert data 12 bulan (Januari s/d Desember)
            if (isset($izin['Bulan']) && is_array($izin['Bulan'])) {
                for ($b = 1; $b <= 12; $b++) {
                    $jumlah = isset($izin['Bulan'][$b]) ? (int)$izin['Bulan'][$b] : 0;

                    $existing = $this->db->where('id_jenis_izin', $id_jenis_izin)
                                         ->where('Tahun', $tahun)
                                         ->where('Bulan', $b)
                                         ->get('RekapIzinBulan')
                                         ->row();

                    if ($existing) {
                        $this->db->where('id', $existing->id)->update('RekapIzinBulan', array(
                            'Jumlah' => $jumlah,
                            'DeleteAt' => NULL,
                            'UpdatedAt' => date('Y-m-d H:i:s')
                        ));
                    } else {
                        if ($jumlah >= 0) { 
                            $this->db->insert('RekapIzinBulan', array(
                                'id_jenis_izin' => $id_jenis_izin,
                                'Bulan' => $b,
                                'Tahun' => $tahun,
                                'Jumlah' => $jumlah,
                                'InputAt' => date('Y-m-d H:i:s')
                            ));
                        }
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Gagal menyimpan data ke database.')));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(array('status' => 'success', 'message' => 'Data Jenis Perizinan & Rekapitulasi Tahun ' . $tahun . ' berhasil disimpan!')));
    }

    // 4. SOFT DELETE DATA JENIS PERIZINAN & REKAPNYA (BUG FIX TAHUN)
    public function delete_izin($id, $tahun_param = null) {
        if (ob_get_length()) ob_clean();
        $now = date('Y-m-d H:i:s');

        // Pastikan parameter tahun tersedia
        if ($tahun_param === null) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Tahun tidak diketahui. Gagal memproses data.')));
        }

        $tahun = (int)$tahun_param;

        // Pengecekan: Apakah jenis izin ini masih memiliki data rekap di TAHUN LAIN yang belum terhapus?
        $cek_tahun_lain = $this->db->where('id_jenis_izin', $id)
                                   ->where('Tahun !=', $tahun)
                                   ->where('DeleteAt IS NULL', null, false)
                                   ->get('RekapIzinBulan')
                                   ->num_rows();

        $this->db->trans_start();

        if ($cek_tahun_lain > 0) {
            // JIKA ADA TAHUN LAIN: Hanya soft-delete data RekapIzinBulan di tahun yang dipilih
            $this->db->where('id_jenis_izin', $id)
                     ->where('Tahun', $tahun)
                     ->update('RekapIzinBulan', array('DeleteAt' => $now));
            $pesan = 'Data Rekap Perizinan tahun ' . $tahun . ' berhasil dihapus.';
        } else {
            // JIKA TIDAK ADA TAHUN LAIN: Soft-delete keseluruhan RekapIzinBulan DAN Induk JenisIzin
            $this->db->where('id', $id)->update('JenisIzin', array('DeleteAt' => $now));
            $this->db->where('id_jenis_izin', $id)
                     ->where('Tahun', $tahun)
                     ->update('RekapIzinBulan', array('DeleteAt' => $now));
            $pesan = 'Seluruh data Jenis Perizinan dan rekapitulasinya berhasil dihapus permanen.';
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Gagal menghapus data dari database.')));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(array('status' => 'success', 'message' => $pesan)));
    }

    // ==========================================
    // BAGIAN PROFIL USAHA (DENGAN FILTER JENIS & SEKTOR)
    // ==========================================
    
    public function ProfilUsaha() {
        // Menerima input filter GET
        $tahun_get = $this->input->get('tahun');
        $jenis_usaha_get = $this->input->get('jenis_usaha');
        $sektor_usaha_get = $this->input->get('sektor_usaha');

        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        
        // Pass filter aktif kembali ke View
        $data['tahun_filter'] = $tahun;
        $data['jenis_filter'] = !empty($jenis_usaha_get) ? $jenis_usaha_get : 'Semua';
        $data['sektor_filter'] = !empty($sektor_usaha_get) ? $sektor_usaha_get : 'Semua';

        // Dropdown Provinsi Awal
        $this->db->where('LENGTH(id)', 2);
        $data['provinsi_list'] = $this->db->get('Distrik')->result();

        // Query Utama Profil Usaha
        $this->db->select('ProfilUsaha.*, 
                           P.NamaDistrik as NamaProvinsi, 
                           K.NamaDistrik as NamaKabupaten, 
                           D.NamaDistrik as NamaDistrik, 
                           V.NamaDistrik as NamaKampung');
        $this->db->from('ProfilUsaha');
        $this->db->join('Distrik P', 'P.id = ProfilUsaha.id_provinsi', 'left');
        $this->db->join('Distrik K', 'K.id = ProfilUsaha.id_kabupaten', 'left');
        $this->db->join('Distrik D', 'D.id = ProfilUsaha.id_distrik', 'left');
        $this->db->join('Distrik V', 'V.id = ProfilUsaha.id_kampung', 'left');
        
        $this->db->where('ProfilUsaha.Tahun', $tahun);
        
        // Aplikasikan Filter Query Jika Dipilih
        if (!empty($jenis_usaha_get) && $jenis_usaha_get !== 'Semua') {
            $this->db->where('ProfilUsaha.JenisUsaha', $jenis_usaha_get);
        }
        if (!empty($sektor_usaha_get) && $sektor_usaha_get !== 'Semua') {
            $this->db->where('ProfilUsaha.SektorUsaha', $sektor_usaha_get);
        }

        $this->db->where('ProfilUsaha.DeleteAt IS NULL', null, false);
        $this->db->order_by('ProfilUsaha.id', 'DESC');
        
        $data['profil_usaha_data'] = $this->db->get()->result();

        $data['title'] = 'Profil Usaha - Admin SINTAMIKA';
        $this->load->view('Admin/Header');
        $this->load->view('Admin/ProfilUsaha', $data);
    }

    public function get_lokasi_anak($id_parent) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $len = strlen($id_parent);
        $target_len = 0;
        
        if ($len == 2) $target_len = 5;      
        elseif ($len == 5) $target_len = 8;  
        elseif ($len == 8) $target_len = 13; 
        
        if ($target_len > 0) {
            $this->db->select('id, NamaDistrik');
            $this->db->where('id LIKE', $id_parent . '.%');
            $this->db->where('LENGTH(id)', $target_len);
            $data = $this->db->get('Distrik')->result();
            
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        }
        
        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(['status' => 'error', 'data' => []]));
    }

    public function get_profil($id) {
        $data = $this->db->where('id', $id)->where('DeleteAt IS NULL', null, false)->get('ProfilUsaha')->row();
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function save_profil() {
        $id = $this->input->post('id');
        $tahun = $this->input->post('Tahun');

        if (strlen((string)$tahun) !== 4 || (int)$tahun <= 2015) {
            echo json_encode(['status' => 'error', 'message' => 'Format Tahun salah. Harus 4 angka dan di atas tahun 2015.']);
            return;
        }

        $data = array(
            'NIB' => $this->input->post('NIB'),
            'JenisUsaha' => $this->input->post('JenisUsaha'), // MENERIMA INPUT JENIS USAHA BARU
            'NamaUsaha' => $this->input->post('NamaUsaha'),
            'NamaPemilik' => $this->input->post('NamaPemilik'),
            'SektorUsaha' => $this->input->post('SektorUsaha'), 
            'id_provinsi' => $this->input->post('id_provinsi'),
            'id_kabupaten' => $this->input->post('id_kabupaten'),
            'id_distrik' => $this->input->post('id_distrik'),
            'id_kampung' => $this->input->post('id_kampung'),
            'Alamat' => $this->input->post('Alamat'),
            'Tahun' => $tahun
        );

        if (empty($id)) {
            $insert = $this->db->insert('ProfilUsaha', $data);
            if ($insert) {
                echo json_encode(['status' => 'success', 'message' => 'Profil Usaha berhasil ditambahkan!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambah data.']);
            }
        } else {
            $this->db->where('id', $id);
            $update = $this->db->update('ProfilUsaha', $data);
            if ($update) {
                echo json_encode(['status' => 'success', 'message' => 'Profil Usaha berhasil diperbarui!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
            }
        }
    }

    public function delete_profil($id) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $this->db->where('id_profil', $id);
        $this->db->where('DeleteAt IS NULL', null, false);
        $cek_investasi = $this->db->count_all_results('DataInvestasi');

        if ($cek_investasi > 0) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode([
                                    'status' => 'error', 
                                    'message' => 'Data Profil Usaha tidak dapat dihapus karena masih memiliki informasi data investasi terkait!'
                                ]));
        }

        $this->db->where('id', $id);
        $this->db->db_debug = FALSE;
        $update = $this->db->update('ProfilUsaha', ['DeleteAt' => date('Y-m-d H:i:s')]);
        
        if ($update) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']));
        } else {
            $db_error = $this->db->error();
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $db_error['message']]));
        }
    }

    // ==========================================
    // BAGIAN DATA INVESTASI & TENAGA KERJA
    // ==========================================

    public function DataInvestasi() {
        $tahun_get = $this->input->get('tahun');
        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');

        if ($tahun <= 2015 || strlen((string)$tahun) !== 4) {
            $tahun = (int)date('Y');
        }

        $data['tahun_filter'] = $tahun;

        $data['profil_list'] = $this->db->where('DeleteAt IS NULL', null, false)
                                       ->order_by('NamaUsaha', 'DESC')
                                       ->get('ProfilUsaha')
                                       ->result();

        $this->db->select('DataInvestasi.*, ProfilUsaha.NamaUsaha, ProfilUsaha.NIB');
        $this->db->from('DataInvestasi');
        $this->db->join('ProfilUsaha', 'ProfilUsaha.id = DataInvestasi.id_profil', 'left');
        $this->db->where('DataInvestasi.Tahun', $tahun);
        $this->db->where('DataInvestasi.DeleteAt IS NULL', null, false);
        $this->db->order_by('DataInvestasi.id', 'DESC');
        $data['investasi_data'] = $this->db->get()->result();

        $data['title'] = 'Data Investasi & Tenaga Kerja - Admin SINTAMIKA';
        $this->load->view('Admin/Header');
        $this->load->view('Admin/DataInvestasi', $data);
    }

    public function get_investasi($id) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $data = $this->db->where('id', $id)
                         ->where('DeleteAt IS NULL', null, false)
                         ->get('DataInvestasi')
                         ->row();
                         
        if ($data) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']));
        }
    }

    public function save_investasi() {
        if (ob_get_level() > 0) ob_clean(); 
        
        $id = $this->input->post('id');
        $tahun = trim((string)$this->input->post('Tahun'));

        if (strlen($tahun) !== 4 || (int)$tahun <= 2015) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Format Tahun salah. Harus 4 angka dan di atas tahun 2015.']));
        }

        $data = array(
            'id_profil' => $this->input->post('id_profil'),
            'Tahun' => (int)$tahun,
            'NilaiInvestasiPMDN' => (double)$this->input->post('NilaiInvestasiPMDN'),
            'NilaiInvestasiPMA' => (double)$this->input->post('NilaiInvestasiPMA'),
            'TenagaKerjaLokal' => (int)$this->input->post('TenagaKerjaLokal'),
            'TenagaKerjaAsing' => (int)$this->input->post('TenagaKerjaAsing'),
            'UpdatedAt' => date('Y-m-d H:i:s')
        );

        $this->db->db_debug = FALSE; 

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataInvestasi', $data);
            if ($insert) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data Investasi berhasil ditambahkan!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menambah data DB: ' . $db_error['message']]));
            }
        } else {
            $this->db->where('id', $id);
            $update = $this->db->update('DataInvestasi', $data);
            if ($update) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data Investasi berhasil diperbarui!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal update data DB: ' . $db_error['message']]));
            }
        }
    }

    public function delete_investasi($id) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $this->db->where('id', $id);
        $this->db->db_debug = FALSE; 
        $update = $this->db->update('DataInvestasi', ['DeleteAt' => date('Y-m-d H:i:s')]);
        
        if ($update) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']));
        } else {
            $db_error = $this->db->error();
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal hapus data DB: ' . $db_error['message']]));
        }
    }

    // ==========================================
    // BAGIAN REKAP DATA MPP (OPD & INSTANSI) - FILTER DINAMIS
    // ==========================================

    public function OpdInstansi() {
        $tahun_get = $this->input->get('tahun');
        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        if ($tahun <= 2015 || strlen((string)$tahun) !== 4) {
            $tahun = (int)date('Y');
        }

        $periode = $this->input->get('periode') ?: 'all';
        $bulan_mulai_input = (int)($this->input->get('bulan_mulai') ?: 1);
        $bulan_selesai_input = (int)($this->input->get('bulan_selesai') ?: 12);

        $start_month = 1;
        $end_month = 12;

        switch ($periode) {
            case 'tw1': $start_month = 1; $end_month = 3; break;
            case 'tw2': $start_month = 4; $end_month = 6; break;
            case 'tw3': $start_month = 7; $end_month = 9; break;
            case 'tw4': $start_month = 10; $end_month = 12; break;
            case 'sm1': $start_month = 1; $end_month = 6; break;
            case 'sm2': $start_month = 7; $end_month = 12; break;
            case 'custom':
                $start_month = max(1, min(12, $bulan_mulai_input));
                $end_month = max($start_month, min(12, $bulan_selesai_input));
                break;
            case 'all':
            default:
                $start_month = 1; $end_month = 12; break;
        }

        // Ambil Data OPD
        $opd_list = $this->db->where('DeleteAt IS NULL', null, false)
                             ->order_by('id', 'DESC')
                             ->get('Opd')
                             ->result();

        foreach ($opd_list as $opd) {
            $pelayanan_list = $this->db->where('id_opd', $opd->id)
                                       ->where('DeleteAt IS NULL', null, false)
                                       ->order_by('id', 'DESC')
                                       ->get('PelayananMpp')
                                       ->result();

            foreach ($pelayanan_list as $ply) {
                // Ambil rekap bulanan sesuai rentang filter
                $rekap = $this->db->where('id_pelayanan', $ply->id)
                                  ->where('Tahun', $tahun)
                                  ->where('Bulan >=', $start_month)
                                  ->where('Bulan <=', $end_month)
                                  ->where('DeleteAt IS NULL', null, false)
                                  ->get('RekapMppBulan')
                                  ->result();

                $data_bulan = array();
                for ($m = $start_month; $m <= $end_month; $m++) {
                    $data_bulan[$m] = 0;
                }

                foreach ($rekap as $rk) {
                    $data_bulan[$rk->Bulan] = (int)$rk->Jumlah;
                }

                // Ambil juga full 12 bulan untuk keperluan form modal edit
                $rekap_full = $this->db->where('id_pelayanan', $ply->id)
                                       ->where('Tahun', $tahun)
                                       ->where('DeleteAt IS NULL', null, false)
                                       ->get('RekapMppBulan')
                                       ->result();
                $full_12_bulan = array_fill(1, 12, 0);
                foreach ($rekap_full as $rf) {
                    $full_12_bulan[$rf->Bulan] = (int)$rf->Jumlah;
                }

                $ply->rekap_bulan = $data_bulan;
                $ply->full_12_bulan = $full_12_bulan;
            }

            $opd->pelayanan = $pelayanan_list;
        }

        $data['opd_data'] = $opd_list;
        $data['selected_tahun'] = $tahun;
        $data['selected_periode'] = $periode;
        $data['start_month'] = $start_month;
        $data['end_month'] = $end_month;
        $data['bulan_mulai'] = $bulan_mulai_input;
        $data['bulan_selesai'] = $bulan_selesai_input;
        $data['title'] = 'Rekap Data MPP - Admin SINTAMIKA';

        $this->load->view('Admin/Header');
        $this->load->view('Admin/OpdInstansi', $data);
    }

    public function get_opd($id) {
        if (ob_get_level() > 0) ob_clean();
        $data = $this->db->where('id', $id)->where('DeleteAt IS NULL', null, false)->get('Opd')->row();
        if ($data) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data OPD tidak ditemukan']));
        }
    }

    public function save_opd_batch() {
        if (ob_get_level() > 0) ob_clean();
        $opd_array = $this->input->post('opd');

        if (empty($opd_array) || !is_array($opd_array)) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Tidak ada data OPD yang dikirim.']));
        }

        $this->db->trans_start();
        foreach ($opd_array as $item) {
            $id = !empty($item['id']) ? $item['id'] : null;
            $nama = trim($item['NamaOpd']);

            if (empty($nama)) continue;

            if (empty($id)) {
                $this->db->insert('Opd', ['NamaOpd' => $nama, 'InputAt' => date('Y-m-d H:i:s')]);
            } else {
                $this->db->where('id', $id)->update('Opd', ['NamaOpd' => $nama, 'UpdatedAt' => date('Y-m-d H:i:s')]);
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data OPD.']));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(['status' => 'success', 'message' => 'Data Instansi/OPD berhasil disimpan.']));
    }

    public function delete_opd($id) {
        if (ob_get_level() > 0) ob_clean();
        $now = date('Y-m-d H:i:s');
        
        $this->db->trans_start();
        $this->db->where('id', $id)->update('Opd', ['DeleteAt' => $now]);
        
        // Soft delete sub-pelayanan
        $pelayanan_ids = $this->db->select('id')->where('id_opd', $id)->get('PelayananMpp')->result_array();
        if (!empty($pelayanan_ids)) {
            $ids = array_column($pelayanan_ids, 'id');
            $this->db->where_in('id', $ids)->update('PelayananMpp', ['DeleteAt' => $now]);
            $this->db->where_in('id_pelayanan', $ids)->update('RekapMppBulan', ['DeleteAt' => $now]);
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(['status' => 'success', 'message' => 'Instansi berhasil dihapus.']));
    }

    public function get_pelayanan_opd($id_opd, $tahun = null) {
        if (ob_get_level() > 0) ob_clean();
        $tahun = !empty($tahun) ? (int)$tahun : (int)date('Y');

        $opd = $this->db->where('id', $id_opd)->get('Opd')->row();
        if (!$opd) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'OPD tidak ditemukan.']));
        }

        $pelayanan = $this->db->where('id_opd', $id_opd)
                              ->where('DeleteAt IS NULL', null, false)
                              ->get('PelayananMpp')
                              ->result();

        foreach ($pelayanan as $p) {
            $rekap = $this->db->where('id_pelayanan', $p->id)
                              ->where('Tahun', $tahun)
                              ->where('DeleteAt IS NULL', null, false)
                              ->get('RekapMppBulan')
                              ->result();
            $data_bulan = array_fill(1, 12, 0);
            foreach ($rekap as $rk) {
                $data_bulan[$rk->Bulan] = (int)$rk->Jumlah;
            }
            $p->data_bulan = $data_bulan;
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode([
                                'status' => 'success',
                                'data' => [
                                    'opd' => $opd,
                                    'pelayanan' => $pelayanan,
                                    'tahun' => $tahun
                                ]
                            ]));
    }

    public function save_pelayanan() {
        if (ob_get_level() > 0) ob_clean();

        $id_opd = $this->input->post('id_opd');
        $tahun = (int)$this->input->post('Tahun');

        if (empty($id_opd) || $tahun <= 2015) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data OPD atau Tahun tidak valid.']));
        }

        $pelayanan_input = $this->input->post('Pelayanan');
        if (!is_array($pelayanan_input)) {
            $pelayanan_input = array();
        }

        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();

        // 1. Ambil semua ID sub-pelayanan aktif yang ada di database untuk OPD ini
        $existing_pelayanan = $this->db->select('id')
                                       ->where('id_opd', $id_opd)
                                       ->where('DeleteAt IS NULL', null, false)
                                       ->get('PelayananMpp')
                                       ->result_array();
        $existing_ids = array_column($existing_pelayanan, 'id');

        // 2. Kumpulkan ID sub-pelayanan yang dikirimkan dari form modal edit
        $submitted_ids = array();
        foreach ($pelayanan_input as $p) {
            if (!empty($p['id_pelayanan'])) {
                $submitted_ids[] = (int)$p['id_pelayanan'];
            }
        }

        // 3. Deteksi sub-pelayanan yang dihapus user (ada di DB tapi tidak dikirim lagi dari form modal)
        $ids_to_delete = array_diff($existing_ids, $submitted_ids);

        // 4. Lakukan Soft Delete untuk sub-pelayanan yang dihapus beserta rekap bulanannya
        if (!empty($ids_to_delete)) {
            $this->db->where_in('id', $ids_to_delete)->update('PelayananMpp', ['DeleteAt' => $now]);
            $this->db->where_in('id_pelayanan', $ids_to_delete)->update('RekapMppBulan', ['DeleteAt' => $now]);
        }

        // 5. Simpan / Update data sub-pelayanan dan rekap bulanannya
        foreach ($pelayanan_input as $p) {
            $id_pelayanan = !empty($p['id_pelayanan']) ? $p['id_pelayanan'] : null;
            $nama_pelayanan = trim($p['NamaPelayanan']);

            if (empty($nama_pelayanan)) continue;

            if (empty($id_pelayanan)) {
                $this->db->insert('PelayananMpp', [
                    'id_opd' => $id_opd,
                    'NamaPelayanan' => $nama_pelayanan,
                    'InputAt' => $now
                ]);
                $id_pelayanan = $this->db->insert_id();
            } else {
                $this->db->where('id', $id_pelayanan)->update('PelayananMpp', [
                    'NamaPelayanan' => $nama_pelayanan,
                    'UpdatedAt' => $now,
                    'DeleteAt' => NULL
                ]);
            }

            if (isset($p['Bulan']) && is_array($p['Bulan'])) {
                for ($b = 1; $b <= 12; $b++) {
                    $jml = isset($p['Bulan'][$b]) ? (int)$p['Bulan'][$b] : 0;
                    $exist = $this->db->where('id_pelayanan', $id_pelayanan)
                                      ->where('Tahun', $tahun)
                                      ->where('Bulan', $b)
                                      ->get('RekapMppBulan')
                                      ->row();

                    if ($exist) {
                        $this->db->where('id', $exist->id)->update('RekapMppBulan', [
                            'Jumlah' => $jml,
                            'DeleteAt' => NULL,
                            'UpdatedAt' => $now
                        ]);
                    } else {
                        $this->db->insert('RekapMppBulan', [
                            'id_pelayanan' => $id_pelayanan,
                            'Bulan' => $b,
                            'Tahun' => $tahun,
                            'Jumlah' => $jml,
                            'InputAt' => $now
                        ]);
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menyimpan rekap pelayanan.']));
        }

        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(['status' => 'success', 'message' => 'Rekap pelayanan berhasil disimpan.']));
    }

    // ==========================================
    // DATA KEPALA DINAS (TUNGGAL)
    // ==========================================
    public function KepalaDinas() {
        $data['title'] = 'Data Kepala Dinas - Admin SINTAMIKA';
        
        // Ambil 1 baris data Kepala Dinas yang belum dihapus
        $this->db->where('DeleteAt IS NULL', null, false);
        $data['kadis'] = $this->db->get('KepalaDinas')->row();

        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/KepalaDinas', $data);
    }

    public function save_kepala_dinas() {
        if (ob_get_level() > 0) ob_clean();
        
        $id = $this->input->post('id');
        $nama = trim($this->input->post('Nama'));
        $pangkat = trim($this->input->post('Pangkat'));
        $nip = trim($this->input->post('NIP'));

        if (empty($nama) || empty($nip)) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Nama dan NIP wajib diisi!']));
        }

        $data = array(
            'Nama' => $nama,
            'Pangkat' => $pangkat,
            'NIP' => $nip
        );

        if (!empty($id)) {
            $data['UpdatedAt'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $update = $this->db->update('KepalaDinas', $data);
        } else {
            // Jaga-jaga jika database awal masih kosong
            $data['InputAt'] = date('Y-m-d H:i:s');
            $update = $this->db->insert('KepalaDinas', $data);
        }

        if ($update) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Data Kepala Dinas berhasil diperbarui!']));
        } else {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']));
        }
    }

    // ==========================================
    // PENGATURAN AKUN (MENU BARU)
    // ==========================================
    public function PengaturanAkun() {
        $data['title'] = 'Pengaturan Akun Admin - SINTAMIKA';
        
        // Asumsi admin hanya punya 1 akun
        // Di sini kita ambil baris pertama dari tabel AkunAdmin
        $data['akun'] = $this->db->get('AkunAdmin')->row(); 
        
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/PengaturanAkun', $data);
    }

    public function update_akun() {
        if (ob_get_level() > 0) ob_clean();
        
        // Tangkap old_username dari hidden input form
        $old_username = trim($this->input->post('old_username'));
        $new_username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        if (empty($new_username)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Username tidak boleh kosong!']));
        }

        // Persiapkan data dengan key sesuai camelCase/PascalCase di DB
        $data = array('Username' => $new_username);

        // Jika password diisi, update password menggunakan password_hash
        if (!empty($password)) {
            $data['Password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Pastikan kita memperbarui menggunakan 'old_username' sebagai patokan primary key
        // Jika belum ada old_username (data kosong di DB), kita asumsikan insert? 
        // Namun sesuai instruksi, ini adalah proses update
        $this->db->where('Username', $old_username);
        $update = $this->db->update('AkunAdmin', $data);

        if ($update) {
            // Update session jika sedang login menggunakan username baru
            $this->session->set_userdata('admin_username', $new_username);
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success', 'message' => 'Informasi Akun berhasil diperbarui.']));
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui informasi akun.']));
        }
    }

    // ==========================================
    // BAGIAN DATA SIUJK (IZIN USAHA JASA KONSTRUKSI)
    // ==========================================

    public function DataSiujk() {
        // Menerima input filter GET
        $tahun_get = $this->input->get('tahun');
        $keterangan_get = $this->input->get('keterangan');

        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        
        // Pass filter aktif kembali ke View
        $data['tahun_filter'] = $tahun;
        $data['keterangan_filter'] = !empty($keterangan_get) ? $keterangan_get : 'Semua';

        // Query Utama Data SIUJK
        $this->db->select('*');
        $this->db->from('DataSiujk');
        $this->db->where('Tahun', $tahun);
        
        // Aplikasikan Filter Query Jika Dipilih
        if (!empty($keterangan_get) && $keterangan_get !== 'Semua') {
            $this->db->where('Keterangan', $keterangan_get);
        }

        $this->db->where('DeleteAt IS NULL', null, false);
        $this->db->order_by('id', 'DESC');
        
        $data['siujk_data'] = $this->db->get()->result();

        $data['title'] = 'Data SIUJK - Admin SINTAMIKA';
        $this->load->view('Admin/Header', $data); // Pastikan Header di load
        $this->load->view('Admin/DataSiujk', $data);
    }

    public function get_siujk($id) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $data = $this->db->where('id', $id)->where('DeleteAt IS NULL', null, false)->get('DataSiujk')->row();
        
        if ($data) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']));
        }
    }

    public function save_siujk() {
        if (ob_get_level() > 0) ob_clean(); 

        $id = $this->input->post('id');
        $tahun = $this->input->post('Tahun');

        if (strlen((string)$tahun) !== 4 || (int)$tahun <= 2015) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Format Tahun salah. Harus 4 angka dan di atas tahun 2015.']));
        }

        $data = array(
            'NomorAdvis' => $this->input->post('NomorAdvis'),
            'NamaPerusahaan' => $this->input->post('NamaPerusahaan'),
            'Jalan' => $this->input->post('Jalan'),
            'KelDistrik' => $this->input->post('KelDistrik'),
            'RtRw' => $this->input->post('RtRw'),
            'NamaPenanggungjawab' => $this->input->post('NamaPenanggungjawab'),
            'NpwpPerusahaan' => $this->input->post('NpwpPerusahaan'),
            'MasaBerlaku' => $this->input->post('MasaBerlaku'),
            'TanggalCetak' => $this->input->post('TanggalCetak'),
            'Keterangan' => $this->input->post('Keterangan'),
            'Tahun' => $tahun
        );

        $this->db->db_debug = FALSE;

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataSiujk', $data);
            if ($insert) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUJK berhasil ditambahkan!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menambah data: ' . $db_error['message']]));
            }
        } else {
            $data['UpdatedAt'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $update = $this->db->update('DataSiujk', $data);
            if ($update) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUJK berhasil diperbarui!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $db_error['message']]));
            }
        }
    }

    public function delete_siujk($id) {
        if (ob_get_level() > 0) ob_clean(); 
        
        $this->db->where('id', $id);
        $this->db->db_debug = FALSE;
        
        // Soft delete
        $update = $this->db->update('DataSiujk', ['DeleteAt' => date('Y-m-d H:i:s')]);
        
        if ($update) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUJK berhasil dihapus']));
        } else {
            $db_error = $this->db->error();
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $db_error['message']]));
        }
    }

    // ==========================================
    // BAGIAN DATA SIUP (SURAT IZIN USAHA PERDAGANGAN)
    // ==========================================

    public function DataSiup() {
        // Menerima input filter dari URL (GET)
        $tahun_get = $this->input->get('tahun');
        $kelembagaan_get = $this->input->get('kelembagaan');

        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        $kelembagaan = !empty($kelembagaan_get) ? trim($kelembagaan_get) : 'Semua';

        // Oper nilai filter ke View
        $data['tahun_filter'] = $tahun;
        $data['kelembagaan_filter'] = $kelembagaan;

        // Query database DataSiup
        $this->db->select('*');
        $this->db->from('DataSiup');
        $this->db->where('Tahun', $tahun);

        if (!empty($kelembagaan) && $kelembagaan !== 'Semua') {
            $this->db->like('Kelembagaan', $kelembagaan);
        }

        $this->db->where('DeleteAt IS NULL', null, false);
        $this->db->order_by('id', 'DESC');

        $data['siup_data'] = $this->db->get()->result();
        $data['title'] = 'Data SIUP - Admin SINTAMIKA';

        // Load halaman View
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/DataSiup', $data);
    }

    public function get_siup($id) {
        if (ob_get_level() > 0) ob_clean();

        $data = $this->db->where('id', $id)->where('DeleteAt IS NULL', null, false)->get('DataSiup')->row();

        if ($data) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']));
        }
    }

    public function save_siup() {
        if (ob_get_level() > 0) ob_clean();

        $id = $this->input->post('id');
        $tahun = $this->input->post('Tahun');

        if (empty($tahun) || strlen((string)$tahun) !== 4 || (int)$tahun <= 2010) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Format Tahun tidak valid. Harus 4 digit angka di atas 2010.']));
        }

        $nama_perusahaan = $this->input->post('NamaPerusahaan');
        if (empty($nama_perusahaan)) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Nama Perusahaan wajib diisi!']));
        }

        $data = array(
            'NomorAdvis' => $this->input->post('NomorAdvis'),
            'NamaPerusahaan' => $nama_perusahaan,
            'NamaPenanggungjawabJabatan' => $this->input->post('NamaPenanggungjawabJabatan'),
            'AlamatPerusahaanDireksi' => $this->input->post('AlamatPerusahaanDireksi'),
            'KekayaanBersih' => $this->input->post('KekayaanBersih'),
            'Kelembagaan' => $this->input->post('Kelembagaan'),
            'KegiatanUsahaKbli' => $this->input->post('KegiatanUsahaKbli'),
            'Direktur' => $this->input->post('Direktur'),
            'BarangJasaUtama' => $this->input->post('BarangJasaUtama'),
            'TanggalKeluar' => !empty($this->input->post('TanggalKeluar')) ? $this->input->post('TanggalKeluar') : null,
            'Tahun' => $tahun
        );

        $this->db->db_debug = FALSE;

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataSiup', $data);

            if ($insert) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUP berhasil ditambahkan!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menambah data: ' . $db_error['message']]));
            }
        } else {
            $data['UpdatedAt'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $update = $this->db->update('DataSiup', $data);

            if ($update) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUP berhasil diperbarui!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $db_error['message']]));
            }
        }
    }

    public function delete_siup($id) {
        if (ob_get_level() > 0) ob_clean();

        $this->db->where('id', $id);
        $this->db->db_debug = FALSE;

        $update = $this->db->update('DataSiup', ['DeleteAt' => date('Y-m-d H:i:s')]);

        if ($update) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'message' => 'Data SIUP berhasil dihapus']));
        } else {
            $db_error = $this->db->error();
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $db_error['message']]));
        }
    }

    // ==========================================
    // BAGIAN DATA PBG (PERSETUJUAN BANGUNAN GEDUNG)
    // ==========================================

    public function DataPbg() {
        // Menerima input filter GET
        $tahun_get = $this->input->get('tahun');
        $distrik_get = $this->input->get('distrik');

        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');
        $distrik = !empty($distrik_get) ? trim($distrik_get) : 'Semua';

        $data['tahun_filter'] = $tahun;
        $data['distrik_filter'] = $distrik;

        // Query database DataPbg
        $this->db->select('*');
        $this->db->from('DataPbg');
        $this->db->where('Tahun', $tahun);

        if (!empty($distrik) && $distrik !== 'Semua') {
            $this->db->like('Distrik', $distrik);
        }

        $this->db->where('DeleteAt IS NULL', null, false);
        $this->db->order_by('id', 'DESC');

        $data['pbg_data'] = $this->db->get()->result();
        $data['title'] = 'Data PBG - Admin SINTAMIKA';

        // Load Halaman View
        $this->load->view('Admin/Header', $data);
        $this->load->view('Admin/DataPbg', $data);
    }

    public function get_pbg($id) {
        if (ob_get_level() > 0) ob_clean();

        $data = $this->db->where('id', $id)->where('DeleteAt IS NULL', null, false)->get('DataPbg')->row();

        if ($data) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } else {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Data PBG tidak ditemukan']));
        }
    }

    public function save_pbg() {
        if (ob_get_level() > 0) ob_clean();

        $id = $this->input->post('id');
        $tahun = $this->input->post('Tahun');

        if (empty($tahun) || strlen((string)$tahun) !== 4 || (int)$tahun <= 2010) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Format Tahun tidak valid. Harus 4 digit angka di atas 2010.']));
        }

        $nama_pemilik = $this->input->post('NamaPemilikBangunan');
        if (empty($nama_pemilik)) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Nama Pemilik Bangunan wajib diisi!']));
        }

        $data = array(
            'NomorPermohonan' => $this->input->post('NomorPermohonan'),
            'NomorSkPbg' => $this->input->post('NomorSkPbg'),
            'NamaPemilikBangunan' => $nama_pemilik,
            'AlamatPemilikBangunan' => $this->input->post('AlamatPemilikBangunan'),
            'GunaBangunan' => $this->input->post('GunaBangunan'),
            'NamaBangunanGedung' => $this->input->post('NamaBangunanGedung'),
            'FungsiBangunanGedung' => $this->input->post('FungsiBangunanGedung'),
            'SubFungsiBangunanGedung' => $this->input->post('SubFungsiBangunanGedung'),
            'KlasifikasiKompleksitas' => $this->input->post('KlasifikasiKompleksitas'),
            'KelasBangunan' => $this->input->post('KelasBangunan'),
            'TotalLuas' => !empty($this->input->post('TotalLuas')) ? $this->input->post('TotalLuas') : 0.00,
            'LuasLantai' => !empty($this->input->post('LuasLantai')) ? $this->input->post('LuasLantai') : 0.00,
            'LuasBasemen' => !empty($this->input->post('LuasBasemen')) ? $this->input->post('LuasBasemen') : 0.00,
            'JumlahLantaiBangunan' => !empty($this->input->post('JumlahLantaiBangunan')) ? $this->input->post('JumlahLantaiBangunan') : 0,
            'TinggiBangunanGedung' => !empty($this->input->post('TinggiBangunanGedung')) ? $this->input->post('TinggiBangunanGedung') : 0.00,
            'JumlahUnitBangunan' => !empty($this->input->post('JumlahUnitBangunan')) ? $this->input->post('JumlahUnitBangunan') : 0,
            'JumlahLapisBasemen' => !empty($this->input->post('JumlahLapisBasemen')) ? $this->input->post('JumlahLapisBasemen') : 0,
            'DiatasTanah' => $this->input->post('DiatasTanah'),
            'LuasTanah' => !empty($this->input->post('LuasTanah')) ? $this->input->post('LuasTanah') : 0.00,
            'PemilikTanah' => $this->input->post('PemilikTanah'),
            'AlamatTanah' => $this->input->post('AlamatTanah'),
            'KelurahanDesa' => $this->input->post('KelurahanDesa'),
            'Distrik' => $this->input->post('Distrik'),
            'Tahun' => $tahun
        );

        $this->db->db_debug = FALSE;

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataPbg', $data);

            if ($insert) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data PBG berhasil ditambahkan!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menambah data: ' . $db_error['message']]));
            }
        } else {
            $data['UpdatedAt'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $update = $this->db->update('DataPbg', $data);

            if ($update) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data PBG berhasil diperbarui!']));
            } else {
                $db_error = $this->db->error();
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $db_error['message']]));
            }
        }
    }

    public function delete_pbg($id) {
        if (ob_get_level() > 0) ob_clean();

        $this->db->where('id', $id);
        $this->db->db_debug = FALSE;

        $update = $this->db->update('DataPbg', ['DeleteAt' => date('Y-m-d H:i:s')]);

        if ($update) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'success', 'message' => 'Data PBG berhasil dihapus']));
        } else {
            $db_error = $this->db->error();
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $db_error['message']]));
        }
    }
}