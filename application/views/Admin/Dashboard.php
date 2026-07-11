<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Admin Dashboard - SIPPU Mimika' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" crossorigin="anonymous"></script>

    <style>
        /* Background & Global Font */
        body { 
            background-color: #030816; 
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Styling */
        .sidebar-menu-item { transition: all 0.2s ease-in-out; }
        .sidebar-menu-item:hover, .sidebar-menu-item.active {
            background: linear-gradient(90deg, rgba(37,99,235,0.15) 0%, transparent 100%);
            border-left: 3px solid #3b82f6;
            color: #ffffff;
        }
        .sidebar-menu-item.active i { color: #60a5fa; }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e3a8a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    </style>
</head>
<body class="min-h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#050e1d] border-r border-[#16243d] flex flex-col h-screen hidden md:flex shrink-0">
        <div class="h-[75px] flex items-center px-6 border-b border-[#16243d] shrink-0 gap-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg?_=20190609141326" alt="Logo" class="w-8 h-10 object-contain rounded-sm" />
            <div class="flex flex-col">
                <span class="text-white font-bold tracking-wide text-lg leading-tight">Admin SIPPU</span>
                <span class="text-[9px] text-slate-400 font-medium uppercase tracking-wider">Mimika Panel</span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1">
            <div class="px-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menu Utama</div>
            <a href="<?= base_url('Dashboard') ?>" class="sidebar-menu-item active flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard Home</span>
            </a>
            
            <div class="px-6 mt-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Data</div>
            <a href="<?= base_url('Admin/JenisIzin') ?>" class="sidebar-menu-item flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="file-signature" class="w-5 h-5"></i>
                <span>Jenis Perizinan</span>
            </a>
            <a href="#" class="sidebar-menu-item flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="building-2" class="w-5 h-5"></i>
                <span>Pelaku Usaha</span>
            </a>
        </nav>

        <div class="p-4 border-t border-[#16243d] bg-[#030917]">
            <div class="flex items-center gap-3 bg-[#0a1324] border border-[#1e2d4a] rounded-xl p-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20">
                    <i data-lucide="user-check" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-bold text-white truncate">Admin Utama</span>
                    <span class="text-[10px] text-emerald-400 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#0a1633] via-[#030816] to-[#030816]">
        
        <header class="h-[75px] bg-[#050e1d]/80 backdrop-blur-md border-b border-[#16243d] flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-blue-400 transition-colors">Menu Utama</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <span class="text-white">Dashboard Home</span>
                </div>
            </div>
        </header>

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
                            <h3 class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1">Total Pelaku Usaha</h3>
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