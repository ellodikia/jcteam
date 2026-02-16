<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../assets/koneksi.php";

$display_name = "Guest User";
$display_role = "House of Sacrifice";
$display_photo = "https://ui-avatars.com/api/?name=Guest&background=random";
$is_logged_in = false;

if (isset($_SESSION['admin_logged_in']) && isset($_SESSION['username'])) {
    $is_logged_in = true;
    $username_session = $_SESSION['username'];
    
    $query_user = "SELECT u.level, 
                    COALESCE(p.nama_lengkap, f.nama_lengkap, u.username) as nama,
                    COALESCE(p.foto, f.foto, 'default.jpg') as foto
                    FROM users u
                    LEFT JOIN admin_pembina p ON u.id_user = p.id_user
                    LEFT JOIN frontliners f ON u.id_user = f.id_user
                    WHERE u.username = '$username_session'";
    
    $result_user = mysqli_query($koneksi, $query_user);
    if ($row_user = mysqli_fetch_assoc($result_user)) {
        $display_name = $row_user['nama'];
        $display_role = strtoupper($row_user['level']);
        $display_photo = ($row_user['foto'] != 'default.jpg' && $row_user['foto'] != 'default_admin.jpg') 
                         ? "../img_users/" . $row_user['foto'] 
                         : "https://ui-avatars.com/api/?name=" . urlencode($display_name) . "&background=random";
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[98%] max-w-[1400px] z-50 px-4">
    <div class="bg-[#0a0f18]/80 backdrop-blur-xl border border-white/10 rounded-full px-6 py-2 flex items-center justify-between shadow-2xl">
        
        <div class="flex items-center space-x-6 text-[10px] font-bold tracking-[0.1em] text-gray-400">
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-white' : 'hover:text-white' ?> transition-colors">BERANDA</a>
            <a href="open_ministry.php" class="hover:text-white transition-colors">OPEN MINISTRY</a>
            <a href="saat_teduh.php" class="hover:text-white transition-colors">SAAT TEDUH</a>
            <a href="jadwal.php" class="hover:text-white transition-colors">JADWAL</a>
            <a href="groups.php" class="hover:text-white transition-colors">GROUPS</a>
            
            <?php if (!$is_logged_in): ?>
                <a href="login.php" class="hover:text-white transition-colors">LOGIN</a>
            <?php endif; ?>

            <div class="h-4 w-[1px] bg-white/10"></div>

            <a href="scan_qr.php" class="text-[#00df9a] flex items-center gap-2 group italic font-black">
                <i class="fas fa-qrcode text-sm"></i>
                <span class="group-hover:tracking-[0.15em] transition-all">SCAN QR</span>
            </a>
        </div>

        <div class="flex items-center space-x-4">
            
            <div class="hidden md:flex items-center bg-black/40 border border-white/5 px-3 py-1.5 rounded-full">
                <span class="flex h-2 w-2 mr-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase">Jaringan Stabil</span>
            </div>

            <div class="flex items-center gap-4 pl-4 border-l border-white/10">
                <div class="text-right hidden sm:block">
                    <h4 class="text-white text-[12px] font-black leading-none mb-1 tracking-tight">
                        <?= strtoupper($display_name) ?>
                    </h4>
                    <p class="text-[8px] text-gray-500 font-bold tracking-[0.1em]">
                        <?= $display_role ?>
                    </p>
                </div>
                
                <div class="relative group cursor-pointer">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-blue-500 rounded-full opacity-30 group-hover:opacity-100 transition duration-500 blur-[2px]"></div>
                    <img src="<?= $display_photo ?>" 
                         class="relative w-9 h-9 rounded-full object-cover border border-black shadow-lg"
                         alt="Profile">
                </div>
            </div>

        </div>
    </div>
</nav>