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
    // BAGIAN PROFIL USAHA
    // ==========================================
    
    public function ProfilUsaha() {
        // Ambil filter tahun, default tahun saat ini
        $tahun_get = $this->input->get('tahun');
        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');

        // Mengambil data distrik untuk dropdown (asumsi Anda punya tabel Distrik)
        $data['distrik_list'] = $this->db->get('Distrik')->result();
        $data['tahun_filter'] = $tahun;

        // Query Profil Usaha beserta join ke Distrik, difilter berdasarkan Tahun
        $this->db->select('ProfilUsaha.*, Distrik.NamaDistrik');
        $this->db->from('ProfilUsaha');
        $this->db->join('Distrik', 'Distrik.id = ProfilUsaha.id_distrik', 'left');
        $this->db->where('ProfilUsaha.Tahun', $tahun);
        $this->db->where('ProfilUsaha.DeleteAt IS NULL', null, false);
        $this->db->order_by('ProfilUsaha.id', 'DESC');
        $data['profil_usaha_data'] = $this->db->get()->result();

        $data['title'] = 'Profil Usaha - Admin SINTAMIKA';
        $this->load->view('Admin/Header');
        // Load view (Header sudah diload di view file utama jika digabung, atau pisah sesuai struktur Anda)
        $this->load->view('Admin/ProfilUsaha', $data);
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

        // Validasi Tahun di Sisi Server (Backend)
        if (strlen((string)$tahun) !== 4 || (int)$tahun <= 2015) {
            echo json_encode(['status' => 'error', 'message' => 'Format Tahun salah. Harus 4 angka dan di atas tahun 2015.']);
            return;
        }

        $data = array(
            'NIB' => $this->input->post('NIB'),
            'NamaUsaha' => $this->input->post('NamaUsaha'),
            'NamaPemilik' => $this->input->post('NamaPemilik'),
            'SektorUsaha' => $this->input->post('SektorUsaha'), // String manual
            'id_distrik' => $this->input->post('id_distrik'),
            'Alamat' => $this->input->post('Alamat'),
            'Tahun' => $tahun
        );

        if (empty($id)) {
            // Tambah Data
            $insert = $this->db->insert('ProfilUsaha', $data);
            if ($insert) {
                echo json_encode(['status' => 'success', 'message' => 'Profil Usaha berhasil ditambahkan!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambah data.']);
            }
        } else {
            // Update Data
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
        
        // Pengecekan apakah Profil Usaha masih memiliki Data Investasi yang aktif
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

        // Jika tidak ada data investasi terkait, lanjutkan proses hapus (soft delete)
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
    // BAGIAN DATA INVESTASI & TENAGA KERJA (DIPERBAIKI)
    // ==========================================

    public function DataInvestasi() {
        $tahun_get = $this->input->get('tahun');
        $tahun = !empty($tahun_get) ? (int)$tahun_get : (int)date('Y');

        if ($tahun <= 2015 || strlen((string)$tahun) !== 4) {
            $tahun = (int)date('Y');
        }

        $data['tahun_filter'] = $tahun;

        $data['profil_list'] = $this->db->where('DeleteAt IS NULL', null, false)
                                       ->order_by('NamaUsaha', 'ASC')
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
        // PERBAIKAN: Gunakan ob_get_level() untuk mencegah PHP Notice
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
        // PERBAIKAN: Mencegah output HTML merusak JSON
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
            'JenisInvestasi' => $this->input->post('JenisInvestasi'),
            'NilaiInvestasi' => (double)$this->input->post('NilaiInvestasi'),
            'TenagaKerjaLokal' => (int)$this->input->post('TenagaKerjaLokal'),
            'TenagaKerjaAsing' => (int)$this->input->post('TenagaKerjaAsing'),
            'UpdatedAt' => date('Y-m-d H:i:s')
        );

        // PERBAIKAN KRUSIAL: Matikan debug DB agar CI tidak mencetak halaman HTML Error 
        // yang membuat AJAX mengira terjadi "Kesalahan Server/Koneksi"
        $this->db->db_debug = FALSE; 

        if (empty($id)) {
            $data['InputAt'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('DataInvestasi', $data);
            if ($insert) {
                return $this->output->set_content_type('application/json')
                                    ->set_output(json_encode(['status' => 'success', 'message' => 'Data Investasi berhasil ditambahkan!']));
            } else {
                // Tangkap error spesifik database
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
        $this->db->db_debug = FALSE; // Matikan HTML error
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
                             ->order_by('id', 'ASC')
                             ->get('Opd')
                             ->result();

        foreach ($opd_list as $opd) {
            $pelayanan_list = $this->db->where('id_opd', $opd->id)
                                       ->where('DeleteAt IS NULL', null, false)
                                       ->order_by('id', 'ASC')
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
}