<?php
include '../assets/koneksi.php';

if (isset($_GET['mark_id'])) {
    $id = $_GET['mark_id'];
    mysqli_query($koneksi, "UPDATE prayer_requests SET status = 1 WHERE id = $id");
    header("Location: prayer_request.php");
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($koneksi, "DELETE FROM prayer_requests WHERE id = $id");
    header("Location: prayer_request.php");
}

$query = "SELECT * FROM prayer_requests ORDER BY status ASC, created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Prayer Inbox</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Prayer <span class="text-purple-500">Inbox</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Community Prayer Requests</p>
            </div>
            <div class="w-12 h-12 bg-purple-600/10 text-purple-500 rounded-2xl flex items-center justify-center text-xl">
                <i class="fas fa-hands-praying"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 relative overflow-hidden group transition-all <?= $row['status'] ? 'opacity-40 grayscale' : 'hover:border-purple-500/50' ?>">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-black uppercase italic text-sm tracking-tight text-purple-400"><?= $row['nama'] ?></h3>
                        <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest"><?= date('d M Y | H:i', strtotime($row['created_at'])) ?></p>
                    </div>
                    <?php if($row['status']): ?>
                        <span class="bg-green-500/10 text-green-500 text-[8px] font-black uppercase px-2 py-1 rounded-full italic">Prayed</span>
                    <?php endif; ?>
                </div>
                
                <p class="text-sm text-slate-300 leading-relaxed italic mb-8">"<?= htmlspecialchars($row['isi_doa']) ?>"</p>
                
                <div class="flex gap-4 border-t border-white/5 pt-6">
                    <?php if(!$row['status']): ?>
                    <a href="?mark_id=<?= $row['id'] ?>" class="flex-1 bg-purple-600 hover:bg-purple-700 text-center py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Mark as Prayed</a>
                    <?php endif; ?>
                    <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="w-11 h-11 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-trash text-xs"></i>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>