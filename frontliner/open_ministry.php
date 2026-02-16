<?php
include '../assets/koneksi.php';

$query_om = "SELECT * FROM open_ministry WHERE status = 'buka' ORDER BY id ASC";
$result_om = mysqli_query($koneksi, $query_om);

$ministries = [];
while ($row = mysqli_fetch_assoc($result_om)) {
    $ministries[] = $row;
}
?>

<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <p class="text-green-400 text-[10px] font-bold uppercase tracking-widest mb-4">Join the Ministry</p>
        <h2 class="text-6xl italic-heavy mb-8">Open Ministry</h2>
        
        <div class="flex flex-wrap justify-center gap-3" id="ministry-tabs">
            <?php foreach ($ministries as $index => $item): ?>
                <button 
                    onclick="switchMinistry(<?= $index ?>)"
                    id="tab-<?= $index ?>"
                    class="tab-btn px-6 py-2 rounded-full border border-white/20 text-[10px] font-black uppercase tracking-widest transition-all duration-300 <?= $index === 0 ? 'bg-white text-black' : 'text-gray-500 hover:border-white' ?>">
                    <?= $item['bidang'] ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="ministry-card" class="glass-card p-12 md:p-16 rounded-[3.5rem] flex flex-col items-start relative overflow-hidden transition-all duration-500 opacity-100">
        <div class="absolute right-0 top-0 w-1/2 h-full opacity-10 pointer-events-none">
            <img id="m-foto" src="../img_open_ministry/<?= $ministries[0]['foto'] ?>" class="w-full h-full object-cover">
        </div>

        <h2 class="text-6xl italic-heavy mb-6">
            <span id="m-bidang"><?= $ministries[0]['bidang'] ?></span> <span class="text-cyan-400">JC</span>
        </h2>
        
        <p id="m-deskripsi" class="max-w-xl text-gray-400 text-lg mb-10 leading-relaxed min-h-[100px]">
            <?= $ministries[0]['deskripsi'] ?>
        </p>

        <button class="bg-cyan-400 text-black px-10 py-4 rounded-2xl font-black uppercase tracking-widest flex items-center gap-4 hover:scale-105 transition shadow-2xl shadow-cyan-500/20">
            Apply Now <i class="fas fa-arrow-right text-sm"></i>
        </button>
    </div>
</section>

<script>
    const dataMinistry = <?= json_encode($ministries) ?>;

    function switchMinistry(index) {
        const item = dataMinistry[index];
        const card = document.getElementById('ministry-card');
        
        // Efek transisi keluar
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';

        setTimeout(() => {
            // Ganti Data
            document.getElementById('m-bidang').innerText = item.bidang;
            document.getElementById('m-deskripsi').innerText = item.deskripsi;
            document.getElementById('m-foto').src = 'admin/img_open_ministry/' + item.foto;

            // Update Style Tombol
            document.querySelectorAll('.tab-btn').forEach((btn, i) => {
                if(i === index) {
                    btn.classList.add('bg-white', 'text-black');
                    btn.classList.remove('text-gray-500', 'border-white/20');
                } else {
                    btn.classList.remove('bg-white', 'text-black');
                    btn.classList.add('text-gray-500', 'border-white/20');
                }
            });

            // Efek transisi masuk
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 300);
    }
</script>