<div class="p-3 sm:p-5 lg:p-6 bg-[#0f172a] text-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-4">

        <!-- HEADER SECTION -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#1e2d4a]/60 backdrop-blur-md p-4 rounded-xl border border-slate-700/50 shadow-lg">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-white tracking-wide flex items-center gap-2">
                    <i data-lucide="store" class="w-5 h-5 text-teal-400"></i>
                    Data Surat Izin Usaha Perdagangan (SIUP)
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">Kelola data perizinan usaha perdagangan dan KBLI.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-teal-500/10 text-teal-400 border border-teal-500/20 text-[11px] font-semibold rounded-full flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                    Aktif: Tahun <?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>
                </span>
            </div>
        </div>

        <!-- FILTER & ACTION BAR -->
        <div class="bg-[#1e2d4a]/40 border border-slate-700/50 p-3 rounded-xl shadow-md">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                
                <!-- FILTER FORM (HANYA FILTER TAHUN) -->
                <form method="GET" action="<?= base_url('Admin/DataSiup') ?>" class="flex items-center gap-2 w-full sm:w-auto">
                    <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Tahun:</label>
                    <input type="number" name="tahun" id="filterTahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" class="w-20 bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2" min="2016" maxlength="4">

                    <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white py-1 px-2.5 rounded-md transition-colors flex items-center gap-1 text-xs font-semibold border border-slate-600">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i> Filter
                    </button>
                </form>

                <!-- BUTTON TAMBAH DATA -->
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button onclick="openModal()" class="flex items-center justify-center gap-1 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_12px_rgba(13,148,136,0.3)]">
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
                                
                                <!-- NOMOR ADVIS -->
                                <td class="p-1 sm:p-1.5 align-top font-mono text-teal-400 font-medium break-all text-[8px] sm:text-[9px]">
                                    <?= htmlspecialchars($row->NomorAdvis) ?>
                                </td>
                                
                                <!-- NAMA PERUSAHAAN / DIREKTUR -->
                                <td class="p-1 sm:p-1.5 align-top break-words">
                                    <div class="font-bold text-white text-[9px] sm:text-[10px] leading-snug"><?= htmlspecialchars($row->NamaPerusahaan) ?></div>
                                    <div class="text-[8px] sm:text-[9px] text-slate-400 leading-tight mt-0.5"><?= htmlspecialchars($row->Direktur) ?></div>
                                </td>
                                
                                <!-- PENANGGUNG JAWAB / KEKAYAAN BERSIH-->
                                <td class="p-1 sm:p-1.5 align-top break-words">
                                    <div class="font-bold text-white text-[9px] sm:text-[10px] leading-snug"><?= htmlspecialchars($row->NamaPenanggungjawabJabatan) ?></div>
                                    <div class="text-[8px] sm:text-[9px] text-slate-400 leading-tight mt-0.5"><?= number_format($row->KekayaanBersih, 0, ',', '.') ?></div>
                                </td>
                                
                                <!-- KELEMBAGAAN / BARANG & JASA -->
                                <td class="p-1 sm:p-1.5 align-top break-words">
                                    <span class="px-1 py-0.5 rounded text-[7px] sm:text-[8px] font-medium inline-block bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-1 leading-none">
                                        <?= htmlspecialchars($row->Kelembagaan) ?>
                                    </span>
                                    <div class="text-[7px] sm:text-[8px] text-slate-300 leading-tight">
                                        <?= htmlspecialchars($row->BarangJasaUtama) ?>
                                    </div>
                                </td>

                                <!-- KBLI -->
                                <td class="p-1 sm:p-1.5 align-top break-words text-[8px] sm:text-[9px] text-slate-400 leading-tight">
                                    <b></b> <?= htmlspecialchars($row->KegiatanUsahaKbli) ?>
                                </td>
                                
                                <!-- ALAMAT -->
                                <td class="p-1 sm:p-1.5 align-top break-words text-[8px] sm:text-[9px] text-slate-300 leading-tight">
                                    <?= htmlspecialchars($row->AlamatPerusahaanDireksi) ?>
                                </td>
                                
                                <!-- TGL KELUAR -->
                                <td class="p-1 sm:p-1.5 text-center align-top text-slate-300 text-[7px] sm:text-[8px] whitespace-nowrap">
                                    <?= !empty($row->TanggalKeluar) ? date('d/m/y', strtotime($row->TanggalKeluar)) : '-' ?>
                                </td>
                                
                                <!-- AKSI -->
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
                
                <!-- NAMA PERUSAHAAN -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Perusahaan <span class="text-rose-400">*</span></label>
                    <input type="text" id="NamaPerusahaan" name="NamaPerusahaan" required placeholder="Contoh: PT. Sumber Makmur Jaya" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- NOMOR ADVIS -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Advis</label>
                    <input type="text" id="NomorAdvis" name="NomorAdvis" placeholder="Nomor Surat Advis / Izin" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- DIREKTUR -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Direktur</label>
                    <input type="text" id="Direktur" name="Direktur" placeholder="Nama lengkap Direktur" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- PENANGGUNG JAWAB & JABATAN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / Jabatan</label>
                    <input type="text" id="NamaPenanggungjawabJabatan" name="NamaPenanggungjawabJabatan" placeholder="Contoh: Ahmad Sanusi (Komisaris)" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- KELEMBAGAAN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kelembagaan <span class="text-rose-400">*</span></label>
                    <input type="text" id="Kelembagaan" name="Kelembagaan" required placeholder="Contoh: Mikro, Kecil, Menengah, Besar, PT, CV" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- KEKAYAAN BERSIH -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kekayaan Bersih (Modal)</label>
                    <input type="text" id="KekayaanBersih" name="KekayaanBersih" placeholder="Contoh: Rp 500.000.000,-" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- TAHUN -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tahun Dokumen <span class="text-rose-400">*</span></label>
                    <input type="number" id="Tahun" name="Tahun" value="<?= date('Y') ?>" min="2010" max="2099" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                </div>

                <!-- TANGGAL KELUAR -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Keluar / Terbit</label>
                    <input type="date" id="TanggalKeluar" name="TanggalKeluar" class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500 [color-scheme:dark]">
                </div>

                <!-- ALAMAT PERUSAHAAN / DIREKSI -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Perusahaan / Direksi</label>
                    <textarea id="AlamatPerusahaanDireksi" name="AlamatPerusahaanDireksi" rows="2" placeholder="Alamat lengkap perusahaan atau domisili direksi..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                </div>

                <!-- KEGIATAN USAHA / KBLI -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kegiatan Usaha / KBLI</label>
                    <textarea id="KegiatanUsahaKbli" name="KegiatanUsahaKbli" rows="2" placeholder="Nomor / uraian KBLI..." class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                </div>

                <!-- BARANG / JASA UTAMA -->
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
<link rel="stylesheet" src="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>

    $(document).ready(function() {
        lucide.createIcons();

        // Matikan error pop-up default dari DataTables
        if ($.fn.dataTable) {
            $.fn.dataTable.ext.errMode = 'none';
        }

        // Inisialisasi DataTables dengan penanganan re-initialization
        if ($.fn.DataTable) {
            // Hancurkan (destroy) instance lama jika tabel sudah pernah di-inisialisasi
            if ($.fn.DataTable.isDataTable('#tableSiup')) {
                $('#tableSiup').DataTable().destroy();
            }

            var table = $('#tableSiup').DataTable({
                destroy: true, // Izinkan overwrite/re-initialization tanpa melempar warning
                responsive: false,
                autoWidth: false,
                pageLength: 10, // Default 10 baris
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                language: {
                    search: "Cari Data:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" },
                    emptyTable: "Belum ada data tersedia"
                }
            });

            // Event handler untuk dropdown kustom entri (10, 25, 50, Semua)
            $('#entriesSelect').off('change').on('change', function() {
                var val = parseInt($(this).val(), 10);
                table.page.len(val).draw();
            });

            // Sync saat DataTables mengganti lengthMenu internal
            $('#tableSiup').off('length.dt').on('length.dt', function(e, settings, len) {
                $('#entriesSelect').val(len);
            });
        }
    });

    $(document).ready(function() {
        lucide.createIcons();

        // Initialize DataTable jika library tersedia
        if ($.fn.DataTable) {
            $('#tableSiup').DataTable({
                responsive: false, // Matikan responsive collapse agar tata letak kolom tetap sesuai persentase
                autoWidth: false,
                language: {
                    search: "Cari Data:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
            });
        }
    });

    function openModal() {
        $('#formSiup')[0].reset();
        $('#siup_id').val('');
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
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Terjadi kesalahan saat mengambil data.', 'error');
            }
        });
    }

    function submitForm(e) {
        e.preventDefault();
        
        $('#btnSave').prop('disabled', true).addClass('opacity-50');

        $.ajax({
            url: "<?= base_url('Admin/save_siup') ?>",
            type: "POST",
            data: $('#formSiup').serialize(),
            dataType: "JSON",
            success: function(res) {
                $('#btnSave').prop('disabled', false).removeClass('opacity-50');
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function() {
                $('#btnSave').prop('disabled', false).removeClass('opacity-50');
                Swal.fire('Error!', 'Gagal menghubungkan ke server.', 'error');
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
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('Admin/delete_siup/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    }
</script>
</body>
</html>