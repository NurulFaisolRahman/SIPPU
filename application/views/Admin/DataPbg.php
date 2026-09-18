<div class="p-4 sm:p-6 lg:p-8 bg-[#0f172a] text-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#1e2d4a]/60 backdrop-blur-md p-4 rounded-xl border border-slate-700/50 shadow-lg">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-white tracking-wide flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-teal-400"></i>
                    Data Persetujuan Bangunan Gedung (PBG)
                </h1>
                <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Kelola data perizinan PBG, spesifikasi teknis, data lokasi, dll.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-teal-500/10 text-teal-400 border border-teal-500/20 text-[10px] font-semibold rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    Aktif: Tahun <?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>
                </span>
            </div>
        </div>

        <!-- FILTER & ACTION BAR -->
        <div class="bg-[#1e2d4a]/40 border border-slate-700/50 p-4 rounded-xl shadow-md">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- FILTER FORM -->
                <form method="GET" action="<?= base_url('Admin/DataPbg') ?>" class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-semibold text-slate-300">Tahun:</label>
                        <input type="number" name="tahun" id="filterTahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" min="2000" max="2099" class="bg-[#0f172a] border border-slate-700 text-slate-200 text-xs rounded-md px-2 py-1 focus:outline-none focus:border-teal-500 w-20 sm:w-24">
                    </div>

                    <button type="submit" class="bg-[#1e2d4a] hover:bg-[#2e4063] text-white p-1 px-3 rounded-md transition-colors flex items-center gap-1 text-[10px] font-semibold border border-slate-600">
                        <i data-lucide="search" class="w-3 h-3"></i> Filter
                    </button>
                </form>

                <!-- BUTTONS ACTION (Added Excel & PDF) -->
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="button" onclick="downloadExcel()" class="flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-md px-3 py-1.5 text-[10px] transition-all shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                        <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> Excel
                    </button>
                    <button type="button" onclick="downloadPDF()" class="flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-md px-3 py-1.5 text-[10px] transition-all shadow-[0_0_12px_rgba(225,29,72,0.3)]">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> PDF
                    </button>
                    <div class="w-px h-5 bg-slate-700 mx-1"></div>
                    <button onclick="openModal()" class="flex items-center justify-center gap-1 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-md px-3 py-1.5 text-[10px] transition-all shadow-[0_0_15px_rgba(13,148,136,0.3)]">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah PBG
                    </button>
                </div>
            </div>
        </div>

        <!-- TABEL DATA -->
        <div class="bg-[#1e2d4a]/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg p-3">
            <div class="w-full">
                <table id="tablePbg" class="w-full text-left border-collapse text-xs break-words">
                    <thead>
                        <tr class="bg-[#0f172a] text-slate-300 border-b border-slate-700 uppercase tracking-wider text-[10px]">
                            <th class="p-2 text-center w-8">No</th>
                            <th class="p-2 w-[18%]">Permohonan & SK</th>
                            <th class="p-2 w-[18%]">Pemilik Bangunan</th>
                            <th class="p-2 w-[20%]">Bangunan & Fungsi</th>
                            <th class="p-2 w-[15%]">Spesifikasi</th>
                            <th class="p-2 w-[15%]">Lokasi & Tanah</th>
                            <th class="p-2 text-center w-12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-slate-200">
                        <?php if(!empty($pbg_data)): ?>
                            <?php foreach($pbg_data as $index => $row): ?>
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="p-2 text-center font-medium text-slate-400 align-top"><?= $index + 1 ?></td>
                                <td class="p-2 align-top">
                                    <div class="font-bold text-teal-400 font-mono text-xs leading-tight"><?= htmlspecialchars($row->NomorSkPbg ? $row->NomorSkPbg : '-') ?></div>
                                    <div class="text-[10px] text-slate-400 mt-1 leading-tight">
                                        <b>Ref:</b> <?= htmlspecialchars($row->NomorPermohonan ? $row->NomorPermohonan : '-') ?>
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="font-bold text-white text-xs leading-tight"><?= htmlspecialchars($row->NamaPemilikBangunan) ?></div>
                                    <div class="text-[10px] text-slate-400 flex items-start gap-1 mt-1 leading-tight">
                                        <i data-lucide="map-pin" class="w-2.5 h-2.5 text-slate-500 mt-0.5 shrink-0"></i>
                                        <span class="line-clamp-2"><?= htmlspecialchars($row->AlamatPemilikBangunan ? $row->AlamatPemilikBangunan : '-') ?></span>
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="font-semibold text-slate-200 text-xs leading-tight line-clamp-2"><?= htmlspecialchars($row->NamaBangunanGedung ? $row->NamaBangunanGedung : '-') ?></div>
                                    <div class="text-[10px] text-slate-400 mt-1 leading-tight">
                                        <span class="inline-block px-1.5 py-0.5 mb-1 rounded bg-slate-800 border border-slate-700 text-teal-300">
                                            <?= htmlspecialchars($row->FungsiBangunanGedung ? $row->FungsiBangunanGedung : '-') ?>
                                        </span>
                                        <div class="text-slate-400"><?= htmlspecialchars($row->GunaBangunan ? '('.$row->GunaBangunan.')' : '') ?></div>
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="text-[10px] text-slate-300">
                                        <b>Luas:</b> <?= number_format((float)$row->TotalLuas, 2, ',', '.') ?> m²
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        <b>Lt:</b> <?= $row->JumlahLantaiBangunan ?> | <b>T:</b> <?= $row->TinggiBangunanGedung ?>m
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="text-[10px] text-slate-200 truncate">
                                        <b>Kec:</b> <?= htmlspecialchars($row->Distrik ? $row->Distrik : '-') ?>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 truncate">
                                        <b>Desa:</b> <?= htmlspecialchars($row->KelurahanDesa ? $row->KelurahanDesa : '-') ?>
                                    </div>
                                </td>
                                <td class="p-2 text-center align-top">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="editData(<?= $row->id ?>)" class="p-1 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 rounded transition-colors border border-amber-500/20" title="Edit Data">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                        <button onclick="deleteData(<?= $row->id ?>)" class="p-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded transition-colors border border-rose-500/20" title="Hapus Data">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
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
</div>

<!-- MODAL FORM INPUT & EDIT PBG -->
<div id="modalPbg" class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-[#1e2d4a] border border-slate-700/80 rounded-xl w-full max-w-4xl shadow-2xl my-8 overflow-hidden transform transition-all">
        
        <div class="flex items-center justify-between p-4 border-b border-slate-700/70 bg-[#0f172a]/50">
            <h3 id="modalTitle" class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="file-plus" class="w-4 h-4 text-teal-400"></i>
                Tambah Data PBG
            </h3>
            <button onclick="closeModal()" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/50 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formPbg" onsubmit="submitForm(event)" class="p-4 sm:p-6 space-y-5 max-h-[75vh] overflow-y-auto">
            <input type="hidden" id="pbg_id" name="id">

            <!-- BAGIAN 1 -->
            <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-teal-400 uppercase tracking-wider border-b border-slate-700/60 pb-1 flex items-center gap-1.5">
                    <i data-lucide="user" class="w-3 h-3"></i> Data Dokumen & Pemilik Bangunan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Nomor Permohonan</label>
                        <input type="text" id="NomorPermohonan" name="NomorPermohonan" placeholder="Contoh: PBG-910901-..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Nomor SK PBG</label>
                        <input type="text" id="NomorSkPbg" name="NomorSkPbg" placeholder="Contoh: SK-PBG-910901-..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Tahun Dokumen <span class="text-rose-400">*</span></label>
                        <input type="number" id="Tahun" name="Tahun" value="<?= isset($tahun_filter) ? $tahun_filter : date('Y') ?>" min="2010" max="2099" required class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Nama Pemilik Bangunan <span class="text-rose-400">*</span></label>
                        <input type="text" id="NamaPemilikBangunan" name="NamaPemilikBangunan" required placeholder="Nama lengkap pemilik/perusahaan..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Alamat Pemilik Bangunan</label>
                        <textarea id="AlamatPemilikBangunan" name="AlamatPemilikBangunan" rows="1" placeholder="Alamat lengkap pemilik..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2 -->
            <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-teal-400 uppercase tracking-wider border-b border-slate-700/60 pb-1 flex items-center gap-1.5">
                    <i data-lucide="building" class="w-3 h-3"></i> Detail Bangunan Gedung
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Nama Bangunan Gedung</label>
                        <input type="text" id="NamaBangunanGedung" name="NamaBangunanGedung" placeholder="Contoh: Gedung Kantor PT. XXX / Ruko 2 Lantai" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Guna Bangunan</label>
                        <input type="text" id="GunaBangunan" name="GunaBangunan" placeholder="Usaha / Hunian / Keagamaan" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Fungsi Bangunan Gedung</label>
                        <input type="text" id="FungsiBangunanGedung" name="FungsiBangunanGedung" placeholder="Fungsi Usaha / Fungsi Hunian" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Sub Fungsi Bangunan</label>
                        <input type="text" id="SubFungsiBangunanGedung" name="SubFungsiBangunanGedung" placeholder="Pertokoan / Rumah Tinggal" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Klasifikasi Kompleksitas</label>
                        <input type="text" id="KlasifikasiKompleksitas" name="KlasifikasiKompleksitas" placeholder="Sederhana / Tidak Sederhana" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Kelas Bangunan</label>
                        <input type="text" id="KelasBangunan" name="KelasBangunan" placeholder="Kelas 1a / Kelas 2" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3 -->
            <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-teal-400 uppercase tracking-wider border-b border-slate-700/60 pb-1 flex items-center gap-1.5">
                    <i data-lucide="ruler" class="w-3 h-3"></i> Spesifikasi Ukuran & Fisik Bangunan
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Total Luas (m²)</label>
                        <input type="number" step="0.01" id="TotalLuas" name="TotalLuas" placeholder="0.00" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Luas Lantai (m²)</label>
                        <input type="number" step="0.01" id="LuasLantai" name="LuasLantai" placeholder="0.00" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Luas Basemen (m²)</label>
                        <input type="number" step="0.01" id="LuasBasemen" name="LuasBasemen" placeholder="0.00" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Jumlah Lantai</label>
                        <input type="number" id="JumlahLantaiBangunan" name="JumlahLantaiBangunan" placeholder="1" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Tinggi Bangunan (m)</label>
                        <input type="number" step="0.01" id="TinggiBangunanGedung" name="TinggiBangunanGedung" placeholder="0.00" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Jumlah Unit</label>
                        <input type="number" id="JumlahUnitBangunan" name="JumlahUnitBangunan" placeholder="1" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Lapis Basemen</label>
                        <input type="number" id="JumlahLapisBasemen" name="JumlahLapisBasemen" placeholder="0" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 4 -->
            <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-teal-400 uppercase tracking-wider border-b border-slate-700/60 pb-1 flex items-center gap-1.5">
                    <i data-lucide="map" class="w-3 h-3"></i> Data Tanah & Lokasi
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Status Di Atas Tanah</label>
                        <input type="text" id="DiatasTanah" name="DiatasTanah" placeholder="Tanah Hak Milik / HGB" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Luas Tanah (m²)</label>
                        <input type="number" step="0.01" id="LuasTanah" name="LuasTanah" placeholder="0.00" class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Pemilik Tanah</label>
                        <input type="text" id="PemilikTanah" name="PemilikTanah" placeholder="Nama Pemilik Tanah..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Kelurahan / Desa</label>
                        <input type="text" id="KelurahanDesa" name="KelurahanDesa" placeholder="Kelurahan / Desa..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Distrik</label>
                        <input type="text" id="Distrik" name="Distrik" placeholder="Nama Distrik..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-300 mb-1">Alamat Tanah</label>
                        <textarea id="AlamatTanah" name="AlamatTanah" rows="1" placeholder="Alamat lengkap lokasi tanah..." class="w-full bg-[#0f172a] border border-slate-700 rounded-md px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-teal-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- MODAL FOOTER -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-700/70">
                <button type="button" onclick="closeModal()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-[10px] font-semibold rounded-md transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSave" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-[10px] font-semibold rounded-md transition-colors shadow-lg flex items-center gap-1.5">
                    <i data-lucide="save" class="w-3 h-3"></i>
                    Simpan Data
                </button>
            </div>
        </form>

    </div>
</div>

<!-- LIBRARIES -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<!-- jsPDF & AutoTable for PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<!-- ExcelJS for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

<!-- Gaya Khusus DataTables -->
<style>
    .dataTables_wrapper .dataTables_length select {
        background-color: #0f172a; border: 1px solid #334155; color: #e2e8f0; border-radius: 0.375rem; padding: 0.1rem 0.5rem; font-size: 0.75rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        background-color: #0f172a; border: 1px solid #334155; color: #e2e8f0; border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate {
        font-size: 0.75rem; color: #94a3b8 !important; margin-top: 0.75rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #94a3b8 !important; padding: 0.2rem 0.6rem; border-radius: 0.25rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0f172a !important; color: #2dd4bf !important; border-color: #334155 !important;
    }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.75rem; color: #cbd5e1; font-size: 0.75rem;
    }
</style>

<script>
    const currentTahunFilter = $('#filterTahun').val();
    const dataPbgExport = <?= json_encode(!empty($pbg_data) ? $pbg_data : []) ?>;
    const monthNamesIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const KADIN_NAMA = <?= json_encode(!empty($_SESSION['kadin_nama']) ? $_SESSION['kadin_nama'] : 'Marselino Mameyao, SKM') ?>;
    const KADIN_PANGKAT = <?= json_encode(!empty($_SESSION['kadin_pangkat']) ? $_SESSION['kadin_pangkat'] : 'Pembina TK. I') ?>;
    const KADIN_NIP = <?= json_encode(!empty($_SESSION['kadin_nip']) ? 'NIP : ' . $_SESSION['kadin_nip'] : 'NIP : 196805141989111002') ?>;

    $(document).ready(function() {
        lucide.createIcons();

        if ($.fn.DataTable) {
            $('#tablePbg').DataTable({
                responsive: false,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                pageLength: 10,
                language: {
                    search: "Cari Data:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Data tidak tersedia",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
            });
        }
    });

    async function downloadExcel() {
        if (typeof window.ExcelJS === 'undefined') {
            Swal.fire('Error', 'Library ExcelJS belum termuat.', 'error'); return;
        }

        const workbook = new window.ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Data PBG');
        const lastColLetter = 'X'; // 24 Columns (A-X) after removing Tahun

        // Headers
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
        
        for(let i = 1; i <= 24; i++) worksheet.getCell(5, i).border = { bottom: { style: 'double' } };
        worksheet.addRow([]); 

        worksheet.addRow([`REKAPITULASI DATA PERSETUJUAN BANGUNAN GEDUNG (PBG)`]);
        worksheet.mergeCells(`A7:${lastColLetter}7`);
        worksheet.getCell('A7').font = { name: 'Arial', size: 12, bold: true };
        worksheet.getCell('A7').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.addRow([`TAHUN ${currentTahunFilter}`]);
        worksheet.mergeCells(`A8:${lastColLetter}8`);
        worksheet.getCell('A8').font = { name: 'Arial', size: 11, bold: true };
        worksheet.getCell('A8').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.addRow([]); 

        // Kolom TAHUN dihapus dari headers
        let headers = [
            'NO', 'NO. PERMOHONAN', 'NO. SK PBG', 'NAMA PEMILIK BANGUNAN', 'ALAMAT PEMILIK',
            'NAMA BANGUNAN', 'GUNA BANGUNAN', 'FUNGSI BANGUNAN', 'SUB FUNGSI',
            'KLASIFIKASI', 'KELAS', 'TOTAL LUAS', 'LUAS LANTAI', 'LUAS BASEMEN',
            'JML LANTAI', 'TINGGI (m)', 'JML UNIT', 'JML LAPIS BASEMEN',
            'STATUS TANAH', 'LUAS TANAH', 'PEMILIK TANAH', 'KELURAHAN/DESA', 'DISTRIK', 'ALAMAT TANAH'
        ];
        const headerRow = worksheet.addRow(headers);
        
        headerRow.eachCell((cell) => {
            cell.font = { name: 'Arial', size: 10, bold: true };
            cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF92CDDC' } };
            cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
        });

        if (dataPbgExport.length > 0) {
            dataPbgExport.forEach((item, index) => {
                // Kolom item.Tahun dihapus dari rowData
                const rowData = [
                    (index + 1),
                    (item.NomorPermohonan || '-'),
                    (item.NomorSkPbg || '-'),
                    (item.NamaPemilikBangunan || '-'),
                    (item.AlamatPemilikBangunan || '-'),
                    (item.NamaBangunanGedung || '-'),
                    (item.GunaBangunan || '-'),
                    (item.FungsiBangunanGedung || '-'),
                    (item.SubFungsiBangunanGedung || '-'),
                    (item.KlasifikasiKompleksitas || '-'),
                    (item.KelasBangunan || '-'),
                    (item.TotalLuas || '-'),
                    (item.LuasLantai || '-'),
                    (item.LuasBasemen || '-'),
                    (item.JumlahLantaiBangunan || '-'),
                    (item.TinggiBangunanGedung || '-'),
                    (item.JumlahUnitBangunan || '-'),
                    (item.JumlahLapisBasemen || '-'),
                    (item.DiatasTanah || '-'),
                    (item.LuasTanah || '-'),
                    (item.PemilikTanah || '-'),
                    (item.KelurahanDesa || '-'),
                    (item.Distrik || '-'),
                    (item.AlamatTanah || '-')
                ];
                const dataRow = worksheet.addRow(rowData);
                
                dataRow.eachCell((cell, colNumber) => {
                    cell.font = { name: 'Arial', size: 10 };
                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                    cell.alignment = { vertical: 'middle', horizontal: (colNumber === 1) ? 'center' : 'left', wrapText: true };
                });
            });
        } else {
            let emptyArr = ['Belum ada data'];
            for(let i=1; i<24; i++) emptyArr.push('');
            const emptyRow = worksheet.addRow(emptyArr);
            worksheet.mergeCells(`A${emptyRow.number}:${lastColLetter}${emptyRow.number}`);
            emptyRow.getCell(1).alignment = { horizontal: 'center' };
        }

        worksheet.addRow([]); worksheet.addRow([]);
        
        const now = new Date();
        const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
        const addSigLine = (text, bold = false) => {
            const row = worksheet.addRow([]);
            const cell = row.getCell(21); // Posisi ttd digeser ke kolom 21 (U) karena kolom berkurang
            cell.value = text;
            cell.font = { name: 'Arial', size: 11, bold: bold };
            cell.alignment = { horizontal: 'left' };
        };

        addSigLine(`TIMIKA, ${dateStr}`);
        addSigLine('Kepala Dinas,');
        addSigLine('Penanaman Modal dan Pelayanan Terpadu');
        addSigLine('Satu Pintu Kabupaten Mimika,');
        
        worksheet.addRow([]); worksheet.addRow([]); worksheet.addRow([]);

        addSigLine(KADIN_NAMA, true);
        addSigLine(KADIN_PANGKAT);
        addSigLine(KADIN_NIP);

        // Column widths - disesuaikan (24 kolom)
        worksheet.columns = [
            { width: 5 }, { width: 25 }, { width: 25 }, { width: 25 }, { width: 30 }, // A-E
            { width: 25 }, { width: 15 }, { width: 20 }, { width: 15 }, // F-I
            { width: 15 }, { width: 10 }, { width: 12 }, { width: 12 }, { width: 12 }, // J-N
            { width: 10 }, { width: 10 }, { width: 10 }, { width: 15 }, // O-R
            { width: 15 }, { width: 12 }, { width: 20 }, { width: 15 }, { width: 15 }, { width: 30 } // S-X
        ];

        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = `Rekap_Data_PBG_${currentTahunFilter}.xlsx`;
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
            allowOutsideClick: false, background: '#0f172a', color: '#fff',
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a3'); // A3 Landscape for max width
            const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
            
            try {
                const logoBase64 = await getBase64ImageFromURL(logoUrl);
                doc.addImage(logoBase64, 'PNG', 40, 6, 22, 28);
            } catch (e) { console.warn("Logo gagal dimuat."); }

            // Header - Centered for A3 (Width is 420mm, Center is 210)
            doc.setTextColor(0, 0, 0); doc.setFont("helvetica", "bold"); doc.setFontSize(15);
            doc.text("PEMERINTAH KABUPATEN MIMIKA", 210, 15, { align: "center" });
            doc.setFontSize(13); doc.text("DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU", 210, 22, { align: "center" });
            doc.setFont("helvetica", "normal"); doc.setFontSize(9);
            doc.text("Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II", 210, 28, { align: "center" });
            doc.text("Tlp. (0901) 3262943 Timika - Papua Pos 99910", 210, 33, { align: "center" });

            doc.setDrawColor(0, 0, 0); doc.setLineWidth(1.0); doc.line(15, 37, 405, 37);
            doc.setLineWidth(0.3); doc.line(15, 38.5, 405, 38.5);

            doc.setFont("helvetica", "bold"); doc.setFontSize(11);
            doc.text("REKAPITULASI DATA PERSETUJUAN BANGUNAN GEDUNG (PBG)", 210, 47, { align: "center" });
            doc.setFontSize(9); doc.text(`TAHUN ${currentTahunFilter}`, 210, 52, { align: "center" });
            
            // Kolom THN dihapus
            let tableHeaders = [
                'NO', 'NO. PERMOHONAN', 'NO. SK PBG', 'PEMILIK', 'ALAMAT PEMILIK',
                'BANGUNAN', 'GUNA BGN', 'FUNGSI', 'SUB FUNGSI',
                'KLASIFIKASI', 'KELAS', 'L.TOTAL', 'L.LANTAI', 'L.BASEMEN',
                'JML LT', 'T (m)', 'JML UNIT', 'LPS BASEMEN',
                'STS TANAH', 'L.TANAH', 'PEMILIK TNH', 'KEL/DESA', 'DISTRIK', 'ALAMAT TANAH'
            ];
            
            let tableBody = [];
            if (dataPbgExport.length > 0) {
                dataPbgExport.forEach((item, index) => {
                    // item.Tahun dihapus dari push array
                    tableBody.push([
                        (index + 1).toString(),
                        (item.NomorPermohonan || '-'),
                        (item.NomorSkPbg || '-'),
                        (item.NamaPemilikBangunan || '-'),
                        (item.AlamatPemilikBangunan || '-'),
                        (item.NamaBangunanGedung || '-'),
                        (item.GunaBangunan || '-'),
                        (item.FungsiBangunanGedung || '-'),
                        (item.SubFungsiBangunanGedung || '-'),
                        (item.KlasifikasiKompleksitas || '-'),
                        (item.KelasBangunan || '-'),
                        (item.TotalLuas || '-'),
                        (item.LuasLantai || '-'),
                        (item.LuasBasemen || '-'),
                        (item.JumlahLantaiBangunan || '-'),
                        (item.TinggiBangunanGedung || '-'),
                        (item.JumlahUnitBangunan || '-'),
                        (item.JumlahLapisBasemen || '-'),
                        (item.DiatasTanah || '-'),
                        (item.LuasTanah || '-'),
                        (item.PemilikTanah || '-'),
                        (item.KelurahanDesa || '-'),
                        (item.Distrik || '-'),
                        (item.AlamatTanah || '-')
                    ]);
                });
            } else {
                tableBody.push([{ content: 'Belum ada data PBG.', colSpan: 24, styles: { halign: 'center' } }]);
            }

            doc.autoTable({
                startY: 57, head: [tableHeaders], body: tableBody, theme: 'grid',
                headStyles: { fillColor: [146, 205, 220], textColor: [0, 0, 0], fontStyle: 'bold', halign: 'center', valign: 'middle', lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 5 },
                bodyStyles: { textColor: [0, 0, 0], lineWidth: 0.2, lineColor: [0, 0, 0], fontSize: 5, valign: 'top', cellPadding: 1 },
                margin: { top: 15, right: 10, bottom: 20, left: 10 }
            });

            const finalY = doc.lastAutoTable.finalY + 10;
            const now = new Date();
            const dateStr = `${now.getDate()} ${monthNamesIndo[now.getMonth()]} ${now.getFullYear()}`;
            const rightMargin = 330; 

            if (finalY > 260) doc.addPage();
            let signY = finalY > 260 ? 20 : finalY;

            doc.setFont("helvetica", "normal"); doc.setFontSize(9);
            doc.text(`TIMIKA, ${dateStr}`, rightMargin, signY);
            doc.text("Kepala Dinas,", rightMargin, signY + 5);
            doc.text("Penanaman Modal dan Pelayanan Terpadu", rightMargin, signY + 10);
            doc.text("Satu Pintu Kabupaten Mimika,", rightMargin, signY + 15);

            const signatureY = signY + 38;
            doc.setFont("helvetica", "bold"); 
            doc.text(KADIN_NAMA, rightMargin, signatureY);
            doc.setFont("helvetica", "normal");
            doc.text(KADIN_PANGKAT, rightMargin, signatureY + 5);
            doc.text(KADIN_NIP, rightMargin, signatureY + 10);

            doc.save(`Rekap_Data_PBG_${currentTahunFilter}.pdf`);
            Swal.close();
        } catch (error) {
            console.error(error);
            Swal.fire({icon: 'error', title: 'Gagal', text: 'Sistem gagal membuat PDF.', background: '#0f172a', color: '#fff'});
        }
    }

    function openModal() {
        $('#formPbg')[0].reset();
        $('#pbg_id').val('');
        $('#Tahun').val(currentTahunFilter); // Default to current filter year
        $('#modalTitle').html('<i data-lucide="file-plus" class="w-4 h-4 text-teal-400"></i> Tambah Data PBG');
        $('#modalPbg').removeClass('hidden');
        lucide.createIcons();
    }

    function closeModal() {
        $('#modalPbg').addClass('hidden');
    }

    function editData(id) {
        $.ajax({
            url: "<?= base_url('Admin/get_pbg/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(res) {
                if (res.status === 'success') {
                    const data = res.data;
                    $('#pbg_id').val(data.id);
                    $('#NomorPermohonan').val(data.NomorPermohonan);
                    $('#NomorSkPbg').val(data.NomorSkPbg);
                    $('#NamaPemilikBangunan').val(data.NamaPemilikBangunan);
                    $('#AlamatPemilikBangunan').val(data.AlamatPemilikBangunan);
                    $('#GunaBangunan').val(data.GunaBangunan);
                    $('#NamaBangunanGedung').val(data.NamaBangunanGedung);
                    $('#FungsiBangunanGedung').val(data.FungsiBangunanGedung);
                    $('#SubFungsiBangunanGedung').val(data.SubFungsiBangunanGedung);
                    $('#KlasifikasiKompleksitas').val(data.KlasifikasiKompleksitas);
                    $('#KelasBangunan').val(data.KelasBangunan);
                    $('#TotalLuas').val(data.TotalLuas);
                    $('#LuasLantai').val(data.LuasLantai);
                    $('#LuasBasemen').val(data.LuasBasemen);
                    $('#JumlahLantaiBangunan').val(data.JumlahLantaiBangunan);
                    $('#TinggiBangunanGedung').val(data.TinggiBangunanGedung);
                    $('#JumlahUnitBangunan').val(data.JumlahUnitBangunan);
                    $('#JumlahLapisBasemen').val(data.JumlahLapisBasemen);
                    $('#DiatasTanah').val(data.DiatasTanah);
                    $('#LuasTanah').val(data.LuasTanah);
                    $('#PemilikTanah').val(data.PemilikTanah);
                    $('#AlamatTanah').val(data.AlamatTanah);
                    $('#KelurahanDesa').val(data.KelurahanDesa);
                    $('#Distrik').val(data.Distrik);
                    $('#Tahun').val(data.Tahun);

                    $('#modalTitle').html('<i data-lucide="edit-3" class="w-4 h-4 text-amber-400"></i> Edit Data PBG');
                    $('#modalPbg').removeClass('hidden');
                    lucide.createIcons();
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Terjadi kesalahan saat mengambil data.', 'error');
            }
        });
    }

    function submitForm(e) {
        e.preventDefault();
        
        $('#btnSave').prop('disabled', true).addClass('opacity-50');

        $.ajax({
            url: "<?= base_url('Admin/save_pbg') ?>",
            type: "POST",
            data: $('#formPbg').serialize(),
            dataType: "JSON",
            success: function(res) {
                $('#btnSave').prop('disabled', false).removeClass('opacity-50');
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function() {
                $('#btnSave').prop('disabled', false).removeClass('opacity-50');
                Swal.fire('Error!', 'Gagal menghubungkan ke server.', 'error');
            }
        });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data PBG yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('Admin/delete_pbg/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    }
</script>
</body>
</html>