<?php
include '../assets/koneksi.php';

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['level'] != 'frontliner') {
    header("location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Dresscode Team - JC HOS</title>
</head>
<body class="bg-slate-950 text-white min-h-screen">

    <?php include 'include/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-6 pt-32 pb-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black uppercase tracking-[0.2em] text-white">Dresscode <span class="text-blue-500">Team</span></h1>
            <p class="text-slate-500 text-sm mt-2 uppercase tracking-widest font-bold">Ketentuan Seragam & Pakaian Pelayanan</p>
            <div class="h-1 w-20 bg-blue-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Mengambil data dresscode terbaru berdasarkan struktur tabel
            $query = mysqli_query($koneksi, "SELECT * FROM dresscode ORDER BY tahun DESC, id DESC");
            
            if (mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)):
            ?>
                <div class="group relative bg-slate-900/50 border border-white/5 rounded-[2.5rem] overflow-hidden hover:border-blue-500/50 transition-all duration-500 shadow-2xl">
                    <div class="relative h-80 overflow-hidden">
                        <img src="../img_dresscode/<?= $row['foto'] ?>" 
                             alt="Dresscode" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        
                        <div class="absolute top-6 left-6">
                            <div class="bg-black/60 backdrop-blur-md border border-white/10 px-4 py-2 rounded-2xl shadow-xl">
                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-tighter leading-none">Periode</p>
                                <p class="text-sm font-bold text-white"><?= $row['bulan'] ?> <?= $row['tahun'] ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Keterangan</h3>
                                <p class="text-slate-200 leading-relaxed italic">
                                    "<?= $row['keterangan'] ?: 'Gunakan pakaian rapi dan sopan sesuai standar pelayanan.' ?>"
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 mt-6 pt-6 border-t border-white/5">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Ketentuan Aktif</span>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile; 
            } else {
                // Tampilan jika data kosong
                echo '
                <div class="col-span-full py-20 text-center">
                    <i class="fas fa-tshirt text-6xl text-slate-800 mb-4"></i>
                    <p class="text-slate-500 font-bold uppercase tracking-widest">Belum ada jadwal dresscode yang dirilis</p>
                </div>';
            }
            ?>
        </div>
    </div>

</body>
</html>