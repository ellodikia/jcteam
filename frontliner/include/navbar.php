<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan file koneksi sudah ter-include sebelum navbar
// include "../assets/koneksi.php"; 

$display_name = "Guest User";
$display_role = "House of Sacrifice";
$display_photo = "https://ui-avatars.com/api/?name=Guest&background=random";
$is_logged_in = false;

// Perbaikan: Pastikan $koneksi tersedia dan session valid
if (isset($koneksi) && isset($_SESSION['admin_logged_in']) && isset($_SESSION['id_user'])) {
    $is_logged_in = true;
    $id_user_nav = $_SESSION['id_user']; // Gunakan ID User untuk query yang lebih akurat
    
    $query_nav = "SELECT u.level, 
                    COALESCE(p.nama_lengkap, f.nama_lengkap, u.username) as nama,
                    COALESCE(p.foto, f.foto, 'default.jpg') as foto
                    FROM users u
                    LEFT JOIN admin_pembina p ON u.id_user = p.id_user
                    LEFT JOIN frontliners f ON u.id_user = f.id_user
                    WHERE u.id_user = ?";
    
    $stmt_nav = mysqli_prepare($koneksi, $query_nav);
    mysqli_stmt_bind_param($stmt_nav, "i", $id_user_nav);
    mysqli_stmt_execute($stmt_nav);
    $result_nav = mysqli_stmt_get_result($stmt_nav);

    if ($row_nav = mysqli_fetch_assoc($result_nav)) {
        $display_name = $row_nav['nama'];
        $display_role = ($row_nav['level'] == 'pembina') ? "PEMBINA" : "FRONTLINER";
        
        // Logika Path Foto
        $foto_db = $row_nav['foto'];
        if ($foto_db != 'default.jpg' && $foto_db != 'default_admin.jpg' && $foto_db != 'default_frontliner.jpg') {
            $display_photo = "../img_users/" . $foto_db;
        } else {
            $display_photo = "https://ui-avatars.com/api/?name=" . urlencode($display_name) . "&background=random";
        }
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[98%] max-w-[1400px] z-50 px-4">
    <div class="bg-[#0a0f18]/80 backdrop-blur-xl border border-white/10 rounded-full px-6 py-2 flex items-center justify-between shadow-2xl">
        
        <div class="flex items-center space-x-6 text-[10px] font-bold tracking-[0.1em] text-gray-400">
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-white' : 'hover:text-white' ?> transition-colors">BERANDA</a>
            <a href="open_ministry.php" class="<?= basename($_SERVER['PHP_SELF']) == 'open_ministry.php' ? 'text-white' : 'hover:text-white' ?> transition-colors">OPEN MINISTRY</a>
            <a href="saat_teduh.php" class="hover:text-white transition-colors">SAAT TEDUH</a>
            <a href="jadwal.php" class="hover:text-white transition-colors">JADWAL</a>
            <a href="groups.php" class="hover:text-white transition-colors">GROUPS</a>
            
            <?php if (!$is_logged_in): ?>
                <a href="login.php" class="hover:text-white transition-colors text-yellow-500">LOGIN</a>
            <?php endif; ?>

            <div class="h-4 w-[1px] bg-white/10"></div>

            <a href="scan_qr.php" class="text-[#00df9a] flex items-center gap-2 group italic font-black">
                <i class="fas fa-qrcode text-sm"></i>
                <span class="group-hover:tracking-[0.15em] transition-all text-[10px]">SCAN QR</span>
            </a>
        </div>

        <div class="flex items-center space-x-4">
            
            <div class="hidden lg:flex items-center bg-black/40 border border-white/5 px-3 py-1.5 rounded-full">
                <span class="relative flex h-2 w-2 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase">Jaringan Stabil</span>
            </div>

            <div class="flex items-center gap-2 px-4 border-l border-white/10">
                <img src="../img/logo.png" alt="Logo" class="w-8 h-8 rounded-full shadow-lg">
                <div class="hidden xl:block text-left">
                    <span class="block text-white text-[10px] font-black leading-none tracking-tighter">JUNIOR</span>
                    <span class="block text-orange-400 text-[8px] font-bold tracking-[0.2em] leading-none">CHURCH</span>
                </div>
            </div>

            <div class="flex items-center gap-4 pl-4 border-l border-white/10">
                <div class="text-right hidden sm:block">
                    <h4 class="text-white text-[11px] font-black leading-none mb-1 tracking-tight">
                        <?= strtoupper($display_name) ?>
                    </h4>
                    <p class="text-[8px] text-gray-500 font-bold tracking-[0.1em]">
                        <?= $display_role ?>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative group cursor-pointer">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-blue-500 rounded-full opacity-30 group-hover:opacity-100 transition duration-500 blur-[2px]"></div>
                        <img src="<?= $display_photo ?>" 
                             class="relative w-9 h-9 rounded-full object-cover border border-black shadow-lg bg-gray-800"
                             alt="Profile">
                    </div>

                    <?php if ($is_logged_in): ?>
                    <a href="logout.php" title="Keluar" 
                       onclick="return confirm('Apakah anda yakin ingin keluar?')"
                       class="w-8 h-8 flex items-center justify-center rounded-full bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300">
                        <i class="fas fa-power-off text-xs"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>