<?php
session_start();
include '../assets/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['id_user'])) {
    header("location: ../login.php?pesan=wajib_login");
    exit;
}

$id_user = $_SESSION['id_user'];
$query_user = mysqli_query($koneksi, "SELECT * FROM frontliners WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query_user);

if (!$user) {
    echo "<script>alert('Data profil tidak ditemukan!'); window.location='../login.php';</script>";
    exit;
}

if (isset($_POST['submit_absensi'])) {
    $status_kehadiran = $_POST['status_kehadiran'];
    $sesi_ibadah = $_POST['sesi_ibadah'];
    $makna_rhema = mysqli_real_escape_string($koneksi, $_POST['makna_rhema']);
    $ayat_referensi = mysqli_real_escape_string($koneksi, $_POST['ayat_referensi']);
    $keterangan_izin = mysqli_real_escape_string($koneksi, $_POST['keterangan_izin'] ?? '');

    date_default_timezone_set('Asia/Jakarta');
    $jam_sekarang = date('H:i');
    $status_ketepatan = 'tepat waktu';
    
    if ($sesi_ibadah == 'SESI 1 (08.00)' && $jam_sekarang > '08:15') $status_ketepatan = 'terlambat';
    elseif ($sesi_ibadah == 'SESI 2 (10.00)' && $jam_sekarang > '10:15') $status_ketepatan = 'terlambat';
    elseif ($sesi_ibadah == 'SESI 3 (12.00)' && $jam_sekarang > '12:15') $status_ketepatan = 'terlambat';
    
    if ($status_kehadiran == 'tidak pelayanan') { $status_ketepatan = 'n/a'; }

    $query_absensi = "INSERT INTO absensi (id_user, status_kehadiran, sesi_ibadah, status_ketepatan, makna_rhema, ayat_referensi, keterangan_izin) 
                      VALUES ('$id_user', '$status_kehadiran', '$sesi_ibadah', '$status_ketepatan', '$makna_rhema', '$ayat_referensi', '$keterangan_izin')";
    
    if (mysqli_query($koneksi, $query_absensi)) {
        echo "<script>alert('Laporan Berhasil Terkirim!'); window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Absensi Frontliner | JC TEAM</title>
    <style>
        /* Memberi ruang lebih besar karena navbar baru lebih tinggi & melayang */
        body { padding-top: 120px; } 
    </style>
</head>
<body class="bg-[#0b0d17] text-white min-h-screen font-sans">

    <?php include 'include/navbar.php'; ?>

    <div class="max-w-2xl mx-auto px-6 py-12">
        <div class="flex justify-between items-center mb-10">
            <a href="index.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-white transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-right">
                <h1 class="text-2xl font-black italic uppercase tracking-tighter">Frontliner</h1>
                <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest">Absensi HOS</p>
            </div>
        </div>

        <form action="" method="POST" class="space-y-6">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 space-y-4">
                <p class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest border-b border-white/10 pb-2">Identitas</p>
                <input type="text" value="<?= $user['nama_lengkap'] ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 opacity-60 outline-none">
                <input type="text" value="<?= $user['bidang'] ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 opacity-60 outline-none">
            </div>

            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 space-y-6">
                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest border-b border-white/10 pb-2">Laporan Kegiatan</p>
                <select name="sesi_ibadah" id="sesi_select" required class="w-full bg-white/5 border border-orange-500/50 rounded-xl px-4 py-4 text-orange-400 outline-none appearance-none">
                    <option value="SESI 1 (08.00)">SESI 1 (08.00)</option>
                    <option value="SESI 2 (10.00)">SESI 2 (10.00)</option>
                    <option value="SESI 3 (12.00)">SESI 3 (12.00)</option>
                    <option value="STATUS: SAKIT">STATUS: SAKIT</option>
                    <option value="STATUS: IZIN KELUARGA">STATUS: IZIN KELUARGA</option>
                </select>
                <input type="hidden" name="status_kehadiran" id="status_kehadiran" value="pelayanan">
                <textarea name="makna_rhema" required placeholder="Makna Rhema..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 min-h-[120px] outline-none focus:border-cyan-500"></textarea>
                <input type="text" name="ayat_referensi" placeholder="Ayat Referensi..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 outline-none focus:border-cyan-500">
                <div id="izin_box" class="hidden">
                    <textarea name="keterangan_izin" placeholder="Alasan izin..." class="w-full bg-red-500/5 border border-red-500/20 rounded-xl px-4 py-4 outline-none"></textarea>
                </div>
            </div>

            <button type="submit" name="submit_absensi" class="w-full bg-white text-black font-black uppercase italic py-5 rounded-[2rem] hover:bg-cyan-400 transition-all active:scale-95">
                Kirim Laporan
            </button>
        </form>
    </div>

    <script>
        const selectSesi = document.getElementById('sesi_select');
        const boxIzin = document.getElementById('izin_box');
        const inputHadir = document.getElementById('status_kehadiran');

        selectSesi.addEventListener('change', function() {
            if (this.value.includes('STATUS:')) {
                boxIzin.classList.remove('hidden');
                inputHadir.value = 'tidak pelayanan';
            } else {
                boxIzin.classList.add('hidden');
                inputHadir.value = 'pelayanan';
            }
        });
    </script>
</body>
</html>