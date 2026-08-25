<main class="flex-1 flex flex-col h-screen overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#0a1633] via-[#030816] to-[#030816]">
        
        <!-- HEADER TOP BAR -->
        <header class="h-[75px] bg-[#050e1d]/80 backdrop-blur-md border-b border-[#16243d] flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-blue-400 transition-colors">SIPPU</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <span class="text-white">Kelola Data Investasi</span>
                </div>
            </div>
        </header>

        <!-- SCROLLABLE CONTENT BODY -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            
            <div class="bg-gradient-to-r from-purple-900 to-[#0a1324] border border-purple-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-purple-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="trending-up" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-2xl font-bold text-white mb-1">Rekapitulasi Investasi & Tenaga Kerja</h2>
                    <p class="text-sm text-purple-200">Manajemen laporan nilai Penanaman Modal Dalam Negeri (PMDN) & Asing (PMA).</p>
                </div>
                <div class="relative z-10 flex gap-4 text-center">
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-purple-500/30 px-4 py-2 rounded-lg">
                        <div class="text-[10px] text-purple-300 uppercase tracking-widest font-semibold mb-0.5">Total Rekap</div>
                        <div class="text-xl font-bold text-white"><?= !empty($investasi_data) ? count($investasi_data) : '0' ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel flex flex-col">
                <div class="p-5 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-end gap-4 bg-[#081122] rounded-t-[0.75rem]">
                    <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)]">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Data Investasi
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table id="tabelInvestasi" class="w-full text-left text-[11px] text-slate-300">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold w-10 text-center whitespace-nowrap">No</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Perusahaan / NIB</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center whitespace-nowrap">Tahun & Jenis</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Nilai Investasi (Rp)</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center whitespace-nowrap">Tenaga Kerja</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center w-24 whitespace-nowrap">Aksi</th>
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
                                    
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="text-white font-bold mb-1"><?= $row->Tahun ?></div>
                                        <?php if($row->JenisInvestasi == 'PMA'): ?>
                                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded text-[10px] font-semibold">PMA</span>
                                        <?php else: ?>
                                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[10px] font-semibold">PMDN</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-mono text-emerald-400 font-semibold text-sm">
                                            Rp <?= number_format($row->NilaiInvestasi, 0, ',', '.') ?>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <?php $total_tk = $row->TenagaKerjaLokal + $row->TenagaKerjaAsing; ?>
                                        <div class="text-white font-bold text-sm mb-0.5"><?= number_format($total_tk, 0, ',', '.') ?> <span class="text-[10px] text-slate-400 font-normal">Orang</span></div>
                                        <div class="text-[10px] text-slate-500 flex items-center justify-center gap-2">
                                            <span title="Pekerja Lokal">LKL: <?= $row->TenagaKerjaLokal ?></span> |
                                            <span title="Pekerja Asing">ASG: <?= $row->TenagaKerjaAsing ?></span>
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500 italic">
                                        Belum ada data Rekap Investasi yang tersimpan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL CRUD INVESTASI -->
    <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
        
        <div id="modalContent" class="relative w-full max-w-[600px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave">
            
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl">
                <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Data Investasi</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <form id="formInvestasi" onsubmit="saveData(event)" class="p-6 flex flex-col gap-4">
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

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Tahun Laporan</label>
                        <input type="number" id="inputTahun" name="Tahun" min="2000" max="2100" value="<?= date('Y') ?>" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jenis Investasi</label>
                        <select id="inputJenis" name="JenisInvestasi" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                            <option value="PMDN">PMDN (Penanaman Modal Dalam Negeri)</option>
                            <option value="PMA">PMA (Penanaman Modal Asing)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Total Nilai Investasi (Rupiah)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm font-bold">Rp</span>
                        <input type="number" id="inputNilai" name="NilaiInvestasi" min="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5 pl-10" placeholder="Contoh: 1500000000" required>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">*Masukkan angka saja tanpa titik (Contoh: 1500000000 untuk 1,5 Miliar)</p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-[#1e2d4a]">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jumlah TK Lokal (Indonesia)</label>
                        <input type="number" id="inputTkLokal" name="TenagaKerjaLokal" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-slate-300 uppercase block">Jumlah TK Asing</label>
                        <input type="number" id="inputTkAsing" name="TenagaKerjaAsing" min="0" value="0" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-purple-500 outline-none p-2.5" required>
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-3 pt-4 border-t border-[#1e2d4a]">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-500 rounded-lg transition-colors shadow-lg shadow-purple-900/40 border border-purple-500/50 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function refreshIcons() {
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }

        $(document).ready(function() {
            refreshIcons();
            if ($.fn.DataTable) {
                $('#tabelInvestasi').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Cari Data:",
                        lengthMenu: "Tampil _MENU_ entri",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(difilter dari _MAX_ data)",
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
                $('#inputTahun').val(new Date().getFullYear());
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
                        $('#inputJenis').val(res.data.JenisInvestasi);
                        $('#inputNilai').val(res.data.NilaiInvestasi);
                        $('#inputTkLokal').val(res.data.TenagaKerjaLokal);
                        $('#inputTkAsing').val(res.data.TenagaKerjaAsing);
                        openModal(true); 
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                    }
                },
                error: function(xhr, status, error) { handleAjaxError(xhr, status, error); }
            });
        }

        function saveData(event) {
            event.preventDefault();
            const $btnSubmit = $('#btnSubmitForm');
            const $form = $('#formInvestasi');
            
            const originalText = $btnSubmit.html();
            $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...`);
            refreshIcons();
            $btnSubmit.prop('disabled', true);

            const formData = new FormData($form[0]);
            
            $.ajax({
                url: '<?= base_url("Admin/save_investasi") ?>',
                method: 'POST',
                data: formData,
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
                error: function(xhr, status, error) { handleAjaxError(xhr, status, error); },
                complete: function() {
                    $btnSubmit.html(originalText);
                    refreshIcons();
                    $btnSubmit.prop('disabled', false);
                }
            });
        }

        function deleteInvestasi(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Data investasi ini akan disembunyikan dari laporan!",
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
                                Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                            }
                        },
                        error: function(xhr, status, error) { handleAjaxError(xhr, status, error); }
                    });
                }
            });
        }

        function handleAjaxError(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error Server',
                text: 'Gagal memproses data. Periksa koneksi atau log server.',
                background: '#0b172e', color: '#fff'
            });
        }
    </script>
</body>
</html>