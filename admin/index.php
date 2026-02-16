<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jcteam";
$koneksi = mysqli_connect($host, $user, $pass, $db);

$res_doc = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dokumentasi");
$total_doc = mysqli_fetch_assoc($res_doc)['total'];

$res_event = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event");
$total_event = mysqli_fetch_assoc($res_event)['total'];

$res_om = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM open_ministry WHERE status = 'buka'");
$total_om = mysqli_fetch_assoc($res_om)['total'];

$res_user = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
$total_user = mysqli_fetch_assoc($res_user)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - JC Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">System <span class="text-blue-500">Overview</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">JC Team Management Control Panel</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-blue-500 uppercase italic leading-none"><?= date('l') ?></p>
                <p class="text-[10px] font-bold text-slate-500 uppercase mt-1"><?= date('d F Y') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white/5 border border-white/10 p-6 rounded-[2.5rem] hover:border-blue-500/50 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-600/10 text-blue-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-black italic tracking-tighter mb-1"><?= $total_doc ?></h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Dokumentasi</p>
            </div>

            <div class="bg-white/5 border border-white/10 p-6 rounded-[2.5rem] hover:border-purple-500/50 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-purple-600/10 text-purple-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-calendar-star"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-black italic tracking-tighter mb-1"><?= $total_event ?></h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Event Aktif</p>
            </div>

            <div class="bg-white/5 border border-white/10 p-6 rounded-[2.5rem] hover:border-green-500/50 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-green-600/10 text-green-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-black italic tracking-tighter mb-1"><?= $total_om ?></h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Open Ministry</p>
            </div>

            <div class="bg-white/5 border border-white/10 p-6 rounded-[2.5rem] hover:border-orange-500/50 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-orange-600/10 text-orange-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-users-gear"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-black italic tracking-tighter mb-1"><?= $total_user ?></h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Admin</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white/5 border border-white/10 rounded-[3rem] p-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="font-black italic uppercase text-sm tracking-tighter mb-6 border-b border-white/10 pb-4">Server Status</h4>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Database</span>
                            <span class="text-[10px] font-black text-green-400 uppercase">Connected</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">PHP Version</span>
                            <span class="text-[10px] font-black text-white uppercase"><?= phpversion() ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Environment</span>
                            <span class="text-[10px] font-black text-blue-400 uppercase italic">Production Mode</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-600/10 blur-[100px] rounded-full"></div>
            </div>
        </div>
    </main>
</body>
</html>