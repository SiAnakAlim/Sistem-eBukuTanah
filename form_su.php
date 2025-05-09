<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Form Peminjaman Buku Tanah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-label {
            font-size: 16px;
            color: #555;
            font-weight: bold; /* Menebalkan label */
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .signature-box {
            cursor: pointer;
            background-color: #f8f9fa;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .signature-box p {
            color: #888;
            font-size: 14px;
        }

        .signature-box img {
            max-width: 100%;
        }

        .modal-body canvas {
            border: 1px solid #000;
            border-radius: 5px;
        }

        .button-group { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn-save { background: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .btn-save:hover { background: #0056b3; }
        .btn-back { background: #6c757d; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; text-align: center; }
        .btn-back:hover { background: #545b62; }

        .calendar-date {
            cursor: pointer;
            font-size: 16px;
            color: #007bff;
            font-weight: bold;
            padding: 8px 20px;
            border: 2px solid transparent;
            border-radius: 30px;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .calendar-date:hover {
            background-color: #e7f1ff;
            border-color: #0056b3;
        }

        .calendar-date.selected {
            background-color: #28a745;
            color: white;
            border-color: #218838;
        }

        .input-info {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .file-info {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .signature-box img {
            max-width: 100%;
        }

        .info-wrapper {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .info-wrapper .calendar-date {
            font-weight: 400;
        }

        .info-wrapper .small-info {
            margin-left: 10px;
            font-size: 14px;
            color: #6c757d;
        }

        .form-control, .btn {
            border-radius: 8px;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 15px;
            }
        }

        .file-input-wrapper {
            margin-top: 15px;
        }

        .file-input-wrapper input {
            font-size: 14px;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Form Peminjaman Surat Ukur</h2>
        <form action="proses_peminjaman_su.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 form-group">
                <label for="tanggal_hari" class="form-label">Hari dan Tanggal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tanggal_hari" name="tanggal_hari" placeholder="Contoh: Senin, 17 Februari 2025" required>
                <i class="bi bi-calendar"></i>
                <div id="calendar-info" class="info-wrapper" style="margin-top: -3px;"> 
                    <span class="calendar-date" data-placeholder="Contoh: Senin, 17 Februari 2025">Sesuai dengan hari ini?</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                <small class="input-info">Tanggal yang terpilih akan otomatis disesuaikan dengan hari ini.</small>
            </div>

            <div class="mb-3">
                <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali">
            </div>
            <div class="mb-3">
                <label for="jenis_hak" class="form-label">Berkas dan Nomor <span class="text-danger">*</span></label>
                <div id="jenisHakContainer">
                    <div class="input-group mb-2 jenis-hak-item">
                        <input type="text" class="form-control" name="jenis_hak[]" required>
                        <button type="button" class="btn btn-danger removeJenisHak">-</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="addJenisHak">+</button>
            </div>
            


            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                <div class="file-input-wrapper">
                    <input type="file" class="form-control mt-2" id="file_keterangan" name="file_keterangan" accept=".pdf, .jpg, .jpeg, .png" />
                    <small class="file-info">File yang diperbolehkan: PDF, JPG, PNG. Maksimal ukuran file: 2MB.</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="nama_peminjam" class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                <div class="signature-box" id="signature-box">
                    <p class="text-muted">Klik untuk tanda tangan</p>
                </div>
                <input type="hidden" name="tanda_tangan" id="tanda_tangan">
            </div>

            <div class="button-group">
                <button type="submit" class="btn-save">Simpan Data</button>
                <a href="index.php" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>

    <!-- Modal untuk tanda tangan -->
    <div class="modal fade" id="signatureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tanda Tangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <canvas id="signatureCanvas" width="400" height="200"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" id="clearSignature" class="btn btn-warning">Hapus</button>
                    <button type="button" id="confirmSignature" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const jenisHakContainer = document.getElementById("jenisHakContainer");
            const addJenisHakBtn = document.getElementById("addJenisHak");
        
            addJenisHakBtn.addEventListener("click", function () {
                const newInputGroup = document.createElement("div");
                newInputGroup.classList.add("input-group", "mb-2", "jenis-hak-item");
        
                newInputGroup.innerHTML = `
                    <input type="text" class="form-control" name="jenis_hak[]" required>
                    <button type="button" class="btn btn-danger removeJenisHak">-</button>
                `;
        
                jenisHakContainer.appendChild(newInputGroup);
        
                // Tambahkan event listener untuk tombol hapus
                newInputGroup.querySelector(".removeJenisHak").addEventListener("click", function () {
                    newInputGroup.remove();
                });
            });
        
            // Event listener untuk menghapus input pertama jika ada tombol `-` yang ditekan
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("removeJenisHak")) {
                    if (document.querySelectorAll(".jenis-hak-item").length > 1) {
                        e.target.parentElement.remove();
                    }
                }
            });
        });
        </script>
        

    <script>
        document.getElementById('signature-box').addEventListener('click', function () {
            new bootstrap.Modal(document.getElementById('signatureModal')).show();
        });

        let canvas = document.getElementById('signatureCanvas');
        let ctx = canvas.getContext('2d');
        let drawing = false;

        canvas.addEventListener('mousedown', (e) => {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mouseup', () => drawing = false);
        canvas.addEventListener('mouseleave', () => drawing = false);

        canvas.addEventListener('mousemove', (e) => {
            if (!drawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        });

        document.getElementById('confirmSignature').addEventListener('click', function () {
            let imageData = canvas.toDataURL('image/png');
            document.getElementById('tanda_tangan').value = imageData;
            document.getElementById('signature-box').innerHTML = '<img src="' + imageData + '" width="200">';
            bootstrap.Modal.getInstance(document.getElementById('signatureModal')).hide();
        });

        document.getElementById('clearSignature').addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        // Fungsi untuk format tanggal
        function formatDate(date) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[date.getDay()];
            const day = date.getDate();
            const monthName = months[date.getMonth()];
            const year = date.getFullYear();

            return `${dayName}, ${day} ${monthName} ${year}`;
        }

        const today = new Date();
const formattedDate = formatDate(today);
const tanggalHariInput = document.getElementById('tanggal_hari');
const calendarDateSpan = document.getElementById('calendar-info').querySelector('.calendar-date');

// Set placeholder dan nilai awal input
tanggalHariInput.placeholder = calendarDateSpan.dataset.placeholder; // ambil dari data-placeholder
tanggalHariInput.value = ""; // nilai input kosong

// Mengubah nilai kolom saat tanggal di klik
calendarDateSpan.addEventListener('click', function() {
    tanggalHariInput.value = formattedDate;
    this.classList.add('selected');
});

// Mengaktifkan input tanggal_hari untuk diketik manual
document.getElementById('tanggal_hari').removeAttribute('readonly');

        // Set default tanggal pinjam dengan tanggal hari ini
        document.getElementById('tanggal_pinjam').valueAsDate = today;

        // Validasi ukuran file maksimum 2MB
        document.getElementById('file_keterangan').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) { // 2MB
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                e.target.value = ''; // Clear file input
            }
        });
    </script>
</body>
</html>
