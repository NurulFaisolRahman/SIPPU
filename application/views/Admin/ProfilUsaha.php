            <!-- ExcelJS untuk Export Excel -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

            <!-- SCROLLABLE CONTENT BODY -->
            <div class="w-full p-1 md:p-1 lg:p-1 pb-16">
                <div class="bg-gradient-to-r from-teal-900 to-[#0a1324] border border-teal-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-teal-900/20 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                        <i data-lucide="briefcase" class="w-48 h-48"></i>
                    </div>
                    <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                        <h2 class="text-2xl font-bold text-white mb-1">Database Profil Usaha</h2>
                        <p class="text-sm text-teal-200">Manajemen data pelaku usaha, NIB, dan sektor industri beserta lokasi detail.</p>
                    </div>
                    <div class="relative z-10 flex gap-4 text-center">
                        <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-teal-500/30 px-4 py-2 rounded-lg">
                            <div class="text-[10px] text-teal-300 uppercase tracking-widest font-semibold mb-0.5">Total Usaha</div>
                            <div class="text-xl font-bold text-white"><?= !empty($profil_usaha_data) ? count($profil_usaha_data) : '0' ?></div>
                        </div>
                    </div>
                </div>

                <div class="admin-panel flex flex-col">
                    
                    <!-- FILTER TAHUN & TOMBOL EXPORT -->
                    <div class="p-5 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#081122] rounded-t-[0.75rem]">
                        <form action="<?= base_url('Admin/ProfilUsaha') ?>" method="GET" class="flex items-center gap-2">
                            <label class="text-xs text-slate-400 font-medium uppercase">Filter Tahun:</label>
                            <input type="number" name="tahun" id="filterTahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" class="w-24 bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2" min="2016" maxlength="4">
                            <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white p-2 rounded-lg transition-colors">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <button onclick="downloadExcel()" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg px-3 py-2 text-sm transition-all shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel
                            </button>
                            <button onclick="downloadPDF()" class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg px-3 py-2 text-sm transition-all shadow-[0_0_10px_rgba(239,68,68,0.2)]">
                                <i data-lucide="file-text" class="w-4 h-4"></i> PDF
                            </button>
                            <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(13,148,136,0.3)] ml-2">
                                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Pelaku Usaha
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="tabelProfil" class="w-full text-left text-[11px] text-slate-300">
                            <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold w-12 !text-center">No</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Nama Usaha (NIB)</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Pemilik</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Sektor Usaha</th>
                                    <th scope="col" class="px-4 py-3 font-semibold w-48">Lokasi (Provinsi - Kampung)</th>
                                    <th scope="col" class="px-4 py-3 font-semibold !text-center w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1e2d4a]/50">
                                <?php if(!empty($profil_usaha_data)): ?>
                                    <?php foreach($profil_usaha_data as $index => $row): ?>
                                    <tr class="hover:bg-[#0c1833] transition-colors">
                                        <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>

                                        <td class="px-4 py-3 whitespace-normal break-words max-w-[200px]">
                                            <div class="text-white font-bold text-sm mb-0.5"><?= htmlspecialchars($row->NamaUsaha) ?></div>
                                            <div class="text-xs text-blue-400 font-mono tracking-wider">NIB: <?= htmlspecialchars($row->NIB) ?></div>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-slate-300 whitespace-nowrap font-medium">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i> <?= htmlspecialchars($row->NamaPemilik) ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-slate-400">
                                            <span class="bg-teal-500/10 text-teal-400 border border-teal-500/20 px-2 py-0.5 rounded text-[10px] inline-block">
                                                <?= htmlspecialchars($row->SektorUsaha ? $row->SektorUsaha : '-') ?>
                                            </span>
                                        </td>

                                        <!-- Kolom Lokasi Lengkap -->
                                        <td class="px-4 py-3 text-slate-400 text-[10px] leading-tight">
                                            <div class="font-bold text-teal-300 mb-0.5"><?= htmlspecialchars($row->NamaProvinsi ? $row->NamaProvinsi : '-') ?></div>
                                            <div>Kab. <?= htmlspecialchars($row->NamaKabupaten ? $row->NamaKabupaten : '-') ?></div>
                                            <div>Kec. <?= htmlspecialchars($row->NamaDistrik ? $row->NamaDistrik : '-') ?></div>
                                            <div class="text-slate-500">Ds. <?= htmlspecialchars($row->NamaKampung ? $row->NamaKampung : '-') ?></div>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-center">
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
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

        <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
            
            <div id="modalContent" class="relative w-full max-w-[650px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave h-[90vh] md:h-auto overflow-hidden">
                
                <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl shrink-0">
                    <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Profil Usaha</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form id="formProfil" onsubmit="saveData(event)" class="flex flex-col h-full overflow-hidden">
                    <div class="p-6 overflow-y-auto flex-1 gap-4 flex flex-col custom-scrollbar">
                        <input type="hidden" id="inputId" name="id" value="">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Tahun Pendataan</label>
                                    <input type="number" id="inputTahun" name="Tahun" placeholder="Misal: 2024" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required min="2016" oninput="if(this.value.length > 4) this.value = this.value.slice(0,4);" maxlength="4">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nomor Induk Berusaha (NIB)</label>
                                    <input type="text" id="inputNIB" name="NIB" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Perusahaan / Usaha</label>
                                    <input type="text" id="inputNamaUsaha" name="NamaUsaha" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Nama Pemilik / Direktur</label>
                                    <input type="text" id="inputNamaPemilik" name="NamaPemilik" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-semibold text-slate-300 uppercase block">Sektor Usaha</label>
                                    <select id="inputSektorUsaha" name="SektorUsaha" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                        <option value="">-- Pilih Sektor --</option>
                                        <option value="Pertanian & Perkebunan">Pertanian & Perkebunan</option>
                                        <option value="Pertambangan & Penggalian">Pertambangan & Penggalian</option>
                                        <option value="Industri Pengolahan">Industri Pengolahan</option>
                                        <option value="Konstruksi">Konstruksi</option>
                                        <option value="Perdagangan Besar & Eceran">Perdagangan Besar & Eceran</option>
                                        <option value="Transportasi & Pergudangan">Transportasi & Pergudangan</option>
                                        <option value="Penyediaan Akomodasi & Makan Minum">Penyediaan Akomodasi & Makan Minum</option>
                                        <option value="Informasi & Komunikasi">Informasi & Komunikasi</option>
                                        <option value="Jasa Keuangan & Asuransi">Jasa Keuangan & Asuransi</option>
                                        <option value="Jasa Kesehatan">Jasa Kesehatan</option>
                                        <option value="Jasa Pendidikan">Jasa Pendidikan</option>
                                        <option value="Sektor Lainnya">Sektor Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- LOKASI HIERARKI (Cascading Dropdown) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-[#1e2d4a] pt-4 mt-2">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Provinsi</label>
                                <select id="inputIdProvinsi" name="id_provinsi" onchange="loadLokasi(this.value, 'inputIdKabupaten')" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    <?php if(!empty($provinsi_list)): ?>
                                        <?php foreach($provinsi_list as $prov): ?>
                                            <option value="<?= $prov->id ?>"><?= htmlspecialchars($prov->NamaDistrik) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Kabupaten</label>
                                <select id="inputIdKabupaten" name="id_kabupaten" onchange="loadLokasi(this.value, 'inputIdDistrik')" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required disabled>
                                    <option value="">-- Pilih Kabupaten --</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Distrik / Kecamatan</label>
                                <select id="inputIdDistrik" name="id_distrik" onchange="loadLokasi(this.value, 'inputIdKampung')" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required disabled>
                                    <option value="">-- Pilih Distrik --</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-slate-300 uppercase block">Kampung / Desa</label>
                                <select id="inputIdKampung" name="id_kampung" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required disabled>
                                    <option value="">-- Pilih Kampung --</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 mt-2">
                            <label class="text-[11px] font-semibold text-slate-300 uppercase block">Alamat Jalan / Lengkap</label>
                            <textarea id="inputAlamat" name="Alamat" rows="2" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-teal-500 outline-none p-2.5" required></textarea>
                        </div>
                    </div>

                    <div class="p-5 flex justify-end gap-3 border-t border-[#1e2d4a]/80 bg-[#071126] rounded-b-xl shrink-0">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                        <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-500 rounded-lg transition-colors shadow-lg shadow-teal-900/40 border border-teal-500/50 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dataProfilExport = <?= !empty($profil_usaha_data) ? json_encode($profil_usaha_data) : '[]' ?>;
        const currentTahunFilter = $('#filterTahun').val();
        const monthNamesIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        document.addEventListener("DOMContentLoaded", function() {
            if ($.fn.DataTable) {
                $('#tabelProfil').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Cari Data:", lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ profil",
                        infoEmpty: "Menampilkan 0 profil", infoFiltered: "(difilter)",
                        emptyTable: "Belum ada data Profil Usaha untuk tahun terpilih.",
                        paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Kembali" }
                    }
                });
            }
        });

        // ==========================================
        // CASCADING DROPDOWN LOGIC
        // ==========================================
        function loadLokasi(parentId, targetSelectId, selectedValue = null) {
            const target = $('#' + targetSelectId);
            target.html('<option value="">Sedang memuat...</option>').prop('disabled', true);
            
            // Reset dropdown di bawahnya secara otomatis
            if (targetSelectId === 'inputIdKabupaten') {
                $('#inputIdDistrik').html('<option value="">-- Pilih Distrik --</option>').prop('disabled', true);
                $('#inputIdKampung').html('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);
            } else if (targetSelectId === 'inputIdDistrik') {
                $('#inputIdKampung').html('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);
            }

            if (!parentId) {
                target.html('<option value="">-- Pilih --</option>');
                return Promise.resolve();
            }

            return $.ajax({
                url: `<?= base_url('Admin/get_lokasi_anak/') ?>${parentId}`,
                method: 'GET',
                dataType: 'json'
            }).then(res => {
                let options = '<option value="">-- Pilih Data --</option>';
                if (res.status === 'success' && res.data.length > 0) {
                    res.data.forEach(item => {
                        options += `<option value="${item.id}">${item.NamaDistrik}</option>`;
                    });
                    target.html(options).prop('disabled', false);
                    
                    if (selectedValue) {
                        target.val(selectedValue);
                    }
                } else {
                    target.html('<option value="">-- Tidak ada sub-data --</option>');
                }
            }).catch(err => {
                target.html('<option value="">-- Error Memuat --</option>');
            });
        }


        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            setTimeout(() => { $('#modalContent').removeClass('modal-leave').addClass('modal-enter'); }, 10);
            
            if (!isEdit) {
                $('#modalTitle').text('Tambah Profil Usaha');
                $('#formProfil')[0].reset();
                $('#inputId').val(''); 
                $('#inputTahun').val(currentTahunFilter);
                
                // Reset select cascading agar disable kembali
                $('#inputIdKabupaten').html('<option value="">-- Pilih Kabupaten --</option>').prop('disabled', true);
                $('#inputIdDistrik').html('<option value="">-- Pilih Distrik --</option>').prop('disabled', true);
                $('#inputIdKampung').html('<option value="">-- Pilih Kampung --</option>').prop('disabled', true);
            }
        }

        function closeModal() {
            $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
            setTimeout(() => { $('#modalCRUD').addClass('hidden'); }, 300); 
        }

        async function editProfil(id) {
            try {
                // Tampilkan loading spinner agar user tahu proses get sedang berlangsung
                Swal.fire({
                    title: 'Memuat Data...',
                    allowOutsideClick: false, background: '#0b172e', color: '#fff',
                    didOpen: () => { Swal.showLoading(); }
                });

                const res = await $.ajax({ url: `<?= base_url('Admin/get_profil/') ?>${id}`, method: 'GET', dataType: 'json' });
                
                if (res && res.status === 'success') {
                    $('#modalTitle').text('Edit Profil Usaha');
                    $('#inputId').val(res.data.id);
                    $('#inputTahun').val(res.data.Tahun);
                    $('#inputNIB').val(res.data.NIB);
                    $('#inputNamaUsaha').val(res.data.NamaUsaha);
                    $('#inputNamaPemilik').val(res.data.NamaPemilik);
                    $('#inputSektorUsaha').val(res.data.SektorUsaha);
                    $('#inputAlamat').val(res.data.Alamat);
                    
                    // Set hierarki Provinsi - Kampung 
                    $('#inputIdProvinsi').val(res.data.id_provinsi);
                    
                    if(res.data.id_provinsi) {
                        await loadLokasi(res.data.id_provinsi, 'inputIdKabupaten', res.data.id_kabupaten);
                        if(res.data.id_kabupaten) {
                            await loadLokasi(res.data.id_kabupaten, 'inputIdDistrik', res.data.id_distrik);
                            if(res.data.id_distrik) {
                                await loadLokasi(res.data.id_distrik, 'inputIdKampung', res.data.id_kampung);
                            }
                        }
                    }
                    
                    Swal.close(); // tutup modal loading
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
                url: '<?= base_url("Admin/save_profil") ?>',
                method: 'POST', data: new FormData($('#formProfil')[0]),
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

        function deleteProfil(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?', text: "Data Profil Usaha akan disembunyikan!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#1e2d4a',
                confirmButtonText: 'Ya, Hapus!', background: '#0b172e', color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`<?= base_url('Admin/delete_profil/') ?>${id}`, function(res) {
                        if (res && res.status === 'success') location.reload();
                        else Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message, background: '#0b172e', color: '#fff' });
                    }, 'json');
                }
            });
        }

        async function downloadExcel() {
            if (typeof window.ExcelJS === 'undefined') {
                alert("Library ExcelJS belum termuat. Pastikan koneksi internet stabil."); return;
            }

            const rentangText = `TAHUN ${currentTahunFilter}`;
            const workbook = new window.ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Profil Usaha');
            
            // Kolom diperbesar hingga J (10 Kolom)
            const totalCols = 10; 
            const lastColLetter = 'J';

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

            worksheet.addRow([`REKAPITULASI PROFIL USAHA ${rentangText}`]);
            worksheet.mergeCells(`A7:${lastColLetter}7`);
            worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
            worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

            worksheet.addRow([]); 

            // Header Tabel Update
            let headers = ['NO', 'NIB', 'NAMA USAHA', 'PEMILIK', 'SEKTOR USAHA', 'PROVINSI', 'KABUPATEN', 'DISTRIK', 'KAMPUNG', 'ALAMAT'];
            const headerRow = worksheet.addRow(headers);
            
            headerRow.eachCell((cell) => {
                cell.font = { name: 'Arial', size: 10, bold: true };
                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
                cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
            });

            if (dataProfilExport.length > 0) {
                dataProfilExport.forEach((item, index) => {
                    const rowData = [
                        (index + 1), item.NIB, item.NamaUsaha, 
                        item.NamaPemilik, (item.SektorUsaha || '-'), 
                        (item.NamaProvinsi || '-'), (item.NamaKabupaten || '-'), 
                        (item.NamaDistrik || '-'), (item.NamaKampung || '-'), 
                        item.Alamat
                    ];
                    const dataRow = worksheet.addRow(rowData);
                    
                    dataRow.eachCell((cell, colNumber) => {
                        cell.font = { name: 'Arial', size: 10 };
                        cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                        if (colNumber === 1 || colNumber === 2) { 
                            cell.alignment = { vertical: 'middle', horizontal: 'center' };
                        } else { 
                            cell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: true };
                        }
                    });
                });
            } else {
                const emptyRow = worksheet.addRow(['Belum ada data', '', '', '', '', '', '', '', '', '']);
                worksheet.mergeCells(`A${emptyRow.number}:J${emptyRow.number}`);
                emptyRow.getCell(1).alignment = { horizontal: 'center' };
            }

            worksheet.addRow([]); worksheet.addRow([]);
            
            const now = new Date();
            const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
            const sigStartCol = 8; // Geser ke kanan
            
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

            // Lebar Kolom
            worksheet.columns = [
                { width: 6 },  { width: 20 }, { width: 35 }, 
                { width: 25 }, { width: 30 }, { width: 20 }, 
                { width: 20 }, { width: 20 }, { width: 20 }, { width: 40 }
            ];
            worksheet.getRow(1).height = 25; worksheet.getRow(2).height = 20;

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Rekap_Profil_Usaha_Mimika_TAHUN_${currentTahunFilter}.xlsx`;
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
                const titleText = `REKAPITULASI PROFIL USAHA TAHUN ${currentTahunFilter}`;
                doc.text(titleText, 148, 47, { align: "center" });
                
                // Meringkas Kolom agar muat di PDF (Lokasi digabung jadi 1 sel per baris)
                let tableHeaders = ['NO', 'NIB', 'NAMA USAHA', 'PEMILIK', 'SEKTOR USAHA', 'LOKASI (PROV-KMP)', 'ALAMAT'];
                let tableBody = [];

                if (dataProfilExport.length > 0) {
                    dataProfilExport.forEach((item, index) => {
                        const strLokasi = `${item.NamaProvinsi||'-'}\nKab. ${item.NamaKabupaten||'-'}\nKec. ${item.NamaDistrik||'-'}\nDs. ${item.NamaKampung||'-'}`;
                        tableBody.push([
                            (index + 1).toString(), item.NIB, item.NamaUsaha, 
                            item.NamaPemilik, (item.SektorUsaha || '-'), strLokasi, item.Alamat
                        ]);
                    });
                } else {
                    tableBody.push([{ content: 'Belum ada data profil usaha.', colSpan: 7, styles: { halign: 'center' } }]);
                }

                doc.autoTable({
                    startY: 54, head: [tableHeaders], body: tableBody, theme: 'grid',
                    headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8 },
                    bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 8, valign: 'top' },
                    columnStyles: { 
                        0: { halign: 'center', cellWidth: 10 }, 1: { halign: 'center', cellWidth: 25 }, 5: { cellWidth: 35 }
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
                doc.text("Marselino Mameyao, SKM", rightMargin, signatureY);

                doc.setFont("helvetica", "normal");
                doc.text("Pembina TK. I", rightMargin, signatureY + 5);
                doc.text("NIP : 196805141989111002", rightMargin, signatureY + 10);

                doc.save(`Rekap_Profil_Usaha_Mimika_TAHUN_${currentTahunFilter}.pdf`);
                Swal.close();
            } catch (error) {
                console.error(error);
                Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0b172e', color: '#fff'});
            }
        }
    </script>
</body>
</html>