<?php
$query = "SELECT * FROM dokumentasi ORDER BY tanggal DESC LIMIT 3";
$result = mysqli_query($koneksi, $query);
?>

<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex justify-between items-end mb-10">
        <div>
            <p class="text-blue-400 text-[10px] font-bold uppercase tracking-widest">Archive</p>
            <h2 class="text-4xl italic-heavy">Dokumentasi Ibadah</h2>
        </div>
        <a href="galeri.php" class="px-6 py-2 rounded-full border border-white/10 text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition inline-block">VIEW ALL</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="group cursor-pointer">
                    <div onclick="window.open('<?= $row['link'] ?>', '_blank')" class="rounded-2xl overflow-hidden aspect-video mb-4 border border-white/10 relative">
                        <?php 
                        $foto_path = "../img_dokumentasi/" . $row['foto'];
                        if (!empty($row['foto']) && file_exists($foto_path)): 
                        ?>
                            <img src="<?= $foto_path ?>" 
                                 alt="<?= $row['judul'] ?>"
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full bg-neutral-900 flex items-center justify-center text-neutral-700 text-[10px] font-bold">NO IMAGE</div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="font-bold text-xs uppercase">
                        <?= $row['judul'] ?> <?= $row['bulan'] ?> Minggu <?= $row['minggu_ke'] ?>
                    </h3>
                    
                    <p class="text-[10px] text-gray-500 mt-1 uppercase">
                        <?php 
                        echo date('d', strtotime($row['tanggal'])) . " " . $row['bulan'] . " " . $row['tahun']; 
                        ?>
                    </p>

                    <div class="mt-4">
                        <a href="<?= $row['link'] ?>" target="_blank" 
                           class="inline-flex items-center gap-2 text-blue-400 hover:text-white text-[9px] font-black uppercase tracking-widest transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7.71 3h8.58l6.71 11.59-4.29 7.41H5.29L1 14.59 7.71 3z"/>
                            </svg>
                            Buka Drive
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-10 border border-dashed border-white/10 rounded-2xl">
                <p class="text-gray-500 text-xs uppercase tracking-widest">Belum ada data dokumentasi</p>
            </div>
        <?php endif; ?>
    </div>
</section>