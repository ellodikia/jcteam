<?php
$query_pembina = "SELECT * FROM admin_pembina ORDER BY nama_lengkap ASC";
$result_pembina = mysqli_query($koneksi, $query_pembina);
?>
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center max-w-2xl mx-auto mb-20">
        <p class="text-yellow-500 text-[10px] font-bold uppercase tracking-[0.5em] mb-4">Leadership</p>
        <h2 class="text-5xl font-heading font-bold uppercase tracking-tight">Meet Your Mentors</h2>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <?php if (mysqli_num_rows($result_pembina) > 0): ?>
            <?php while($pembina = mysqli_fetch_assoc($result_pembina)): ?>
                <div class="group relative card-hover">
                    <div class="rounded-[2.5rem] overflow-hidden aspect-[3/4] border border-white/5 bg-neutral-900 mb-6">
                        <?php 
                        $foto_user = "img_users/" . $pembina['foto'];
                        if (!empty($pembina['foto']) && file_exists($foto_user)): 
                        ?>
                            <img src="<?= $foto_user ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-neutral-800 font-bold uppercase text-[10px]">No Photo</div>
                        <?php endif; ?>
                    </div>
                    <div class="px-2">
                        <h4 class="font-bold text-xl tracking-tight text-white mb-1"><?= $pembina['nama_lengkap'] ?></h4>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest"><?= $pembina['bidang'] ?: 'Staff' ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-4 py-20 glass rounded-[2.5rem] text-center italic text-gray-600 uppercase text-[10px] tracking-widest">Mentors data unavailable</div>
        <?php endif; ?>
    </div>
</section>