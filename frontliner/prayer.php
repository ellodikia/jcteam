<?php
include '../assets/koneksi.php';

$pesan = "";

if (isset($_POST['submit_prayer'])) {
    $is_anon = isset($_POST['is_anonymous']) ? 1 : 0;
    $nama    = $is_anon ? "Anonymous" : mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori = $_POST['kategori'];
    $isi_doa = mysqli_real_escape_string($koneksi, $_POST['isi_doa']);

    $query = "INSERT INTO prayer_requests (nama, kategori, isi_doa, is_anonymous) 
              VALUES ('$nama', '$kategori', '$isi_doa', '$is_anon')";
    
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Permohonan doa telah terkirim.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Request | JC HOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #000; 
            color: #fff; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        .glass-card {
            background: rgba(17, 17, 17, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-input { 
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-input:focus { 
            border-color: #a855f7; 
            background: rgba(168, 85, 247, 0.05);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
            outline: none; 
        }
        .category-radio:checked + .category-card { 
            border-color: #a855f7; 
            background: linear-gradient(145deg, rgba(168, 85, 247, 0.2), rgba(0, 0, 0, 0));
            transform: translateY(-2px);
        }
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_30%_-20%,_#3b0764_0%,_#000_50%)]">

<?php include('include/navbar.php'); ?>

<main class="max-w-4xl mx-auto px-6 py-20">
    <div class="text-center mb-16 relative">
        <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-40 h-40 bg-purple-600/20 blur-[100px] rounded-full"></div>
        
        <a href="index.php" class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-purple-400 transition-colors mb-8 group">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Hub
        </a>
        
        <h1 class="text-6xl md:text-7xl font-extrabold italic uppercase tracking-tighter leading-none mb-4">
            Prayer <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-500">Request</span>
        </h1>
        <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-[0.5em] opacity-60">We stand with you in faith</p>
    </div>

    <?php if($pesan): ?>
        <div class="max-w-2xl mx-auto mb-10 flex items-center justify-center gap-3 bg-purple-500/10 border border-purple-500/50 text-purple-200 p-5 rounded-3xl animate-pulse">
            <i class="fas fa-check-circle"></i>
            <span class="text-[10px] font-black uppercase tracking-widest"><?= $pesan ?></span>
        </div>
    <?php endif; ?>

    <div class="max-w-2xl mx-auto">
        <form action="" method="POST" class="glass-card p-10 md:p-14 rounded-[3rem] shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-600/10 blur-[60px] rounded-full"></div>

            <div class="mb-10 relative">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Your Identity</label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_anonymous" id="anon-check" class="hidden peer">
                        <div class="w-5 h-5 border border-white/10 rounded-lg peer-checked:bg-purple-600 peer-checked:border-purple-600 flex items-center justify-center transition-all duration-300">
                            <i class="fas fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase text-gray-500 group-hover:text-gray-300 transition">Go Anonymous</span>
                    </label>
                </div>
                <input type="text" name="nama" id="nama-field" placeholder="Enter your full name" 
                       class="w-full p-5 glass-input rounded-2xl text-sm font-semibold placeholder:text-gray-700">
            </div>

            <div class="mb-10">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 block mb-5">Select Category</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php 
                    $cats = [
                        ['id' => 'General', 'icon' => 'fa-globe'],
                        ['id' => 'Health', 'icon' => 'fa-heartbeat'],
                        ['id' => 'Study', 'icon' => 'fa-user-graduate'],
                        ['id' => 'Family', 'icon' => 'fa-home']
                    ];
                    foreach($cats as $c): ?>
                    <label class="relative group">
                        <input type="radio" name="kategori" value="<?= $c['id'] ?>" class="hidden peer category-radio" required <?= $c['id'] == 'General' ? 'checked' : '' ?>>
                        <div class="category-card p-5 rounded-[2rem] text-center border border-white/5 transition-all duration-500 hover:bg-white/5 peer-checked:border-purple-500/50">
                            <i class="fas <?= $c['icon'] ?> block mb-3 text-lg text-gray-600 group-hover:text-purple-400 transition-colors"></i>
                            <span class="text-[10px] font-black uppercase tracking-tighter text-gray-500 peer-checked:text-white"><?= $c['id'] ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-12">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 block mb-5">The Prayer</label>
                <textarea name="isi_doa" rows="5" required placeholder="Tell us your burden..." 
                          class="w-full p-7 glass-input rounded-[2rem] text-sm leading-relaxed placeholder:text-gray-700"></textarea>
            </div>

            <button type="submit" name="submit_prayer" 
                    class="btn-glow w-full bg-white text-black py-6 rounded-[2rem] font-black uppercase tracking-[0.3em] text-[11px] hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 shadow-2xl">
                Send My Request
            </button>
        </form>
    </div>
</main>

<script>
    const anonCheck = document.getElementById('anon-check');
    const namaField = document.getElementById('nama-field');
    
    anonCheck.addEventListener('change', function() {
        if(this.checked) {
            namaField.value = "Anonymous";
            namaField.classList.add('opacity-20', 'pointer-events-none');
            namaField.style.filter = "blur(2px)";
        } else {
            namaField.value = "";
            namaField.classList.remove('opacity-20', 'pointer-events-none');
            namaField.style.filter = "none";
        }
    });
</script>

</body>
</html>