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
                    <span class="text-white">Kelola Profil Usaha</span>
                </div>
            </div>
        </header>

        <!-- SCROLLABLE CONTENT BODY -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            
            <div class="bg-gradient-to-r from-teal-900 to-[#0a1324] border border-teal-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-teal-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="briefcase" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-2xl font-bold text-white mb-1">Database Profil Usaha</h2>
                    <p class="text-sm text-teal-200">Manajemen data pelaku usaha, NIB, dan sektor industri di wilayah Mimika.</p>
                </div>
                <div class="relative z-10 flex gap-4 text-center">
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-teal-500/30 px-4 py-2 rounded-lg">
                        <div class="text-[10px] text-teal-300 uppercase tracking-widest font-semibold mb-0.5">Total Usaha</div>
                        <div class="text-xl font-bold text-white"><?= !empty($profil_usaha_data) ? count($profil_usaha_data) : '0' ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel flex flex-col">
                <!-- Toolbar Atas Tabel -->
                <div class="p-5 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-end gap-4 bg-[#081122] rounded-t-[0.75rem]">
                    <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(13,148,136,0.3)] hover:shadow-[0_0_20px_rgba(13,148,136,0.5)]">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Pelaku Usaha
                    </button>
                </div>

                <!-- Tabel Data -->
                <div class="overflow-x-auto">
                    <table id="tabelProfil" class="w-full text-left text-[11px] text-slate-300">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold w-12 text-center whitespace-nowrap">No</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Nama Usaha (NIB)</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Pemilik</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Sektor & Distrik</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center w-28 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e2d4a]/50">
                            <?php if(!empty($profil_usaha_data)): ?>
                                <?php foreach($profil_usaha_data as $index => $row): ?>
                                <tr class="hover:bg-[#0c1833] transition-colors">
                                    <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>
                                    
                                    <!-- Kolom Usaha & NIB -->
                                    <td class="px-4 py-3 whitespace-normal break-words max-w-[200px]">
                                        <div class="text-white font-bold text-sm mb-0.5"><?= htmlspecialchars($row->NamaUsaha) ?></div>
                                        <div class="text-xs text-blue-400 font-mono tracking-wider">NIB: <?= htmlspecialchars($row->NIB) ?></div>
                                    </td>
                                    
                                    <!-- Kolom Pemilik -->
                                    <td class="px-4 py-3 text-slate-300 whitespace-nowrap font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i> <?= htmlspecialchars($row->NamaPemilik) ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Kolom Sektor & Distrik (Hasil JOIN MySQL) -->
                                    <td class="px-4 py-3 text-slate-400">
                                        <span class="bg-teal-500/10 text-teal-400 border border-teal-500/20 px-2 py-0.5 rounded text-[10px] inline-block mb-1">
                                            <?= htmlspecialchars($row->NamaSektor ? $row->NamaSektor : 'Sektor Tidak Valid') ?>
                                        </span><br>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <i data-lucide="map-pin" class="w-3 h-3 text-slate-500"></i> 
                                            <?= htmlspecialchars($row->NamaDistrik ? $row->NamaDistrik : 'Distrik Tidak Valid') ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editProfil(<?= $row->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="deleteProfil(<?= $row->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500 italic">
                                        Belum ada data Profil Usaha yang tersimpan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL CRUD PROFIL USAHA -->
    <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
        
        <div id="modalContent" class="relative w-full max-w-[650px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave">
            
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl">
                <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Profil Usaha</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <form id="formProfil" onsubmit="saveData(event)" class="p-6 flex flex-col gap-4">
                <input type="hidden" id="inputId" name="id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kiri -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nomor Induk Berusaha (NIB)</label>
                            <input type="text" id="inputNIB" name="NIB" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Perusahaan / Usaha</label>
                            <input type="text" id="inputNamaUsaha" name="NamaUsaha" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Pemilik / Direktur</label>
                            <input type="text" id="inputNamaPemilik" name="NamaPemilik" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                        </div>
                    </div>
                    
                    <!-- Kanan (Dropdown Dinamis) -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Sektor Usaha</label>
                            <select id="inputIdSektor" name="id_sektor" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                <option value="">-- Pilih Sektor --</option>
                                <?php if(!empty($sektor_list)): ?>
                                    <?php foreach($sektor_list as $sektor): ?>
                                        <option value="<?= $sektor->id ?>"><?= htmlspecialchars($sektor->NamaSektor) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Lokasi (Distrik)</label>
                            <select id="inputIdDistrik" name="id_distrik" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                <option value="">-- Pilih Distrik --</option>
                                <?php if(!empty($distrik_list)): ?>
                                    <?php foreach($distrik_list as $distrik): ?>
                                        <option value="<?= $distrik->id ?>"><?= htmlspecialchars($distrik->NamaDistrik) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Alamat Lengkap</label>
                            <textarea id="inputAlamat" name="Alamat" rows="2" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-3 pt-4 border-t border-[#1e2d4a]">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-500 rounded-lg transition-colors shadow-lg shadow-teal-900/40 border border-teal-500/50 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function refreshIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        $(document).ready(function() {
            refreshIcons();

            if ($.fn.DataTable) {
                $('#tabelProfil').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Cari Data:",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ profil",
                        infoEmpty: "Menampilkan 0 profil",
                        infoFiltered: "(difilter dari _MAX_ profil)",
                        paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Kembali" }
                    }
                });
            }
        });

        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            setTimeout(() => { $('#modalContent').removeClass('modal-leave').addClass('modal-enter'); }, 10);

            if (!isEdit) {
                $('#modalTitle').text('Tambah Profil Usaha');
                $('#formProfil')[0].reset();
                $('#inputId').val(''); 
            }
        }

        function closeModal() {
            $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
            setTimeout(() => { $('#modalCRUD').addClass('hidden'); }, 300); 
        }

        function editProfil(id) {
            $.ajax({
                url: `<?= base_url('Admin/get_profil/') ?>${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        $('#modalTitle').text('Edit Profil Usaha');
                        $('#inputId').val(res.data.id);
                        $('#inputNIB').val(res.data.NIB);
                        $('#inputNamaUsaha').val(res.data.NamaUsaha);
                        $('#inputNamaPemilik').val(res.data.NamaPemilik);
                        
                        // Set dropdown id relasi
                        $('#inputIdSektor').val(res.data.id_sektor);
                        $('#inputIdDistrik').val(res.data.id_distrik);
                        
                        $('#inputAlamat').val(res.data.Alamat);
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
            const $form = $('#formProfil');
            
            const originalText = $btnSubmit.html();
            $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`);
            refreshIcons();
            $btnSubmit.prop('disabled', true);

            const formData = new FormData($form[0]);
            
            $.ajax({
                url: '<?= base_url("Admin/save_profil") ?>',
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

        function deleteProfil(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Profil Usaha akan disembunyikan dari sistem!",
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
                        url: `<?= base_url('Admin/delete_profil/') ?>${id}`,
                        method: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if (res && res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'Dihapus!', text: res.message, background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false })
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
            console.error("AJAX Error:", xhr, status, error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                html: 'Gagal memproses permintaan ke server. Silakan coba lagi.',
                background: '#0b172e', color: '#fff'
            });
        }
    </script>
</body>
</html>