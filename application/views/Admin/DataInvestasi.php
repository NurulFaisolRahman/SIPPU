        <!-- ExcelJS untuk Export Excel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
        <!-- jsPDF & AutoTable untuk Export PDF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
        
        <!-- SCROLLABLE CONTENT BODY -->
        <div class="w-full p-1 md:p-1 lg:p-1 pb-16">
            
            <div class="bg-gradient-to-r from-purple-900 to-[#0a1324] border border-purple-800/50 rounded-xl p-4 mb-4 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-purple-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="trending-up" class="w-40 h-40"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-xl font-bold text-white mb-1">Rekapitulasi Investasi & Tenaga Kerja</h2>
                    <p class="text-xs text-purple-200">Manajemen laporan nilai Penanaman Modal Dalam Negeri (PMDN) & Asing (PMA).</p>
                </div>
                <div class="relative z-10 flex gap-3 text-center">
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-purple-500/30 px-3 py-1.5 rounded-lg">
                        <div class="text-[9px] text-purple-300 uppercase tracking-widest font-semibold mb-0.5">Total Rekap</div>
                        <div class="text-lg font-bold text-white"><?= !empty($investasi_data) ? count($investasi_data) : '0' ?></div>
                    </div>
                    <?php 
                        $total_nilai_investasi = 0;
                        if(!empty($investasi_data)) {
                            foreach($investasi_data as $inv) { 
                                $total_nilai_investasi += (double)$inv->NilaiInvestasiPMDN + (double)$inv->NilaiInvestasiPMA; 
                            }
                        }
                    ?>
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-emerald-500/30 px-3 py-1.5 rounded-lg">
                        <div class="text-[9px] text-emerald-300 uppercase tracking-widest font-semibold mb-0.5">Total Investasi</div>
                        <div class="text-lg font-bold text-emerald-400">Rp <?= number_format($total_nilai_investasi, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel flex flex-col">
                <!-- FILTER TAHUN & TOMBOL EXPORT -->
                <div class="p-3 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-between gap-2 bg-[#081122] rounded-t-[0.75rem]">
                    <form action="<?= base_url('Admin/DataInvestasi') ?>" method="GET" class="flex items-center gap-2">
                        <label class="text-[10px] text-slate-400 font-semibold uppercase">Filter Tahun:</label>
                        <input type="number" name="tahun" id="filterTahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" class="w-20 bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded md:rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-1.5" min="2016" maxlength="4">
                        <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white p-1.5 rounded md:rounded-lg transition-colors">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>

                    <div class="flex items-center gap-2">
                        <button onclick="downloadExcel()" class="flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded md:rounded-lg px-2.5 py-1.5 text-xs transition-all shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                            <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Excel
                        </button>
                        <button onclick="downloadPDF()" class="flex items-center justify-center gap-1.5 bg-red-600 hover:bg-red-500 text-white font-medium rounded md:rounded-lg px-2.5 py-1.5 text-xs transition-all shadow-[0_0_10px_rgba(239,68,68,0.2)]">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                        </button>
                        <button onclick="openModal()" class="flex items-center justify-center gap-1.5 bg-purple-600 hover:bg-purple-500 text-white font-medium rounded md:rounded-lg px-3 py-1.5 text-xs transition-all shadow-[0_0_15px_rgba(147,51,234,0.3)] ml-1">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tabelInvestasi" class="w-full text-left text-[11px] text-slate-300">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold w-10 !text-center whitespace-nowrap">No</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Perusahaan / NIB</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-right whitespace-nowrap">Nilai PMDN (Rp)</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-right whitespace-nowrap">Nilai PMA (Rp)</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center whitespace-nowrap">Tenaga Kerja</th>
                                <th scope="col" class="px-4 py-3 font-semibold !text-center w-24 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e2d4a]/50">
                            <?php if(!empty($investasi_data)): ?>
                                <?php foreach($investasi_data as $index => $row): ?>
                                <tr class="hover:bg-[#0c1833] transition-colors">
                                    <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>
                                    
                                    <td class="px-4 py-3 whitespace-normal break-words max-w-[200px]">
                                        <div class="text-white font-bold text-sm mb-0.5"><?= htmlspecialchars($row->NamaUsaha ?: 'Tidak Diketahui') ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono tracking-wider">NIB: <?= htmlspecialchars($row->NIB ?: '-') ?></div>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="font-mono text-blue-400 font-semibold text-sm">
                                            <?= number_format($row->NilaiInvestasiPMDN, 0, ',', '.') ?>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="font-mono text-rose-400 font-semibold text-sm">
                                            <?= number_format($row->NilaiInvestasiPMA, 0, ',', '.') ?>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <?php $total_tk = $row->TenagaKerjaLokal + $row->TenagaKerjaAsing; ?>
                                        <div class="text-white font-bold text-sm mb-0.5"><?= number_format($total_tk, 0, ',', '.') ?> <span class="text-[10px] text-slate-400 font-normal">Org</span></div>
                                        <div class="text-[10px] text-slate-500 flex items-center justify-center gap-1.5">
                                            <span title="Pekerja Lokal" class="bg-slate-800 px-1.5 rounded"><b>LKL: <?= number_format($row->TenagaKerjaLokal, 0, ',', '.') ?></b></span>
                                            <span title="Pekerja Asing" class="bg-slate-800 px-1.5 rounded"><b>ASG: <?= number_format($row->TenagaKerjaAsing, 0, ',', '.') ?></b></span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editInvestasi(<?= $row->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="deleteInvestasi(<?= $row->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus">
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

        <!-- MODAL CRUD INVESTASI -->
        <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
            
            <div id="modalContent" class="relative w-full max-w-[600px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave h-[90vh] md:h-auto overflow-hidden">
                
                <div class="flex items-center justify-between p-4 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl shrink-0">
                    <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Data Investasi</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form id="formInvestasi" onsubmit="saveData(event)" class="flex flex-col h-full overflow-hidden">
                    <div class="p-5 overflow-y-auto flex-1 gap-4 flex flex-col custom-scrollbar">
                        <input type="hidden" id="inputId" name="id" value="">

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Pilih Profil Usaha / Perusahaan</label>
                            <select id="inputIdProfil" name="id_profil" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                <?php if(!empty($profil_list)): ?>
                                    <?php foreach($profil_list as $profil): ?>
                                        <option value="<?= $profil->id ?>"><?= htmlspecialchars($profil->NamaUsaha) ?> (NIB: <?= htmlspecialchars($profil->NIB) ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Tahun Laporan</label>
                            <input type="number" id="inputTahun" name="Tahun" min="2016" max="2100" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-blue-300 uppercase block">Nilai Investasi PMDN (Rupiah)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm font-bold">Rp</span>
                                    <input type="number" id="inputNilaiPMDN" name="NilaiInvestasiPMDN" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-blue-500 outline-none p-2.5 pl-10" placeholder="0" required>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-rose-300 uppercase block">Nilai Investasi PMA (Rupiah)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm font-bold">Rp</span>
                                    <input type="number" id="inputNilaiPMA" name="NilaiInvestasiPMA" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-rose-500 outline-none p-2.5 pl-10" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-500 -mt-2">*Masukkan angka saja tanpa titik. Biarkan 0 jika tidak ada nilai untuk jenis tersebut.</p>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#1e2d4a] mt-2">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jumlah TK Lokal (Indonesia)</label>
                                <input type="number" id="inputTkLokal" name="TenagaKerjaLokal" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jumlah TK Asing</label>
                                <input type="number" id="inputTkAsing" name="TenagaKerjaAsing" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 flex justify-end gap-3 border-t border-[#1e2d4a]/80 bg-[#071126] rounded-b-xl shrink-0">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                        <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-500 rounded-lg transition-colors shadow-lg shadow-purple-900/40 border border-purple-500/50 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const dataInvestasiExport = <?= !empty($investasi_data) ? json_encode($investasi_data) : '[]' ?>;
            const currentTahunFilter = $('#filterTahun').val() || '<?= date("Y") ?>';
            const monthNamesIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            document.addEventListener("DOMContentLoaded", function() {
                if ($.fn.DataTable) {
                    $('#tabelInvestasi').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        language: {
                            search: "Cari Data:",
                            lengthMenu: "Tampil _MENU_ entri",
                            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                            infoEmpty: "Menampilkan 0 data",
                            infoFiltered: "(difilter)",
                            emptyTable: "Belum ada data Rekap Investasi untuk tahun terpilih.",
                            paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Kembali" }
                        }
                    });
                }
            });

            function openModal(isEdit = false) {
                $('#modalCRUD').removeClass('hidden');
                setTimeout(() => { $('#modalContent').removeClass('modal-leave').addClass('modal-enter'); }, 10);

                if (!isEdit) {
                    $('#modalTitle').text('Tambah Data Investasi');
                    $('#formInvestasi')[0].reset();
                    $('#inputId').val(''); 
                    $('#inputTahun').val(currentTahunFilter);
                    $('#inputNilaiPMDN').val('0');
                    $('#inputNilaiPMA').val('0');
                }
            }

            function closeModal() {
                $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
                setTimeout(() => { $('#modalCRUD').addClass('hidden'); }, 300); 
            }

            function editInvestasi(id) {
                $.ajax({
                    url: `<?= base_url('Admin/get_investasi/') ?>${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.status === 'success') {
                            $('#modalTitle').text('Edit Data Investasi');
                            $('#inputId').val(res.data.id);
                            $('#inputIdProfil').val(res.data.id_profil);
                            $('#inputTahun').val(res.data.Tahun);
                            $('#inputNilaiPMDN').val(res.data.NilaiInvestasiPMDN);
                            $('#inputNilaiPMA').val(res.data.NilaiInvestasiPMA);
                            $('#inputTkLokal').val(res.data.TenagaKerjaLokal);
                            $('#inputTkAsing').val(res.data.TenagaKerjaAsing);
                            openModal(true); 
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                        }
                    },
                    error: function(xhr, status, error) { 
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan jaringan.', background: '#0b172e', color: '#fff' });
                    }
                });
            }

            function saveData(event) {
                event.preventDefault();
                
                const tahunInput = $('#inputTahun').val();
                if(tahunInput.length !== 4 || parseInt(tahunInput) <= 2015) {
                    Swal.fire({ icon: 'warning', title: 'Tahun Tidak Valid', text: 'Tahun harus terdiri dari 4 digit dan lebih besar dari 2015.', background: '#0b172e', color: '#fff' });
                    return;
                }

                const $btnSubmit = $('#btnSubmitForm');
                const originalText = $btnSubmit.html();
                $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...`);
                if(typeof lucide !== 'undefined') lucide.createIcons();
                $btnSubmit.prop('disabled', true);

                $.ajax({
                    url: '<?= base_url("Admin/save_investasi") ?>',
                    method: 'POST',
                    data: new FormData($('#formInvestasi')[0]),
                    processData: false, 
                    contentType: false, 
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.status === 'success') {
                            closeModal();
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false })
                            .then(() => location.reload()); 
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                        }
                    },
                    error: function() { 
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan.', background: '#0b172e', color: '#fff' });
                    },
                    complete: function() {
                        $btnSubmit.html(originalText);
                        if(typeof lucide !== 'undefined') lucide.createIcons();
                        $btnSubmit.prop('disabled', false);
                    }
                });
            }

            function deleteInvestasi(id) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data investasi ini akan disembunyikan dari rekapitulasi!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#1e2d4a',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    background: '#0b172e', color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `<?= base_url('Admin/delete_investasi/') ?>${id}`,
                            method: 'POST',
                            dataType: 'json',
                            success: function(res) {
                                if (res && res.status === 'success') {
                                    location.reload();
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                                }
                            }
                        });
                    }
                });
            }

            // ================== EXPORT EXCEL ==================
            async function downloadExcel() {
                if (typeof window.ExcelJS === 'undefined') {
                    alert("Library ExcelJS belum termuat. Pastikan koneksi internet stabil."); return;
                }

                const rentangText = `TAHUN ${currentTahunFilter}`;
                const workbook = new window.ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Data Investasi');
                
                // Kolom: NO, NAMA PERUSAHAAN, NIB, NILAI PMDN (RP), NILAI PMA (RP), TK LOKAL, TK ASING, TOTAL TK (Total 8 Kolom)
                const totalCols = 8; 
                const lastColLetter = 'H';

                // KOP SURAT
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

                worksheet.addRow([]); worksheet.addRow([]); 
                
                // Garis Double Kop Surat
                for(let i = 1; i <= totalCols; i++) {
                    worksheet.getCell(5, i).border = { bottom: { style: 'double' } };
                }

                // Judul Laporan
                worksheet.addRow([`REKAPITULASI DATA INVESTASI DAN TENAGA KERJA ${rentangText}`]);
                worksheet.mergeCells(`A7:${lastColLetter}7`);
                worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
                worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

                worksheet.addRow([]); 

                // Header Tabel
                let headers = ['NO', 'NAMA PERUSAHAAN', 'NIB', 'NILAI PMDN (RP)', 'NILAI PMA (RP)', 'TK LOKAL', 'TK ASING', 'TOTAL TK'];
                const headerRow = worksheet.addRow(headers);
                
                headerRow.eachCell((cell) => {
                    cell.font = { name: 'Arial', size: 10, bold: true };
                    cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                });

                let sumNilaiPMDN = 0;
                let sumNilaiPMA = 0;
                let sumTkLokal = 0;
                let sumTkAsing = 0;

                // Data Body
                if (dataInvestasiExport.length > 0) {
                    dataInvestasiExport.forEach((item, index) => {
                        const nilaiPMDN = parseFloat(item.NilaiInvestasiPMDN) || 0;
                        const nilaiPMA = parseFloat(item.NilaiInvestasiPMA) || 0;
                        const tkLokal = parseInt(item.TenagaKerjaLokal) || 0;
                        const tkAsing = parseInt(item.TenagaKerjaAsing) || 0;
                        const totalTk = tkLokal + tkAsing;

                        sumNilaiPMDN += nilaiPMDN;
                        sumNilaiPMA += nilaiPMA;
                        sumTkLokal += tkLokal;
                        sumTkAsing += tkAsing;

                        const rowData = [
                            (index + 1), 
                            (item.NamaUsaha || 'Tidak Diketahui'), 
                            (item.NIB || '-'), 
                            nilaiPMDN, 
                            nilaiPMA, 
                            tkLokal, 
                            tkAsing, 
                            totalTk
                        ];
                        const dataRow = worksheet.addRow(rowData);
                        
                        dataRow.eachCell((cell, colNumber) => {
                            cell.font = { name: 'Arial', size: 10 };
                            cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                            if (colNumber === 1 || colNumber === 3) { 
                                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                            } else if (colNumber === 4 || colNumber === 5) { // Nilai PMDN dan PMA
                                cell.alignment = { vertical: 'middle', horizontal: 'right' };
                                cell.numFmt = '#,##0';
                            } else if (colNumber >= 6) { // TK
                                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                                cell.numFmt = '#,##0';
                            } else { 
                                cell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: true };
                            }
                        });
                    });

                    // Row Total Summary (Merge A s.d C)
                    const totalRowData = ['TOTAL REKAPITULASI', '', '', sumNilaiPMDN, sumNilaiPMA, sumTkLokal, sumTkAsing, (sumTkLokal + sumTkAsing)];
                    const totalRow = worksheet.addRow(totalRowData);
                    worksheet.mergeCells(`A${totalRow.number}:C${totalRow.number}`);

                    totalRow.eachCell((cell, colNumber) => {
                        cell.font = { name: 'Arial', size: 10, bold: true };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCE6F1' } };
                        cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                        if (colNumber === 1) cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        else if (colNumber === 4 || colNumber === 5) { cell.alignment = { vertical: 'middle', horizontal: 'right' }; cell.numFmt = '#,##0'; }
                        else cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    });

                } else {
                    const emptyRow = worksheet.addRow(['Belum ada data rekap investasi.', '', '', '', '', '', '', '']);
                    worksheet.mergeCells(`A${emptyRow.number}:H${emptyRow.number}`);
                    emptyRow.getCell(1).alignment = { horizontal: 'center' };
                }

                worksheet.addRow([]); worksheet.addRow([]);
                
                // Tanda Tangan
                const now = new Date();
                const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                const sigStartCol = 6; 
                
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

                addSigLine('Marselino Mameyao, SKM', true);
                addSigLine('Pembina TK. I');
                addSigLine('NIP : 196805141989111002');

                // Lebar Kolom Excel
                worksheet.columns = [
                    { width: 6 },  { width: 35 }, { width: 20 }, 
                    { width: 22 }, { width: 22 }, { width: 14 }, { width: 14 }, { width: 14 }
                ];
                worksheet.getRow(1).height = 25; worksheet.getRow(2).height = 20;

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Rekap_Data_Investasi_Mimika_TAHUN_${currentTahunFilter}.xlsx`;
                document.body.appendChild(a); a.click(); document.body.removeChild(a); window.URL.revokeObjectURL(url);
            }

            // ================== EXPORT PDF ==================
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

                    // KOP SURAT
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

                    // JUDUL
                    doc.setFont("helvetica", "bold"); doc.setFontSize(11);
                    const titleText = `REKAPITULASI DATA INVESTASI DAN TENAGA KERJA TAHUN ${currentTahunFilter}`;
                    doc.text(titleText, 148, 47, { align: "center" });
                    
                    // DATA TABEL 
                    let tableHeaders = ['NO', 'NAMA PERUSAHAAN', 'NIB', 'NILAI PMDN (RP)', 'NILAI PMA (RP)', 'TK LOKAL', 'TK ASING', 'TOTAL TK'];
                    let tableBody = [];

                    let sumNilaiPMDN = 0;
                    let sumNilaiPMA = 0;
                    let sumTkLokal = 0;
                    let sumTkAsing = 0;

                    if (dataInvestasiExport.length > 0) {
                        dataInvestasiExport.forEach((item, index) => {
                            const nilaiPMDN = parseFloat(item.NilaiInvestasiPMDN) || 0;
                            const nilaiPMA = parseFloat(item.NilaiInvestasiPMA) || 0;
                            const tkLokal = parseInt(item.TenagaKerjaLokal) || 0;
                            const tkAsing = parseInt(item.TenagaKerjaAsing) || 0;
                            const totalTk = tkLokal + tkAsing;

                            sumNilaiPMDN += nilaiPMDN;
                            sumNilaiPMA += nilaiPMA;
                            sumTkLokal += tkLokal;
                            sumTkAsing += tkAsing;

                            tableBody.push([
                                (index + 1).toString(),
                                (item.NamaUsaha || 'Tidak Diketahui'),
                                (item.NIB || '-'),
                                'Rp ' + nilaiPMDN.toLocaleString('id-ID'),
                                'Rp ' + nilaiPMA.toLocaleString('id-ID'),
                                tkLokal.toLocaleString('id-ID'),
                                tkAsing.toLocaleString('id-ID'),
                                totalTk.toLocaleString('id-ID')
                            ]);
                        });

                        // Row Total Summary
                        tableBody.push([
                            { content: 'TOTAL REKAPITULASI', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: 'Rp ' + sumNilaiPMDN.toLocaleString('id-ID'), styles: { halign: 'right', fontStyle: 'bold' } },
                            { content: 'Rp ' + sumNilaiPMA.toLocaleString('id-ID'), styles: { halign: 'right', fontStyle: 'bold' } },
                            { content: sumTkLokal.toLocaleString('id-ID'), styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: sumTkAsing.toLocaleString('id-ID'), styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: (sumTkLokal + sumTkAsing).toLocaleString('id-ID'), styles: { halign: 'center', fontStyle: 'bold' } }
                        ]);

                    } else {
                        tableBody.push([{ content: 'Belum ada data rekap investasi.', colSpan: 8, styles: { halign: 'center' } }]);
                    }

                    doc.autoTable({
                        startY: 54, 
                        head: [tableHeaders], 
                        body: tableBody, 
                        theme: 'grid',
                        headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                        bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                        columnStyles: { 
                            0: { halign: 'center', cellWidth: 10 }, 
                            1: { halign: 'left' },
                            2: { halign: 'center', cellWidth: 35 }, 
                            3: { halign: 'right', cellWidth: 35 },
                            4: { halign: 'right', cellWidth: 35 },
                            5: { halign: 'center' },
                            6: { halign: 'center' },
                            7: { halign: 'center' }
                        },
                        margin: { top: 15, right: 15, bottom: 20, left: 15 }
                    });

                    // TANDA TANGAN
                    const finalY = doc.lastAutoTable.finalY + 10;
                    const now = new Date();
                    const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
                    const rightMargin = 205; 

                    // Deteksi page break
                    if (finalY > 170) { doc.addPage(); }

                    let signY = finalY > 170 ? 20 : finalY;

                    doc.setFont("helvetica", "normal"); doc.setFontSize(9);
                    doc.text(`TIMIKA, ${dateStr}`, rightMargin, signY);
                    doc.text("Kepala Dinas,", rightMargin, signY + 5);
                    doc.text("Penanaman Modal dan Pelayanan Terpadu", rightMargin, signY + 10);
                    doc.text("Satu Pintu Kabupaten Mimika,", rightMargin, signY + 15);

                    const signatureY = signY + 38;
                    doc.setFont("helvetica", "bold");
                    doc.text("Marselino Mameyao, SKM", rightMargin, signatureY);

                    doc.setFont("helvetica", "normal");
                    doc.text("Pembina TK. I", rightMargin, signatureY + 5);
                    doc.text("NIP : 196805141989111002", rightMargin, signatureY + 10);

                    doc.save(`Rekap_Data_Investasi_Mimika_TAHUN_${currentTahunFilter}.pdf`);
                    Swal.close();
                } catch (error) {
                    console.error(error);
                    Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0b172e', color: '#fff'});
                }
            }
        </script>
</body>
</html>