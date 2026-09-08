    <!-- SheetJS Library untuk Export Excel Client-side -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <style>
        /* Styling scrollbar kustom untuk tabel rekap */
        .table-responsive::-webkit-scrollbar { height: 8px; width: 8px; }
        .table-responsive::-webkit-scrollbar-track { background: #050e1d; border-radius: 4px; }
        .table-responsive::-webkit-scrollbar-thumb { background: #1e2d4a; border-radius: 4px; }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    </style>

    <!-- SCROLLABLE CONTENT BODY -->
    <div class="w-full p-1 md:p-1 lg:p-1 pb-16">
        
        <!-- HEADER HERO BANNER -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-950 to-[#0a1324] border border-blue-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-blue-900/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                <i data-lucide="shield" class="w-48 h-48 text-blue-300"></i>
            </div>
            <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                <h2 class="text-2xl font-bold text-white mb-1">Manajemen Jenis Perizinan & Rekap Bulanan</h2>
                <p class="text-sm text-blue-200">Kelola master perizinan dan rekapitulasi perizinan per bulan untuk Tahun <?= $selected_tahun ?>.</p>
            </div>
            <div class="relative z-10 flex gap-4 text-center">
                <div class="bg-[#050e1d]/60 backdrop-blur-sm border border-blue-500/30 px-5 py-2.5 rounded-xl shadow-inner">
                    <div class="text-[10px] text-blue-300 uppercase tracking-widest font-semibold mb-0.5">Total Jenis Izin Aktif</div>
                    <div class="text-2xl font-bold text-white"><?= !empty($jenis_izin_data) ? count($jenis_izin_data) : '0' ?></div>
                </div>
                <div class="bg-[#050e1d]/60 backdrop-blur-sm border border-blue-500/30 px-5 py-2.5 rounded-xl shadow-inner">
                    <div class="text-[10px] text-blue-300 uppercase tracking-widest font-semibold mb-0.5">Tahun Filter</div>
                    <div class="text-2xl font-bold text-emerald-400"><?= $selected_tahun ?></div>
                </div>
            </div>
        </div>

        <!-- MAIN PANEL -->
        <div class="admin-panel flex flex-col bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl overflow-hidden">
            
            <!-- TOOLBAR ATAS: FILTER BULAN, TAHUN & BUTTONS EXPORT -->
            <div class="p-4 sm:p-5 border-b border-[#1e2d4a] flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-[#081122]">
                
                <!-- FILTER RENTANG BULAN & FILTER TAHUN -->
                <div class="flex flex-wrap items-center gap-3">
                    
                    <!-- FILTER TAHUN TABEL -->
                    <div class="flex items-center gap-2 bg-[#050e1d] border border-[#1e2d4a] rounded-lg px-3 py-1.5 focus-within:border-blue-500 transition-colors">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                        <span class="text-xs text-slate-400 font-semibold uppercase">Tahun:</span>
                        <input type="number" id="filterTahunMain" value="<?= $selected_tahun ?>" min="2016" max="2099" maxlength="4" placeholder="YYYY" class="w-16 bg-transparent text-emerald-400 font-bold text-xs outline-none text-center">
                        <button onclick="applyTahunFilter()" class="bg-emerald-600 hover:bg-emerald-500 text-white p-1 rounded transition-colors" title="Terapkan Filter Tahun">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    <div class="h-5 w-[1px] bg-[#1e2d4a] hidden sm:block"></div>

                    <!-- <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 uppercase tracking-wider">
                        <i data-lucide="filter" class="w-4 h-4 text-blue-400"></i> Rentang Bulan:
                    </div> -->

                    <!-- PRESET FILTER BULAN -->
                    <select id="selectPresetRange" onchange="applyPresetRange()" class="bg-[#050e1d] border border-[#1e2d4a] text-blue-300 text-xs font-medium rounded-lg px-3 py-2 outline-none focus:border-blue-500 transition-colors">
                        <option value="1-12" selected>1 Tahun Penuh (Jan - Des)</option>
                        <option value="1-3">Triwulan I (Jan - Mar)</option>
                        <option value="4-6">Triwulan II (Apr - Jun)</option>
                        <option value="7-9">Triwulan III (Jul - Sep)</option>
                        <option value="10-12">Triwulan IV (Okt - Des)</option>
                        <option value="1-6">Semester 1 (Jan - Jun)</option>
                        <option value="7-12">Semester 2 (Jul - Des)</option>
                        <option value="custom">Kustom Rentang Bulan...</option>
                    </select>

                    <!-- KUSTOM RENTANG BULAN SELECT -->
                    <div id="containerCustomRange" class="hidden flex items-center gap-2">
                        <select id="startMonth" onchange="updateTableColumns()" class="bg-[#050e1d] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-2.5 py-2 outline-none focus:border-blue-500">
                            <?php 
                            $months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                            foreach($months as $idx => $m) {
                                echo "<option value='".($idx+1)."'>$m</option>";
                            }
                            ?>
                        </select>
                        <span class="text-xs text-slate-400">s/d</span>
                        <select id="endMonth" onchange="updateTableColumns()" class="bg-[#050e1d] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-2.5 py-2 outline-none focus:border-blue-500">
                            <?php 
                            foreach($months as $idx => $m) {
                                $sel = ($idx == 11) ? 'selected' : '';
                                echo "<option value='".($idx+1)."' $sel>$m</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- TOMBOL AKSI: DOWNLOAD EXCEL, PDF & TAMBAH DATA -->
                <div class="flex flex-wrap items-center gap-2">
                    <button onclick="downloadExcel()" class="flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg px-3 py-2 text-xs transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.5)]">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel
                    </button>
                    <button onclick="downloadPDF()" class="flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-lg px-3 py-2 text-xs transition-all shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)]">
                        <i data-lucide="file-down" class="w-4 h-4"></i> PDF
                    </button>
                    <button onclick="openModal()" class="flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-3 py-2 text-xs transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)] hover:shadow-[0_0_20px_rgba(37,99,235,0.5)]">
                        <i data-lucide="plus" class="w-4 h-4"></i> Perizinan
                    </button>
                </div>
            </div>

            <!-- TABEL DATA DINAMIS -->
            <div class="table-responsive overflow-x-auto w-full">
                <table id="tabelIzin" class="w-full text-left text-[10px] text-slate-300 relative">
                    <thead class="text-[9px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a] sticky top-0 z-20">
                        <tr id="tableHeaderRow">
                            <th scope="col" class="px-2 py-2.5 font-semibold w-8 text-center align-middle whitespace-nowrap border-r border-[#1e2d4a]/50">No</th>
                            <th scope="col" class="px-2 py-2.5 font-semibold min-w-[130px] text-center align-middle whitespace-nowrap border-r border-[#1e2d4a]/50">Nama Jenis Perizinan</th>
                            
                            <!-- HEADERS BULAN (JAN - DES) -->
                            <?php 
                            $shortMonths = array('JAN','FEB','MAR','APR','MEI','JUN','JUL','AGU','SEP','OKT','NOV','DES');
                            foreach($shortMonths as $idx => $mName): 
                                $bNum = $idx + 1;
                            ?>
                                <th scope="col" class="col-month col-month-<?= $bNum ?> px-1.5 py-2.5 font-semibold text-center align-middle whitespace-nowrap border-r border-[#1e2d4a]/30 w-10"><?= $mName ?></th>
                            <?php endforeach; ?>

                            <th scope="col" class="px-2 py-2.5 font-semibold text-center align-middle whitespace-nowrap w-16 text-emerald-400 bg-[#071329] border-r border-[#1e2d4a]/50">TOTAL</th>
                            <th scope="col" class="px-2 py-2.5 font-semibold text-center align-middle w-16 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e2d4a]/50">
                        <?php 
                        $columnSums = array_fill(1, 12, 0);
                        $grandTotal = 0;
                        
                        if(!empty($jenis_izin_data)): 
                            foreach($jenis_izin_data as $index => $row): 
                                $rowTotal = 0;
                        ?>
                            <tr class="hover:bg-[#0c1833] transition-colors group">
                                <td class="px-2 py-2 text-center align-middle font-medium text-slate-500 border-r border-[#1e2d4a]/40"><?= $index + 1 ?></td>
                                <td class="px-2 py-2 text-left align-middle text-white font-medium whitespace-normal break-words border-r border-[#1e2d4a]/40">
                                    <?= htmlspecialchars($row->JenisIzin) ?>
                                </td>

                                <!-- KOLOM BULAN 1 SD 12 -->
                                <?php 
                                for($b = 1; $b <= 12; $b++):
                                    $val = isset($row->rekap_bulan[$b]) ? $row->rekap_bulan[$b] : 0;
                                    $rowTotal += $val;
                                    $columnSums[$b] += $val;
                                    $textStyle = $val > 0 ? 'text-white font-semibold' : 'text-slate-600';
                                ?>
                                    <td class="col-month col-month-<?= $b ?> px-1.5 py-2 text-center align-middle border-r border-[#1e2d4a]/30 <?= $textStyle ?>">
                                        <?= number_format($val, 0, ',', '.') ?>
                                    </td>
                                <?php endfor; ?>

                                <?php $grandTotal += $rowTotal; ?>
                                <td class="px-2 py-2 text-center align-middle font-bold text-emerald-400 bg-[#071329]/60 border-r border-[#1e2d4a]/40">
                                    <?= number_format($rowTotal, 0, ',', '.') ?>
                                </td>

                                <td class="px-2 py-2 text-center align-middle">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick="editIzin(<?= $row->id ?>)" class="p-1 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit Data & Rekap Bulan">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="deleteIzin(<?= $row->id ?>)" class="p-1 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus Jenis Perizinan">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="16" class="px-4 py-8 text-center text-slate-500 italic text-[11px]">
                                    Belum ada data Jenis Perizinan yang tersimpan untuk Tahun <?= $selected_tahun ?>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <!-- FOOTER TOTAL AKUMULASI VERTIKAL DI BARIS PALING BAWAH -->
                    <tfoot class="bg-[#050e1d] font-bold text-white border-t-2 border-[#1e2d4a]">
                        <tr>
                            <td colspan="2" class="px-2 py-3 text-center align-middle uppercase tracking-widest text-[10px] text-blue-300 border-r border-[#1e2d4a]">
                                Total Akumulasi
                            </td>
                            <?php for($b = 1; $b <= 12; $b++): ?>
                                <td class="col-month col-month-<?= $b ?> px-1.5 py-3 text-center align-middle border-r border-[#1e2d4a]/40 text-blue-200" id="footSumMonth<?= $b ?>">
                                    <?= number_format($columnSums[$b], 0, ',', '.') ?>
                                </td>
                            <?php endfor; ?>
                            <td class="px-2 py-3 text-center align-middle text-emerald-400 bg-[#071329] border-r border-[#1e2d4a] text-[11px]" id="footGrandTotal">
                                <?= number_format($grandTotal, 0, ',', '.') ?>
                            </td>
                            <td class="bg-[#050e1d]"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL INPUT BATCH 12 BULAN, INPUT TAHUN (>2015) & NAMA PERIZINAN -->
    <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        <div id="modalContent" class="relative w-full max-w-5xl bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
            
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a] bg-[#071126] rounded-t-xl shrink-0">
                <div>
                    <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Input Data Jenis Perizinan</h3>
                    <p class="text-[11px] text-blue-400 mt-0.5">Input Nama Perizinan, Tahun Target (Harus > 2015), dan Rekapitulasi 12 Bulan.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="addIzinBlock()" class="text-[11px] font-medium bg-blue-600/20 text-blue-300 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-md border border-blue-500/40 transition-colors flex items-center gap-1">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Baris Perizinan
                    </button>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-md transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            
            <form id="formIzin" onsubmit="saveData(event)" class="p-4 sm:p-6 flex flex-col gap-5 overflow-y-auto">
                
                <!-- INPUT TAHUN DENGAN VALIDASI (> 2015) -->
                <div class="bg-[#071126] border border-[#1e2d4a] p-4 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-days" class="w-5 h-5 text-emerald-400"></i>
                        <div>
                            <label for="inputTahunModal" class="text-xs font-bold text-white block">Tahun Rekapitulasi <span class="text-rose-400">*</span></label>
                            <p class="text-[10px] text-slate-400">Format 4 digit angka &amp; harus di atas 2015 (Contoh: 2026)</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto">
                        <input type="number" id="inputTahunModal" name="Tahun" value="<?= $selected_tahun ?>" min="2016" max="2099" maxlength="4" placeholder="Contoh: 2026" class="w-full sm:w-36 bg-[#050e1d] border border-[#1e2d4a] text-emerald-400 text-sm font-bold text-center rounded-lg p-2 outline-none focus:border-emerald-500 transition-colors" required>
                    </div>
                </div>

                <!-- CONTAINER DINAMIS UNTUK FORM MULTI BARIS -->
                <div id="izinContainer" class="space-y-4">
                    <!-- Javascript merender block izin di sini -->
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-[#1e2d4a] sticky bottom-0 bg-[#0b172e] py-2 mt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitForm" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-colors shadow-lg shadow-blue-900/40 border border-blue-500/50 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Data &amp; Rekap
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS & PDF/EXCEL EXPORT LOGIC -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script>
        const dataIzinPDF = <?= !empty($jenis_izin_data) ? json_encode($jenis_izin_data) : '[]' ?>;
        let currentYearPDF = <?= $selected_tahun ?>;
        const monthNamesIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const monthShortIndo = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];

        let izinIndex = 0;

        function refreshIcons() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $(document).ready(function() {
            refreshIcons();
            updateTableColumns();
        });

        // FILTER TAHUN UTAMA
        function applyTahunFilter() {
            const yr = parseInt($('#filterTahunMain').val());
            if (isNaN(yr) || yr <= 2015 || $('#filterTahunMain').val().length !== 4) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Tahun Salah!',
                    text: 'Tahun harus berupa 4 digit angka dan lebih besar dari 2015 (Contoh: 2026).',
                    background: '#0b172e', color: '#fff'
                });
                return;
            }
            window.location.href = `<?= base_url('Admin/JenisIzin/') ?>${yr}`;
        }

        // 1. TAMBAH BLOK FORM DINAMIS (ARRAY)
        function addIzinBlock(idIzin = '', nama = '', dataBulan = null) {
            const idx = izinIndex++;
            let gridHTML = '';
            
            for(let i=1; i<=12; i++) {
                let val = (dataBulan && dataBulan[i]) ? dataBulan[i] : 0;
                gridHTML += `
                    <div class="flex flex-col gap-1 bg-[#081122] p-2 rounded-lg border border-[#1e2d4a]/80 focus-within:border-blue-500 transition-colors">
                        <label class="text-[9px] text-slate-400 font-bold uppercase text-center">${monthNamesIndo[i-1]}</label>
                        <input type="number" name="Izin[${idx}][Bulan][${i}]" value="${val}" min="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-center text-white text-xs font-bold rounded p-1.5 outline-none focus:border-emerald-500 transition-colors">
                    </div>
                `;
            }

            $('#izinContainer').append(`
                <div class="izin-block border border-[#1e2d4a] rounded-xl bg-[#071126] p-4 group transition-colors hover:border-emerald-900/50">
                    <input type="hidden" name="Izin[${idx}][id]" value="${idIzin}">
                    
                    <div class="flex items-center gap-3 mb-4">
                        <i data-lucide="file-text" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <input type="text" name="Izin[${idx}][JenisIzin]" value="${nama}" placeholder="Ketik Nama Jenis Perizinan (Contoh: SIUP)..." class="flex-1 bg-[#050e1d] border border-[#1e2d4a] text-white text-[13px] font-medium rounded-lg p-2 outline-none focus:border-emerald-500" required>
                        <button type="button" onclick="$(this).closest('.izin-block').remove()" class="p-2 text-slate-500 hover:text-red-400 bg-slate-800 hover:bg-red-900/20 rounded-md transition-colors" title="Hapus Baris">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-12 gap-2">
                        ${gridHTML}
                    </div>
                </div>
            `);
            refreshIcons();
        }

        // 2. DYNAMIC RENTANG BULAN PRESET
        function applyPresetRange() {
            const val = $('#selectPresetRange').val();
            if (val === 'custom') {
                $('#containerCustomRange').removeClass('hidden');
            } else {
                $('#containerCustomRange').addClass('hidden');
                const parts = val.split('-');
                $('#startMonth').val(parts[0]);
                $('#endMonth').val(parts[1]);
            }
            updateTableColumns();
        }

        function updateTableColumns() {
            const startM = parseInt($('#startMonth').val());
            const endM = parseInt($('#endMonth').val());

            $('.col-month').hide();
            for (let b = 1; b <= 12; b++) {
                if (b >= startM && b <= endM) { $(`.col-month-${b}`).show(); }
            }
        }

        // 3. MODAL CONTROLS
        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            if (!isEdit) {
                $('#modalTitle').text('Tambah Jenis Perizinan Baru (Bisa Multi Baris)');
                $('#inputTahunModal').val(currentYearPDF);
                $('#izinContainer').empty();
                addIzinBlock(); 
            }
        }

        function closeModal() {
            $('#modalCRUD').addClass('hidden');
        }

        // 4. EDIT DATA VIA AJAX
        function editIzin(id) {
            $.ajax({
                url: `<?= base_url('Admin/get_izin/') ?>${id}/${currentYearPDF}`,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        $('#modalTitle').text('Edit Jenis Perizinan & Rekap Bulan');
                        $('#inputTahunModal').val(res.data.tahun);
                        $('#izinContainer').empty();
                        addIzinBlock(res.data.id, res.data.JenisIzin, res.data.data_bulan);
                        openModal(true);
                    } else {
                        Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff'});
                    }
                }
            });
        }

        // 5. SIMPAN DATA VIA AJAX DENGAN VALIDASI TAHUN (> 2015)
        function saveData(event) {
            event.preventDefault();
            
            const tahunInputStr = $('#inputTahunModal').val().trim();
            const tahunInput = parseInt(tahunInputStr);

            if (isNaN(tahunInput) || tahunInputStr.length !== 4 || tahunInput <= 2015) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Tahun Gagal!',
                    text: 'Tahun harus terdiri dari 4 digit angka dan bernilai di atas 2015 (Contoh: 2026).',
                    background: '#0b172e', color: '#fff'
                });
                $('#inputTahunModal').focus();
                return;
            }

            if($('.izin-block').length === 0) {
                Swal.fire({icon:'warning', text:'Tambahkan minimal 1 Jenis Perizinan.', background:'#0b172e', color:'#fff'}); 
                return;
            }

            const $btnSubmit = $('#btnSubmitForm');
            const originalText = $btnSubmit.html();
            $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`).prop('disabled', true);

            const formData = new FormData($('#formIzin')[0]);

            $.ajax({
                url: '<?= base_url("Admin/save_izin") ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        closeModal();
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: res.message,
                            background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false
                        }).then(() => {
                            window.location.href = `<?= base_url('Admin/JenisIzin/') ?>${tahunInput}`;
                        });
                    } else {
                        Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff'});
                        $btnSubmit.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let msg = 'Gagal memproses data. Pastikan format input benar.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire({icon: 'error', title: 'Kesalahan Sistem', text: msg, background: '#0b172e', color: '#fff'});
                    $btnSubmit.html(originalText).prop('disabled', false);
                }
            });
        }

        // 6. HAPUS DATA VIA AJAX (BUG FIX TAHUN)
        function deleteIzin(id) {
            Swal.fire({
                title: `Hapus Rekap Tahun ${currentYearPDF}?`, 
                text: "Data rekapitulasi untuk tahun ini akan dihapus. Jika ini adalah satu-satunya tahun yang tersisa, master jenis izin juga akan terhapus.", 
                icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#1e2d4a',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', background: '#0b172e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Pass id dan tahun yang sedang dilihat di layar
                    $.ajax({
                        url: `<?= base_url('Admin/delete_izin/') ?>${id}/${currentYearPDF}`,
                        method: 'POST', dataType: 'json',
                        success: function(res) {
                            if (res && res.status === 'success') {
                                Swal.fire({icon: 'success', title: 'Dihapus!', text: res.message, background: '#0b172e', color: '#fff', timer: 2000, showConfirmButton: false}).then(() => location.reload());
                            } else {
                                Swal.fire({icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff'});
                            }
                        }
                    });
                }
            });
        }

        // 7. EXPORT REKAP DINAMIS DENGAN FORMAT EXCEL (.XLSX)
        async function downloadExcel() {

            if (typeof ExcelJS === 'undefined') {
                alert("ExcelJS library is missing. Please include it to export with formatting.");
                return;
            }

            const startM = parseInt($('#startMonth').val());
            const endM = parseInt($('#endMonth').val());
            // Assuming these are available globally as in the original code
            const currentYearPDF = new Date().getFullYear(); // Or your specific variable
            const monthNamesIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const monthShortIndo = ["JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGU", "SEP", "OKT", "NOV", "DES"];

            let rentangText = "";
            if (startM === 1 && endM === 12) rentangText = `TAHUN ${currentYearPDF}`;
            else if (startM === endM) rentangText = `BULAN ${monthNamesIndo[startM-1].toUpperCase()} TAHUN ${currentYearPDF}`;
            else rentangText = `PERIODE ${monthNamesIndo[startM-1].toUpperCase()} - ${monthNamesIndo[endM-1].toUpperCase()} TAHUN ${currentYearPDF}`;

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Rekap Perizinan');

            
            // Add empty rows for the logo space if needed (though tricky to position perfectly without base64 image insert)
            // We will center text across columns to simulate the header
            
            // Calculate total columns for merging
            const totalCols = (endM - startM + 1) + 3; // NO, JENIS IJIN, [Months...], TOTAL
            const lastColLetter = String.fromCharCode(64 + totalCols); // Assumes < 26 columns

            worksheet.addRow(['PEMERINTAH KABUPATEN MIMIKA']);
            worksheet.mergeCells(`A1:${lastColLetter}1`);
            worksheet.getCell('A1').font = { name: 'Arial', size: 14, bold: true };
            worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow(['DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU']);
            worksheet.mergeCells(`A2:${lastColLetter}2`);
            worksheet.getCell('A2').font = { name: 'Arial', size: 12, bold: true };
            worksheet.getCell('A2').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow(['Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II']);
            worksheet.mergeCells(`A3:${lastColLetter}3`);
            worksheet.getCell('A3').font = { name: 'Arial', size: 10 };
            worksheet.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow(['Tlp. (0901) 3262943 Timika - Papua Pos 99910']);
            worksheet.mergeCells(`A4:${lastColLetter}4`);
            worksheet.getCell('A4').font = { name: 'Arial', size: 10 };
            worksheet.getCell('A4').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow([]); // Row 5 empty for spacing
            
            // Simulating the thick double line. ExcelJS borders apply to cells.
            // We'll apply a thick bottom border to an empty row.
            worksheet.addRow([]); // Row 6 for line
            for(let i = 1; i <= totalCols; i++) {
                worksheet.getCell(5, i).border = {
                    bottom: { style: 'double' } // closest to the image's thick line
                };
            }

            worksheet.addRow([`REKAP JUMLAH PERIZINAN DAN NON PERIZINAN ${rentangText}`]);
            worksheet.mergeCells(`A7:${lastColLetter}7`);
            worksheet.getCell('A7').font = { name: 'Arial', size: 11, bold: true };
            worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow([]); // Row 9 empty spacing

            let headers = ['NO', 'JENIS IJIN'];
            for (let b = startM; b <= endM; b++) {
                headers.push(monthShortIndo[b - 1]);
            }
            headers.push('TOTAL');
            
            const headerRow = worksheet.addRow(headers);
            
            // Style Table Headers
            headerRow.eachCell((cell) => {
                cell.font = { name: 'Arial', size: 10, bold: true };
                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFB8CCE4' } // Light blue color similar to image
                };
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });

            let sums = Array(endM - startM + 1).fill(0);
            let grandTotalExcel = 0;

            // Mock data if dataIzinPDF is not defined for this snippet to run
            let dataList = typeof dataIzinPDF !== 'undefined' ? dataIzinPDF : [];
            
            // Fallback mock data matching image if empty
            if(dataList.length === 0) {
                dataList = [
                    { JenisIzin: 'Surat Izin Dokter Gigi (SIPDGI)', rekap_bulan: {1:5, 2:4, 3:0, 4:2, 5:0, 6:10} },
                    { JenisIzin: 'Surat Izin Penyelenggaraan Optik (SIPO)', rekap_bulan: {1:0, 2:0, 3:0, 4:0, 5:0, 6:0} },
                    { JenisIzin: 'Surat Izin Spikolog Klinis (SIPPK)', rekap_bulan: {1:0, 2:0, 3:0, 4:0, 5:0, 6:0} }
                ];
            }

            if (dataList && dataList.length > 0) {
                dataList.forEach((item, index) => {
                    let rowData = [(index + 1), item.JenisIzin];
                    let rowTotal = 0;
                    let colIdx = 0;

                    for (let b = startM; b <= endM; b++) {
                        let val = (item.rekap_bulan && item.rekap_bulan[b]) ? parseInt(item.rekap_bulan[b]) : 0;
                        rowData.push(val);
                        rowTotal += val;
                        sums[colIdx] += val;
                        colIdx++;
                    }
                    rowData.push(rowTotal);
                    grandTotalExcel += rowTotal;
                    
                    const dataRow = worksheet.addRow(rowData);
                    
                    // Style Data Rows
                    dataRow.eachCell((cell, colNumber) => {
                        cell.font = { name: 'Arial', size: 10 };
                        cell.border = {
                            top: { style: 'thin' },
                            left: { style: 'thin' },
                            bottom: { style: 'thin' },
                            right: { style: 'thin' }
                        };
                        if (colNumber === 1 || colNumber > 2) { // No and Numbers
                            cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        } else { // Nama Jenis Perizinan
                            cell.alignment = { vertical: 'middle', horizontal: 'left' };
                        }
                    });
                });
            }

            let footRowData = ['TOTAL', ''];
            sums.forEach(s => footRowData.push(s));
            footRowData.push(grandTotalExcel);
            
            const footRow = worksheet.addRow(footRowData);
            
            // Merge TOTAL cells
            worksheet.mergeCells(`A${footRow.number}:B${footRow.number}`);
            
            footRow.eachCell((cell, colNumber) => {
                cell.font = { name: 'Arial', size: 10, bold: true };
                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFE6E6E6' } // Very light grey, as in image
                };
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });

            worksheet.addRow([]);
            worksheet.addRow([]);
            
            const now = new Date();
            const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
            
            // Calculate where signature should go (usually right-aligned)
            const sigStartCol = Math.max(3, totalCols - 3); // Put it a few columns from the end
            const sigColLetter = String.fromCharCode(64 + sigStartCol);
            
            // Helper function to add signature text
            const addSigLine = (text, bold = false) => {
                const row = worksheet.addRow([]);
                const cell = row.getCell(sigStartCol);
                cell.value = text;
                cell.font = { name: 'Arial', size: 10, bold: bold };
                cell.alignment = { horizontal: 'left' }; // Left align within its column area
            };

            addSigLine(`TIMIKA, ${dateStr}`);
            addSigLine('Kepala Dinas,');
            addSigLine('Penanaman Modal dan Pelayanan Terpadu');
            addSigLine('Satu Pintu Kabupaten Mimika,');
            
            worksheet.addRow([]); // Space for signature
            worksheet.addRow([]);
            worksheet.addRow([]);

            addSigLine('Marselino Mameyao, SKM', true);
            addSigLine('Pembina TK. I');
            addSigLine('NIP : 196805141989111002');


            worksheet.columns = [
                { width: 5 },   // NO
                { width: 45 },  // JENIS IJIN
                // Dynamically add widths for month columns and total
                ...Array.from({length: endM - startM + 2}, () => ({ width: 10 }))
            ];

            const buffer = await workbook.xlsx.writeBuffer();
            
            // Create a Blob and trigger download (Browser compatible)
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Rekap_Data_Perizinan_Mimika_`+`PERIODE ${monthNamesIndo[startM-1].toUpperCase()} - ${monthNamesIndo[endM-1].toUpperCase()} TAHUN ${currentYearPDF}`+`.xlsx`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // 8. EXPORT REKAP DINAMIS DENGAN FORMAT PDF
        function getBase64ImageFromURL(url) {
            return new Promise((resolve, reject) => {
                var img = new Image();
                img.setAttribute("crossOrigin", "anonymous");
                img.onload = () => {
                    var canvas = document.createElement("canvas");
                    canvas.width = img.width; canvas.height = img.height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    resolve(canvas.toDataURL("image/png"));
                };
                img.onerror = error => reject(error);
                img.src = url;
            });
        }

        async function downloadPDF() {
            Swal.fire({
                title: 'Menyiapkan Laporan PDF...', text: 'Membentuk layout laporan, mohon tunggu...',
                allowOutsideClick: false, background: '#0b172e', color: '#fff',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape', 'mm', 'a4');

                const startM = parseInt($('#startMonth').val());
                const endM = parseInt($('#endMonth').val());

                const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
                try {
                    const logoBase64 = await getBase64ImageFromURL(logoUrl);
                    doc.addImage(logoBase64, 'PNG', 25, 6, 22, 28);
                } catch (e) {
                    console.warn("Logo tidak terload.");
                }

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
                let rentangText = "";
                if (startM === 1 && endM === 12) rentangText = `TAHUN ${currentYearPDF}`;
                else if (startM === endM) rentangText = `BULAN ${monthNamesIndo[startM-1].toUpperCase()} TAHUN ${currentYearPDF}`;
                else rentangText = `PERIODE ${monthNamesIndo[startM-1].toUpperCase()} - ${monthNamesIndo[endM-1].toUpperCase()} TAHUN ${currentYearPDF}`;

                const titleText = `REKAP JUMLAH PERIZINAN DAN NON PERIZINAN ${rentangText}`;
                doc.text(titleText, 148, 47, { align: "center" });
                
                let tableHeaders = ['NO', 'JENIS IJIN'];
                for (let b = startM; b <= endM; b++) { tableHeaders.push(monthShortIndo[b - 1]); }
                tableHeaders.push('TOTAL');

                let tableBody = [];
                let sums = array_fill_js(endM - startM + 1, 0);
                let grandTotalPDF = 0;

                if (dataIzinPDF && dataIzinPDF.length > 0) {
                    dataIzinPDF.forEach((item, index) => {
                        let row = [(index + 1).toString(), item.JenisIzin];
                        let rowTotal = 0; let colIdx = 0;

                        for (let b = startM; b <= endM; b++) {
                            let val = (item.rekap_bulan && item.rekap_bulan[b]) ? parseInt(item.rekap_bulan[b]) : 0;
                            row.push(val.toLocaleString('id-ID'));
                            rowTotal += val; sums[colIdx] += val; colIdx++;
                        }

                        row.push(rowTotal.toLocaleString('id-ID'));
                        grandTotalPDF += rowTotal; tableBody.push(row);
                    });
                } else {
                    tableBody.push(['-', 'Belum ada data jenis perizinan', ...array_fill_js(endM - startM + 2, '0')]);
                }

                let footRow = [{ content: 'TOTAL', colSpan: 2, styles: { halign: 'center' } }];
                sums.forEach(s => footRow.push(s.toLocaleString('id-ID')));
                footRow.push(grandTotalPDF.toLocaleString('id-ID'));

                doc.autoTable({
                    startY: 54, head: [tableHeaders], body: tableBody, foot: [footRow], theme: 'grid',
                    headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                    bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                    footStyles: { fillColor: [220, 230, 241], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                    columnStyles: { 0: { halign: 'center', cellWidth: 10 }, 1: { cellWidth: 80 } },
                    didParseCell: function(data) { if (data.section === 'body' && data.column.index >= 2) { data.cell.styles.halign = 'center'; } },
                    margin: { top: 15, right: 15, bottom: 20, left: 15 }
                });

                const finalY = doc.lastAutoTable.finalY + 10;
                const now = new Date();
                const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                const rightMargin = 205; 

                doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                doc.text(`TIMIKA, ${dateStr}`, rightMargin, finalY);
                doc.text("Kepala Dinas,", rightMargin, finalY + 5);
                doc.text("Penanaman Modal dan Pelayanan Terpadu", rightMargin, finalY + 10);
                doc.text("Satu Pintu Kabupaten Mimika,", rightMargin, finalY + 15);

                const signatureY = finalY + 38;
                doc.setFont("helvetica", "bold");
                doc.text("Marselino Mameyao, SKM", rightMargin, signatureY);
                const textWidthNama = doc.getTextWidth("Marselino Mameyao, SKM");

                doc.setFont("helvetica", "normal");
                doc.text("Pembina TK. I", rightMargin, signatureY + 5);
                doc.text("NIP : 196805141989111002", rightMargin, signatureY + 10);

                doc.save(`Rekap_Data_Perizinan_Mimika_${currentYearPDF}.pdf`);
                Swal.close();
            } catch (error) {
                Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0b172e', color: '#fff'});
            }
        }

        function array_fill_js(length, value) {
            var arr = []; for (var i = 0; i < length; i++) { arr.push(value); } return arr;
        }
    </script>
</body>
</html>