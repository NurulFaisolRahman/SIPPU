<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPPU Dashboard - Mimika</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- ApexCharts untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 (Untuk Notifikasi Login) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Background Utama */
        body { 
            background-color: #040d1f; 
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Panel Style */
        .glass-panel { 
            background-color: #0b172e; 
            border: 1px solid #1a2c4e; 
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e3a8a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        /* Animasi Infografis Grid */
        .district-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .district-card:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            z-index: 10;
        }
        
        /* Animasi Modal Login */
        .modal-enter {
            opacity: 1;
            transform: scale(1);
        }
        .modal-leave {
            opacity: 0;
            transform: scale(0.95);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- HEADER NAVIGATION -->
    <header class="bg-[#050e1d] border-b border-[#16243d] sticky top-0 z-40">
        <div class="max-w-[1900px] mx-auto px-4 lg:px-6 h-[75px] flex items-center justify-between">
            <!-- Logo & Title -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- Menggunakan lambang Mimika -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg?_=20190609141326" alt="Logo" class="w-10 h-12 object-contain rounded-sm" />
                <div class="flex flex-col justify-center mt-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-white font-bold text-[32px] leading-none tracking-wide">SIPPU</h1>
                        <div class="text-white text-[9px] sm:text-[10px] font-medium tracking-wider uppercase leading-[1.3] hidden sm:block">
                            SISTEM INFORMASI PROFIL<br>PELAKU USAHA
                        </div>
                    </div>
                    <p class="text-white text-[11px] sm:text-[12px] font-bold tracking-wide mt-1">DPMPTSP KABUPATEN <span class="text-yellow-400">MIMIKA</span></p>
                </div>
            </div>

            <!-- Navbar Menu -->
            <nav class="hidden xl:flex items-center gap-1.5 text-[11px] font-medium">
                <a href="#" class="flex flex-col items-center justify-center gap-1 bg-[#0a234f] border border-[#1e3a8a] rounded-lg px-4 py-1.5 text-white shadow-inner transition-colors whitespace-nowrap">
                    <i data-lucide="home" class="w-5 h-5 text-blue-400"></i> Dashboard
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="file-text" class="w-5 h-5"></i> Profil Usaha
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="file-check" class="w-5 h-5"></i> Perizinan
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i> Investasi
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i> Laporan
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="map-pin" class="w-5 h-5"></i> Pemetaan
                </a>
                <a href="#" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap">
                    <i data-lucide="settings" class="w-5 h-5"></i> Pengaturan
                </a>
            </nav>

            <!-- Time & REVISED LOGIN BUTTON -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden lg:flex items-center gap-3 bg-[#0a1324] border border-[#1e2d4a] rounded-lg px-3 py-1.5">
                    <i data-lucide="calendar" class="w-5 h-5 text-slate-300"></i>
                    <div class="flex flex-col justify-center">
                        <span id="date-display" class="text-[9px] text-slate-300 leading-tight">Memuat...</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span id="time-display" class="text-[15px] font-bold text-white leading-none tracking-wide">--:--:--</span>
                            <span class="text-[9px] font-normal text-slate-400">WIT</span>
                        </div>
                    </div>
                </div>
                
                <!-- TOMBOL LOGIN -->
                <button onclick="toggleModal('loginModal')" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 border border-blue-400/50 text-white rounded-lg px-3 py-1.5 sm:px-4 sm:py-2 transition-all duration-200 shadow-[0_0_15px_rgba(37,99,235,0.3)] hover:shadow-[0_0_20px_rgba(37,99,235,0.5)]">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span class="text-[11px] sm:text-xs font-semibold tracking-wide">Login Admin</span>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="flex-1 max-w-[1900px] w-full mx-auto px-4 lg:px-6 py-4">
        
        <!-- GRID UTAMA: KIRI (9) & KANAN (3) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            <!-- ================= SISI KIRI (Col 9) ================= -->
            <div class="col-span-1 lg:col-span-9 flex flex-col gap-4">
                
                <!-- ROW 1 KIRI: TITLE & SELECT -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-white uppercase tracking-wider">DASHBOARD EKSEKUTIF</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ringkasan Data Pelaku Usaha & Investasi Daerah Kabupaten Mimika</p>
                    </div>
                    <div class="flex gap-2">
                        <select class="bg-[#0b172e] border border-[#1e2d4a] text-[11px] rounded px-3 py-1.5 text-slate-200 outline-none focus:border-blue-500 cursor-pointer">
                            <option>Tahun 2026</option>
                            <option>Tahun 2025</option>
                        </select>
                        <select class="bg-[#0b172e] border border-[#1e2d4a] text-[11px] rounded px-3 py-1.5 text-slate-200 outline-none focus:border-blue-500 cursor-pointer">
                            <option>Semua Distrik</option>
                            <option>Distrik Mimika Baru</option>
                            <option>Distrik Tembagapura</option>
                        </select>
                    </div>
                </div>

                <!-- ROW 2 KIRI: KPI CARDS (5 Cards) -->
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- KPI 1 -->
                    <div class="bg-[#082255] border border-[#12367a] rounded-lg px-3 py-2 flex items-center gap-3">
                        <i data-lucide="users" class="w-10 h-10 text-blue-300 shrink-0"></i>
                        <div class="min-w-0">
                            <div class="text-[8px] xl:text-[9px] whitespace-nowrap font-semibold text-blue-200 uppercase tracking-wider mb-0.5 leading-tight">Total Pelaku Usaha</div>
                            <div class="text-xl 2xl:text-2xl font-bold text-white leading-none mb-1 whitespace-nowrap">12.458</div>
                            <div class="text-[9px] text-emerald-400 flex items-center gap-1 font-medium whitespace-nowrap">
                                <i data-lucide="arrow-up" class="w-3 h-3"></i> +8,45% <span class="text-slate-300 font-normal hidden 2xl:inline">Bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <!-- KPI 2 -->
                    <div class="bg-[#074226] border border-[#0b5f3a] rounded-lg px-3 py-2 flex items-center gap-3">
                        <i data-lucide="file-check-2" class="w-10 h-10 text-emerald-300 shrink-0"></i>
                        <div class="min-w-0">
                            <div class="text-[8px] xl:text-[9px] whitespace-nowrap font-semibold text-emerald-200 uppercase tracking-wider mb-0.5 leading-tight">NIB Aktif</div>
                            <div class="text-xl 2xl:text-2xl font-bold text-white leading-none mb-1 whitespace-nowrap">10.987</div>
                            <div class="text-[9px] text-emerald-400 flex items-center gap-1 font-medium whitespace-nowrap">
                                <i data-lucide="arrow-up" class="w-3 h-3"></i> +7,12% <span class="text-slate-300 font-normal hidden 2xl:inline">Bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <!-- KPI 3 -->
                    <div class="bg-[#6b4700] border border-[#946400] rounded-lg px-3 py-2 flex items-center gap-3">
                        <i data-lucide="coins" class="w-10 h-10 text-yellow-300 shrink-0"></i>
                        <div class="min-w-0">
                            <div class="text-[8px] xl:text-[9px] whitespace-nowrap font-semibold text-yellow-200 uppercase tracking-wider mb-0.5 leading-tight">Investasi (PMDN)</div>
                            <div class="text-xl 2xl:text-2xl font-bold text-white leading-none mb-1 whitespace-nowrap">Rp 2,45 T</div>
                            <div class="text-[9px] text-emerald-400 flex items-center gap-1 font-medium whitespace-nowrap">
                                <i data-lucide="arrow-up" class="w-3 h-3"></i> +15,3% <span class="text-slate-300 font-normal hidden 2xl:inline">Bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <!-- KPI 4 -->
                    <div class="bg-[#2d1b5a] border border-[#482888] rounded-lg px-3 py-2 flex items-center gap-3">
                        <i data-lucide="bar-chart-3" class="w-10 h-10 text-purple-300 shrink-0"></i>
                        <div class="min-w-0">
                            <div class="text-[8px] xl:text-[9px] whitespace-nowrap font-semibold text-purple-200 uppercase tracking-wider mb-0.5 leading-tight">Investasi (PMA)</div>
                            <div class="text-xl 2xl:text-2xl font-bold text-white leading-none mb-1 whitespace-nowrap">Rp 1,28 T</div>
                            <div class="text-[9px] text-emerald-400 flex items-center gap-1 font-medium whitespace-nowrap">
                                <i data-lucide="arrow-up" class="w-3 h-3"></i> +11,0% <span class="text-slate-300 font-normal hidden 2xl:inline">Bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <!-- KPI 5 -->
                    <div class="bg-[#054955] border border-[#086a7a] rounded-lg px-3 py-2 flex items-center gap-3">
                        <i data-lucide="briefcase" class="w-10 h-10 text-teal-300 shrink-0"></i>
                        <div class="min-w-0">
                            <div class="text-[8px] xl:text-[9px] whitespace-nowrap font-semibold text-teal-200 uppercase tracking-wider mb-0.5 leading-tight">Total Tenaga Kerja</div>
                            <div class="text-xl 2xl:text-2xl font-bold text-white leading-none mb-1 whitespace-nowrap">86.743</div>
                            <div class="text-[9px] text-emerald-400 flex items-center gap-1 font-medium whitespace-nowrap">
                                <i data-lucide="arrow-up" class="w-3 h-3"></i> +6,35% <span class="text-slate-300 font-normal hidden 2xl:inline">Bulan lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 3 KIRI: INFOGRAFIS & TREND -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    
                    <!-- Infografis Distribusi Heatmap -->
                    <div class="col-span-1 lg:col-span-7 glass-panel p-4 flex flex-col h-[380px]">
                        <div class="mb-3 flex justify-between items-start">
                            <div>
                                <h3 class="text-[12px] font-semibold text-white uppercase tracking-wide">Infografis Sebaran Investasi</h3>
                                <p class="text-[10px] text-slate-400">Peta distribusi (Heatmap Grid) di 18 Distrik Mimika</p>
                            </div>
                            <!-- Legend Singkat -->
                            <div class="flex items-center gap-1.5 text-[8px] text-slate-400 bg-[#061022] px-2 py-1 rounded border border-[#1e2d4a]">
                                <span class="w-2 h-2 rounded-full bg-[#ef4444]"></span> &gt;500M
                                <span class="w-2 h-2 rounded-full bg-[#eab308] ml-1"></span> 100-500M
                                <span class="w-2 h-2 rounded-full bg-[#10b981] ml-1"></span> &lt;100M
                                <span class="w-2 h-2 rounded-full bg-slate-600 ml-1"></span> 0 Data
                            </div>
                        </div>
                        
                        <!-- Grid 18 Distrik -->
                        <div class="flex-1 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5 overflow-y-auto pb-2 pr-1">
                            <?php 
                            $heatmapData = [
                                ['name' => 'Mimika Baru', 'val' => 1214, 'color' => 'from-[#ef4444]/30 to-[#ef4444]/5', 'border' => 'border-[#ef4444]/60', 'text' => 'text-[#ef4444]'],
                                ['name' => 'Tembagapura', 'val' => 854, 'color' => 'from-[#ef4444]/30 to-[#ef4444]/5', 'border' => 'border-[#ef4444]/60', 'text' => 'text-[#ef4444]'],
                                ['name' => 'Wania', 'val' => 812, 'color' => 'from-[#ef4444]/30 to-[#ef4444]/5', 'border' => 'border-[#ef4444]/60', 'text' => 'text-[#ef4444]'],
                                ['name' => 'Kuala Kencana', 'val' => 654, 'color' => 'from-[#ef4444]/30 to-[#ef4444]/5', 'border' => 'border-[#ef4444]/60', 'text' => 'text-[#ef4444]'],
                                ['name' => 'Poumako', 'val' => 422, 'color' => 'from-[#eab308]/30 to-[#eab308]/5', 'border' => 'border-[#eab308]/60', 'text' => 'text-[#eab308]'],
                                ['name' => 'Kwamki Narama', 'val' => 395, 'color' => 'from-[#eab308]/30 to-[#eab308]/5', 'border' => 'border-[#eab308]/60', 'text' => 'text-[#eab308]'],
                                ['name' => 'Agimuga', 'val' => 363, 'color' => 'from-[#eab308]/30 to-[#eab308]/5', 'border' => 'border-[#eab308]/60', 'text' => 'text-[#eab308]'],
                                ['name' => 'Mimika Tengah', 'val' => 313, 'color' => 'from-[#eab308]/30 to-[#eab308]/5', 'border' => 'border-[#eab308]/60', 'text' => 'text-[#eab308]'],
                                ['name' => 'Mimika Barat', 'val' => 219, 'color' => 'from-[#eab308]/30 to-[#eab308]/5', 'border' => 'border-[#eab308]/60', 'text' => 'text-[#eab308]'],
                                ['name' => 'Iwaka', 'val' => 85, 'color' => 'from-[#10b981]/30 to-[#10b981]/5', 'border' => 'border-[#10b981]/60', 'text' => 'text-[#10b981]'],
                                ['name' => 'Alama', 'val' => 45, 'color' => 'from-[#10b981]/30 to-[#10b981]/5', 'border' => 'border-[#10b981]/60', 'text' => 'text-[#10b981]'],
                                ['name' => 'Mimika Timut Jauh', 'val' => 15, 'color' => 'from-[#10b981]/30 to-[#10b981]/5', 'border' => 'border-[#10b981]/60', 'text' => 'text-[#10b981]'],
                                ['name' => 'Amar', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400'],
                                ['name' => 'Hoya', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400'],
                                ['name' => 'Jila', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400'],
                                ['name' => 'Mimika Barat Jauh', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400'],
                                ['name' => 'Mimika Barat Tengah', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400'],
                                ['name' => 'Jita', 'val' => 0, 'color' => 'from-slate-600/20 to-transparent', 'border' => 'border-slate-700', 'text' => 'text-slate-400']
                            ];
                            
                            foreach($heatmapData as $cell) {
                                $valBase = $cell['val'];
                                $displayVal = $valBase;
                                $unitText = 'MILIAR';

                                if ($valBase >= 1000) {
                                    $displayVal = round($valBase / 1000, 2);
                                    $displayVal = str_replace('.', ',', $displayVal); 
                                    $unitText = 'TRILIUN';
                                } elseif ($valBase > 0 && $valBase < 1) {
                                    $displayVal = $valBase * 1000;
                                    $unitText = 'JUTA';
                                } elseif ($valBase == 0) {
                                    $displayVal = 0;
                                    $unitText = 'N/A';
                                }
                            ?>
                            <div class="district-card relative bg-gradient-to-br <?= $cell['color'] ?> border <?= $cell['border'] ?> rounded-lg p-2.5 flex flex-col justify-center items-center cursor-pointer group overflow-hidden">
                                <div class="text-[9px] text-slate-300 font-medium text-center line-clamp-2 leading-tight mb-1 group-hover:text-white transition-colors h-6 flex items-center"><?= $cell['name'] ?></div>
                                <div class="text-sm font-bold <?= $cell['text'] ?>"><?= $displayVal ?></div>
                                <div class="text-[7px] text-slate-500 uppercase tracking-widest mt-0.5"><?= $unitText ?></div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Trend Chart Container -->
                    <div class="col-span-1 lg:col-span-5 glass-panel p-3 flex flex-col h-[380px]">
                        <div class="flex justify-between items-start mb-1">
                            <div>
                                <h3 class="text-[12px] font-semibold text-white uppercase tracking-wide">Trend Realisasi Investasi</h3>
                                <p class="text-[10px] text-slate-400">(PMDN + PMA)</p>
                            </div>
                        </div>
                        <div class="text-[9px] text-slate-400 mt-1">Rp (Miliar)</div>
                        <div id="chartTrend" class="flex-1 w-full mt-1 -ml-2"></div>
                    </div>

                </div>

            </div>
            
            <!-- ================= SISI KANAN (Col 3) ================= -->
            <div class="col-span-1 lg:col-span-3 flex flex-col gap-4">
                
                <!-- Ranking Sektor Usaha -->
                <div class="glass-panel p-4 flex flex-col h-[360px]">
                    <div class="mb-3">
                        <h3 class="text-[12px] font-semibold text-white uppercase tracking-wide">Ranking Sektor Usaha</h3>
                        <p class="text-[9px] text-slate-400">Berdasarkan Realisasi Investasi (PMDN + PMA)</p>
                    </div>
                    
                    <div class="flex-1 overflow-hidden flex flex-col justify-between py-1">
                        <?php 
                        $rankings = [
                            ['Industri Pengolahan', '1,45 T', '34,5%', 85],
                            ['Perdagangan & Reparasi', '1,12 T', '26,7%', 65],
                            ['Pertambangan', '0,98 T', '22,2%', 60],
                            ['Pertanian, Kehutanan', '0,68 T', '16,2%', 40],
                            ['Konstruksi', '0,45 T', '10,7%', 25],
                            ['Jasa Lainnya', '0,28 T', '6,7%', 15],
                            ['Transportasi', '0,11 T', '2,6%', 6],
                            ['Akomodasi & Mamin', '0,08 T', '1,0%', 3],
                            ['Informasi & Komunikasi', '0,43 T', '1,0%', 2]
                        ];
                        foreach($rankings as $index => $item) {
                            $num = $index + 1;
                        ?>
                        <div class="flex items-start gap-2">
                            <div class="w-4 h-4 shrink-0 bg-[#1e2d4a] text-slate-300 text-[9px] font-bold rounded flex items-center justify-center mt-0.5">
                                <?= $num ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center text-[10px] mb-1">
                                    <span class="text-white truncate flex-1 pr-2" title="<?= $item[0] ?>"><?= $item[0] ?></span>
                                    <div class="flex items-center gap-1.5 text-right shrink-0 whitespace-nowrap">
                                        <span class="text-white font-medium w-[45px] text-right">Rp <?= $item[1] ?></span>
                                        <span class="text-slate-300 w-[28px] text-right"><?= $item[2] ?></span>
                                    </div>
                                </div>
                                <div class="w-full h-1 bg-[#1e2d4a] rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500" style="width: <?= $item[3] ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Top 5 Kecamatan -->
                <div class="glass-panel p-4 flex flex-col h-[180px]">
                    <div class="mb-3">
                        <h3 class="text-[12px] font-semibold text-white uppercase tracking-wide">Top 5 Distrik <span class="text-[9px] text-slate-400 normal-case">(Nilai Investasi)</span></h3>
                    </div>
                    <div class="flex-1 flex flex-col justify-between pt-1">
                        <?php 
                        $topKecamatan = [
                            ['Distrik Mimika Baru', '1,25 T', 80],
                            ['Distrik Wania', '0,98 T', 65],
                            ['Distrik Tembagapura', '0,76 T', 50],
                            ['Distrik Kuala Kencana', '0,54 T', 35],
                            ['Distrik Kwamki Narama', '0,43 T', 25]
                        ];
                        foreach($topKecamatan as $index => $kec) {
                            $num = $index + 1;
                        ?>
                        <div class="flex items-center gap-2 text-[10px]">
                            <div class="w-4 h-4 shrink-0 bg-[#1e2d4a] text-slate-300 text-[9px] font-bold rounded flex items-center justify-center"><?= $num ?></div>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-white"><?= $kec[0] ?></span>
                                    <span class="text-white font-medium">Rp <?= $kec[1] ?></span>
                                </div>
                                <div class="w-full h-1 bg-[#1e2d4a] rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500" style="width: <?= $kec[2] ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            </div>

        </div>

        <!-- ================= ROW BAWAH ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-4">
            
            <!-- 1. Data NIB Aktif -->
            <div class="col-span-1 lg:col-span-5 glass-panel p-3 flex flex-col h-[220px]">
                <div class="mb-2 flex justify-between items-end">
                    <div>
                        <h3 class="text-[11px] font-semibold text-white uppercase tracking-wide">Data NIB Aktif</h3>
                        <p class="text-[9px] text-slate-400">5 Pendaftar Terbaru</p>
                    </div>
                    <button class="text-[9px] bg-blue-600/20 text-blue-400 px-2 py-1 rounded border border-blue-500/30 hover:bg-blue-600/40 transition-colors">Lihat Semua</button>
                </div>
                <div class="flex-1 mt-1 overflow-hidden">
                    <table class="w-full text-left text-[10px] whitespace-nowrap text-slate-300 h-full">
                        <thead class="border-b border-slate-700/50 sticky top-0 bg-[#0b172e] z-10">
                            <tr>
                                <th class="pb-1.5 font-medium pr-3">No</th>
                                <th class="pb-1.5 font-medium pr-3">NIB</th>
                                <th class="pb-1.5 font-medium pr-3">Nama Pelaku Usaha</th>
                                <th class="pb-1.5 font-medium pr-3">Sektor Usaha</th>
                                <th class="pb-1.5 font-medium pr-3">Lokasi (Distrik)</th>
                                <th class="pb-1.5 font-medium text-emerald-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            <?php 
                            $tableData = [
                                ['9120301234567', 'CV Maju Bersama', 'Pertambangan', 'Kec. Tembagapura'],
                                ['8120409876543', 'PT Sejahtera Abadi', 'Perdagangan', 'Kec. Mimika Baru'],
                                ['9120405678912', 'UD Mandiri Jaya', 'Jasa Lainnya', 'Kec. Wania'],
                                ['9120502223331', 'PT Makmur Sentosa', 'Konstruksi', 'Kec. Kuala Kencana'],
                                ['9120504445556', 'CV Harapan Baru', 'Pertanian', 'Kec. Kwamki Narama']
                            ];
                            foreach($tableData as $index => $row) { ?>
                            <tr class="hover:bg-slate-800/30">
                                <td class="py-1.5 pr-3"><?= $index+1 ?></td>
                                <td class="py-1.5 pr-3 text-blue-400"><?= $row[0] ?></td>
                                <td class="py-1.5 pr-3 text-white font-medium"><?= $row[1] ?></td>
                                <td class="py-1.5 pr-3"><?= $row[2] ?></td>
                                <td class="py-1.5 pr-3"><?= $row[3] ?></td>
                                <td class="py-1.5 text-emerald-400 font-medium">Aktif</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Statistik Sektor Usaha -->
            <div class="col-span-1 lg:col-span-3 glass-panel p-3 flex flex-col h-[220px]">
                <h3 class="text-[11px] font-semibold text-white uppercase tracking-wide mb-1">Statistik Sektor Usaha</h3>
                <div class="flex-1 flex items-center justify-between">
                    <div id="chartDonut" class="w-[55%] relative -left-3"></div>
                    <div class="w-[45%] space-y-1.5 text-[9px] pr-1">
                        <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><div class="w-2 h-2 bg-[#3b82f6]"></div> <span class="text-slate-300">Perdagangan</span></div> <span class="text-white font-bold">3.456</span></div>
                        <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><div class="w-2 h-2 bg-[#ef4444]"></div> <span class="text-slate-300">Pertambangan</span></div> <span class="text-white font-bold">2.789</span></div>
                        <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><div class="w-2 h-2 bg-[#eab308]"></div> <span class="text-slate-300">Pertanian</span></div> <span class="text-white font-bold">2.123</span></div>
                        <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><div class="w-2 h-2 bg-[#10b981]"></div> <span class="text-slate-300">Jasa Lain</span></div> <span class="text-white font-bold">1.987</span></div>
                        <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><div class="w-2 h-2 bg-[#8b5cf6]"></div> <span class="text-slate-300">Konstruksi</span></div> <span class="text-white font-bold">1.245</span></div>
                    </div>
                </div>
            </div>

            <!-- 3. Informasi Cepat -->
            <div class="col-span-1 lg:col-span-2 glass-panel p-3 flex flex-col h-[220px] justify-between">
                <h3 class="text-[11px] font-semibold text-white uppercase tracking-wide mb-1">Informasi Cepat</h3>
                
                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5 pt-1">
                    <div class="flex items-center gap-1.5">
                        <div class="p-1 bg-blue-500/20 text-blue-400 rounded"><i data-lucide="file-check" class="w-3.5 h-3.5"></i></div>
                        <div>
                            <div class="text-[8px] text-slate-400 leading-tight">Perizinan Terbit Bulan Ini</div>
                            <div class="text-[13px] font-bold text-white leading-none mt-0.5">1.245</div>
                        </div>
                    </div>
                    <div class="text-[9px] text-emerald-400 flex items-center gap-0.5 font-bold">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> 14,5%
                    </div>
                </div>
                
                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5 pt-1">
                    <div class="flex items-center gap-1.5">
                        <div class="p-1 bg-yellow-500/20 text-yellow-400 rounded"><i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i></div>
                        <div>
                            <div class="text-[8px] text-slate-400 leading-tight">Perizinan Akan Berakhir (30 Hr)</div>
                            <div class="text-[13px] font-bold text-white leading-none mt-0.5">352</div>
                        </div>
                    </div>
                    <a href="#" class="text-[8px] text-blue-400 hover:text-blue-300 transition-colors whitespace-nowrap">Lihat Detail &rarr;</a>
                </div>

                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5 pt-1">
                    <div class="flex items-center gap-1.5">
                        <div class="p-1 bg-orange-500/20 text-orange-400 rounded"><i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i></div>
                        <div>
                            <div class="text-[8px] text-slate-400 leading-tight">Pengaduan Masuk Bulan Ini</div>
                            <div class="text-[13px] font-bold text-white leading-none mt-0.5">28</div>
                        </div>
                    </div>
                    <a href="#" class="text-[8px] text-blue-400 hover:text-blue-300 transition-colors whitespace-nowrap">Lihat Detail &rarr;</a>
                </div>
                
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-1.5">
                        <div class="p-1 bg-red-500/20 text-red-400 rounded"><i data-lucide="shield-alert" class="w-3.5 h-3.5"></i></div>
                        <div>
                            <div class="text-[8px] text-slate-400 leading-tight">Pelaku Usaha Belum Verifikasi</div>
                            <div class="text-[13px] font-bold text-white leading-none mt-0.5">142</div>
                        </div>
                    </div>
                    <a href="#" class="text-[8px] text-blue-400 hover:text-blue-300 transition-colors whitespace-nowrap">Lihat Detail &rarr;</a>
                </div>
            </div>

            <!-- 4. Berita & Kegiatan -->
            <div class="col-span-1 lg:col-span-2 glass-panel p-3 flex flex-col h-[220px] relative overflow-hidden group">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=600&auto=format&fit=crop')] bg-cover bg-center opacity-30 mix-blend-overlay transition-transform duration-500 group-hover:scale-110"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0b172e] via-[#0b172e]/80 to-[#0b172e]/40"></div>
                
                <h3 class="text-[11px] font-semibold text-white uppercase tracking-wide relative z-10 mb-1">Berita & Kegiatan</h3>
                
                <div class="relative z-10 flex-1 flex flex-col justify-end pb-1">
                    <span class="bg-blue-600 text-white text-[8px] px-1.5 py-0.5 rounded w-fit mb-1.5 font-semibold">INFORMASI</span>
                    <h4 class="text-[11px] font-bold text-white leading-snug mb-1">DPMPTSP Gelar Forum Investasi Daerah 2026</h4>
                    <p class="text-[9px] text-slate-300 line-clamp-2 mb-2">Meningkatkan kolaborasi dengan pelaku usaha untuk investasi berkelanjutan.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-[8px] text-slate-400">17 Juni 2026</span>
                        <a href="#" class="text-[9px] text-blue-400 hover:text-blue-300">Selengkapnya &rarr;</a>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#050e1d] border-t border-[#1e2d4a] mt-8 py-6">
        <div class="max-w-[1900px] mx-auto px-4 lg:px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-10 shrink-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg?_=20190609141326" class="w-full h-full object-contain rounded-sm">
                </div>
                <div>
                    <h2 class="text-white font-bold text-sm tracking-widest leading-none mb-1">DPMPTSP KABUPATEN MIMIKA</h2>
                    <p class="text-slate-400 text-[10px] font-medium tracking-wide uppercase">Smart Investment For Better Future</p>
                </div>
            </div>
            <div class="text-[10px] text-slate-500 text-center md:text-right">
                &copy; 2026 Pemerintah Kabupaten Mimika.<br class="hidden md:block">
                Sistem Informasi Profil Pelaku Usaha (SIPPU). All rights reserved.
            </div>
        </div>
    </footer>

    <!-- ================= MODAL LOGIN ================= -->
    <div id="loginModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Overlay Gelap (Backdrop) -->
        <div class="absolute inset-0 bg-[#040d1f]/80 backdrop-blur-sm transition-opacity duration-300" onclick="toggleModal('loginModal')"></div>
        
        <!-- Konten Modal -->
        <div id="loginModalContent" class="relative w-full max-w-[380px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl shadow-blue-900/20 flex flex-col transition-all duration-300 modal-leave">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center border border-blue-500/30">
                        <i data-lucide="shield-check" class="w-4 h-4 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm leading-tight tracking-wide">Login Administrator</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">SIPPU DPMPTSP Mimika</p>
                    </div>
                </div>
                <button onclick="toggleModal('loginModal')" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Body Form Modal (UPDATE: Menggunakan onsubmit agar enter key berfungsi) -->
            <form id="loginForm" class="p-6 flex flex-col gap-5" onsubmit="doLogin(event)">
                <!-- Input Username -->
                <div class="space-y-1.5">
                    <label for="username" class="text-[11px] font-semibold text-slate-300 uppercase tracking-wider block">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400 group-focus-within:text-blue-400 transition-colors"></i>
                        </div>
                        <input type="text" id="username" name="username" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2.5 outline-none transition-all placeholder:text-slate-600" placeholder="Masukkan username admin">
                    </div>
                </div>
                
                <!-- Input Password dengan Toggle -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-end">
                        <label for="passwordField" class="text-[11px] font-semibold text-slate-300 uppercase tracking-wider block">Password</label>
                        <a href="#" class="text-[10px] text-blue-400 hover:text-blue-300 hover:underline">Lupa password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400 group-focus-within:text-blue-400 transition-colors"></i>
                        </div>
                        <input type="password" id="passwordField" name="password" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block pl-10 pr-10 p-2.5 outline-none transition-all placeholder:text-slate-600" placeholder="••••••••">
                        <!-- Tombol Show/Hide -->
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors focus:outline-none" title="Tampilkan/Sembunyikan Password">
                            <i data-lucide="eye" id="eyeIconToggle" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit (UPDATE: Tipe diubah menjadi submit) -->
                <button type="submit" id="btnLoginSubmit" class="mt-2 w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold rounded-lg text-sm px-5 py-3 text-center flex justify-center items-center gap-2 transition-all shadow-lg shadow-blue-900/40 border border-blue-500/50 hover:shadow-blue-900/60 transform hover:-translate-y-0.5">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk Ke Sistem
                </button>

                <p class="text-center text-[10px] text-slate-500 mt-1">Akses ini khusus untuk pegawai berwenang.</p>
            </form>
        </div>
    </div>

    <!-- SCRIPT LOGIKA -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        var BaseURL = '<?= base_url() ?>';
        // 1. Inisialisasi Icon
        lucide.createIcons();

        // 2. Waktu Real-Time (WIT)
        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jayapura' };
            document.getElementById('date-display').innerText = now.toLocaleDateString('id-ID', dateOptions);
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jayapura' };
            document.getElementById('time-display').innerText = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 3. FUNGSI LOGIKA MODAL LOGIN
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            const content = document.getElementById(modalID + 'Content');
            
            if (modal.classList.contains('hidden')) {
                // Proses Membuka Modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('modal-leave');
                    content.classList.add('modal-enter');
                }, 10);
            } else {
                // Proses Menutup Modal
                content.classList.remove('modal-enter');
                content.classList.add('modal-leave');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300); 
            }
        }

        // 4. FUNGSI SHOW / HIDE PASSWORD
        function togglePasswordVisibility() {
            const pwdField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIconToggle');

            if (pwdField.type === 'password') {
                pwdField.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwdField.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons({ attrs: { class: ['w-4', 'h-4'] } });
        }

        // 5. UPDATE: FUNGSI AJAX LOGIN KE CODEIGNITER 3
        async function doLogin(event) {
            // Mencegah form melakukan reload halaman bawaan browser
            event.preventDefault(); 
            
            const btn = document.getElementById('btnLoginSubmit');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('passwordField');

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (username == '' || password == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Username dan Password wajib diisi!',
                    background: '#0b172e', color: '#fff'
                });
                return;
            }

            // Animasi Loading Tombol
            const originalText = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...`;
            lucide.createIcons();
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            var Admin = { username : $("#username").val(),
                            password : $("#passwordField").val() }
            $.post(BaseURL+"SIPPU/proses_login", Admin).done(function(Respon) {
                if (JSON.parse(Respon).status === 'success') {
                    // Berhasil Login
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: 'Mengarahkan ke Dashboard Admin...',
                        background: '#0b172e', color: '#fff',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect ke controller Admin
                        window.location.href = '<?= base_url("Admin") ?>';
                    });
                } else {
                    // Gagal Login (Username / Pass salah)
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: JSON.parse(Respon).message,
                        background: '#0b172e', color: '#fff',
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Kembalikan tombol ke semula
                    btn.innerHTML = originalText;
                    lucide.createIcons();
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            })   
            // try {
            //     // Persiapkan Data menggunakan URLSearchParams agar sesuai dengan
            //     // standar form submission ($this->input->post()) di CI3
            //     // const params = new URLSearchParams();
            //     // params.append('username', username);
            //     // params.append('password', password);
                
                
            //     // Fetch data dari SIPPU controller fungsi proses_login
            //     // const response = await fetch('<?= base_url("SIPPU/proses_login") ?>', {
            //     //     method: 'POST',
            //     //     headers: {
            //     //         'Content-Type': 'application/x-www-form-urlencoded',
            //     //         'X-Requested-With': 'XMLHttpRequest' // Tanda AJAX untuk CI
            //     //     },
            //     //     body: params
            //     // });

            //     // const result = await response.json();
                

            // } catch (error) {
            //     console.error('Error AJAX:', error);
            //     // Jika koneksi / server bermasalah (ex: CI3 error 500)
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Kesalahan Sistem',
            //         text: 'Gagal terhubung ke server. Silakan coba lagi nanti.',
            //         background: '#0b172e', color: '#fff',
            //         confirmButtonColor: '#3b82f6'
            //     });
                
            //     // Kembalikan tombol
            //     btn.innerHTML = originalText;
            //     lucide.createIcons();
            //     btn.disabled = false;
            //     btn.classList.remove('opacity-75', 'cursor-not-allowed');
            // }
        }

        // 6. Konfigurasi Grafik Trend
        const trendOptions = {
            series: [
                { name: '2025', data: [400, 600, 800, 1000, 1200, 1400, 1700, 1900, 2200, 2400, 2600, 2800] },
                { name: '2026', data: [600, 800, 1100, 1300, 1600, 1900, 2200, 2500, 2800, 3100, 3300, 3500] }
            ],
            chart: { height: '100%', type: 'area', toolbar: { show: false }, fontFamily: 'inherit', background: 'transparent' },
            colors: ['#0ea5e9', '#22c55e'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            markers: { size: 4, colors: ['#040d1f'], strokeColors: ['#0ea5e9', '#22c55e'], strokeWidth: 2, hover: { size: 6 } },
            grid: { borderColor: '#1e2d4a', strokeDashArray: 2, padding: { bottom: 20 }, xaxis: { lines: { show: true } }, yaxis: { lines: { show: true } } },
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], labels: { style: { colors: '#94a3b8', fontSize: '9px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { min: 0, max: 3500, tickAmount: 7, labels: { style: { colors: '#94a3b8', fontSize: '9px' } } },
            legend: { show: true, position: 'top', horizontalAlign: 'right', labels: { colors: '#e2e8f0' }, fontSize: '10px', markers: { width: 10, height: 10, radius: 10 } },
            theme: { mode: 'dark' }
        };
        new ApexCharts(document.querySelector("#chartTrend"), trendOptions).render();

        // 7. Konfigurasi Grafik Donut
        const donutOptions = {
            series: [3456, 2789, 2123, 1987, 1245],
            chart: { type: 'donut', height: 180, background: 'transparent' },
            labels: ['Perdagangan', 'Pertambangan', 'Pertanian', 'Jasa Lainnya', 'Konstruksi'],
            colors: ['#3b82f6', '#ef4444', '#eab308', '#10b981', '#8b5cf6'],
            stroke: { show: false },
            dataLabels: { enabled: false },
            legend: { show: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '10px', color: '#94a3b8' },
                            value: { show: true, fontSize: '16px', fontWeight: 'bold', color: '#fff' },
                            total: { show: true, showAlways: true, label: 'Total Usaha', fontSize: '9px', color: '#94a3b8', formatter: function (w) { return "11.600" } }
                        }
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#chartDonut"), donutOptions).render();

    </script>
</body>
</html>