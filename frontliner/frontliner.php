<?php
include '../assets/koneksi.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$username_session = $_SESSION['username'];
$query_me = mysqli_query($koneksi, "SELECT u.level, f.nama_lengkap, f.bidang, f.foto 
                                   FROM users u 
                                   JOIN frontliners f ON u.id_user = f.id_user 
                                   WHERE u.username = '$username_session'");
$me = mysqli_fetch_assoc($query_me);

$path_foto = "../img_users/";
$foto_me = (!empty($me['foto']) && $me['foto'] != 'default_frontliner.jpg') 
           ? $path_foto . $me['foto'] 
           : "https://ui-avatars.com/api/?name=" . urlencode($me['nama_lengkap']) . "&background=06b6d4&color=fff";
?>

<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="glass-card rounded-[2rem] p-8 flex items-center justify-between mb-12 border border-white/5 bg-white/5 backdrop-blur-md shadow-2xl">
        <div class="flex items-center gap-6">
            <div class="relative">
                <img src="<?= $foto_me ?>" class="w-20 h-20 rounded-full border-2 border-cyan-500 p-1 object-cover">
                <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-[#0b0d17]"></div>
            </div>
            <div>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                    <?= $me['nama_lengkap'] ?>
                </h2>
                <p class="text-[10px] font-bold text-cyan-400 tracking-widest uppercase">
                    <?= $me['level'] ?> // <?= $me['bidang'] ?> <span class="bg-cyan-500/20 px-2 py-0.5 rounded ml-2">ONLINE</span>
                </p>
            </div>
        </div>
        <div class="flex gap-1 items-end h-8 opacity-50">
            <div class="w-1 bg-gray-700 h-3"></div>
            <div class="w-1 bg-gray-700 h-5"></div>
            <div class="w-1 bg-cyan-500 h-4 shadow-[0_0_10px_#06b6d4]"></div>
            <div class="w-1 bg-gray-700 h-6"></div>
        </div>
    </div>
    
    <div class="mb-16">
        <h3 class="text-[10px] font-black text-green-400 uppercase tracking-[0.2em] flex items-center gap-2 mb-8">
            <span class="w-2 h-2 bg-green-500 rounded-full shadow-[0_0_8px_#22c55e] animate-pulse"></span> 
            Pengguna Aktif Frontliner JC
        </h3>
        
        <div class="flex flex-wrap gap-6">
            <?php
            // Ambil semua data frontliner
            $query_team = mysqli_query($koneksi, "SELECT * FROM frontliners ORDER BY nama_lengkap ASC");
            while($team = mysqli_fetch_assoc($query_team)):
                $foto_team = (!empty($team['foto']) && $team['foto'] != 'default_frontliner.jpg') 
                             ? $path_foto . $team['foto'] 
                             : "https://ui-avatars.com/api/?name=" . urlencode($team['nama_lengkap']) . "&background=random";
            ?>
            <div class="w-64 glass-card border border-green-500/20 bg-white/[0.02] p-6 rounded-[2rem] text-center hover:border-green-500/50 transition-all group">
                <div class="relative w-16 h-16 mx-auto mb-4">
                    <img src="<?= $foto_team ?>" class="w-full h-full rounded-full border border-white/10 object-cover group-hover:scale-110 transition-transform">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-[#0b0d17]"></div>
                </div>
                <h4 class="font-bold text-sm text-white uppercase tracking-tight"><?= $team['nama_lengkap'] ?></h4>
                <p class="text-[9px] text-gray-500 uppercase mt-1 tracking-widest">
                    Frontliner | <?= $team['bidang'] ?>
                </p>
                <p class="text-[8px] text-green-500/70 mt-4 italic font-bold">
                    Aktif hari ini pukul : <?= date('H:i') ?>
                </p>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>