            <!-- MAIN CONTENT START -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#0a1633] via-[#030816] to-[#030816]">
        
        <!-- Header Top Navbar -->
        <header class="h-[75px] bg-[#050e1d]/80 backdrop-blur-md border-b border-[#16243d] flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-blue-400 transition-colors">SIPPU</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <span class="text-white">Dashboard</span>
                </div>
            </div>
        </header>

        <!-- Scrollable Content Container -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white mb-1">Beranda Administrator</h2>
                <p class="text-sm text-slate-400">Ringkasan statistik data master SIPPU Kabupaten Mimika.</p>
            </div>

            <!-- GRID INFOGRAFIS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- KARTU INFOGRAFIS: TOTAL JENIS IZIN -->
                <div class="bg-gradient-to-br from-[#0a1324] to-[#082255] border border-blue-800/50 rounded-xl p-6 shadow-lg shadow-blue-900/20 relative overflow-hidden group transition-all hover:-translate-y-1 hover:shadow-blue-900/40">
                    <!-- Icon Background Blur -->
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 transition-transform group-hover:scale-110 duration-500">
                        <i data-lucide="file-signature" class="w-32 h-32 text-blue-400"></i>
                    </div>
                    
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h3 class="text-[11px] font-bold text-blue-300 uppercase tracking-widest mb-1">Total Jenis Perizinan</h3>
                            <!-- Menampilkan Nilai dari Database -->
                            <div class="text-4xl font-bold text-white mb-2">
                                <?= isset($jenis_izin_data) ? number_format(count($jenis_izin_data), 0, ',', '.') : '0' ?>
                            </div>
                            <div class="text-[10px] text-emerald-400 flex items-center gap-1 font-medium">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Aktif di Sistem
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-full bg-blue-600/20 flex items-center justify-center border border-blue-500/30 shrink-0">
                            <i data-lucide="folders" class="w-6 h-6 text-blue-400"></i>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-4 pt-4 border-t border-blue-800/50">
                        <a href="<?= base_url('Admin') ?>" class="text-[11px] text-blue-400 hover:text-white transition-colors flex items-center gap-1">
                            Kelola Data <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- KARTU INFOGRAFIS PLACEHOLDER (Pelaku Usaha) -->
                <div class="bg-gradient-to-br from-[#0a1324] to-[#073326] border border-emerald-800/50 rounded-xl p-6 shadow-lg shadow-emerald-900/20 relative overflow-hidden group transition-all hover:-translate-y-1 hover:shadow-emerald-900/40 opacity-70">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 transition-transform group-hover:scale-110 duration-500">
                        <i data-lucide="building-2" class="w-32 h-32 text-emerald-400"></i>
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h3 class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1">Total Profil Usaha</h3>
                            <div class="text-4xl font-bold text-white mb-2">--</div>
                            <div class="text-[10px] text-slate-400 flex items-center gap-1 font-medium">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Menunggu Modul
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-full bg-emerald-600/20 flex items-center justify-center border border-emerald-500/30 shrink-0">
                            <i data-lucide="users" class="w-6 h-6 text-emerald-400"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script>
        // Inisialisasi Icons Lucide
        lucide.createIcons();
    </script>
</body>
</html>