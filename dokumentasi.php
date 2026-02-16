<?php
$query = "SELECT * FROM dokumentasi ORDER BY tanggal DESC LIMIT 3";
$result = mysqli_query($koneksi, $query);
?>
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16">
        <div>
            <span class="text-blue-400 text-[10px] font-bold uppercase tracking-[0.4em]">Memory Archive</span>
            <h2 class="text-5xl font-heading font-bold uppercase tracking-tighter mt-2">Documentation</h2>
        </div>
        <a href="galeri.php" class="px-8 py-3 rounded-full glass text-[10px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-300">View Gallery</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="group">
                    <div onclick="window.open('<?= $row['link'] ?>', '_blank')" class="rounded-[2rem] overflow-hidden aspect-video mb-6 border border-white/5 relative cursor-pointer bg-neutral-900 shadow-2xl">
                        <?php 
                        $foto_path = "img_dokumentasi/" . $row['foto'];
                        if (!empty($row['foto']) && file_exists($foto_path)): 
                        ?>
                            <img src="<?= $foto_path ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-neutral-800 text-[10px] font-bold uppercase tracking-widest">Missing Image</div>
                        <?php endif; ?>
                        <div class="absolute top-4 left-4 bg-black/60 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/10 text-[9px] font-bold uppercase tracking-widest"><?= $row['bulan'] ?></div>
                    </div>
                    
                    <h3 class="font-bold text-lg uppercase tracking-tight mb-2"><?= $row['judul'] ?></h3>
                    <div class="flex items-center justify-between mt-4">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </p>
                        <a href="<?= $row['link'] ?>" target="_blank" class="text-blue-400 hover:text-white transition-colors">
                            <i class="fab fa-google-drive text-lg"></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>