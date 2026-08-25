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

    <!-- SweetAlert2 (Untuk Notifikasi CRUD) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" crossorigin="anonymous"></script>

    <!-- Google APIs jQuery 3.7.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- jsPDF & AutoTable untuk Export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>

    <!-- DataTables CSS/JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <style>
        /* Background & Global Font */
        body { 
            background-color: #030816; 
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Glass Panel Khusus Tabel & Card */
        .admin-panel { 
            background-color: #0a1329; 
            border: 1px solid #1a2c4e; 
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
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

        /* Animasi Modal */
        .modal-enter { opacity: 1; transform: scale(1); }
        .modal-leave { opacity: 0; transform: scale(0.95); }

        /* Custom Styling untuk DataTables (Dark Mode Integration) */
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
<body class="min-h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#050e1d] border-r border-[#16243d] flex flex-col h-screen hidden md:flex shrink-0">
        <div class="h-[75px] flex items-center px-6 border-b border-[#16243d] shrink-0 gap-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg?_=20190609141326" alt="Logo" class="w-8 h-10 object-contain rounded-sm" />
            <div class="flex flex-col">
                <span class="text-white font-bold tracking-wide text-lg leading-tight">Admin SIPPU</span>
                <span class="text-[9px] text-slate-400 font-medium uppercase tracking-wider">DPMPTSP Panel</span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1">
            <!-- Cek aktif menu -->
            <?php $current_uri = $this->uri->segment(2); ?>
            
            <a href="<?= base_url('Admin') ?>" class="sidebar-menu-item <?= ($current_uri == '' || $current_uri == 'index') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="<?= base_url('Admin/JenisIzin') ?>" class="sidebar-menu-item <?= ($current_uri == 'JenisIzin') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="file-signature" class="w-5 h-5"></i>
                <span>Jenis Perizinan</span>
            </a>

            <a href="<?= base_url('Admin/ProfilUsaha') ?>" class="sidebar-menu-item <?= ($current_uri == 'ProfilUsaha') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="building-2" class="w-5 h-5"></i>
                <span>Profil Usaha</span>
            </a>

            <a href="<?= base_url('Admin/DataInvestasi') ?>" class="sidebar-menu-item <?= ($current_uri == 'DataInvestasi') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="building-2" class="w-5 h-5"></i>
                <span>Data Investasi</span>
            </a>

            <a href="<?= base_url('Admin/SektorUsaha') ?>" class="sidebar-menu-item <?= ($current_uri == 'SektorUsaha') ? 'active' : '' ?> flex items-center gap-3 px-6 py-3 text-sm text-slate-400 border-left border-transparent">
                <i data-lucide="file-signature" class="w-5 h-5"></i>
                <span>Sektor Usaha</span>
            </a>
        </nav>

        <div class="p-4 border-t border-[#16243d] bg-[#030917] flex flex-col gap-3">
            <!-- User Info -->
            <div class="flex items-center gap-3 bg-[#0a1324] border border-[#1e2d4a] rounded-xl p-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20">
                    <i data-lucide="user-check" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-bold text-white truncate"><?= $this->session->userdata('username') ? $this->session->userdata('username') : 'Admin' ?></span>
                    <span class="text-[10px] text-emerald-400 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online</span>
                </div>
            </div>
            
            <!-- TOMBOL LOGOUT -->
            <a href="<?= base_url('Admin/logout') ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-600 border border-red-500/30 hover:border-red-600 text-red-400 hover:text-white rounded-lg py-2.5 transition-all text-xs font-semibold tracking-wide">
                <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
            </a>
        </div>
    </aside>