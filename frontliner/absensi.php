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
    $status_kehadiran = 'pelayanan';
    $sesi_ibadah = $_POST['sesi_ibadah'];
    $sesi_pelayanan = $_POST['sesi_pelayanan']; // Ambil dari input baru
    $makna_rhema = mysqli_real_escape_string($koneksi, $_POST['makna_rhema']);
    $ayat_referensi = mysqli_real_escape_string($koneksi, $_POST['ayat_referensi']);

    date_default_timezone_set('Asia/Jakarta');
    $jam_sekarang = date('H:i');
    $status_ketepatan = 'tepat waktu';
    
    if ($sesi_pelayanan == '1' && $jam_sekarang > '08:15') $status_ketepatan = 'terlambat';
    elseif ($sesi_pelayanan == '2' && $jam_sekarang > '10:15') $status_ketepatan = 'terlambat';
    elseif ($sesi_pelayanan == '3' && $jam_sekarang > '12:15') $status_ketepatan = 'terlambat';

    $query_absensi = "INSERT INTO absensi (id_user, status_kehadiran, sesi_ibadah, sesi_pelayanan, status_ketepatan, makna_rhema, ayat_referensi) 
                      VALUES ('$id_user', '$status_kehadiran', '$sesi_ibadah', '$sesi_pelayanan', '$status_ketepatan', '$makna_rhema', '$ayat_referensi')";
    
    if (mysqli_query($koneksi, $query_absensi)) {
        echo "<script>alert('Laporan Pelayanan Berhasil Terkirim!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
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
    <title>Absensi Pelayanan | JC TEAM</title>
    <style>
        body { padding-top: 120px; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-[#0b0d17] text-white min-h-screen font-sans">

    <?php include 'include/navbar.php'; ?>

    <div class="max-w-2xl mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-10">
            <a href="index.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-white transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-right">
                <h1 class="text-2xl font-black italic uppercase tracking-tighter">Frontliner</h1>
                <p class="text-[10px] text-green-400 font-bold uppercase tracking-widest">Status: Pelayanan</p>
            </div>
        </div>

        <form action="" method="POST" class="space-y-6">
            
            <div class="glass rounded-3xl p-8 space-y-6 shadow-2xl">
                <p class="text-[10px] font-bold text-cyan-400 uppercase tracking-[0.3em] border-b border-white/10 pb-2">Informasi Sesi</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 uppercase">Sesi Ibadah (Dikuti)</label>
                        <select name="sesi_ibadah" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 mt-1 text-sm font-bold text-white outline-none appearance-none cursor-pointer focus:border-cyan-500">
                            <option value="SESI 1 (08.00)">SESI 1 (08.00)</option>
                            <option value="SESI 2 (10.00)">SESI 2 (10.00)</option>
                            <option value="SESI 3 (12.00)">SESI 3 (12.00)</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[9px] font-bold text-gray-500 uppercase">Sesi Pelayanan (Tugas)</label>
                        <select name="sesi_pelayanan" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 mt-1 text-sm font-bold text-white outline-none appearance-none cursor-pointer focus:border-green-500">
                            <option value="1">Sesi Pelayanan 1</option>
                            <option value="2">Sesi Pelayanan 2</option>
                            <option value="3">Sesi Pelayanan 3</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="glass rounded-3xl p-8 space-y-6 shadow-2xl">
                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-[0.3em] border-b border-white/10 pb-2">Laporan Rohani</p>
                
                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase">Makna / Rhema</label>
                    <textarea name="makna_rhema" required placeholder="Tuliskan berkat firman hari ini..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 mt-1 text-sm outline-none focus:border-orange-500 transition-all min-h-[120px]"></textarea>
                </div>

                <div>
                    <label class="text-[9px] font-bold text-gray-500 uppercase">Ayat Referensi</label>
                    <input type="text" name="ayat_referensi" placeholder="Contoh: Filipi 4:13" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 mt-1 text-sm outline-none focus:border-orange-500 transition-all">
                </div>
            </div>

            <button type="submit" name="submit_absensi" class="w-full bg-[#00df9a] text-black font-black uppercase italic tracking-tighter py-5 rounded-[2rem] hover:bg-white transition-all shadow-xl shadow-green-500/20 active:scale-95">
                Kirim Laporan Pelayanan
            </button>
        </form>
    </div>

    <script>
        console.log("Waktu Server: <?= date('H:i') ?>");
    </script>
</body>
</html>