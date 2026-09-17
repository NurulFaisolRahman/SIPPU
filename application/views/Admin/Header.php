<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Admin Dashboard - SINTAMIKA Mimika' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg">

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" crossorigin="anonymous"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" crossorigin="anonymous"></script>

    <!-- jQuery 3.7.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jsPDF & AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>

    <!-- ExcelJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

    <!-- DataTables CSS/JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <style>
        body { 
            background-color: #030816; 
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-panel { 
            background-color: #0a1329; 
            border: 1px solid #1a2c4e; 
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        }

        #sidebar {
            width: 16rem;
            transition: width 0.3s ease-in-out;
        }
        #sidebar.collapsed {
            width: 4.5rem;
        }
        #sidebar.collapsed .sidebar-text {
            display: none !important;
        }
        #sidebar.collapsed .sidebar-header {
            padding: 0;
            justify-content: center;
        }
        #sidebar.collapsed .sidebar-menu-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        #sidebar.collapsed .user-card {
            padding: 0.5rem;
            justify-content: center;
        }

        .sidebar-menu-item { transition: all 0.2s ease-in-out; }
        .sidebar-menu-item:hover, .sidebar-menu-item.active {
            background: linear-gradient(90deg, rgba(37,99,235,0.15) 0%, transparent 100%);
            border-left: 3px solid #3b82f6;
            color: #ffffff;
        }
        .sidebar-menu-item.active i { color: #60a5fa; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e3a8a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        .modal-enter { opacity: 1; transform: scale(1); }
        .modal-leave { opacity: 0; transform: scale(0.95); }

        .dataTables_wrapper { padding: 1rem 1.25rem; }
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #94a3b8 !important;
            font-size: 11px;
            margin-bottom: 10px;
        }
        .dataTables_wrapper .dataTables_length select {
            background-color: #030917; border: 1px solid #1e2d4a; color: #e2e8f0; border-radius: 0.375rem; padding: 2px 6px; outline: none;
        }
        .dataTables_wrapper .dataTables_filter input {
            background-color: #030917; border: 1px solid #1e2d4a; color: #e2e8f0; border-radius: 0.375rem; padding: 4px 8px; margin-left: 8px; outline: none;
        }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #3b82f6; }
        table.dataTable tbody tr { background-color: transparent !important; }
        table.dataTable.no-footer { border-bottom: 1px solid #1e2d4a; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #94a3b8 !important; border: 1px solid transparent !important; padding: 4px 10px !important; margin-left: 2px; border-radius: 0.375rem; cursor: pointer;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #1e2d4a !important; color: #fff !important; border: 1px solid #1e2d4a !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #2563eb !important; color: #fff !important; border: 1px solid #2563eb !important; font-weight: bold;
        }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden">

    <!-- SIDEBAR -->
    <!-- ID 'sidebar' ditambahkan untuk target manipulasi Javascript -->
    <!-- Class w-64 dihapus dari class Tailwind karena sudah dihandle CSS di atas -->
    <aside id="sidebar" class="bg-[#050e1d] border-r border-[#16243d] flex flex-col h-screen shrink-0 z-20">
        <div class="sidebar-header h-[75px] flex items-center px-6 border-b border-[#16243d] shrink-0 gap-3 transition-all">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg?_=20190609141326" alt="Logo" class="w-8 h-10 object-contain rounded-sm shrink-0" />
            <div class="sidebar-text flex flex-col">
                <span class="text-white font-bold tracking-wide text-lg leading-tight">Admin SINTAMIKA</span>
                <span class="text-[9px] text-slate-400 font-medium uppercase tracking-wider">DPMPTSP Panel</span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1">
            <!-- Cek aktif menu -->
            <?php $current_uri = $this->uri->segment(2); ?>
            
            <!-- GROUP DROPDOWN: JENIS PERIZINAN -->
            <?php 
                $perizinan_active = in_array($current_uri, ['JenisIzin', 'DataSiujk', 'DataSiup', 'DataPbg']); 
            ?>
            <div>
                <button type="button" onclick="toggleSubmenu('sub-perizinan')" class="sidebar-menu-item w-full flex items-center justify-between px-6 py-3 text-sm text-slate-400 border-left border-transparent focus:outline-none <?= $perizinan_active ? 'active' : '' ?>">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-signature" class="w-5 h-5 shrink-0"></i>
                        <span class="sidebar-text font-medium">Data Perizinan</span>
                    </div>
                    <i data-lucide="chevron-down" id="arrow-sub-perizinan" class="w-4 h-4 shrink-0 sidebar-text transition-transform duration-200 <?= $perizinan_active ? 'rotate-180' : '' ?>"></i>
                </button>
                
                <div id="sub-perizinan" class="sidebar-text pl-11 space-y-1 my-1 <?= $perizinan_active ? '' : 'hidden' ?>">
                    <a href="<?= base_url('Admin/JenisIzin') ?>" class="flex items-center gap-2 py-2 px-3 text-xs rounded-lg transition-colors <?= ($current_uri == 'JenisIzin') ? 'text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                        <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 shrink-0"></i> Rekap Perizinan
                    </a>
                    <a href="<?= base_url('Admin/DataSiujk') ?>" class="flex items-center gap-2 py-2 px-3 text-xs rounded-lg transition-colors <?= ($current_uri == 'DataSiujk') ? 'text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                        <i data-lucide="hard-hat" class="w-3.5 h-3.5 shrink-0"></i> Data SIUJK
                    </a>
                    <a href="<?= base_url('Admin/DataSiup') ?>" class="flex items-center gap-2 py-2 px-3 text-xs rounded-lg transition-colors <?= ($current_uri == 'DataSiup') ? 'text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                        <i data-lucide="store" class="w-3.5 h-3.5 shrink-0"></i> Data SIUP
                    </a>
                    <a href="<?= base_url('Admin/DataPbg') ?>" class="flex items-center gap-2 py-2 px-3 text-xs rounded-lg transition-colors <?= ($current_uri == 'DataPbg') ? 'text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                        <i data-lucide="building" class="w-3.5 h-3.5 shrink-0"></i> Data PBG
                    </a>
                </div>
            </div>

            <!-- Icon diubah menjadi 'layers' (atau 'clipboard-list' / 'building-2') agar sesuai dengan Rekap Data MPP Hierarkis -->
            <a href="<?= base_url('Admin/OpdInstansi') ?>" class="sidebar-menu-item <?= ($current_uri == 'OpdInstansi') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="layers" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text">Rekap Data MPP</span>
            </a>

            <a href="<?= base_url('Admin/ProfilUsaha') ?>" class="sidebar-menu-item <?= ($current_uri == 'ProfilUsaha') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="building-2" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text">Profil Usaha</span>
            </a>

            <!-- Icon diubah menjadi trending-up agar sesuai dengan Investasi -->
            <a href="<?= base_url('Admin/DataInvestasi') ?>" class="sidebar-menu-item <?= ($current_uri == 'DataInvestasi') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="trending-up" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text">Data Investasi</span>
            </a>

            <!-- Menu Tambahan Baru -->
            <div class="px-6 py-2 mt-2 sidebar-text">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pengaturan</span>
            </div>

            <a href="<?= base_url('Admin/PengaturanAkun') ?>" class="sidebar-menu-item <?= ($current_uri == 'PengaturanAkun') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="user-cog" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text">Pengaturan Akun</span>
            </a>

            <a href="<?= base_url('Admin/KepalaDinas') ?>" class="sidebar-menu-item <?= ($current_uri == 'KepalaDinas') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="contact" class="w-5 h-5 shrink-0"></i>
                <span class="sidebar-text">Data Kepala Dinas</span>
            </a>
        </nav>

        <div class="p-4 border-t border-[#16243d] bg-[#030917] flex flex-col gap-3">
            <!-- User Info -->
            <div class="user-card flex items-center gap-3 bg-[#0a1324] border border-[#1e2d4a] rounded-xl p-3 transition-all">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20">
                    <i data-lucide="user-check" class="w-5 h-5 text-white shrink-0"></i>
                </div>
                <div class="sidebar-text flex flex-col min-w-0">
                    <span class="text-xs font-bold text-white truncate"><?= $this->session->userdata('username') ? $this->session->userdata('username') : 'Admin' ?></span>
                    <span class="text-[10px] text-emerald-400 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online</span>
                </div>
            </div>
            
            <!-- TOMBOL LOGOUT -->
            <a href="<?= base_url('Admin/logout') ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-600 border border-red-500/30 hover:border-red-600 text-red-400 hover:text-white rounded-lg py-2.5 transition-all text-xs font-semibold tracking-wide">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> <span class="sidebar-text">Keluar</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col overflow-hidden w-full transition-all duration-300">
        
        <!-- TOP NAVIGATION BAR (Ditambahkan untuk tombol toggle & judul Halaman) -->
        <header class="h-[75px] bg-[#0a1329] border-b border-[#16243d] flex items-center px-6 shrink-0 justify-between z-10">
            <div class="flex items-center gap-4">
                <!-- TOMBOL TOGGLE SIDEBAR -->
                <button id="toggle-sidebar" class="text-slate-400 hover:text-white bg-[#030917] border border-[#1e2d4a] p-2 rounded-lg hover:bg-[#1e2d4a] transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <h2 class="text-white font-semibold text-lg"><?= isset($title) ? $title : 'Dashboard' ?></h2>
            </div>
            
            <!-- Tanggal / Jam Server (Opsional, Pemanis Header) -->
            <div class="hidden sm:block text-xs font-medium text-slate-400">
                <?= date('d M Y') ?>
            </div>
        </header>

        <!-- KONTEN HALAMAN UTAMA (Di-scroll) -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#030816] p-6">
            <!-- NOTA: Konten dari file view Anda (seperti v_dashboard, dll) akan masuk / dirender di dalam area ini. -->

            <!-- SCRIPT INISIALISASI & TOGGLE LOGIC -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    const sidebar = document.getElementById('sidebar');
                    const toggleBtn = document.getElementById('toggle-sidebar');

                    if (toggleBtn && sidebar) {
                        toggleBtn.addEventListener('click', function() {
                            sidebar.classList.toggle('collapsed');
                        });
                    }
                });

                function toggleSubmenu(id) {
                    const el = document.getElementById(id);
                    const arrow = document.getElementById('arrow-' + id);
                    if (el) {
                        el.classList.toggle('hidden');
                    }
                    if (arrow) {
                        arrow.classList.toggle('rotate-180');
                    }
                }
            </script>