<?php
$query_om = "SELECT * FROM open_ministry WHERE status = 'buka' ORDER BY id ASC";
$result_om = mysqli_query($koneksi, $query_om);
$ministries = [];
while ($row = mysqli_fetch_assoc($result_om)) { $ministries[] = $row; }
?>
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="mb-16">
        <span class="text-green-500 text-[10px] font-bold uppercase tracking-[0.4em]">Serve the King</span>
        <h2 class="text-5xl md:text-7xl font-heading font-bold uppercase tracking-tighter mt-4 mb-10">Open Ministry</h2>
        
        <div class="flex flex-wrap gap-2" id="ministry-tabs">
            <?php foreach ($ministries as $index => $item): ?>
                <button onclick="switchMinistry(<?= $index ?>)" id="tab-<?= $index ?>" class="tab-btn px-8 py-3 rounded-xl border border-white/5 glass text-[10px] font-bold uppercase tracking-widest transition-all duration-300 <?= $index === 0 ? 'bg-white !text-black' : 'text-gray-500 hover:text-white' ?>">
                    <?= $item['bidang'] ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="ministry-card" class="glass p-10 md:p-20 rounded-[3.5rem] flex flex-col md:flex-row gap-12 items-center relative overflow-hidden transition-all duration-500 border border-white/10">
        <div class="w-full md:w-1/2 relative z-10">
            <h2 class="text-5xl md:text-7xl font-heading font-bold uppercase tracking-tighter mb-8 leading-none">
                <span id="m-bidang"><?= $ministries[0]['bidang'] ?></span> <span class="text-cyan-500">JC</span>
            </h2>
            <p id="m-deskripsi" class="text-gray-400 text-lg mb-12 leading-relaxed max-w-lg">
                <?= $ministries[0]['deskripsi'] ?>
            </p>
            <button class="group px-10 py-5 bg-cyan-500 text-black rounded-2xl font-black uppercase text-xs tracking-[0.2em] flex items-center gap-4 hover:bg-white transition-all duration-300">
                Apply Now <i class="fas fa-arrow-right transition-transform group-hover:translate-x-2"></i>
            </button>
        </div>
        <div class="w-full md:w-1/2 aspect-square rounded-[2.5rem] overflow-hidden border border-white/5">
            <img id="m-foto" src="img_open_ministry/<?= $ministries[0]['foto'] ?>" class="w-full h-full object-cover opacity-80 transition-all duration-700">
        </div>
    </div>
</section>

<script>
    const dataMinistry = <?= json_encode($ministries) ?>;
    function switchMinistry(index) {
        const item = dataMinistry[index];
        const card = document.getElementById('ministry-card');
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            document.getElementById('m-bidang').innerText = item.bidang;
            document.getElementById('m-deskripsi').innerText = item.deskripsi;
            document.getElementById('m-foto').src = 'admin/img_open_ministry/' + item.foto;
            document.querySelectorAll('.tab-btn').forEach((btn, i) => {
                if(i === index) {
                    btn.classList.add('bg-white', 'text-black');
                    btn.classList.remove('text-gray-500');
                } else {
                    btn.classList.remove('bg-white', 'text-black');
                    btn.classList.add('text-gray-500');
                }
            });
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 400);
    }
</script>