        <div class="p-3 sm:p-5 lg:p-6 bg-[#0f172a] text-slate-100 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-4">

                <?php
                // LOGIKA FILTER DINAMIS (Berdasarkan TanggalKeluar)
                $filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : (isset($tahun_filter) ? $tahun_filter : date('Y'));
                $filter_periode = isset($_GET['periode']) ? $_GET['periode'] : 'tahunan';
                $filter_b_awal = isset($_GET['bulan_awal']) ? $_GET['bulan_awal'] : 1;
                $filter_b_akhir = isset($_GET['bulan_akhir']) ? $_GET['bulan_akhir'] : 12;

                $filtered_siup = [];
                if (!empty($siup_data)) {
                    foreach ($siup_data as $row) {
                        $tgl_keluar = $row->TanggalKeluar;
                        
                        // Ambil tahun dari TanggalKeluar. Jika kosong, fallback ke field Tahun
                        $row_tahun = !empty($tgl_keluar) ? date('Y', strtotime($tgl_keluar)) : $row->Tahun;
                        $row_bulan = !empty($tgl_keluar) ? date('n', strtotime($tgl_keluar)) : null;

                        // Lewati jika tahun tidak cocok
                        if ($row_tahun != $filter_tahun) continue;

                        // Evaluasi Periode Bulan
                        $match_periode = false;
                        switch ($filter_periode) {
                            case 'tahunan': $match_periode = true; break;
                            case 'tw1': if ($row_bulan >= 1 && $row_bulan <= 3) $match_periode = true; break;
                            case 'tw2': if ($row_bulan >= 4 && $row_bulan <= 6) $match_periode = true; break;
                            case 'tw3': if ($row_bulan >= 7 && $row_bulan <= 9) $match_periode = true; break;
                            case 'tw4': if ($row_bulan >= 10 && $row_bulan <= 12) $match_periode = true; break;
                            case 'sm1': if ($row_bulan >= 1 && $row_bulan <= 6) $match_periode = true; break;
                            case 'sm2': if ($row_bulan >= 7 && $row_bulan <= 12) $match_periode = true; break;
                            case 'custom': if ($row_bulan >= $filter_b_awal && $row_bulan <= $filter_b_akhir) $match_periode = true; break;
                        }

                        if ($match_periode) {
                            $filtered_siup[] = $row;
                        }
                    }
                }
                // Timpa array asli dengan data yang sudah di-filter
                $siup_data = $filtered_siup;
                ?>

                <!-- HEADER SECTION -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1e2d4a]/60 backdrop-blur-md p-4 rounded-xl border border-slate-700/50 shadow-lg">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white tracking-wide flex items-center gap-2">
                            <i data-lucide="store" class="w-5 h-5 text-teal-400"></i>
                            Data Surat Izin Usaha Perdagangan (SIUP)
                        </h1>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola data perizinan usaha perdagangan dan KBLI.</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2.5 py-1 bg-teal-500/10 text-teal-400 border border-teal-500/20 text-[11px] font-semibold rounded-full flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                            Aktif: Tahun <?= htmlspecialchars($filter_tahun) ?>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Total: <?= count($siup_data) ?> Dokumen</span>
                    </div>
                </div>

                <!-- FILTER & ACTION BAR -->
                <div class="bg-[#1e2d4a]/40 border border-slate-700/50 p-3 rounded-xl shadow-md">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                        
                        <form method="GET" action="<?= base_url('Admin/DataSiup') ?>" class="flex flex-wrap items-center gap-3">
                            <?php
                            $bulan_array = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            ?>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Tahun:</label>
                                <input type="number" name="tahun" id="filterTahun" value="<?= $filter_tahun ?>" class="w-20 bg-[#050e1d] border border-slate-700 text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2" min="2010" maxlength="4">
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Periode:</label>
                                <select name="periode" id="filterPeriode" onchange="toggleCustomBulan()" class="bg-[#050e1d] border border-slate-700 text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <option value="tahunan" <?= $filter_periode == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                                    <option value="tw1" <?= $filter_periode == 'tw1' ? 'selected' : '' ?>>Triwulan I</option>
                                    <option value="tw2" <?= $filter_periode == 'tw2' ? 'selected' : '' ?>>Triwulan II</option>
                                    <option value="tw3" <?= $filter_periode == 'tw3' ? 'selected' : '' ?>>Triwulan III</option>
                                    <option value="tw4" <?= $filter_periode == 'tw4' ? 'selected' : '' ?>>Triwulan IV</option>
                                    <option value="sm1" <?= $filter_periode == 'sm1' ? 'selected' : '' ?>>Semester 1</option>
                                    <option value="sm2" <?= $filter_periode == 'sm2' ? 'selected' : '' ?>>Semester 2</option>
                                    <option value="custom" <?= $filter_periode == 'custom' ? 'selected' : '' ?>>Custom Bulan</option>
                                </select>
                            </div>

                            <div id="customBulanWrapper" class="<?= $filter_periode == 'custom' ? 'flex' : 'hidden' ?> items-center gap-1">
                                <select name="bulan_awal" id="filterBulanAwal" class="bg-[#050e1d] border border-slate-700 text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <?php foreach($bulan_array as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= $filter_b_awal == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-slate-400 text-xs">-</span>
                                <select name="bulan_akhir" id="filterBulanAkhir" class="bg-[#050e1d] border border-slate-700 text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <?php foreach($bulan_array as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= $filter_b_akhir == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white py-1.5 px-3 rounded-md transition-colors flex items-center gap-1.5 text-xs font-semibold border border-slate-600 shadow-sm">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>

                        <!-- BUTTON EXPORT & TAMBAH DATA -->
                        <div class="flex items-center gap-2 w-full xl:w-auto justify-end">
                            <button type="button" onclick="downloadExcel()" class="flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Excel
                            </button>
                            <button type="button" onclick="downloadPDF()" class="flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_12px_rgba(225,29,72,0.3)]">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                            </button>
                            <div class="w-px h-6 bg-slate-700 mx-1"></div>
                            <button onclick="openModal()" class="flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_12px_rgba(13,148,136,0.3)]">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah SIUP
                            </button>
                        </div>
                    </div>
                </div>

                <!-- TABEL DATA -->
                <div class="bg-[#1e2d4a]/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg p-2">
                    <div class="w-full">
                        <table id="tableSiup" class="w-full text-left border-collapse table-fixed text-[8px] sm:text-[9px]">
                            <thead>
                                <tr class="bg-[#0f172a] text-slate-300 border-b border-slate-700 uppercase tracking-wider text-[8px]">
                                    <th class="p-1 sm:p-1.5 text-center w-[3%]">No</th>
                                    <th class="p-1 sm:p-1.5 w-[11%]">Nomor Advis</th>
                                    <th class="p-1 sm:p-1.5 w-[11%]">Perusahaan / Direktur</th>
                                    <th class="p-1 sm:p-1.5 w-[11%]">Penanggung Jawab / Kekayaan</th>
                                    <th class="p-1 sm:p-1.5 w-[10%]">Kelembagaan / Barang & Jasa</th>
                                    <th class="p-1 sm:p-1.5 w-[20%]">KBLI</th>
                                    <th class="p-1 sm:p-1.5 w-[10%]">Alamat</th>
                                    <th class="p-1 sm:p-1.5 text-center w-[6%]">Tgl Keluar</th>
                                    <th class="p-1 sm:p-1.5 text-center w-[4%]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50 text-slate-200">
                                <?php if(!empty($siup_data)): ?>
                                    <?php foreach($siup_data as $index => $row): ?>
                                    <tr class="hover:bg-slate-800/50 transition-colors">
                                        <td class="p-1 sm:p-1.5 text-center font-medium text-slate-400 align-top"><?= $index + 1 ?></td>
                                        
                                        <td class="p-1 sm:p-1.5 align-top font-mono text-teal-400 font-medium break-all text-[8px] sm:text-[9px]">
                                            <?= htmlspecialchars($row->NomorAdvis) ?>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 align-top break-words">
                                            <div class="font-bold text-white text-[9px] sm:text-[10px] leading-snug"><?= htmlspecialchars($row->NamaPerusahaan) ?></div>
                                            <div class="text-[8px] sm:text-[9px] text-slate-400 leading-tight mt-0.5"><?= htmlspecialchars($row->Direktur) ?></div>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 align-top break-words">
                                            <div class="font-bold text-white text-[9px] sm:text-[10px] leading-snug"><?= htmlspecialchars($row->NamaPenanggungjawabJabatan) ?></div>
                                            <div class="text-[8px] sm:text-[9px] text-slate-400 leading-tight mt-0.5"><?= is_numeric($row->KekayaanBersih) ? 'Rp ' . number_format($row->KekayaanBersih, 0, ',', '.') : htmlspecialchars($row->KekayaanBersih) ?></div>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 align-top break-words">
                                            <span class="px-1 py-0.5 rounded text-[7px] sm:text-[8px] font-medium inline-block bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-1 leading-none">
                                                <?= htmlspecialchars($row->Kelembagaan) ?>
                                            </span>
                                            <div class="text-[7px] sm:text-[8px] text-slate-300 leading-tight">
                                                <?= htmlspecialchars($row->BarangJasaUtama) ?>
                                            </div>
                                        </td>

                                        <td class="p-1 sm:p-1.5 align-top break-words text-[8px] sm:text-[9px] text-slate-400 leading-tight">
                                            <?= htmlspecialchars($row->KegiatanUsahaKbli) ?>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 align-top break-words text-[8px] sm:text-[9px] text-slate-300 leading-tight">
                                            <?= htmlspecialchars($row->AlamatPerusahaanDireksi) ?>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 text-center align-top text-slate-300 text-[7px] sm:text-[8px] whitespace-nowrap">
                                            <?= !empty($row->TanggalKeluar) ? date('d/m/Y', strtotime($row->TanggalKeluar)) : '-' ?>
                                        </td>
                                        
                                        <td class="p-1 sm:p-1.5 text-center align-top">
                                            <div class="flex flex-col items-center justify-center gap-1">
                                                <button onclick="editData(<?= $row->id ?>)" class="p-1 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 rounded transition-colors border border-amber-500/20" title="Edit Data">
                                                    <i data-lucide="edit-3" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                                                </button>
                                                <button onclick="deleteData(<?= $row->id ?>)" class="p-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded transition-colors border border-rose-500/20" title="Hapus Data">
                                                    <i data-lucide="trash-2" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL FORM INPUT SIUP -->
        <div id="modalSiup" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 overflow-y-auto">
            <div class="bg-[#1e2d4a] border border-slate-700/80 rounded-2xl w-full max-w-3xl shadow-2xl my-8 overflow-hidden transform transition-all">
                
                <!-- MODAL HEADER -->
                <div class="flex items-center justify-between p-4 border-b border-slate-700/70 bg-[#0f172a]/50">
                    <h3 id="modalTitle" class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="file-plus" class="w-4 h-4 text-teal-400"></i>
                        Tambah Data SIUP
                    </h3>
                    <button onclick="closeModal()" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- MODAL FORM -->
                <form id="formSiup" onsubmit="submitForm(event)" class="p-5 space-y-3">
                    <input type="hidden" id="siup_id" name="id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Perusahaan <span class="text-rose-400">*</span></label>
                            <input type="text" id="NamaPerusahaan" name="NamaPerusahaan" required placeholder="Contoh: PT. Sumber Makmur Jaya" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Advis</label>
                            <input type="text" id="NomorAdvis" name="NomorAdvis" placeholder="Nomor Surat Advis / Izin" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Direktur</label>
                            <input type="text" id="Direktur" name="Direktur" placeholder="Nama lengkap Direktur" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / Jabatan</label>
                            <input type="text" id="NamaPenanggungjawabJabatan" name="NamaPenanggungjawabJabatan" placeholder="Contoh: Ahmad Sanusi (Komisaris)" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Kelembagaan <span class="text-rose-400">*</span></label>
                            <input type="text" id="Kelembagaan" name="Kelembagaan" required placeholder="Contoh: Mikro, Kecil, Menengah, Besar, PT, CV" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Kekayaan Bersih (Modal)</label>
                            <input type="text" id="KekayaanBersih" name="KekayaanBersih" placeholder="Contoh: 500000000" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Tahun Dokumen <span class="text-rose-400">*</span></label>
                            <input type="number" id="Tahun" name="Tahun" value="<?= htmlspecialchars($filter_tahun) ?>" min="2010" max="2099" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Keluar / Terbit</label>
                            <input type="date" id="TanggalKeluar" name="TanggalKeluar" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500 [color-scheme:dark]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Perusahaan / Direksi</label>
                            <textarea id="AlamatPerusahaanDireksi" name="AlamatPerusahaanDireksi" rows="2" placeholder="Alamat lengkap perusahaan atau domisili direksi..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Kegiatan Usaha / KBLI</label>
                            <textarea id="KegiatanUsahaKbli" name="KegiatanUsahaKbli" rows="2" placeholder="Nomor / uraian KBLI..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Barang / Jasa Utama</label>
                            <textarea id="BarangJasaUtama" name="BarangJasaUtama" rows="2" placeholder="Komoditas barang atau jasa utama..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                        </div>

                    </div>

                    <!-- MODAL FOOTER -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-700/70">
                        <button type="button" onclick="closeModal()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btnSave" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-xs font-semibold rounded-lg transition-colors shadow-lg flex items-center gap-1.5">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- LIBRARIES -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- jsPDF & AutoTable -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
        <!-- ExcelJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

        <script>
            // VARIABEL GLOBAL EXPORT & FILTER
            const currentTahunFilter = $('#filterTahun').val();
            const dataSiupExport = <?= json_encode(!empty($siup_data) ? $siup_data : []) ?>;
            const monthNamesIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const KADIN_NAMA = <?= json_encode(!empty($_SESSION['kadin_nama']) ? $_SESSION['kadin_nama'] : 'Marselino Mameyao, SKM') ?>;
            const KADIN_PANGKAT = <?= json_encode(!empty($_SESSION['kadin_pangkat']) ? $_SESSION['kadin_pangkat'] : 'Pembina TK. I') ?>;
            const KADIN_NIP = <?= json_encode(!empty($_SESSION['kadin_nip']) ? 'NIP : ' . $_SESSION['kadin_nip'] : 'NIP : 196805141989111002') ?>;
            
            function toggleCustomBulan() {
                if ($('#filterPeriode').val() === 'custom') {
                    $('#customBulanWrapper').removeClass('hidden').addClass('flex');
                } else {
                    $('#customBulanWrapper').removeClass('flex').addClass('hidden');
                }
            }

            function formatDateIndo(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return dateStr;
                return `${d.getDate()} ${monthNamesIndo[d.getMonth()]} ${d.getFullYear()}`;
            }

            function formatRupiah(numberStr) {
                if (!numberStr || isNaN(numberStr)) return numberStr;
                return 'Rp ' + parseFloat(numberStr).toLocaleString('id-ID');
            }

            // ==========================================
            // EXPORT LOGIC (EXCEL & PDF)
            // ==========================================
            function getDynamicTitleStr() {
                let info = `TAHUN ${currentTahunFilter}`;
                const periodeVal = $('#filterPeriode').val();
                let periodeStr = "";
                
                switch(periodeVal) {
                    case 'tw1': periodeStr = "TRIWULAN I"; break;
                    case 'tw2': periodeStr = "TRIWULAN II"; break;
                    case 'tw3': periodeStr = "TRIWULAN III"; break;
                    case 'tw4': periodeStr = "TRIWULAN IV"; break;
                    case 'sm1': periodeStr = "SEMESTER 1"; break;
                    case 'sm2': periodeStr = "SEMESTER 2"; break;
                    case 'custom':
                        const bAwal = $('#filterBulanAwal option:selected').text();
                        const bAkhir = $('#filterBulanAkhir option:selected').text();
                        periodeStr = `BULAN ${bAwal.toUpperCase()} - ${bAkhir.toUpperCase()}`;
                        break;
                }
                if(periodeStr) info += ` | PERIODE: ${periodeStr}`;
                return info;
            }

            function getFileNameStr() {
                let pName = "TAHUNAN";
                switch($('#filterPeriode').val()) {
                    case 'tw1': pName = "TRIWULAN_I"; break;
                    case 'tw2': pName = "TRIWULAN_II"; break;
                    case 'tw3': pName = "TRIWULAN_III"; break;
                    case 'tw4': pName = "TRIWULAN_IV"; break;
                    case 'sm1': pName = "SEMESTER_1"; break;
                    case 'sm2': pName = "SEMESTER_2"; break;
                    case 'custom': pName = `BULAN_${$('#filterBulanAwal').val()}_SD_${$('#filterBulanAkhir').val()}`; break;
                }
                return `Rekap_Data_SIUP_${currentTahunFilter}_${pName}`;
            }

            async function downloadExcel() {
                if (typeof window.ExcelJS === 'undefined') {
                    Swal.fire('Error', 'Library ExcelJS belum termuat.', 'error'); return;
                }

                const workbook = new window.ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Data SIUP');
                const lastColLetter = 'K'; 

                worksheet.addRow(['PEMERINTAH KABUPATEN MIMIKA']);
                worksheet.mergeCells(`A1:${lastColLetter}1`);
                worksheet.getCell('A1').font = { name: 'Arial', size: 14, bold: true };
                worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow(['DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU']);
                worksheet.mergeCells(`A2:${lastColLetter}2`);
                worksheet.getCell('A2').font = { name: 'Arial', size: 14, bold: true };
                worksheet.getCell('A2').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow(['Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II']);
                worksheet.mergeCells(`A3:${lastColLetter}3`);
                worksheet.getCell('A3').font = { name: 'Arial', size: 11 };
                worksheet.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow(['Tlp. (0901) 3262943 Timika - Papua Pos 99910']);
                worksheet.mergeCells(`A4:${lastColLetter}4`);
                worksheet.getCell('A4').font = { name: 'Arial', size: 11 };
                worksheet.getCell('A4').alignment = { vertical: 'middle', horizontal: 'center' };
                
                for(let i = 1; i <= 11; i++) worksheet.getCell(5, i).border = { bottom: { style: 'double' } };
                worksheet.addRow([]); 

                worksheet.addRow([`REKAPITULASI DATA SIUP`]);
                worksheet.mergeCells(`A7:${lastColLetter}7`);
                worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
                worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow([getDynamicTitleStr()]);
                worksheet.mergeCells(`A8:${lastColLetter}8`);
                worksheet.getCell('A8').font = { name: 'Arial', size: 11, bold: true };
                worksheet.getCell('A8').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow([]); 

                // Header
                let headers = ['NO', 'NOMOR ADVIS', 'NAMA PERUSAHAAN', 'DIREKTUR', 'P.JAWAB / JABATAN', 'KEKAYAAN BERSIH', 'KELEMBAGAAN', 'KBLI / KEG USAHA', 'BARANG / JASA', 'ALAMAT', 'TGL KELUAR'];
                const headerRow = worksheet.addRow(headers);
                
                headerRow.eachCell((cell) => {
                    cell.font = { name: 'Arial', size: 10, bold: true };
                    cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                });

                if (dataSiupExport.length > 0) {
                    dataSiupExport.forEach((item, index) => {
                        const rowData = [
                            (index + 1), 
                            (item.NomorAdvis || '-'), 
                            (item.NamaPerusahaan || '-'), 
                            (item.Direktur || '-'), 
                            (item.NamaPenanggungjawabJabatan || '-'), 
                            (formatRupiah(item.KekayaanBersih) || '-'), 
                            (item.Kelembagaan || '-'), 
                            (item.KegiatanUsahaKbli || '-'), 
                            (item.BarangJasaUtama || '-'),
                            (item.AlamatPerusahaanDireksi || '-'),
                            formatDateIndo(item.TanggalKeluar)
                        ];
                        const dataRow = worksheet.addRow(rowData);
                        
                        dataRow.eachCell((cell, colNumber) => {
                            cell.font = { name: 'Arial', size: 10 };
                            cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                            cell.alignment = { vertical: 'middle', horizontal: (colNumber === 1 || colNumber === 11) ? 'center' : 'left', wrapText: true };
                        });
                    });
                } else {
                    const emptyRow = worksheet.addRow(['Belum ada data', '', '', '', '', '', '', '', '', '', '']);
                    worksheet.mergeCells(`A${emptyRow.number}:${lastColLetter}${emptyRow.number}`);
                    emptyRow.getCell(1).alignment = { horizontal: 'center' };
                }

                worksheet.addRow([]); worksheet.addRow([]);
                
                const now = new Date();
                const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                const addSigLine = (text, bold = false) => {
                    const row = worksheet.addRow([]);
                    const cell = row.getCell(9);
                    cell.value = text;
                    cell.font = { name: 'Arial', size: 11, bold: bold };
                    cell.alignment = { horizontal: 'left' };
                };

                addSigLine(`TIMIKA, ${dateStr}`);
                addSigLine('Kepala Dinas,');
                addSigLine('Penanaman Modal dan Pelayanan Terpadu');
                addSigLine('Satu Pintu Kabupaten Mimika,');
                
                worksheet.addRow([]); worksheet.addRow([]); worksheet.addRow([]);

                addSigLine(KADIN_NAMA, true);
                addSigLine(KADIN_PANGKAT);
                addSigLine(KADIN_NIP);

                worksheet.columns = [
                    { width: 6 }, { width: 20 }, { width: 25 }, { width: 20 }, 
                    { width: 20 }, { width: 20 }, { width: 15 }, { width: 35 }, 
                    { width: 25 }, { width: 30 }, { width: 15 }
                ];

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a'); a.href = url; a.download = getFileNameStr() + '.xlsx';
                document.body.appendChild(a); a.click(); document.body.removeChild(a); window.URL.revokeObjectURL(url);
            }

            function getBase64ImageFromURL(url) {
                return new Promise((resolve, reject) => {
                    var img = new Image(); img.setAttribute("crossOrigin", "anonymous");
                    img.onload = () => {
                        var canvas = document.createElement("canvas");
                        canvas.width = img.width; canvas.height = img.height;
                        var ctx = canvas.getContext("2d"); ctx.drawImage(img, 0, 0);
                        resolve(canvas.toDataURL("image/png"));
                    };
                    img.onerror = error => reject(error); img.src = url;
                });
            }

            async function downloadPDF() {
                Swal.fire({
                    title: 'Menyiapkan Laporan PDF...', text: 'Mohon tunggu...',
                    allowOutsideClick: false, background: '#0f172a', color: '#fff',
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape', 'mm', 'a4');
                    const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
                    
                    try {
                        const logoBase64 = await getBase64ImageFromURL(logoUrl);
                        doc.addImage(logoBase64, 'PNG', 25, 6, 22, 28);
                    } catch (e) { console.warn("Logo gagal dimuat."); }

                    doc.setTextColor(0, 0, 0); doc.setFont("helvetica", "bold"); doc.setFontSize(15);
                    doc.text("PEMERINTAH KABUPATEN MIMIKA", 148, 15, { align: "center" });
                    doc.setFontSize(13); doc.text("DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU", 148, 22, { align: "center" });
                    doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                    doc.text("Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II", 148, 28, { align: "center" });
                    doc.text("Tlp. (0901) 3262943 Timika - Papua Pos 99910", 148, 33, { align: "center" });

                    doc.setDrawColor(0, 0, 0); doc.setLineWidth(1.0); doc.line(15, 37, 282, 37);
                    doc.setLineWidth(0.3); doc.line(15, 38.5, 282, 38.5);

                    doc.setFont("helvetica", "bold"); doc.setFontSize(11);
                    doc.text("REKAPITULASI DATA SIUP", 148, 47, { align: "center" });
                    doc.setFontSize(9); doc.text(getDynamicTitleStr(), 148, 52, { align: "center" });
                    
                    let tableHeaders = ['NO', 'NO ADVIS', 'PERUSAHAAN / DIREKTUR', 'PENANGGUNG JAWAB & KEKAYAAN BERSIH', 'KELEMBAGAAN & KBLI', 'ALAMAT', 'TGL KELUAR'];
                    let tableBody = [];

                    if (dataSiupExport.length > 0) {
                        dataSiupExport.forEach((item, index) => {
                            const persDir = `${item.NamaPerusahaan||'-'}\nDir: ${item.Direktur||'-'}`;
                            const pjModal = `${item.NamaPenanggungjawabJabatan||'-'}\n${formatRupiah(item.KekayaanBersih)||'-'}`;
                            const lemKbli = `Lbg: ${item.Kelembagaan||'-'}\nKBLI: ${item.KegiatanUsahaKbli||'-'}`;
                            
                            tableBody.push([
                                (index + 1).toString(), 
                                (item.NomorAdvis||'-'), 
                                persDir, pjModal, lemKbli, 
                                (item.AlamatPerusahaanDireksi||'-'), 
                                formatDateIndo(item.TanggalKeluar)
                            ]);
                        });
                    } else {
                        tableBody.push([{ content: 'Belum ada data SIUP.', colSpan: 7, styles: { halign: 'center' } }]);
                    }

                    doc.autoTable({
                        startY: 57, head: [tableHeaders], body: tableBody, theme: 'grid',
                        headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                        bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8, valign: 'top' },
                        columnStyles: { 0: { halign: 'center', cellWidth: 10 }, 1: { cellWidth: 30 }, 2: { cellWidth: 45 }, 3: { cellWidth: 45 }, 4: { cellWidth: 50 }, 6: { halign: 'center', cellWidth: 25 } },
                        margin: { top: 15, right: 15, bottom: 20, left: 15 }
                    });

                    const finalY = doc.lastAutoTable.finalY + 10;
                    const now = new Date();
                    const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                    const rightMargin = 205; 

                    if (finalY > 170) doc.addPage();
                    let signY = finalY > 170 ? 20 : finalY;

                    doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                    doc.text(`TIMIKA, ${dateStr}`, rightMargin, signY);
                    doc.text("Kepala Dinas,", rightMargin, signY + 5);
                    doc.text("Penanaman Modal dan Pelayanan Terpadu", rightMargin, signY + 10);
                    doc.text("Satu Pintu Kabupaten Mimika,", rightMargin, signY + 15);

                    const signatureY = signY + 38;
                    
                    doc.setFont("helvetica", "bold"); 
                    doc.text(KADIN_NAMA, rightMargin, signatureY);
                    doc.setFont("helvetica", "normal");
                    doc.text(KADIN_PANGKAT, rightMargin, signatureY + 5);
                    doc.text(KADIN_NIP, rightMargin, signatureY + 10);

                    doc.save(getFileNameStr() + '.pdf');
                    Swal.close();
                } catch (error) {
                    console.error(error);
                    Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0f172a', color: '#fff'});
                }
            }

            // ==========================================
            // INITIALIZATION & CRUD
            // ==========================================
            $(document).ready(function() {
                lucide.createIcons();
                if ($.fn.dataTable) $.fn.dataTable.ext.errMode = 'none';

                if ($.fn.DataTable) {
                    if ($.fn.DataTable.isDataTable('#tableSiup')) {
                        $('#tableSiup').DataTable().destroy();
                    }

                    $('#tableSiup').DataTable({
                        responsive: false,
                        autoWidth: false,
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        language: {
                            search: "Cari Data:", lengthMenu: "Tampilkan _MENU_ entri",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Kembali" },
                            emptyTable: "Belum ada data tersedia pada periode ini"
                        }
                    });
                }
            });

            function openModal() {
                $('#formSiup')[0].reset();
                $('#siup_id').val('');
                $('#Tahun').val(currentTahunFilter); // Isi otomatis dengan tahun filter
                $('#modalTitle').html('<i data-lucide="file-plus" class="w-4 h-4 text-teal-400"></i> Tambah Data SIUP');
                $('#modalSiup').removeClass('hidden');
                lucide.createIcons();
            }

            function closeModal() {
                $('#modalSiup').addClass('hidden');
            }

            function editData(id) {
                $.ajax({
                    url: "<?= base_url('Admin/get_siup/') ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            const data = res.data;
                            $('#siup_id').val(data.id);
                            $('#NomorAdvis').val(data.NomorAdvis);
                            $('#NamaPerusahaan').val(data.NamaPerusahaan);
                            $('#NamaPenanggungjawabJabatan').val(data.NamaPenanggungjawabJabatan);
                            $('#AlamatPerusahaanDireksi').val(data.AlamatPerusahaanDireksi);
                            $('#KekayaanBersih').val(data.KekayaanBersih);
                            $('#Kelembagaan').val(data.Kelembagaan);
                            $('#KegiatanUsahaKbli').val(data.KegiatanUsahaKbli);
                            $('#Direktur').val(data.Direktur);
                            $('#BarangJasaUtama').val(data.BarangJasaUtama);
                            $('#TanggalKeluar').val(data.TanggalKeluar);
                            $('#Tahun').val(data.Tahun);

                            $('#modalTitle').html('<i data-lucide="edit-3" class="w-4 h-4 text-amber-400"></i> Edit Data SIUP');
                            $('#modalSiup').removeClass('hidden');
                            lucide.createIcons();
                        } else {
                            Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0f172a', color: '#fff'});
                        }
                    },
                    error: function() {
                        Swal.fire({icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat mengambil data.', background: '#0f172a', color: '#fff'});
                    }
                });
            }

            function submitForm(e) {
                e.preventDefault();
                $('#btnSave').prop('disabled', true).addClass('opacity-50').html('<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Menyimpan...');
                lucide.createIcons();

                $.ajax({
                    url: "<?= base_url('Admin/save_siup') ?>",
                    type: "POST",
                    data: $('#formSiup').serialize(),
                    dataType: "JSON",
                    success: function(res) {
                        $('#btnSave').prop('disabled', false).removeClass('opacity-50').html('<i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Data');
                        lucide.createIcons();
                        
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success', title: 'Berhasil!', text: res.message, 
                                timer: 1500, showConfirmButton: false, background: '#0f172a', color: '#fff'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0f172a', color: '#fff'});
                        }
                    },
                    error: function() {
                        $('#btnSave').prop('disabled', false).removeClass('opacity-50').html('<i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Data');
                        lucide.createIcons();
                        Swal.fire({icon: 'error', title: 'Error!', text: 'Gagal menghubungkan ke server.', background: '#0f172a', color: '#fff'});
                    }
                });
            }

            function deleteData(id) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data SIUP yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: '#0f172a',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?= base_url('Admin/delete_siup/') ?>" + id,
                            type: "POST",
                            dataType: "JSON",
                            success: function(res) {
                                if (res.status === 'success') {
                                    Swal.fire({
                                        icon: 'success', title: 'Terhapus!', text: res.message, 
                                        timer: 1500, showConfirmButton: false, background: '#0f172a', color: '#fff'
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0f172a', color: '#fff'});
                                }
                            },
                            error: function() {
                                Swal.fire({icon: 'error', title: 'Error!', text: 'Gagal menghapus data.', background: '#0f172a', color: '#fff'});
                            }
                        });
                    }
                });
            }
        </script>
    </body>
</html>