<!-- SCROLLABLE CONTENT BODY -->
            <?php
            $filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : (isset($tahun_filter) ? $tahun_filter : date('Y'));
            $filter_periode = isset($_GET['periode']) ? $_GET['periode'] : 'tahunan';
            $filter_b_awal = isset($_GET['bulan_awal']) ? $_GET['bulan_awal'] : 1;
            $filter_b_akhir = isset($_GET['bulan_akhir']) ? $_GET['bulan_akhir'] : 12;
            $filter_status = isset($_GET['keterangan']) ? $_GET['keterangan'] : (isset($keterangan_filter) ? $keterangan_filter : 'Semua');

            $filtered_siujk = [];
            if (!empty($siujk_data)) {
                foreach ($siujk_data as $row) {
                    $tgl_cetak = $row->TanggalCetak;
                    // Ekstrak Tahun dan Bulan dari Tanggal Cetak
                    $row_tahun = $tgl_cetak ? date('Y', strtotime($tgl_cetak)) : null;
                    $row_bulan = $tgl_cetak ? date('n', strtotime($tgl_cetak)) : null;
                    $row_status = $row->Keterangan;

                    // Lewati jika tahun tidak cocok
                    if ($row_tahun != $filter_tahun) continue;
                    
                    // Lewati jika status tidak sesuai (dan bukan 'Semua')
                    if ($filter_status != 'Semua' && $row_status != $filter_status) continue;

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
                        $filtered_siujk[] = $row;
                    }
                }
            }
            // Timpa array asli dengan data yang sudah di filter agar total count dan tabel menyesuaikan
            $siujk_data = $filtered_siujk;
            ?>

            <div class="w-full p-1 md:p-1 lg:p-1 pb-16">
                <div class="bg-gradient-to-r from-teal-900 to-[#0a1324] border border-teal-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-teal-900/20 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                        <i data-lucide="hard-hat" class="w-48 h-48"></i>
                    </div>
                    <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                        <h2 class="text-2xl font-bold text-white mb-1">Database Surat Izin Usaha Jasa Konstruksi (SIUJK)</h2>
                        <p class="text-sm text-teal-200">Manajemen data Nomor Advis, Perusahaan, dan Masa Berlaku SIUJK.</p>
                    </div>
                    <div class="relative z-10 flex gap-4 text-center">
                        <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-teal-500/30 px-4 py-2 rounded-lg">
                            <div class="text-[10px] text-teal-300 uppercase tracking-widest font-semibold mb-0.5">Total SIUJK</div>
                            <div class="text-xl font-bold text-white"><?= !empty($siujk_data) ? count($siujk_data) : '0' ?></div>
                        </div>
                    </div>
                </div>

                <div class="admin-panel flex flex-col">
                    
                    <!-- FILTER DATA & TOMBOL TAMBAH, EXCEL, PDF -->
                    <div class="p-5 border-b border-[#1e2d4a] flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-[#081122] rounded-t-[0.75rem]">
                        <form action="<?= base_url('Admin/DataSiujk') ?>" method="GET" class="flex flex-wrap items-center gap-3">
                            <?php
                            $bulan_array = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            ?>
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Tahun:</label>
                                <input type="number" name="tahun" id="filterTahun" value="<?= $filter_tahun ?>" class="w-16 bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2" min="2016" maxlength="4">
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Periode:</label>
                                <select name="periode" id="filterPeriode" onchange="toggleCustomBulan()" class="bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
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
                                <select name="bulan_awal" id="filterBulanAwal" class="bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <?php foreach($bulan_array as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= $filter_b_awal == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-slate-400 text-xs">-</span>
                                <select name="bulan_akhir" id="filterBulanAkhir" class="bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <?php foreach($bulan_array as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= $filter_b_akhir == $num ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Status:</label>
                                <select name="keterangan" id="filterKeterangan" class="bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <option value="Semua" <?= $filter_status == 'Semua' ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="Baru" <?= $filter_status == 'Baru' ? 'selected' : '' ?>>Baru</option>
                                    <option value="Perpanjang" <?= $filter_status == 'Perpanjang' ? 'selected' : '' ?>>Perpanjang</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white p-1.5 px-3 rounded-md transition-colors flex items-center gap-1.5 text-xs font-semibold">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>

                        <div class="flex items-center gap-2 mt-4 xl:mt-0 w-full xl:w-auto justify-end">
                            <button type="button" onclick="downloadExcel()" class="flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Excel
                            </button>
                            <button type="button" onclick="downloadPDF()" class="flex items-center justify-center gap-1.5 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                            </button>
                            <button type="button" onclick="openModal()" class="flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_15px_rgba(13,148,136,0.3)]">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah SIUJK
                            </button>
                        </div>
                    </div>

                    <!-- TABEL DATA -->
                    <div class="overflow-x-auto">
                        <table id="tabelSiujk" class="w-full text-left text-[11px] text-slate-300">
                            <thead class="text-[8px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold w-10 !text-center">No</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Nama Perusahaan</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">No Advis & NPWP</th>
                                    <th scope="col" class="px-4 py-3 font-semibold w-48">Alamat Lengkap</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Masa Berlaku</th>
                                    <th scope="col" class="px-4 py-3 font-semibold !text-center">Status</th>
                                    <th scope="col" class="px-4 py-3 font-semibold !text-center w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1e2d4a]/50">
                                <?php if(!empty($siujk_data)): ?>
                                    <?php foreach($siujk_data as $index => $row): ?>
                                    <tr class="hover:bg-[#0c1833] transition-colors">
                                        <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>

                                        <td class="px-4 py-3 whitespace-normal break-words max-w-[200px]">
                                            <div class="text-white mb-0.5"><?= htmlspecialchars($row->NamaPerusahaan) ?></div>
                                            <div class="text-[10px] text-slate-400"><i data-lucide="user" class="w-3 h-3 inline"></i> <?= htmlspecialchars($row->NamaPenanggungjawab) ?></div>
                                        </td>
                                        
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-teal-400 font-mono text-xs mb-0.5"><?= htmlspecialchars($row->NomorAdvis) ?></div>
                                            <div class="text-[10px] text-blue-300">NPWP: <?= htmlspecialchars($row->NpwpPerusahaan) ?></div>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-slate-400 text-[10px] leading-tight">
                                            <div class="font-medium text-slate-300"><?= htmlspecialchars($row->Jalan) ?></div>
                                            <div>Kel/Distrik: <?= htmlspecialchars($row->KelDistrik) ?></div>
                                            <div>RT/RW: <?= htmlspecialchars($row->RtRw) ?></div>
                                        </td>

                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <?php 
                                                $is_expired = (strtotime($row->MasaBerlaku) < time());
                                                $text_color = $is_expired ? 'text-red-400' : 'text-emerald-400';
                                            ?>
                                            <div class="<?= $text_color ?> font-semibold text-xs"><?= date('d M Y', strtotime($row->MasaBerlaku)) ?></div>
                                            <div class="text-[9px] text-slate-500">Cetak: <?= $row->TanggalCetak ? date('d/m/Y', strtotime($row->TanggalCetak)) : '-' ?></div>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <?php if($row->Keterangan == 'Baru'): ?>
                                                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[10px]">Baru</span>
                                            <?php else: ?>
                                                <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded text-[10px]">Perpanjang</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="editSiujk(<?= $row->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit">
                                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                </button>
                                                <button onclick="deleteSiujk(<?= $row->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
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
        </main>

        <!-- MODAL CRUD SIUJK -->
        <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
            
            <div id="modalContent" class="relative w-full max-w-[800px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave h-[90vh] md:h-auto overflow-hidden">
                
                <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl shrink-0">
                    <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Data SIUJK</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form id="formSiujk" onsubmit="saveData(event)" class="flex flex-col h-full overflow-hidden">
                    <div class="p-6 overflow-y-auto flex-1 gap-4 flex flex-col custom-scrollbar">
                        <input type="hidden" id="inputId" name="id" value="">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kolom Kiri -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Tahun Data</label>
                                        <input type="number" id="inputTahun" name="Tahun" placeholder="2024" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required min="2016" maxlength="4">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Status</label>
                                        <select id="inputKeterangan" name="Keterangan" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                            <option value="Baru">Baru</option>
                                            <option value="Perpanjang">Perpanjang</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nomor Advis</label>
                                    <input type="text" id="inputNomorAdvis" name="NomorAdvis" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Perusahaan</label>
                                    <input type="text" id="inputNamaPerusahaan" name="NamaPerusahaan" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Penanggungjawab</label>
                                    <input type="text" id="inputNamaPenanggungjawab" name="NamaPenanggungjawab" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5">
                                </div>
                                
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">NPWP Perusahaan</label>
                                    <input type="text" id="inputNpwpPerusahaan" name="NpwpPerusahaan" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5">
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jalan / Alamat</label>
                                    <textarea id="inputJalan" name="Jalan" rows="2" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5"></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Kelurahan / Distrik</label>
                                        <input type="text" id="inputKelDistrik" name="KelDistrik" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">RT / RW</label>
                                        <input type="text" id="inputRtRw" name="RtRw" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" placeholder="001/002">
                                    </div>
                                </div>

                                <div class="border-t border-[#1e2d4a] pt-4 mt-2"></div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Tanggal Cetak</label>
                                        <input type="date" id="inputTanggalCetak" name="TanggalCetak" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5 picker-dark">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-semibold text-emerald-400 uppercase block">Masa Berlaku (S/D)</label>
                                        <input type="date" id="inputMasaBerlaku" name="MasaBerlaku" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none p-2.5 picker-dark">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex justify-end gap-3 border-t border-[#1e2d4a]/80 bg-[#071126] rounded-b-xl shrink-0">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                        <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-500 rounded-lg transition-colors shadow-lg shadow-teal-900/40 border border-teal-500/50 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahan CSS untuk input date supaya icon kalender terlihat putih di dark mode -->
    <style>
        .picker-dark::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
            cursor: pointer;
        }
    </style>

    <script>
        // VARIABLE GLOBAL UNTUK EXPORT & FILTER
        const currentTahunFilter = $('#filterTahun').val();
        const currentKeteranganFilter = $('#filterKeterangan').val();
        const dataSiujkExport = <?= json_encode(!empty($siujk_data) ? $siujk_data : []) ?>;
        const monthNamesIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const KADIN_NAMA = <?= json_encode(!empty($_SESSION['kadin_nama']) ? $_SESSION['kadin_nama'] : 'Marselino Mameyao, SKM') ?>;
        const KADIN_PANGKAT = <?= json_encode(!empty($_SESSION['kadin_pangkat']) ? $_SESSION['kadin_pangkat'] : 'Pembina TK. I') ?>;
        const KADIN_NIP = <?= json_encode(!empty($_SESSION['kadin_nip']) ? 'NIP : ' . $_SESSION['kadin_nip'] : 'NIP : 196805141989111002') ?>;
            
        function formatDateIndo(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return `${d.getDate()} ${monthNamesIndo[d.getMonth()]} ${d.getFullYear()}`;
        }

        function toggleCustomBulan() {
            if ($('#filterPeriode').val() === 'custom') {
                $('#customBulanWrapper').removeClass('hidden').addClass('flex');
            } else {
                $('#customBulanWrapper').removeClass('flex').addClass('hidden');
            }
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

            if(currentKeteranganFilter && currentKeteranganFilter !== 'Semua') {
                info += ` | STATUS: ${currentKeteranganFilter.toUpperCase()}`;
            }
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
            let statusStr = currentKeteranganFilter !== 'Semua' ? currentKeteranganFilter.toUpperCase() : 'SEMUA_STATUS';
            return `Rekap_Data_SIUJK_${currentTahunFilter}_${pName}_${statusStr}`;
        }

        async function downloadExcel() {
            if (typeof window.ExcelJS === 'undefined') {
                alert("Library ExcelJS belum termuat. Pastikan koneksi internet stabil."); return;
            }

            const workbook = new window.ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Data SIUJK');
            const totalCols = 11; 
            const lastColLetter = 'K'; // A-K

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
            
            for(let i = 1; i <= totalCols; i++) {
                worksheet.getCell(5, i).border = { bottom: { style: 'double' } };
            }

            worksheet.addRow([]); 

            worksheet.addRow([`REKAPITULASI DATA SIUJK`]);
            worksheet.mergeCells(`A7:${lastColLetter}7`);
            worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
            worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow([getDynamicTitleStr()]);
            worksheet.mergeCells(`A8:${lastColLetter}8`);
            worksheet.getCell('A8').font = { name: 'Arial', size: 11, bold: true };
            worksheet.getCell('A8').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow([]); 

            // Header Tabel
            let headers = ['NO', 'NOMOR ADVIS', 'NAMA PERUSAHAAN', 'PENANGGUNGJAWAB', 'NPWP', 'JALAN / ALAMAT', 'KEL/DISTRIK', 'RT/RW', 'TGL CETAK', 'MASA BERLAKU', 'STATUS'];
            const headerRow = worksheet.addRow(headers);
            
            headerRow.eachCell((cell) => {
                cell.font = { name: 'Arial', size: 10, bold: true };
                cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
                cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
            });

            if (dataSiujkExport.length > 0) {
                dataSiujkExport.forEach((item, index) => {
                    const rowData = [
                        (index + 1), 
                        (item.NomorAdvis || '-'), 
                        (item.NamaPerusahaan || '-'), 
                        (item.NamaPenanggungjawab || '-'), 
                        (item.NpwpPerusahaan || '-'), 
                        (item.Jalan || '-'), 
                        (item.KelDistrik || '-'), 
                        (item.RtRw || '-'),
                        formatDateIndo(item.TanggalCetak),
                        formatDateIndo(item.MasaBerlaku),
                        (item.Keterangan || '-')
                    ];
                    const dataRow = worksheet.addRow(rowData);
                    
                    dataRow.eachCell((cell, colNumber) => {
                        cell.font = { name: 'Arial', size: 10 };
                        cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                        if (colNumber === 1 || colNumber === 11) { 
                            cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        } else { 
                            cell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: true };
                        }
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
            const sigStartCol = 9; // Digeser ke kiri menjadi kolom I (sebelumnya J/10)
            
            const addSigLine = (text, bold = false) => {
                const row = worksheet.addRow([]);
                const cell = row.getCell(sigStartCol);
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
                { width: 6 },  { width: 25 }, { width: 35 }, 
                { width: 25 }, { width: 20 }, { width: 35 }, 
                { width: 20 }, { width: 10 }, { width: 15 },
                { width: 15 }, { width: 15 }
            ];
            worksheet.getRow(1).height = 25; worksheet.getRow(2).height = 20;

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = getFileNameStr() + '.xlsx';
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
                allowOutsideClick: false, background: '#0b172e', color: '#fff',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape', 'mm', 'a4');

                const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
                try {
                    const logoBase64 = await getBase64ImageFromURL(logoUrl);
                    doc.addImage(logoBase64, 'PNG', 25, 6, 22, 28);
                } catch (e) { console.warn("Logo tidak terload."); }

                doc.setTextColor(0, 0, 0);
                doc.setFont("helvetica", "bold"); doc.setFontSize(15);
                doc.text("PEMERINTAH KABUPATEN MIMIKA", 148, 15, { align: "center" });
                
                doc.setFontSize(13);
                doc.text("DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU", 148, 22, { align: "center" });
                
                doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                doc.text("Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II", 148, 28, { align: "center" });
                doc.text("Tlp. (0901) 3262943 Timika - Papua Pos 99910", 148, 33, { align: "center" });

                doc.setDrawColor(0, 0, 0);
                doc.setLineWidth(1.0); doc.line(15, 37, 282, 37);
                doc.setLineWidth(0.3); doc.line(15, 38.5, 282, 38.5);

                doc.setFont("helvetica", "bold"); doc.setFontSize(11);
                doc.text("REKAPITULASI DATA SIUJK", 148, 47, { align: "center" });
                
                doc.setFontSize(9);
                doc.text(getDynamicTitleStr(), 148, 52, { align: "center" });
                
                // Menyingkat beberapa kolom untuk muat di lanskap A4
                let tableHeaders = ['NO', 'NO ADVIS & NPWP', 'PERUSAHAAN & PENANGGUNGJAWAB', 'ALAMAT LENGKAP', 'TGL CETAK', 'MASA BERLAKU', 'STATUS'];
                let tableBody = [];

                if (dataSiujkExport.length > 0) {
                    dataSiujkExport.forEach((item, index) => {
                        const advisNpwp = `Advis: ${item.NomorAdvis||'-'}\nNPWP: ${item.NpwpPerusahaan||'-'}`;
                        const persTanggungjawab = `${item.NamaPerusahaan||'-'}\nPimpinan: ${item.NamaPenanggungjawab||'-'}`;
                        const alamatLkp = `${item.Jalan||'-'}\nKel/Dist: ${item.KelDistrik||'-'}\nRT/RW: ${item.RtRw||'-'}`;
                        
                        tableBody.push([
                            (index + 1).toString(), 
                            advisNpwp, 
                            persTanggungjawab, 
                            alamatLkp, 
                            formatDateIndo(item.TanggalCetak), 
                            formatDateIndo(item.MasaBerlaku),
                            (item.Keterangan||'-')
                        ]);
                    });
                } else {
                    tableBody.push([{ content: 'Belum ada data SIUJK.', colSpan: 7, styles: { halign: 'center' } }]);
                }

                doc.autoTable({
                    startY: 57, head: [tableHeaders], body: tableBody, theme: 'grid',
                    headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                    bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8, valign: 'top' },
                    columnStyles: { 
                        0: { halign: 'center', cellWidth: 10 }, 
                        1: { cellWidth: 40 },
                        2: { cellWidth: 60 },
                        3: { cellWidth: 55 },
                        6: { halign: 'center' }
                    },
                    margin: { top: 15, right: 15, bottom: 20, left: 15 }
                });

                const finalY = doc.lastAutoTable.finalY + 10;
                const now = new Date();
                const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                const rightMargin = 205; 

                if (finalY > 170) { doc.addPage(); }
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
                Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0b172e', color: '#fff'});
            }
        }
        // ==========================================

        document.addEventListener("DOMContentLoaded", function() {
            if ($.fn.DataTable) {
                $('#tabelSiujk').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Cari Data:", lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data", infoFiltered: "(difilter)",
                        emptyTable: "Belum ada data SIUJK untuk filter terpilih.",
                        paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Kembali" }
                    }
                });
            }
        });

        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            setTimeout(() => { $('#modalContent').removeClass('modal-leave').addClass('modal-enter'); }, 10);
            
            if (!isEdit) {
                $('#modalTitle').text('Tambah Data SIUJK');
                $('#formSiujk')[0].reset();
                $('#inputId').val(''); 
                $('#inputTahun').val(currentTahunFilter);
                $('#inputKeterangan').val('Baru');
            }
        }

        function closeModal() {
            $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
            setTimeout(() => { $('#modalCRUD').addClass('hidden'); }, 300); 
        }

        async function editSiujk(id) {
            try {
                Swal.fire({
                    title: 'Memuat Data...',
                    allowOutsideClick: false, background: '#0b172e', color: '#fff',
                    didOpen: () => { Swal.showLoading(); }
                });

                const res = await $.ajax({ url: `<?= base_url('Admin/get_siujk/') ?>${id}`, method: 'GET', dataType: 'json' });
                
                if (res && res.status === 'success') {
                    $('#modalTitle').text('Edit Data SIUJK');
                    $('#inputId').val(res.data.id);
                    $('#inputTahun').val(res.data.Tahun);
                    $('#inputNomorAdvis').val(res.data.NomorAdvis);
                    $('#inputNamaPerusahaan').val(res.data.NamaPerusahaan);
                    $('#inputJalan').val(res.data.Jalan);
                    $('#inputKelDistrik').val(res.data.KelDistrik);
                    $('#inputRtRw').val(res.data.RtRw);
                    $('#inputNamaPenanggungjawab').val(res.data.NamaPenanggungjawab);
                    $('#inputNpwpPerusahaan').val(res.data.NpwpPerusahaan);
                    $('#inputMasaBerlaku').val(res.data.MasaBerlaku);
                    $('#inputTanggalCetak').val(res.data.TanggalCetak);
                    $('#inputKeterangan').val(res.data.Keterangan);
                    
                    Swal.close(); 
                    openModal(true); 
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat memuat data dari server.', background: '#0b172e', color: '#fff' });
            }
        }

        function saveData(event) {
            event.preventDefault();
            
            const tahunInput = $('#inputTahun').val();
            if(tahunInput.length !== 4 || parseInt(tahunInput) <= 2015) {
                Swal.fire({ icon: 'warning', title: 'Tahun Tidak Valid', text: 'Tahun harus terdiri dari 4 digit dan lebih dari 2015.', background: '#0b172e', color: '#fff' });
                return;
            }

            const $btn = $('#btnSubmitForm');
            const originalText = $btn.html();
            $btn.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`).prop('disabled', true);
            
            $.ajax({
                url: '<?= base_url("Admin/save_siujk") ?>',
                method: 'POST', data: new FormData($('#formSiujk')[0]),
                processData: false, contentType: false, dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        closeModal();
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload()); 
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                    }
                },
                complete: function() { $btn.html(originalText).prop('disabled', false); lucide.createIcons(); }
            });
        }

        function deleteSiujk(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?', text: "Data SIUJK akan dihapus (soft delete)!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#1e2d4a',
                confirmButtonText: 'Ya, Hapus!', background: '#0b172e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`<?= base_url('Admin/delete_siujk/') ?>${id}`, function(res) {
                        if (res && res.status === 'success') location.reload();
                        else Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                    }, 'json');
                }
            });
        }
    </script>
</body>
</html>