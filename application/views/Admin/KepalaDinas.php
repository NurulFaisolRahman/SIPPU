<div class="admin-panel p-4 md:p-6">
    <!-- Header Section -->
    <div class="flex items-center gap-3 mb-5">
        <div class="p-2 bg-blue-500/10 border border-blue-500/20 rounded-lg shadow-inner shadow-blue-500/10">
            <i data-lucide="award" class="w-4 h-4 text-blue-400"></i>
        </div>
        <div>
            <h1 class="text-sm md:text-base font-bold text-white tracking-wide">Data Kepala Dinas DPMPTSP</h1>
            <p class="text-[11px] text-slate-400">Kelola profil pimpinan dan penandatangan dokumen resmi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Preview Profile Card (Sisi Kiri/Atas) -->
        <div class="lg:col-span-1 bg-[#0b1426] border border-[#1e2d4a] rounded-xl p-5 shadow-xl shadow-black/20 flex flex-col items-center justify-center text-center relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-28 h-28 bg-blue-600/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 p-0.5 mb-3 shadow-lg shadow-blue-500/20">
                <div class="w-full h-full bg-[#030917] rounded-full flex items-center justify-center">
                    <i data-lucide="user-check" class="w-8 h-8 text-blue-400"></i>
                </div>
            </div>

            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-2">
                <i data-lucide="shield-check" class="w-3 h-3"></i> Kepala Dinas
            </span>

            <h2 class="text-xs font-bold text-white mb-1" id="previewNama">
                <?= isset($kadis->Nama) ? htmlspecialchars($kadis->Nama) : 'Belum Ada Data' ?>
            </h2>
            <p class="text-[11px] text-slate-400 mb-1" id="previewPangkat">
                <?= isset($kadis->Pangkat) ? htmlspecialchars($kadis->Pangkat) : '-' ?>
            </p>
            <p class="text-[10px] text-slate-500 font-mono" id="previewNIP">
                NIP: <?= isset($kadis->NIP) ? htmlspecialchars($kadis->NIP) : '-' ?>
            </p>
        </div>

        <!-- Form Edit Card (Sisi Kanan) -->
        <div class="lg:col-span-2 bg-[#0b1426] border border-[#1e2d4a] rounded-xl p-5 shadow-xl shadow-black/20 relative overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#1e2d4a]/80">
                <h3 class="text-xs font-bold text-white flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-blue-400"></i> Form Edit Informasi Kepala Dinas
                </h3>
            </div>

            <form id="formKadis" class="space-y-4">
                <input type="hidden" name="id" value="<?= isset($kadis->id) ? $kadis->id : '' ?>">

                <!-- Nama Field -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300 flex items-center gap-2">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i> Nama Lengkap (Beserta Gelar)
                    </label>
                    <input type="text" name="Nama" id="inputNama" required value="<?= isset($kadis->Nama) ? htmlspecialchars($kadis->Nama) : '' ?>" placeholder="Cth: Dr. John Doe, M.Si"
                        class="w-full bg-[#030917] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-inner">
                </div>

                <!-- Pangkat Field -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300 flex items-center gap-2">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-slate-400"></i> Pangkat / Golongan
                    </label>
                    <input type="text" name="Pangkat" id="inputPangkat" value="<?= isset($kadis->Pangkat) ? htmlspecialchars($kadis->Pangkat) : '' ?>" placeholder="Cth: Pembina Utama Muda (IV/c)"
                        class="w-full bg-[#030917] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-inner">
                </div>

                <!-- NIP Field -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-3.5 h-3.5 text-slate-400"></i> NIP (Nomor Induk Pegawai)
                    </label>
                    <input type="text" name="NIP" id="inputNIP" required value="<?= isset($kadis->NIP) ? htmlspecialchars($kadis->NIP) : '' ?>" placeholder="Cth: 19800101 200501 1 001"
                        class="w-full bg-[#030917] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-inner">
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-[#1e2d4a]/60 flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium py-2 px-4 rounded-lg transition-all flex items-center gap-2 shadow-lg shadow-blue-500/20 active:scale-95">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Live preview sync
        $('#inputNama').on('input', function() {
            $('#previewNama').text($(this).val() || 'Belum Ada Data');
        });
        $('#inputPangkat').on('input', function() {
            $('#previewPangkat').text($(this).val() || '-');
        });
        $('#inputNIP').on('input', function() {
            $('#previewNIP').text('NIP: ' + ($(this).val() || '-'));
        });

        // Ajax submit update
        $('#formKadis').on('submit', function(e) {
            e.preventDefault();
            
            let btnSubmit = $(this).find('button[type="submit"]');
            let originalText = btnSubmit.html();
            btnSubmit.html('<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Menyimpan...').prop('disabled', true);
            lucide.createIcons();

            $.ajax({
                url: "<?= base_url('Admin/save_kepala_dinas') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if(res.status === 'success') {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: res.message, 
                            background: '#0a1329', 
                            color: '#fff',
                            customClass: { title: 'text-sm font-bold', htmlContainer: 'text-xs' }
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Gagal!', 
                            text: res.message, 
                            background: '#0a1329', 
                            color: '#fff',
                            customClass: { title: 'text-sm font-bold', htmlContainer: 'text-xs' }
                        });
                        btnSubmit.html(originalText).prop('disabled', false);
                    }
                },
                error: function() {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Oops...', 
                        text: 'Terjadi kesalahan sistem.', 
                        background: '#0a1329', 
                        color: '#fff',
                        customClass: { title: 'text-sm font-bold', htmlContainer: 'text-xs' }
                    });
                    btnSubmit.html(originalText).prop('disabled', false);
                }
            });
        });
    });
</script>
</main>
</div>
</body>
</html>