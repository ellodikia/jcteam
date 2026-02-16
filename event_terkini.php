<?php
$query_event = "SELECT * FROM event ORDER BY created_at DESC LIMIT 3";
$result_event = mysqli_query($koneksi, $query_event);
?>
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex items-center gap-4 mb-12">
        <h2 class="text-4xl font-heading font-bold uppercase tracking-tighter">Upcoming Event</h2>
        <div class="h-px flex-grow bg-gradient-to-r from-purple-500/50 to-transparent"></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php if (mysqli_num_rows($result_event) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result_event)): ?>
                <div class="group cursor-pointer" onclick="window.open('<?= $row['link'] ?>', '_blank')">
                    <div class="rounded-[2rem] overflow-hidden aspect-[4/5] mb-6 border border-white/5 relative bg-neutral-900">
                        <?php 
                        $foto_path = "img_events/" . $row['foto'];
                        if (!empty($row['foto']) && file_exists($foto_path)): 
                        ?>
                            <img src="<?= $foto_path ?>" alt="<?= $row['judul'] ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-neutral-700 text-[10px] font-bold tracking-widest">NO POSTER</div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-8">
                            <span class="text-white text-[10px] font-bold uppercase tracking-widest border border-white/20 px-4 py-2 rounded-full backdrop-blur-md">View Details</span>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg uppercase tracking-tight group-hover:text-purple-400 transition-colors duration-300"><?= $row['judul'] ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-8 h-px bg-purple-500"></span>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Mark your calendar</p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-20 glass rounded-[2.5rem] border-dashed">
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em]">No events scheduled at the moment</p>
            </div>
        <?php endif; ?>
    </div>
</section>