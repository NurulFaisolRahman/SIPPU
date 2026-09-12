<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SINTAMIKA extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load library dan helper yang dibutuhkan
        $this->load->library('session');
        $this->load->helper('url');
        
        // Load database (pastikan pengaturan database di application/config/database.php sudah benar)
        $this->load->database(); 
    }

    public function index() {
        $tahun_selected = $this->input->get('tahun') ? $this->input->get('tahun') : 2026;
        $distrik_selected = $this->input->get('distrik') ? $this->input->get('distrik') : null;

        // Data untuk dikirim ke View
        $data['tahun_selected']   = $tahun_selected;
        $data['distrik_selected'] = $distrik_selected;
        $data['list_tahun']       = $this->_get_tahun_options();
        $data['list_distrik']     = $this->_get_all_distrik();

        // 1. KPI Summary
        $data['kpi']              = $this->_get_kpi_summary($tahun_selected, $distrik_selected);
        
        // 2. Heatmap Sebaran Investasi
        $data['heatmap']          = $this->_get_heatmap_distrik($tahun_selected);
        
        // 3. Trend Graph Data
        $data['trend_data']       = $this->_get_trend_bulanan($tahun_selected);
        
        // 4. Ranking Sektor Usaha
        $data['ranking_sektor']   = $this->_get_ranking_sektor($tahun_selected, $distrik_selected);
        
        // 5. Top 5 Distrik
        $data['top_distrik']      = $this->_get_top_distrik($tahun_selected);
        
        // 6. Data NIB Terbaru
        $data['nib_terbaru']      = $this->_get_nib_terbaru(5);
        
        // 7. Statistik Sektor (Donut Chart)
        $data['stat_sektor']      = $this->_get_stat_sektor($tahun_selected, $distrik_selected);
        
        // 8. Informasi Cepat
        $data['info_cepat']       = $this->_get_informasi_cepat($tahun_selected);

        $this->load->view('index', $data);
    }

    // 1. Ambil daftar tahun unik dari database
    private function _get_tahun_options() {
        $query = $this->db->query("
            SELECT DISTINCT Tahun FROM (
                SELECT Tahun FROM ProfilUsaha WHERE DeleteAt IS NULL
                UNION
                SELECT Tahun FROM DataInvestasi WHERE DeleteAt IS NULL
                UNION
                SELECT Tahun FROM RekapIzinBulan WHERE DeleteAt IS NULL
                UNION
                SELECT Tahun FROM RekapMppBulan WHERE DeleteAt IS NULL
            ) t ORDER BY Tahun DESC
        ");
        return $query->result_array();
    }

    // 2. Ambil daftar distrik HANYA untuk Kabupaten Mimika
    // Kode Kemendagri Mimika Lama: 91.09, Baru (Papua Tengah): 94.04
    // Level Distrik memiliki panjang 8 karakter (XX.XX.XX)
    private function _get_all_distrik() {
        $this->db->where('(id LIKE "91.09.%" OR id LIKE "94.04.%")');
        $this->db->where('CHAR_LENGTH(id)', 8);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('Distrik')->result_array();
    }

    // 3. Ringkasan KPI Cards
    private function _get_kpi_summary($tahun = null, $id_distrik = null) {
        $where_pu = "WHERE pu.DeleteAt IS NULL";
        if (!empty($tahun)) $where_pu .= " AND pu.Tahun = ".$this->db->escape($tahun);
        if (!empty($id_distrik)) $where_pu .= " AND pu.id_distrik = ".$this->db->escape($id_distrik);

        $where_di = "WHERE di.DeleteAt IS NULL AND pu.DeleteAt IS NULL";
        if (!empty($tahun)) $where_di .= " AND di.Tahun = ".$this->db->escape($tahun);
        if (!empty($id_distrik)) $where_di .= " AND pu.id_distrik = ".$this->db->escape($id_distrik);

        // Total Pelaku Usaha
        $total_pu = $this->db->query("SELECT COUNT(pu.id) as total FROM ProfilUsaha pu $where_pu")->row()->total ?? 0;

        // NIB Aktif
        $nib_aktif = $this->db->query("SELECT COUNT(DISTINCT pu.NIB) as total FROM ProfilUsaha pu $where_pu AND pu.NIB IS NOT NULL AND pu.NIB != ''")->row()->total ?? 0;

        // Investasi PMDN (Kolom Baru)
        $pmdn = $this->db->query("SELECT COALESCE(SUM(di.NilaiInvestasiPMDN), 0) as total FROM DataInvestasi di JOIN ProfilUsaha pu ON di.id_profil = pu.id $where_di")->row()->total ?? 0;

        // Investasi PMA (Kolom Baru)
        $pma = $this->db->query("SELECT COALESCE(SUM(di.NilaiInvestasiPMA), 0) as total FROM DataInvestasi di JOIN ProfilUsaha pu ON di.id_profil = pu.id $where_di")->row()->total ?? 0;

        // Total Tenaga Kerja
        $tk = $this->db->query("SELECT COALESCE(SUM(di.TenagaKerjaLokal + di.TenagaKerjaAsing), 0) as total FROM DataInvestasi di JOIN ProfilUsaha pu ON di.id_profil = pu.id $where_di")->row()->total ?? 0;

        return [
            'total_pelaku_usaha' => (int)$total_pu,
            'nib_aktif'          => (int)$nib_aktif,
            'investasi_pmdn'     => (float)$pmdn,
            'investasi_pma'      => (float)$pma,
            'total_tenaga_kerja' => (int)$tk
        ];
    }

    // 4. Heatmap Sebaran Investasi (Hanya 18 Distrik Mimika)
    private function _get_heatmap_distrik($tahun = null) {
        $tahun_filter = !empty($tahun) ? "AND di.Tahun = ".$this->db->escape($tahun) : "";

        $sql = "
            SELECT 
                d.id, 
                d.NamaDistrik, 
                COALESCE(SUM(di.NilaiInvestasiPMDN + di.NilaiInvestasiPMA), 0) as total_investasi,
                COUNT(DISTINCT pu.id) as total_usaha
            FROM Distrik d
            LEFT JOIN ProfilUsaha pu ON d.id = pu.id_distrik AND pu.DeleteAt IS NULL
            LEFT JOIN DataInvestasi di ON pu.id = di.id_profil AND di.DeleteAt IS NULL $tahun_filter
            WHERE (d.id LIKE '91.09.%' OR d.id LIKE '94.04.%') AND CHAR_LENGTH(d.id) = 8
            GROUP BY d.id, d.NamaDistrik
            ORDER BY d.id ASC
        ";

        return $this->db->query($sql)->result_array();
    }

    // 5. Ranking Sektor Usaha (PMDN + PMA)
    private function _get_ranking_sektor($tahun = null, $id_distrik = null) {
        $where = "WHERE pu.DeleteAt IS NULL";
        if (!empty($tahun)) $where .= " AND pu.Tahun = ".$this->db->escape($tahun);
        if (!empty($id_distrik)) $where .= " AND pu.id_distrik = ".$this->db->escape($id_distrik);

        $sql = "
            SELECT 
                pu.SektorUsaha,
                COALESCE(SUM(di.NilaiInvestasiPMDN + di.NilaiInvestasiPMA), 0) as total_investasi,
                COUNT(DISTINCT pu.id) as total_usaha
            FROM ProfilUsaha pu
            LEFT JOIN DataInvestasi di ON pu.id = di.id_profil AND di.DeleteAt IS NULL
            $where
            GROUP BY pu.SektorUsaha
            ORDER BY total_investasi DESC, total_usaha DESC
        ";

        return $this->db->query($sql)->result_array();
    }

    // 6. Top 5 Distrik Berdasarkan Nilai Investasi (Hanya Distrik Mimika)
    private function _get_top_distrik($tahun = null) {
        $tahun_filter = !empty($tahun) ? "AND di.Tahun = ".$this->db->escape($tahun) : "";

        $sql = "
            SELECT 
                d.NamaDistrik,
                COALESCE(SUM(di.NilaiInvestasiPMDN + di.NilaiInvestasiPMA), 0) as total_investasi
            FROM Distrik d
            LEFT JOIN ProfilUsaha pu ON d.id = pu.id_distrik AND pu.DeleteAt IS NULL
            LEFT JOIN DataInvestasi di ON pu.id = di.id_profil AND di.DeleteAt IS NULL $tahun_filter
            WHERE (d.id LIKE '91.09.%' OR d.id LIKE '94.04.%') AND CHAR_LENGTH(d.id) = 8
            GROUP BY d.id, d.NamaDistrik
            ORDER BY total_investasi DESC
            LIMIT 5
        ";

        return $this->db->query($sql)->result_array();
    }

    // 7. Data NIB Aktif / Profil Usaha Terbaru (5 Terakhir)
    private function _get_nib_terbaru($limit = 5) {
        $this->db->select('pu.*, d.NamaDistrik');
        $this->db->from('ProfilUsaha pu');
        $this->db->join('Distrik d', 'pu.id_distrik = d.id', 'left');
        $this->db->where('pu.DeleteAt', NULL);
        $this->db->order_by('pu.InputAt', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    // 8. Statistik Sektor Usaha (Perhitungan Jumlah Usaha)
    private function _get_stat_sektor($tahun = null, $id_distrik = null) {
        $where = "WHERE DeleteAt IS NULL";
        if (!empty($tahun)) $where .= " AND Tahun = ".$this->db->escape($tahun);
        if (!empty($id_distrik)) $where .= " AND id_distrik = ".$this->db->escape($id_distrik);

        $sql = "
            SELECT 
                SektorUsaha, 
                COUNT(id) as total 
            FROM ProfilUsaha 
            $where 
            GROUP BY SektorUsaha 
            ORDER BY total DESC
        ";
        return $this->db->query($sql)->result_array();
    }

    // 9. Trend Perizinan & Rekap per Bulan (12 Bulan)
    private function _get_trend_bulanan($tahun = 2026) {
        $tahun_lalu = $tahun - 1;

        $query = function($th) {
            return $this->db->query("
                SELECT Bulan, COALESCE(SUM(Jumlah), 0) as total 
                FROM RekapIzinBulan 
                WHERE Tahun = ? AND DeleteAt IS NULL 
                GROUP BY Bulan 
                ORDER BY Bulan ASC
            ", [$th])->result_array();
        };

        $data_skrg = array_fill(1, 12, 0);
        $data_lalu = array_fill(1, 12, 0);

        foreach ($query($tahun) as $row) {
            $data_skrg[(int)$row['Bulan']] = (int)$row['total'];
        }
        foreach ($query($tahun_lalu) as $row) {
            $data_lalu[(int)$row['Bulan']] = (int)$row['total'];
        }

        return [
            'tahun_skrg' => array_values($data_skrg),
            'tahun_lalu' => array_values($data_lalu)
        ];
    }

    // 10. Informasi Cepat
    private function _get_informasi_cepat($tahun = null) {
        $th = $tahun ?? date('Y');

        $izin_terbit = $this->db->query("
            SELECT COALESCE(SUM(Jumlah), 0) as total 
            FROM RekapIzinBulan 
            WHERE Tahun = ? AND DeleteAt IS NULL
        ", [$th])->row()->total ?? 0;

        $layanan_mpp = $this->db->query("
            SELECT COALESCE(SUM(Jumlah), 0) as total 
            FROM RekapMppBulan 
            WHERE Tahun = ? AND DeleteAt IS NULL
        ", [$th])->row()->total ?? 0;

        $total_jenis_izin = $this->db->query("
            SELECT COUNT(id) as total 
            FROM JenisIzin 
            WHERE DeleteAt IS NULL
        ")->row()->total ?? 0;

        $total_opd = $this->db->query("
            SELECT COUNT(id) as total 
            FROM Opd 
            WHERE DeleteAt IS NULL
        ")->row()->total ?? 0;

        return [
            'izin_terbit'      => (int)$izin_terbit,
            'layanan_mpp'      => (int)$layanan_mpp,
            'total_jenis_izin' => (int)$total_jenis_izin,
            'total_opd'        => (int)$total_opd
        ];
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