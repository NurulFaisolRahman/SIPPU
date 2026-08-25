    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#0a1633] via-[#030816] to-[#030816]">
        
        <!-- HEADER TOP BAR -->
        <header class="h-[75px] bg-[#050e1d]/80 backdrop-blur-md border-b border-[#16243d] flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-blue-400 transition-colors">SIPPU</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 mx-2 opacity-50"></i>
                    <span class="text-white">Kelola Jenis Perizinan</span>
                </div>
            </div>
        </header>

        <!-- SCROLLABLE CONTENT BODY -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            
            <div class="bg-gradient-to-r from-blue-900 to-[#0a1324] border border-blue-800/50 rounded-xl p-6 mb-6 flex flex-col sm:flex-row items-center justify-between shadow-lg shadow-blue-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                    <i data-lucide="shield" class="w-48 h-48"></i>
                </div>
                <div class="relative z-10 text-center sm:text-left mb-4 sm:mb-0">
                    <h2 class="text-2xl font-bold text-white mb-1">Manajemen Jenis Perizinan</h2>
                    <p class="text-sm text-blue-200">Kelola dan update daftar tipe perizinan yang terdaftar pada sistem.</p>
                </div>
                <div class="relative z-10 flex gap-4 text-center">
                    <div class="bg-[#050e1d]/50 backdrop-blur-sm border border-blue-500/30 px-4 py-2 rounded-lg">
                        <div class="text-[10px] text-blue-300 uppercase tracking-widest font-semibold mb-0.5">Total Data</div>
                        <div class="text-xl font-bold text-white"><?= !empty($jenis_izin_data) ? count($jenis_izin_data) : '0' ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-panel flex flex-col">
                <!-- Toolbar Atas Tabel -->
                <div class="p-5 border-b border-[#1e2d4a] flex flex-col md:flex-row md:items-center justify-end gap-4 bg-[#081122] rounded-t-[0.75rem]">
                    <button onclick="downloadPDF()" class="flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)]">
                        <i data-lucide="file-down" class="w-4 h-4"></i> Download Data Perizinan
                    </button>
                    <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)] hover:shadow-[0_0_20px_rgba(37,99,235,0.5)]">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Data Baru
                    </button>
                </div>

                <!-- Tabel Data -->
                <div class="overflow-x-auto">
                    <!-- Sesuai Desain: Memiliki Tepat 5 Kolom pada <thead> dan <tbody> -->
                    <table id="tabelIzin" class="w-full text-left text-[11px] text-slate-300">
                        <thead class="text-[10px] uppercase bg-[#050e1d] text-slate-400 border-b border-[#1e2d4a]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-semibold w-12 text-center whitespace-nowrap">No</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Nama Jenis Perizinan</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Tgl Input</th>
                                <th scope="col" class="px-4 py-3 font-semibold whitespace-nowrap">Terakhir Update</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-center w-28 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e2d4a]/50">
                            <?php if(!empty($jenis_izin_data)): ?>
                                <?php foreach($jenis_izin_data as $index => $row): ?>
                                <tr class="hover:bg-[#0c1833] transition-colors">
                                    <!-- Kolom 1 -->
                                    <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $index + 1 ?></td>
                                    <!-- Kolom 2 -->
                                    <td class="px-4 py-3 text-white font-medium whitespace-normal break-words max-w-[200px] md:max-w-[300px]">
                                        <?= htmlspecialchars($row->JenisIzin) ?>
                                    </td>
                                    <!-- Kolom 3 -->
                                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap"><?= date('d M Y, H:i', strtotime($row->InputAt)) ?></td>
                                    <!-- Kolom 4 -->
                                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                                        <?= !empty($row->UpdatedAt) ? date('d M Y, H:i', strtotime($row->UpdatedAt)) : '-' ?>
                                    </td>
                                    <!-- Kolom 5: Tombol Aksi -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editIzin(<?= $row->id ?>)" class="p-1.5 text-blue-400 hover:text-white hover:bg-blue-600 rounded transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="deleteIzin(<?= $row->id ?>)" class="p-1.5 text-red-400 hover:text-white hover:bg-red-600 rounded transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500 italic">
                                        Belum ada data Jenis Perizinan yang tersimpan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div id="modalCRUD" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#030816]/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
        
        <!-- Modal Konten -->
        <div id="modalContent" class="relative w-full max-w-[450px] bg-[#0b172e] border border-[#1e2d4a] rounded-xl shadow-2xl flex flex-col transition-all duration-300 modal-leave">
            
            <div class="flex items-center justify-between p-5 border-b border-[#1e2d4a]/80 bg-[#071126] rounded-t-xl">
                <h3 id="modalTitle" class="text-white font-bold text-sm tracking-wide">Tambah Jenis Perizinan</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white bg-slate-800/50 hover:bg-slate-700/50 p-1.5 rounded-md transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <form id="formIzin" onsubmit="saveData(event)" class="p-6 flex flex-col gap-4">
                <!-- ID Hidden Input untuk Deteksi Tambah atau Edit -->
                <input type="hidden" id="inputId" name="id" value="">

                <div class="space-y-1.5">
                    <label for="inputJenisIzin" class="text-[11px] font-semibold text-slate-300 uppercase tracking-wider block">Nama Jenis Perizinan</label>
                    <input type="text" id="inputJenisIzin" name="JenisIzin" class="w-full bg-[#050e1d] border border-[#1e2d4a] text-white text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition-all placeholder:text-slate-600" placeholder="Contoh: Surat Izin Usaha Perdagangan (SIUP)" required>
                </div>

                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitForm" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-colors shadow-lg shadow-blue-900/40 border border-blue-500/50 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function refreshIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        $(document).ready(function() {
            refreshIcons();

            // Inisialisasi DataTables
            if ($.fn.DataTable) {
                $('#tabelIzin').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                        infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                        infoFiltered: "(difilter dari _MAX_ total entri)",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "Lanjut",
                            previous: "Kembali"
                        }
                    }
                });
            }
        });

        function openModal(isEdit = false) {
            $('#modalCRUD').removeClass('hidden');
            setTimeout(() => {
                $('#modalContent').removeClass('modal-leave').addClass('modal-enter');
            }, 10);

            if (!isEdit) {
                $('#modalTitle').text('Tambah Jenis Perizinan');
                $('#formIzin')[0].reset();
                $('#inputId').val(''); // Kosongkan ID untuk mode INSERT
            }
        }

        function closeModal() {
            $('#modalContent').removeClass('modal-enter').addClass('modal-leave');
            setTimeout(() => {
                $('#modalCRUD').addClass('hidden');
            }, 300); 
        }

        function editIzin(id) {
            $.ajax({
                url: `<?= base_url('Admin/get_izin/') ?>${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        $('#modalTitle').text('Edit Jenis Perizinan');
                        $('#inputId').val(res.data.id);
                        $('#inputJenisIzin').val(res.data.JenisIzin);
                        openModal(true); // Buka modal dalam mode Edit
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message || 'Data tidak ditemukan',
                            background: '#0b172e',
                            color: '#fff'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    handleAjaxError(xhr, status, error);
                }
            });
        }

        function saveData(event) {
            event.preventDefault();
            
            const $btnSubmit = $('#btnSubmitForm');
            const $form = $('#formIzin');
            if (!$btnSubmit.length || !$form.length) return;

            const originalText = $btnSubmit.html();
            $btnSubmit.html(`<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`);
            refreshIcons();
            $btnSubmit.prop('disabled', true);

            // Menampung form data menggunakan object native FormData
            const formData = new FormData($form[0]);
            
            $.ajax({
                url: '<?= base_url("Admin/save_izin") ?>',
                method: 'POST',
                data: formData,
                processData: false, // Penting agar jQuery tidak memproses instansi FormData menjadi string query
                contentType: false, // Penting agar header boundary multipart terkonfigurasi dengan benar
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(res) {
                    if (res && res.status === 'success') {
                        closeModal();
                        Swal.fire({
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: res.message,
                            background: '#0b172e', 
                            color: '#fff', 
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => location.reload()); 
                    } else {
                        Swal.fire({
                            icon: 'error', 
                            title: 'Gagal!', 
                            text: res ? res.message : 'Sistem gagal menyimpan data.', 
                            background: '#0b172e', 
                            color: '#fff'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    handleAjaxError(xhr, status, error);
                },
                complete: function() {
                    $btnSubmit.html(originalText);
                    refreshIcons();
                    $btnSubmit.prop('disabled', false);
                }
            });
        }

        function deleteIzin(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#1e2d4a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#0b172e', 
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('Admin/delete_izin/') ?>${id}`,
                        method: 'POST',
                        dataType: 'json',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(res) {
                            if (res && res.status === 'success') {
                                Swal.fire({
                                    icon: 'success', 
                                    title: 'Dihapus!', 
                                    text: res.message, 
                                    background: '#0b172e', 
                                    color: '#fff', 
                                    timer: 1500, 
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: res ? res.message : 'Gagal menghapus data.',
                                    background: '#0b172e',
                                    color: '#fff'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr, status, error);
                        }
                    });
                }
            });
        }

        function handleAjaxError(xhr, status, error) {
            console.error("AJAX Error Details:", xhr, status, error);
            let errorMessage = "Gagal memproses permintaan ke server. Silakan coba lagi.";
            
            // Mengatasi error parsing JSON jika server mengembalikan output kotor (HTML/Text error PHP)
            if (status === 'parsererror') {
                errorMessage = "Server tidak merespon dengan format JSON murni. Silakan periksa <b>Console Developer (F12)</b> untuk melihat log kesalahan PHP backend.";
                console.warn("Server Response Text:\n", xhr.responseText);
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Sistem',
                html: errorMessage,
                background: '#0b172e', 
                color: '#fff'
            });
        }

        
        // Simpan data array jenis izin dari PHP ke variabel JavaScript untuk membetuk rows PDF
        const dataIzinPDF = <?= !empty($jenis_izin_data) ? json_encode($jenis_izin_data) : '[]' ?>;

        // Fungsi Helper untuk mengambil Base64 gambar dari URL (Dibutuhkan jsPDF untuk menggambar Logo)
        function getBase64ImageFromURL(url) {
            return new Promise((resolve, reject) => {
                var img = new Image();
                img.setAttribute("crossOrigin", "anonymous");
                img.onload = () => {
                    var canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    var dataURL = canvas.toDataURL("image/png");
                    resolve(dataURL);
                };
                img.onerror = error => {
                    reject(error);
                };
                img.src = url;
            });
        }

        // Fungsi Utama Pembuatan Dokumen Laporan PDF
        async function downloadPDF() {
            Swal.fire({
                title: 'Menyiapkan Dokumen...',
                text: 'Membentuk layout PDF, mohon tunggu sebentar',
                allowOutsideClick: false,
                background: '#0b172e',
                color: '#fff',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                // Inisialisasi jsPDF mode Landscape, satuan millimeter, ukuran kertas A4
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape', 'mm', 'a4');

                // 1. Ambil & Gambar Logo Lambang Kabupaten Mimika
                const logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/10/Lambang_Kabupaten_Mimika.jpg';
                let logoBase64 = null;
                try {
                    logoBase64 = await getBase64ImageFromURL(logoUrl);
                } catch (e) {
                    console.warn("Gagal meload logo via canvas karena restriksi CORS dari server Wikimedia.");
                }

                if (logoBase64) {
                    // Penempatan Logo Kiri (x:15, y:10, width:22, height:28)
                    doc.addImage(logoBase64, 'PNG', 15, 10, 22, 28);
                }

                // 2. KOP SURAT (Pemerintah Kabupaten Mimika) - Teks berada ditengah
                doc.setTextColor(0, 0, 0);
                
                doc.setFont("helvetica", "bold");
                doc.setFontSize(16);
                doc.text("PEMERINTAH KABUPATEN MIMIKA", 148, 16, { align: "center" });
                
                doc.setFontSize(14);
                doc.text("DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU", 148, 23, { align: "center" });
                
                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.text("Jl. Poros Kuala Kencana Pusat Pemerintahan Gedung D Lantai II", 148, 29, { align: "center" });
                doc.text("Tlp. (0901) 3262943 Timika - Papua Pos 99910", 148, 34, { align: "center" });

                // 3. Garis Pembatas Header (Dua lapis: Tebal dan Tipis persis seperti format standard KOP)
                doc.setDrawColor(0, 0, 0);
                doc.setLineWidth(1.0);
                doc.line(15, 39, 282, 39);
                doc.setLineWidth(0.3);
                doc.line(15, 40.5, 282, 40.5);

                // 4. Judul Laporan Dokumen (Diberikan garis bawah)
                doc.setFont("helvetica", "bold");
                doc.setFontSize(11);
                const titleText = "REKAP JUMLAH PERIZINAN DAN NON PERIZINAN TAHUN 2026";
                doc.text(titleText, 148, 50, { align: "center" });
                
                const titleWidth = doc.getTextWidth(titleText);
                doc.setLineWidth(0.3);
                doc.line(148 - (titleWidth / 2), 51, 148 + (titleWidth / 2), 51);

                // 5. Pembentukan Data Rows Tabel
                let tableBody = [];
                if (dataIzinPDF && dataIzinPDF.length > 0) {
                    dataIzinPDF.forEach((item, index) => {
                        // Sesuai permintaan, Kolom Nama Izin dari Database, Kolom Bulan di set "0"
                        tableBody.push([
                            index + 1,
                            item.JenisIzin,
                            '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'
                        ]);
                    });
                } else {
                    tableBody.push(['', 'Belum ada data jenis perizinan di sistem', '', '', '', '', '', '', '', '', '', '', '', '', '']);
                }

                // 6. Membuat Tabel dengan gaya AutoTable
                doc.autoTable({
                    startY: 58,
                    head: [['NO', 'JENIS IJIN', 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES', 'TOTAL']],
                    body: tableBody,
                    theme: 'grid', // Membuat garis tabel full di semua sisi cell
                    headStyles: {
                        fillColor: [146, 205, 220], // Biru muda cyan (persis warna pada gambar unggahan)
                        textColor: [0, 0, 0],
                        fontStyle: 'bold',
                        halign: 'center',
                        valign: 'middle',
                        lineWidth: 0.2,
                        lineColor: [0, 0, 0]
                    },
                    bodyStyles: {
                        textColor: [0, 0, 0],
                        lineWidth: 0.2,
                        lineColor: [0, 0, 0],
                        fontSize: 9
                    },
                    columnStyles: {
                        0: { halign: 'center', cellWidth: 12 },   // Kolom Nomor
                        1: { cellWidth: 90 },                     // Kolom Jenis Izin (lebar diperbesar)
                        2: { halign: 'center' }, 3: { halign: 'center' }, 4: { halign: 'center' },
                        5: { halign: 'center' }, 6: { halign: 'center' }, 7: { halign: 'center' },
                        8: { halign: 'center' }, 9: { halign: 'center' }, 10: { halign: 'center' },
                        11: { halign: 'center' }, 12: { halign: 'center' }, 13: { halign: 'center' },
                        14: { halign: 'center', fontStyle: 'bold' } // Kolom Total
                    },
                    margin: { top: 15, right: 15, bottom: 15, left: 15 }
                });

                // 7. Simpan & Paksa Download File PDF di Browser Pengguna
                doc.save('Rekap_Data_Perizinan_Mimika.pdf');
                Swal.close();
                
            } catch (error) {
                console.error("Gagal Generate PDF:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Proses Gagal',
                    text: 'Terjadi kesalahan sistem saat menyusun dokumen PDF.',
                    background: '#0b172e',
                    color: '#fff'
                });
            }
        }
    </script>
</body>
</html>