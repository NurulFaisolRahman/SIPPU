<main class="flex-1 flex flex-col h-screen overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#0a1633] via-[#030816] to-[#030816]">
        
        <header class="h-[75px] bg-[#050e1d]/80 backdrop-blur-md border-b border-[#16243d] flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-blue-400 transition-colors">SIPPU</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <a href="#" class="hover:text-blue-400 transition-colors">Master Data</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <span class="text-white">Kelola Sektor Usaha</span>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            
            <!-- Banner Dashboard Khusus Sektor Usaha -->
            <div class="bg-gradient-to-r from-indigo-900 to-[#0a1324] border border-indigo-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-indigo-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="pie-chart" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-2xl font-bold text-white mb-1">Manajemen Sektor Usaha</h2>
                    <p class="text-sm text-indigo-200">Kelola master data klasifikasi sektor industri untuk entitas pelaku usaha.</p>
                </div>
                <div class="relative z-10 flex gap-4 text-center">
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-indigo-500/30 px-4 py-2 rounded-lg">
                        <div class="text-[10px] text-indigo-300 uppercase tracking-widest font-semibold mb-0.5">Total Kategori</div>
                        <div class="text-xl font-bold text-white"><?= !empty($sektor_usaha_data) ? count($sektor_usaha_data) : '0' ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel flex flex-col">
                <!-- Toolbar Atas Tabel -->
                <div class="p-5 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-end gap-4 bg-[#081122] rounded-t-[0.75rem]">
                    <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_20px_rgba(79,70,229,0.5)]">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori Sektor
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table id="tabelSektor" class="w-full text-left text-[11px] text-slate-300">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold w-12 text-center whitespace-nowrap">No</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Nama Sektor Usaha</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Tanggal Dibuat</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Terakhir Update</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center w-28 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e2d4a]/50">
                            <?php if(!empty($sektor_usaha_data)): ?>
                                <?php foreach($sektor_usaha_data as $index => $row): ?>
                                <tr class="hover:bg-[#0c1833] transition-colors">
                                    <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>
                                    
                                    <td class="px-4 py-3 font-bold text-white whitespace-normal break-words max-w-[200px]">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-400"></i>
                                            <?= htmlspecialchars($row->NamaSektor) ?>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                                        <?= !empty($row->InputAt) ? date('d M Y, H:i', strtotime($row->InputAt)) : '-' ?>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                                        <?= !empty($row->UpdateAt) ? date('d M Y, H:i', strtotime($row->UpdateAt)) : '-' ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editSektor(<?= $row->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit Data">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="deleteSektor(<?= $row->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus Data">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500 italic">
                                        Belum ada master data Sektor Usaha yang tersimpan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL CRUD SEKTOR USAHA -->
    <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
        
        <!-- Modal Konten -->
        <div id="modalContent" class="relative w-full max-w-[450px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave">
            
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl">
                <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Sektor Usaha</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <form id="formSektor" onsubmit="saveData(event)" class="p-6 flex flex-col gap-4">
                <input type="hidden" id="inputId" name="id" value="">

                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Kategori Sektor Industri/Usaha</label>
                    <input type="text" id="inputNamaSektor" name="NamaSektor" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none p-2.5 transition-all placeholder:text-slate-600" placeholder="Contoh: Industri Pengolahan..." required>
                </div>

                <div class="mt-4 flex justify-end gap-3 pt-4 border-t border-[#1e2d4a]">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg transition-colors shadow-lg shadow-indigo-900/40 border border-indigo-500/50 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Sektor
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

            // Inisialisasi DataTables
            if ($.fn.DataTable) {
                $('#tabelSektor').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Pencarian Cepat:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ sektor",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(filter dari _MAX_ total data)",
                        paginate: { first: "Awal", last: "Akhir", next: "Maju", previous: "Mundur" }
                    }
                });
            }
        });

        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            setTimeout(() => { $('#modalContent').removeClass('modal-leave').addClass('modal-enter'); }, 10);

            if (!isEdit) {
                $('#modalTitle').text('Tambah Sektor Usaha Baru');
                $('#formSektor')[0].reset();
                $('#inputId').val(''); 
            }
        }

        function closeModal() {
            $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
            setTimeout(() => { $('#modalCRUD').addClass('hidden'); }, 300); 
        }

        function editSektor(id) {
            $.ajax({
                url: `<?= base_url('Admin/get_sektor/') ?>${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        $('#modalTitle').text('Edit Sektor Usaha');
                        $('#inputId').val(res.data.id);
                        $('#inputNamaSektor').val(res.data.NamaSektor);
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
            const $form = $('#formSektor');
            
            const originalText = $btnSubmit.html();
            $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...`);
            refreshIcons();
            $btnSubmit.prop('disabled', true);

            const formData = new FormData($form[0]);
            
            $.ajax({
                url: '<?= base_url("Admin/save_sektor") ?>',
                method: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
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

        function deleteSektor(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Sektor usaha akan dihapus (disembunyikan) dari daftar sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#1e2d4a',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batalkan',
                background: '#0b172e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('Admin/delete_sektor/') ?>${id}`,
                        method: 'POST',
                        dataType: 'json',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(res) {
                            if (res && res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'Telah Dihapus!', text: res.message, background: '#0b172e', color: '#fff', timer: 1500, showConfirmButton: false })
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
            let errorMessage = "Gagal memproses permintaan ke server. Silakan coba lagi.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (status === 'parsererror') {
                errorMessage = "Terjadi kesalahan format response dari server backend.";
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                html: errorMessage,
                background: '#0b172e', color: '#fff'
            });
        }
    </script>
</body>
</html>