<!-- SCROLLABLE CONTENT BODY -->
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
                    
                    <!-- FILTER DATA & TOMBOL TAMBAH -->
                    <div class="p-5 border-b border-[#1e2d4a] flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-[#081122] rounded-t-[0.75rem]">
                        <form action="<?= base_url('Admin/DataSiujk') ?>" method="GET" class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Tahun:</label>
                                <input type="number" name="tahun" id="filterTahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" class="w-20 bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2" min="2016" maxlength="4">
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] text-slate-400 font-medium uppercase hidden sm:block">Status:</label>
                                <select name="keterangan" id="filterKeterangan" class="bg-[#050e1d] border border-[#1e2d4a] text-white text-xs rounded-md focus:ring-1 focus:ring-teal-500 outline-none p-2">
                                    <option value="Semua" <?= (isset($keterangan_filter) && $keterangan_filter == 'Semua') ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="Baru" <?= (isset($keterangan_filter) && $keterangan_filter == 'Baru') ? 'selected' : '' ?>>Baru</option>
                                    <option value="Perpanjang" <?= (isset($keterangan_filter) && $keterangan_filter == 'Perpanjang') ? 'selected' : '' ?>>Perpanjang</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white p-1.5 px-3 rounded-md transition-colors flex items-center gap-1.5 text-xs font-semibold">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>

                        <div class="flex items-center gap-2 mt-4 xl:mt-0 w-full xl:w-auto justify-end">
                            <button onclick="openModal()" class="flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-md px-3 py-1.5 text-xs transition-all shadow-[0_0_15px_rgba(13,148,136,0.3)]">
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
                                                // Logika warna untuk masa berlaku (Merah jika sudah lewat)
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
        const currentTahunFilter = $('#filterTahun').val();

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