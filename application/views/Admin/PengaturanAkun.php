            <div class="admin-panel p-4 md:p-6">
                <!-- Header Section -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="p-2 bg-blue-500/10 border border-blue-500/20 rounded-lg shadow-inner shadow-blue-500/10">
                        <i data-lucide="user-cog" class="w-4 h-4 text-blue-400"></i>
                    </div>
                    <div>
                        <h1 class="text-sm md:text-base font-bold text-white tracking-wide">Pengaturan Akun Admin</h1>
                        <p class="text-[11px] text-slate-400">Kelola informasi kredensial untuk login aplikasi</p>
                    </div>
                </div>

                <!-- Form Card Section -->
                <div class="bg-[#0b1426] border border-[#1e2d4a] rounded-xl p-5 max-w-lg shadow-xl shadow-black/20 relative overflow-hidden">
                    <!-- Efek Glow Dekoratif (Opsional untuk estetika) -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <form id="formAkun" class="space-y-4 relative z-10">
                        
                        <!-- HIDDEN FIELD: Menyimpan Username lama sebagai acuan WHERE clause saat update -->
                        <input type="hidden" name="old_username" value="<?= isset($akun->Username) ? $akun->Username : '' ?>">
                        
                        <!-- Username Field -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-300 flex items-center gap-2">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i> Username
                            </label>
                            <input type="text" name="username" value="<?= isset($akun->Username) ? $akun->Username : '' ?>" required
                                class="w-full bg-[#030917] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-inner">
                            <p class="text-[10px] text-slate-400 mt-1 flex items-start gap-1">
                                <i data-lucide="info" class="w-3 h-3 mt-[1px] text-blue-400/80 shrink-0"></i>
                                Isi atau ubah kolom ini dengan username baru untuk mengganti yang lama.
                            </p>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-300 flex items-center gap-2">
                                <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-400"></i> Password Baru
                            </label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                                class="w-full bg-[#030917] border border-[#1e2d4a] text-slate-200 text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-inner">
                            <p class="text-[10px] text-slate-400 mt-1 flex items-start gap-1">
                                <i data-lucide="info" class="w-3 h-3 mt-[1px] text-blue-400/80 shrink-0"></i>
                                Isi kolom ini hanya jika Anda ingin mengganti password lama dengan yang baru.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 mt-2 border-t border-[#1e2d4a]/60">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium py-2 px-5 rounded-lg transition-all flex items-center gap-2 shadow-lg shadow-blue-500/20 active:scale-95">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

                <script>
                    $(document).ready(function() {
                        $('#formAkun').on('submit', function(e) {
                            e.preventDefault();
                            
                            // Gunakan button state agar user tahu proses sedang berjalan
                            let btnSubmit = $(this).find('button[type="submit"]');
                            let originalText = btnSubmit.html();
                            btnSubmit.html('<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Menyimpan...').prop('disabled', true);
                            lucide.createIcons();

                            $.ajax({
                                url: "<?= base_url('Admin/update_akun') ?>",
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