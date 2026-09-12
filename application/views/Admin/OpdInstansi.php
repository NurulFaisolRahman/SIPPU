<div class="w-full p-1 md:p-1 lg:p-1 pb-16">
            
            <!-- HEADER HERO CONTAINER -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-950 to-[#0a1324] border border-blue-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg relative overflow-hidden shrink-0">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="layers" class="w-48 h-48 text-blue-300"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-2xl font-bold text-white mb-1">Rekap Data MPP</h2>
                    <p class="text-sm text-blue-200">Kelola Instansi dan input rekap pelayanan sesuai periode filter dinamis.</p>
                </div>

                <!-- FILTER TOOLBAR DINAMIS -->
                <form method="GET" action="<?= base_url('Admin/OpdInstansi') ?>" class="relative z-10 flex flex-wrap items-center gap-2 bg-[#050e1d]/80 p-3 rounded-xl border border-blue-700/40 backdrop-blur-sm">
                    <div class="flex flex-col">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tahun</label>
                        <select name="tahun" class="bg-[#0b172e] border border-[#1e2d4a] text-white text-xs font-semibold rounded-lg px-2.5 py-1.5 outline-none focus:border-blue-500">
                            <?php 
                            $curr_y = (int)date('Y');
                            for($y = $curr_y + 1; $y >= 2018; $y--) {
                                $sel = ($y == $selected_tahun) ? 'selected' : '';
                                echo "<option value='$y' $sel>$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Periode Laporan</label>
                        <select id="selectPeriode" name="periode" onchange="toggleCustomBulan()" class="bg-[#0b172e] border border-[#1e2d4a] text-white text-xs font-semibold rounded-lg px-2.5 py-1.5 outline-none focus:border-blue-500">
                            <option value="all" <?= $selected_periode == 'all' ? 'selected' : '' ?>>1 Tahun Penuh (Jan - Des)</option>
                            <option value="tw1" <?= $selected_periode == 'tw1' ? 'selected' : '' ?>>Triwulan 1 (Jan - Mar)</option>
                            <option value="tw2" <?= $selected_periode == 'tw2' ? 'selected' : '' ?>>Triwulan 2 (Apr - Jun)</option>
                            <option value="tw3" <?= $selected_periode == 'tw3' ? 'selected' : '' ?>>Triwulan 3 (Jul - Sep)</option>
                            <option value="tw4" <?= $selected_periode == 'tw4' ? 'selected' : '' ?>>Triwulan 4 (Okt - Des)</option>
                            <option value="sm1" <?= $selected_periode == 'sm1' ? 'selected' : '' ?>>Semester 1 (Jan - Jun)</option>
                            <option value="sm2" <?= $selected_periode == 'sm2' ? 'selected' : '' ?>>Semester 2 (Jul - Des)</option>
                            <option value="custom" <?= $selected_periode == 'custom' ? 'selected' : '' ?>>Custom Rentang Bulan</option>
                        </select>
                    </div>
                    <div id="customBulanWrapper" class="flex items-center gap-2 <?= $selected_periode == 'custom' ? '' : 'hidden' ?>">
                        <div class="flex flex-col">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Dari</label>
                            <select name="bulan_mulai" class="bg-[#0b172e] border border-[#1e2d4a] text-white text-xs font-semibold rounded-lg px-2 py-1.5 outline-none">
                                <?php 
                                $bln_names = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
                                foreach($bln_names as $i => $bm) {
                                    $idx = $i + 1;
                                    $s = ($idx == $bulan_mulai) ? 'selected' : '';
                                    echo "<option value='$idx' $s>$bm</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <span class="text-slate-400 text-xs mt-4">s/d</span>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sampai</label>
                            <select name="bulan_selesai" class="bg-[#0b172e] border border-[#1e2d4a] text-white text-xs font-semibold rounded-lg px-2 py-1.5 outline-none">
                                <?php 
                                foreach($bln_names as $i => $bm) {
                                    $idx = $i + 1;
                                    $s = ($idx == $bulan_selesai) ? 'selected' : '';
                                    echo "<option value='$idx' $s>$bm</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-colors flex items-center gap-1 mt-4">
                            <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- MAIN DATA TABLE CONTAINER -->
            <div class="admin-panel flex flex-col bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-xl overflow-hidden">
                
                <div class="p-5 border-b border-[#1e2d4a] flex flex-col lg:flex-row items-center justify-between gap-4 bg-[#081122]">
                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                        <div class="text-xs font-semibold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="calendar-days" class="w-4 h-4 text-blue-400"></i>
                            <span>Data: <strong class="text-emerald-400"><?= $selected_tahun ?></strong> (Bulan <?= $start_month ?> s/d <?= $end_month ?>)</span>
                        </div>
                        
                        <!-- SEARCH BAR ADDED HERE -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                            </div>
                            <input type="text" id="searchInput" oninput="performSearch(this.value)" class="bg-[#030917] border border-[#1e2d4a] text-white text-xs rounded-lg block w-full pl-9 pr-2.5 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-500 transition-all shadow-inner" placeholder="Cari Instansi / Layanan...">
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto justify-end">
                        <button onclick="downloadExcel()" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg px-3.5 py-2 text-xs shadow-lg transition-colors">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel
                        </button>
                        <button onclick="downloadPDF()" class="flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-lg px-3.5 py-2 text-xs shadow-lg transition-colors">
                            <i data-lucide="file-down" class="w-4 h-4"></i> PDF
                        </button>
                        <button onclick="openModalOpd()" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-3.5 py-2 text-xs shadow-lg transition-colors">
                            <i data-lucide="building" class="w-4 h-4"></i> Tambah Instansi
                        </button>
                    </div>
                </div>

                <!-- TABEL DATA OPD -->
                <div class="table-container overflow-x-auto w-full">
                    <table class="w-full text-left text-[12px] text-slate-300 relative">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a] sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center"></th>
                                <th class="px-4 py-3 w-12 text-center">No</th>
                                <th class="px-4 py-3">Nama OPD / Instansi</th>
                                <th class="px-4 py-3 text-center">Jumlah Layanan</th>
                                <th class="px-4 py-3 text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyOpd" class="divide-y divide-[#1e2d4a]/60">
                            <?php if(!empty($opd_data)): ?>
                                <?php foreach($opd_data as $idx => $opd): ?>
                                
                                <tr class="hover:bg-[#0c1833] cursor-pointer transition-colors group parent-row" data-idx="<?= $idx ?>" onclick="toggleRow('opd-<?= $opd->id ?>', this)">
                                    <td class="px-4 py-3 text-center">
                                        <i data-lucide="chevron-right" class="w-4 h-4 icon-expand transition-transform duration-200"></i>
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-500 font-medium opd-number"><?= $idx + 1 ?></td>
                                    <td class="px-4 py-3 text-white font-semibold"><?= htmlspecialchars($opd->NamaOpd) ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-blue-950 text-blue-300 border border-blue-800 px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                            <?= isset($opd->pelayanan) ? count((array)$opd->pelayanan) : 0 ?> Jenis
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editPelayanan(<?= $opd->id ?>)" class="p-1.5 text-emerald-400 hover:text-white hover:bg-emerald-600 rounded transition-colors" title="Input/Edit Rekap Layanan">
                                                <i data-lucide="file-edit" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="editOpd(<?= $opd->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit Nama Instansi">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="deleteOpd(<?= $opd->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus Instansi">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr id="opd-<?= $opd->id ?>" class="hidden bg-[#071126] child-row" data-idx="<?= $idx ?>">
                                    <td colspan="5" class="p-4 border-y border-blue-900/30">
                                        <?php 
                                        // MEMBALIK URUTAN ARRAY DESCENDING
                                        $pelayanan_list = isset($opd->pelayanan) ? array_reverse((array)$opd->pelayanan) : [];
                                        if(!empty($pelayanan_list)): 
                                        ?>
                                            <div class="space-y-4 pl-4 md:pl-8">
                                                <?php foreach($pelayanan_list as $ply): ?>
                                                    <div class="bg-[#050e1d] p-3 rounded-lg border border-[#1e2d4a]">
                                                        <div class="text-[11px] font-bold text-blue-400 mb-2 flex items-center gap-2 uppercase tracking-wide">
                                                            <i data-lucide="list-checks" class="w-3.5 h-3.5"></i> <?= htmlspecialchars($ply->NamaPelayanan) ?>
                                                        </div>
                                                        
                                                        <div class="overflow-x-auto border border-[#1e2d4a] rounded shadow-inner">
                                                            <table class="w-full text-center text-[11px] text-slate-300">
                                                                <thead class="bg-[#081122] text-[9px] uppercase text-slate-400">
                                                                    <tr>
                                                                        <th class="py-2 px-2 border-r border-[#1e2d4a] w-14">Tahun</th>
                                                                        <?php 
                                                                        $bln_headers = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
                                                                        for($m = $start_month; $m <= $end_month; $m++):
                                                                        ?>
                                                                            <th class="py-2 px-2 border-r border-[#1e2d4a]/50"><?= $bln_headers[$m-1] ?></th>
                                                                        <?php endfor; ?>
                                                                        <th class="py-2 px-2 text-emerald-400">Total Periode</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-[#0b172e]">
                                                                    <tr class="hover:bg-[#0e1f3f]">
                                                                        <td class="py-2 px-2 border-r border-[#1e2d4a] font-bold text-blue-300"><?= $selected_tahun ?></td>
                                                                        <?php 
                                                                        $tot_row = 0;
                                                                        for($m = $start_month; $m <= $end_month; $m++):
                                                                            $val = isset($ply->rekap_bulan[$m]) ? $ply->rekap_bulan[$m] : 0;
                                                                            $tot_row += $val;
                                                                            $cls = $val > 0 ? 'text-white font-bold' : 'text-slate-600';
                                                                        ?>
                                                                            <td class="py-2 px-2 border-r border-[#1e2d4a]/50 <?= $cls ?>"><?= number_format($val, 0, ',', '.') ?></td>
                                                                        <?php endfor; ?>
                                                                        <td class="py-2 px-2 font-bold text-emerald-400"><?= number_format($tot_row, 0, ',', '.') ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-[11px] text-center text-slate-500 italic p-2">Belum ada data sub-layanan.</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Data Instansi belum tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- AREA PAGINASI & FILTER ENTRIES -->
                <div class="p-4 border-t border-[#1e2d4a] bg-[#081122] flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-[11px] font-semibold text-slate-400">Tampilkan:</label>
                        <select id="itemsPerPage" onchange="changeItemsPerPage(this.value)" class="bg-[#030917] border border-[#1e2d4a] text-white text-[11px] rounded-md px-2 py-1 outline-none focus:border-blue-500">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">Semua</option>
                        </select>
                        <label class="text-[11px] text-slate-400">entri</label>
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Menampilkan <span id="page-info-start" class="font-bold text-white">0</span> - <span id="page-info-end" class="font-bold text-white">0</span> dari <span id="page-info-total" class="font-bold text-white">0</span> Instansi
                    </div>
                    <div id="pagination-controls" class="flex items-center gap-1"></div>
                </div>
            </div>
        </div>

        <!-- MODAL CRUD INSTANSI -->
        <div id="modalOpd" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm" onclick="closeModal('modalOpd')"></div>
            <div class="relative w-full max-w-2xl bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-[#1e2d4a] bg-[#071126] rounded-t-xl">
                    <h3 id="modalTitleOpd" class="text-white font-bold text-sm">Data Instansi (OPD)</h3>
                    <button type="button" onclick="closeModal('modalOpd')" class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-md"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                <form id="formOpd" onsubmit="saveOpd(event)" class="p-4 sm:p-6 flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#1e2d4a]">
                        <label class="text-[11px] font-semibold text-slate-300 uppercase">Daftar Instansi</label>
                        <button type="button" onclick="addOpdRow()" class="text-[10px] font-bold bg-blue-600/20 text-blue-300 hover:bg-blue-600 hover:text-white px-2 py-1 rounded border border-blue-500/40 flex items-center gap-1">
                            <i data-lucide="plus" class="w-3 h-3"></i> Tambah Baris OPD
                        </button>
                    </div>
                    <div id="opdContainer" class="space-y-3 max-h-[50vh] overflow-y-auto px-1"></div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-[#1e2d4a]">
                        <button type="button" onclick="closeModal('modalOpd')" class="px-4 py-2 text-xs text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg">Batal</button>
                        <button type="submit" id="btnSubmitOpd" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan OPD
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL CRUD REKAP PELAYANAN (12 BULAN) -->
        <div id="modalPelayanan" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm" onclick="closeModal('modalPelayanan')"></div>
            <div class="relative w-full max-w-5xl bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col max-h-[90vh]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-b border-[#1e2d4a] bg-[#071126] rounded-t-xl gap-3">
                    <div>
                        <h3 class="text-white font-bold text-sm">Input Rekap Pelayanan (12 Bulan)</h3>
                        <p id="pelayananSubtitle" class="text-[11px] text-blue-400">Instansi: -</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-[#050e1d] border border-[#1e2d4a] rounded px-2 py-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
                            <select id="selectTahunPelayanan" class="bg-transparent text-blue-300 text-xs font-semibold outline-none cursor-pointer" onchange="changeTahunPelayanan()">
                                <?php 
                                for($y = $curr_y + 1; $y >= 2018; $y--) {
                                    $s = ($y == $selected_tahun) ? 'selected' : '';
                                    echo "<option value='$y' $s>Tahun $y</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="button" onclick="closeModal('modalPelayanan')" class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-md"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                </div>
                
                <form id="formPelayanan" onsubmit="savePelayanan(event)" class="p-4 sm:p-6 flex flex-col gap-5 overflow-y-auto">
                    <input type="hidden" id="inputPelayananIdOpd" name="id_opd" value="">
                    <input type="hidden" id="inputPelayananTahunHidden" name="Tahun" value="<?= $selected_tahun ?>">
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-[#1e2d4a] pb-2">
                            <label class="text-[11px] font-semibold text-blue-400 uppercase flex items-center gap-2"><i data-lucide="layout-grid" class="w-4 h-4"></i> Sub-Layanan & Input 12 Bulan</label>
                            <button type="button" onclick="addPelayananBlock()" class="text-[11px] font-medium bg-blue-600/20 text-blue-300 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-md border border-blue-500/40 transition-colors flex items-center gap-1">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Sub-Layanan Baru
                            </button>
                        </div>
                        <div id="pelayananContainer" class="space-y-4"></div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-[#1e2d4a] sticky bottom-0 bg-[#0b172e] py-2">
                        <button type="button" onclick="closeModal('modalPelayanan')" class="px-4 py-2 text-xs text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg">Batal</button>
                        <button type="submit" id="btnSubmitPelayanan" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Rekap
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('toggle-sidebar');
                if (toggleBtn && sidebar) {
                    toggleBtn.addEventListener('click', function() {
                        sidebar.classList.toggle('collapsed');
                    });
                }
            });

            const rawDataOPD = <?= json_encode($opd_data ?: []) ?>;
            const selectedTahun = <?= $selected_tahun ?>;
            const startMonth = <?= $start_month ?>;
            const endMonth = <?= $end_month ?>;
            const selectedPeriode = '<?= $selected_periode ?>';

            const monthNamesIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const monthShortIndo = ["JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGS", "SEP", "OKT", "NOV", "DES"];

            let opdIndex = 0;
            let pIndex = 0;
            let currentOpdIdForPelayanan = null;

            // VARIABEL PENCARIAN & PAGINASI
            let filteredIndices = [];
            let currentSearchTerm = "";
            let itemsPerPage = 5;
            let currentPage = 1;
            let totalPages = 1;

            // Inisialisasi index pencarian
            function initSearch() {
                filteredIndices = rawDataOPD.map((_, index) => index);
            }

            function toggleCustomBulan() {
                const val = $('#selectPeriode').val();
                if (val === 'custom') {
                    $('#customBulanWrapper').removeClass('hidden');
                } else {
                    $('#customBulanWrapper').addClass('hidden');
                }
            }

            function getPeriodeText() {
                if (selectedPeriode === 'tw1') return `TRIWULAN I (JANUARI - MARET) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'tw2') return `TRIWULAN II (APRIL - JUNI) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'tw3') return `TRIWULAN III (JULI - SEPTEMBER) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'tw4') return `TRIWULAN IV (OKTOBER - DESEMBER) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'sm1') return `SEMESTER I (JANUARI - JUNI) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'sm2') return `SEMESTER II (JULI - DESEMBER) TAHUN ${selectedTahun}`;
                if (selectedPeriode === 'custom') return `BULAN ${monthNamesIndo[startMonth - 1].toUpperCase()} S/D ${monthNamesIndo[endMonth - 1].toUpperCase()} TAHUN ${selectedTahun}`;
                if (startMonth === 1 && endMonth === 12) return `1 TAHUN PENUH (${selectedTahun})`;
                return `BULAN ${monthNamesIndo[startMonth - 1].toUpperCase()} S/D ${monthNamesIndo[endMonth - 1].toUpperCase()} TAHUN ${selectedTahun}`;
            }

            // ==========================================
            // EXPORT EXCEL
            // ==========================================
            async function downloadExcel() {
                if (typeof window.ExcelJS === 'undefined') {
                    alert("Library ExcelJS belum termuat."); return;
                }
                const workbook = new window.ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Rekap MPP');
                const monthColsCount = (endMonth - startMonth + 1);
                const totalCols = 3 + monthColsCount + 1;
                const lastColLetter = String.fromCharCode(64 + totalCols);

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
                worksheet.getCell('A3').font = { name: 'Arial', size: 10 };
                worksheet.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow(['Tlp. (0901) 3262943 Timika - Papua Pos 99910']);
                worksheet.mergeCells(`A4:${lastColLetter}4`);
                worksheet.getCell('A4').font = { name: 'Arial', size: 10 };
                worksheet.getCell('A4').alignment = { vertical: 'middle', horizontal: 'center' };

                for(let i = 1; i <= totalCols; i++) {
                    worksheet.getCell(5, i).border = { bottom: { style: 'double' } };
                }
                worksheet.addRow([]);

                worksheet.addRow([`REKAPITULASI DATA PELAYANAN MAL PELAYANAN PUBLIK MIMIKA`]);
                worksheet.mergeCells(`A7:${lastColLetter}7`);
                worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
                worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow([`PERIODE : ${getPeriodeText()}`]);
                worksheet.mergeCells(`A8:${lastColLetter}8`);
                worksheet.getCell('A8').font = { name: 'Arial', size: 11, bold: true };
                worksheet.getCell('A8').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow([]);

                let headers = ['NO', 'NAMA OPD / INSTANSI', 'JENIS LAYANAN'];
                for (let m = startMonth; m <= endMonth; m++) headers.push(monthShortIndo[m - 1]);
                headers.push('TOTAL');

                const headerRow = worksheet.addRow(headers);
                headerRow.eachCell((cell) => {
                    cell.font = { name: 'Arial', size: 10, bold: true };
                    cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                });

                let monthSum = {};
                for(let m = startMonth; m <= endMonth; m++) monthSum[m] = 0;
                let grandTotal = 0;

                // Menggunakan filteredIndices (data hasil pencarian) jika ingin mengeksport data yang dicari, 
                // namun biasanya export digunakan untuk semua data. Di sini kita tetap export SEMUA data (rawDataOPD).
                if (rawDataOPD.length > 0) {
                    rawDataOPD.forEach((opd, index) => {
                        const no = index + 1;
                        const namaOpd = opd.NamaOpd || '-';
                        const pelayanan = opd.pelayanan ? [...opd.pelayanan].reverse() : [];

                        if (pelayanan.length === 0) {
                            let emptyRowData = [no, namaOpd, 'Belum ada data jenis layanan'];
                            for (let m = startMonth; m <= endMonth; m++) emptyRowData.push(0);
                            emptyRowData.push(0);

                            const r = worksheet.addRow(emptyRowData);
                            r.eachCell(c => c.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } });
                        } else {
                            pelayanan.forEach((ply, pIdx) => {
                                let rowData = [
                                    pIdx === 0 ? no : '',
                                    pIdx === 0 ? namaOpd : '',
                                    ply.NamaPelayanan
                                ];

                                let totRow = 0;
                                for (let m = startMonth; m <= endMonth; m++) {
                                    const val = (ply.rekap_bulan && ply.rekap_bulan[m]) ? parseInt(ply.rekap_bulan[m]) : 0;
                                    rowData.push(val);
                                    totRow += val;
                                    monthSum[m] += val;
                                }
                                grandTotal += totRow;
                                rowData.push(totRow);

                                const dataRow = worksheet.addRow(rowData);
                                dataRow.eachCell((cell, colNum) => {
                                    cell.font = { name: 'Arial', size: 10 };
                                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                                    if (colNum === 1) cell.alignment = { vertical: 'middle', horizontal: 'center' };
                                    else if (colNum === 2 || colNum === 3) cell.alignment = { vertical: 'middle', horizontal: 'left' };
                                    else cell.alignment = { vertical: 'middle', horizontal: 'center' };
                                });
                            });
                        }
                    });

                    let totalRowData = ['TOTAL', '', ''];
                    for (let m = startMonth; m <= endMonth; m++) totalRowData.push(monthSum[m]);
                    totalRowData.push(grandTotal);

                    const totRow = worksheet.addRow(totalRowData);
                    worksheet.mergeCells(`A${totRow.number}:C${totRow.number}`);
                    totRow.eachCell((cell) => {
                        cell.font = { name: 'Arial', size: 10, bold: true };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCE6F1' } };
                        cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    });
                }

                worksheet.addRow([]); worksheet.addRow([]);

                const now = new Date();
                const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                const sigCol = Math.max(4, totalCols - 3);

                const addSig = (text, bold = false) => {
                    const r = worksheet.addRow([]);
                    const c = r.getCell(sigCol);
                    c.value = text;
                    c.font = { name: 'Arial', size: 10, bold: bold };
                };

                addSig(`TIMIKA, ${dateStr}`);
                addSig('Kepala Dinas,');
                addSig('Penanaman Modal dan Pelayanan Terpadu');
                addSig('Satu Pintu Kabupaten Mimika,');
                worksheet.addRow([]); worksheet.addRow([]); worksheet.addRow([]);
                addSig('Marselino Mameyao, SKM', true);
                addSig('Pembina TK. I');
                addSig('NIP : 196805141989111002');

                let colWidths = [{ width: 6 }, { width: 35 }, { width: 30 }];
                for (let m = startMonth; m <= endMonth; m++) colWidths.push({ width: 10 });
                colWidths.push({ width: 14 });
                worksheet.columns = colWidths;

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Rekap_MPP_Mimika_${selectedTahun}_${selectedPeriode}.xlsx`;
                document.body.appendChild(a); a.click(); document.body.removeChild(a); window.URL.revokeObjectURL(url);
            }

            // ==========================================
            // EXPORT PDF
            // ==========================================
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
                    title: 'Menyiapkan PDF...', text: 'Mohon tunggu...',
                    allowOutsideClick: false, background: '#0b172e', color: '#fff',
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape', 'mm', 'a4');

                    const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
                    try {
                        const logoBase64 = await getBase64ImageFromURL(logoUrl);
                        doc.addImage(logoBase64, 'PNG', 25, 5, 22, 28);
                    } catch (e) { console.warn("Logo tidak terload."); }

                    doc.setTextColor(0, 0, 0);
                    doc.setFont("helvetica", "bold"); doc.setFontSize(15);
                    doc.text("PEMERINTAH KABUPATEN MIMIKA", 148, 14, { align: "center" });
                    
                    doc.setFontSize(13);
                    doc.text("DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU", 148, 20, { align: "center" });
                    
                    doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                    doc.text("Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II", 148, 26, { align: "center" });
                    doc.text("Tlp. (0901) 3262943 Timika - Papua Pos 99910", 148, 31, { align: "center" });

                    doc.setLineWidth(1.0); doc.line(15, 35, 282, 35);
                    doc.setLineWidth(0.3); doc.line(15, 36.5, 282, 36.5);

                    doc.setFont("helvetica", "bold"); doc.setFontSize(10);
                    doc.text("REKAP DATA PELAYANAN OPD TEKNIS INSTANSI VERTIKAL, BUMN/BUMD", 148, 43, { align: "center" });
                    doc.text("DI MAL PELAYANAN PUBLIK MIMIKA", 148, 48, { align: "center" });
                    doc.text(`PERIODE : ${getPeriodeText()}`, 148, 53, { align: "center" });

                    let tableHeaders = ['NO', 'NAMA OPD / INSTANSI', 'JENIS LAYANAN'];
                    for (let m = startMonth; m <= endMonth; m++) tableHeaders.push(monthShortIndo[m - 1]);
                    tableHeaders.push('TOTAL');

                    let tableBody = [];
                    let monthSum = {};
                    for(let m = startMonth; m <= endMonth; m++) monthSum[m] = 0;
                    let grandTotal = 0;

                    if (rawDataOPD.length > 0) {
                        rawDataOPD.forEach((opd, index) => {
                            const no = index + 1;
                            const namaOpd = opd.NamaOpd || '-';
                            const pelayanan = opd.pelayanan ? [...opd.pelayanan].reverse() : [];

                            if (pelayanan.length === 0) {
                                let row = [{ content: no }, { content: namaOpd }, { content: "Belum ada jenis layanan", colSpan: (endMonth - startMonth + 2), styles: { halign: 'center', fontStyle: 'italic' } }];
                                tableBody.push(row);
                            } else {
                                const rowSpanCount = pelayanan.length;
                                pelayanan.forEach((ply, pIdx) => {
                                    let row = [];
                                    if (pIdx === 0) {
                                        row.push({ content: no, rowSpan: rowSpanCount, styles: { halign: 'center', valign: 'middle' } });
                                        row.push({ content: namaOpd, rowSpan: rowSpanCount, styles: { valign: 'middle' } });
                                    }
                                    row.push(ply.NamaPelayanan);

                                    let totRow = 0;
                                    for (let m = startMonth; m <= endMonth; m++) {
                                        const val = (ply.rekap_bulan && ply.rekap_bulan[m]) ? parseInt(ply.rekap_bulan[m]) : 0;
                                        row.push({ content: val.toString(), styles: { halign: 'center' } });
                                        totRow += val;
                                        monthSum[m] += val;
                                    }
                                    grandTotal += totRow;
                                    row.push({ content: totRow.toString(), styles: { halign: 'center', fontStyle: 'bold' } });

                                    tableBody.push(row);
                                });
                            }
                        });

                        let footRow = [{ content: 'TOTAL', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } }];
                        for (let m = startMonth; m <= endMonth; m++) {
                            footRow.push({ content: monthSum[m].toString(), styles: { halign: 'center', fontStyle: 'bold' } });
                        }
                        footRow.push({ content: grandTotal.toString(), styles: { halign: 'center', fontStyle: 'bold' } });
                        tableBody.push(footRow);
                    }

                    const monthColsCount = (endMonth - startMonth + 1);
                    const fontSizeDynamic = monthColsCount > 8 ? 7 : 8;

                    doc.autoTable({
                        startY: 58,
                        head: [tableHeaders],
                        body: tableBody,
                        theme: 'grid',
                        headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: fontSizeDynamic },
                        bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: fontSizeDynamic },
                        columnStyles: { 0: { cellWidth: 10 }, 1: { cellWidth: 50 }, 2: { cellWidth: 'auto' } },
                        margin: { top: 15, right: 15, bottom: 20, left: 15 }
                    });

                    const finalY = doc.lastAutoTable.finalY + 8;
                    const now = new Date();
                    const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                    const rightMargin = 205;

                    let signY = finalY > 165 ? 20 : finalY;
                    if (finalY > 165) doc.addPage();

                    doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                    doc.text(`TIMIKA, ${dateStr}`, rightMargin, signY);
                    doc.text("Kepala Dinas,", rightMargin, signY + 5);
                    doc.text("Penanaman Modal dan Pelayanan Terpadu", rightMargin, signY + 10);
                    doc.text("Satu Pintu Kabupaten Mimika,", rightMargin, signY + 15);

                    const signatureY = signY + 35;
                    doc.setFont("helvetica", "bold");
                    doc.text("Marselino Mameyao, SKM", rightMargin, signatureY);
                    doc.setFont("helvetica", "normal");
                    doc.text("Pembina TK. I", rightMargin, signatureY + 5);
                    doc.text("NIP : 196805141989111002", rightMargin, signatureY + 10);

                    doc.save(`Rekap_MPP_Mimika_${selectedTahun}_${selectedPeriode}_Bulan_${startMonth}_d_${endMonth}.pdf`);
                    Swal.close();
                } catch (error) {
                    console.error(error);
                    Swal.fire({icon: 'error', title: 'Gagal', text: 'Gagal membuat PDF.', background: '#0b172e', color: '#fff'});
                }
            }

            // ==========================================
            // FITUR PENCARIAN & FILTER
            // ==========================================
            function performSearch(term) {
                currentSearchTerm = term.toLowerCase();
                
                if (currentSearchTerm === "") {
                    filteredIndices = rawDataOPD.map((_, index) => index);
                } else {
                    filteredIndices = [];
                    rawDataOPD.forEach((opd, index) => {
                        let match = false;
                        
                        // Cari di Nama OPD
                        if (opd.NamaOpd && opd.NamaOpd.toLowerCase().includes(currentSearchTerm)) {
                            match = true;
                        }
                        
                        // Cari di Nama Pelayanan (Sub-Layanan) jika ada
                        if (!match && opd.pelayanan) {
                            const pelayananArr = Array.isArray(opd.pelayanan) ? opd.pelayanan : Object.values(opd.pelayanan);
                            pelayananArr.forEach(ply => {
                                if (ply.NamaPelayanan && ply.NamaPelayanan.toLowerCase().includes(currentSearchTerm)) {
                                    match = true;
                                }
                            });
                        }
                        
                        if (match) {
                            filteredIndices.push(index);
                        }
                    });
                }
                
                // Hitung ulang total halaman
                changeItemsPerPage($('#itemsPerPage').val(), true); 
            }

            // ==========================================
            // PAGINASI DINAMIS YANG TERINTEGRASI PENCARIAN
            // ==========================================
            function changeItemsPerPage(val, fromSearch = false) {
                const currentTotal = filteredIndices.length;
                
                if (val === 'all') {
                    itemsPerPage = currentTotal === 0 ? 1 : currentTotal;
                } else {
                    itemsPerPage = parseInt(val);
                }
                
                totalPages = Math.ceil(currentTotal / itemsPerPage);
                
                // Jika dari pencarian, reset ke halaman 1
                if (fromSearch) {
                    renderTablePage(1);
                } else {
                    // Pastikan current page tidak melebihi total pages jika data mengecil
                    if (currentPage > totalPages) currentPage = totalPages || 1;
                    renderTablePage(currentPage);
                }
            }

            function renderTablePage(page) {
                currentPage = page;
                const currentTotal = filteredIndices.length;

                // Handle jika tidak ada hasil pencarian
                if (currentTotal === 0) {
                    $('.parent-row, .child-row').addClass('page-hidden hidden');
                    $('#page-info-start').text(0);
                    $('#page-info-end').text(0);
                    $('#page-info-total').text(0);
                    $('#pagination-controls').empty();
                    
                    if ($('#no-results-row').length === 0) {
                         $('#tableBodyOpd').append('<tr id="no-results-row"><td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Tidak ada Instansi atau Sub-Layanan yang cocok dengan pencarian Anda.</td></tr>');
                    } else {
                         $('#no-results-row').show();
                    }
                    return;
                } else {
                     $('#no-results-row').hide();
                }

                // Kalkulasi data untuk halaman saat ini
                const start = (page - 1) * itemsPerPage;
                const end = Math.min(start + itemsPerPage, currentTotal);
                
                // Ambil index DOM (dari rawDataOPD) yang berhak tampil di halaman ini
                const indicesToShow = filteredIndices.slice(start, end);

                // 1. Sembunyikan SEMUA baris terlebih dahulu
                $('.parent-row').addClass('page-hidden hidden');
                
                // Pastikan child row kembali tertutup/tersembunyi saat render
                $('.child-row').addClass('page-hidden hidden'); 
                $('.icon-expand').removeClass('rotate-90');

                // 2. Tampilkan HANYA baris yang sesuai index dan halaman
                indicesToShow.forEach(idx => {
                    // Tampilkan Parent
                    $(`.parent-row[data-idx="${idx}"]`).removeClass('page-hidden hidden');
                    // Child row dibuat SIAP DIBUKA (hapus page-hidden), 
                    // tapi biarkan class 'hidden' agar tetap tertutup (user harus klik expand)
                    $(`.child-row[data-idx="${idx}"]`).removeClass('page-hidden'); 
                });

                // Update teks info (menampilkan X - Y dari Z)
                const startDisplay = currentTotal > 0 ? start + 1 : 0;
                $('#page-info-start').text(startDisplay);
                $('#page-info-end').text(end);
                $('#page-info-total').text(currentTotal);

                buildPagination();
            }

            function buildPagination() {
                if (totalPages <= 1) { $('#pagination-controls').empty(); return; }
                
                let html = `<button onclick="renderTablePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-2 py-1 rounded border border-[#1e2d4a] bg-[#050e1d] text-slate-400 disabled:opacity-50 hover:bg-[#1e2d4a] transition-colors"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>`;
                
                for(let i=1; i<=totalPages; i++) {
                    const active = i === currentPage ? 'bg-blue-600 text-white border-blue-500' : 'bg-[#050e1d] text-slate-400 hover:bg-[#1e2d4a]';
                    html += `<button onclick="renderTablePage(${i})" class="px-3 py-1 rounded border border-[#1e2d4a] ${active} text-xs transition-colors">${i}</button>`;
                }
                
                html += `<button onclick="renderTablePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-2 py-1 rounded border border-[#1e2d4a] bg-[#050e1d] text-slate-400 disabled:opacity-50 hover:bg-[#1e2d4a] transition-colors"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>`;
                
                $('#pagination-controls').html(html);
                refreshIcons();
            }

            function refreshIcons() { if (typeof lucide !== 'undefined') lucide.createIcons(); }
            
            function toggleRow(id, el) { 
                const target = $('#' + id);
                target.toggleClass('hidden'); 
                $(el).find('.icon-expand').toggleClass('rotate-90'); 
            }
            
            function openModal(id) { $(`#${id}`).removeClass('hidden'); }
            function closeModal(id) { $(`#${id}`).addClass('hidden'); }

            // ==========================================
            // MODAL CRUD OPD & PELAYANAN
            // ==========================================
            function addOpdRow(id = '', nama = '') {
                const idx = opdIndex++;
                $('#opdContainer').append(`
                    <div class="flex items-center gap-3 bg-[#050e1d] p-2 rounded-lg border border-[#1e2d4a]">
                        <i data-lucide="building" class="w-4 h-4 text-slate-400 shrink-0 ml-2"></i>
                        <input type="hidden" name="opd[${idx}][id]" value="${id}">
                        <input type="text" name="opd[${idx}][NamaOpd]" value="${nama}" placeholder="Nama Instansi..." class="flex-1 bg-transparent text-white text-sm outline-none px-2" required>
                        <button type="button" onclick="$(this).parent().remove()" class="p-2 text-slate-500 hover:text-red-400"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                `);
                refreshIcons();
            }

            function openModalOpd() {
                $('#modalTitleOpd').text('Tambah Instansi Baru');
                $('#opdContainer').empty();
                addOpdRow();
                openModal('modalOpd');
            }

            function editOpd(id) {
                $.get(`<?= base_url('Admin/get_opd/') ?>${id}`, function(res) {
                    if (res.status === 'success') {
                        $('#modalTitleOpd').text('Edit Nama Instansi');
                        $('#opdContainer').empty();
                        addOpdRow(res.data.id, res.data.NamaOpd);
                        openModal('modalOpd');
                    }
                });
            }

            function saveOpd(e) {
                e.preventDefault();
                $.ajax({
                    url: '<?= base_url("Admin/save_opd_batch") ?>', method: 'POST',
                    data: $('#formOpd').serialize(), dataType: 'json',
                    success: function(res) {
                        if(res.status === 'success') location.reload();
                        else Swal.fire({icon:'error', text:res.message, background:'#0b172e', color:'#fff'});
                    }
                });
            }

            function deleteOpd(id) {
                Swal.fire({
                    title: 'Hapus Instansi?', text: 'Seluruh data layanan dan rekap akan dihapus.',
                    icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Hapus',
                    background: '#0b172e', color: '#fff'
                }).then((res) => {
                    if(res.isConfirmed) $.post(`<?= base_url('Admin/delete_opd/') ?>${id}`, () => location.reload());
                });
            }

            function addPelayananBlock(idPly = '', nama = '', dataBulan = null) {
                const idx = pIndex++;
                let gridHTML = '';
                for(let i=1; i<=12; i++) {
                    let val = (dataBulan && dataBulan[i]) ? dataBulan[i] : 0;
                    gridHTML += `
                        <div class="flex flex-col gap-1">
                            <label class="text-[9px] text-slate-400 text-center font-bold">${monthShortIndo[i-1]}</label>
                            <input type="number" name="Pelayanan[${idx}][Bulan][${i}]" value="${val}" min="0" class="w-full bg-[#0a1324] border border-[#1e2d4a] text-center text-white text-[11px] rounded p-1 outline-none focus:border-blue-500">
                        </div>
                    `;
                }

                $('#pelayananContainer').append(`
                    <div class="pelayanan-block border border-[#1e2d4a] rounded-lg bg-[#081122] p-3">
                        <input type="hidden" name="Pelayanan[${idx}][id_pelayanan]" value="${idPly}">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="briefcase" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <input type="text" name="Pelayanan[${idx}][NamaPelayanan]" value="${nama}" placeholder="Nama Sub-Layanan..." class="flex-1 bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded p-2 outline-none" required>
                            <button type="button" onclick="$(this).closest('.pelayanan-block').remove()" class="p-1.5 text-slate-500 hover:text-red-400"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                        <div class="bg-[#050e1d] p-2 rounded border border-[#1e2d4a]">
                            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-12 gap-2">${gridHTML}</div>
                        </div>
                    </div>
                `);
                refreshIcons();
            }

            function editPelayanan(id_opd, tahun = null) {
                currentOpdIdForPelayanan = id_opd;
                if(!tahun) tahun = $('#selectTahunPelayanan').val();
                $('#selectTahunPelayanan').val(tahun);
                $('#inputPelayananTahunHidden').val(tahun);
                $('#inputPelayananIdOpd').val(id_opd);

                $.ajax({
                    url: `<?= base_url('Admin/get_pelayanan_opd/') ?>${id_opd}/${tahun}`,
                    method: 'GET', dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            $('#pelayananSubtitle').text(`Instansi: ${res.data.opd.NamaOpd}`);
                            $('#pelayananContainer').empty();
                            if (res.data.pelayanan && res.data.pelayanan.length > 0) {
                                res.data.pelayanan.forEach(p => addPelayananBlock(p.id, p.NamaPelayanan, p.data_bulan));
                            } else {
                                addPelayananBlock();
                            }
                            openModal('modalPelayanan');
                        }
                    }
                });
            }

            function changeTahunPelayanan() {
                if(currentOpdIdForPelayanan) editPelayanan(currentOpdIdForPelayanan, $('#selectTahunPelayanan').val());
            }

            function savePelayanan(e) {
                e.preventDefault();
                $.ajax({
                    url: '<?= base_url("Admin/save_pelayanan") ?>', method: 'POST',
                    data: $('#formPelayanan').serialize(), dataType: 'json',
                    success: function(res) {
                        if(res.status === 'success') location.reload();
                        else Swal.fire({icon:'error', text:res.message, background:'#0b172e', color:'#fff'});
                    }
                });
            }

            $(document).ready(() => {
                refreshIcons();
                initSearch(); // Inisialisasi index data pencarian
                changeItemsPerPage($('#itemsPerPage').val() || 5); // Default memanggil 5 entri sesuai HTML
                toggleCustomBulan();
            });
        </script>
    </div>
</body>
</html>