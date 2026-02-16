<?php
include '../assets/koneksi.php';

$query_pembina = "SELECT * FROM admin_pembina ORDER BY nama_lengkap ASC";
$result_pembina = mysqli_query($koneksi, $query_pembina);
?>

<section class="max-w-7xl mx-auto px-6 py-16 text-center">
    <p class="text-yellow-500 text-[10px] font-bold uppercase tracking-[0.4em] mb-4">Authority</p>
    <h2 class="text-5xl italic-heavy mb-16">Pembina JC HOS</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php if (mysqli_num_rows($result_pembina) > 0): ?>
            <?php while($pembina = mysqli_fetch_assoc($result_pembina)): ?>
                <div class="glass-card rounded-[2rem] overflow-hidden group border border-white/5">
                    <?php 
                    $foto_user = "../img_users/" . $pembina['foto'];
                    
                    if (!empty($pembina['foto']) && file_exists($foto_user)): 
                    ?>
                        <img src="<?= $foto_user ?>" 
                             alt="<?= $pembina['nama_lengkap'] ?>"
                             class="w-full aspect-[3/4] object-cover grayscale group-hover:grayscale-0 transition duration-700">
                    <?php else: ?>
                        <div class="w-full aspect-[3/4] bg-neutral-900 flex items-center justify-center text-neutral-700 font-bold uppercase text-[10px]">No Photo</div>
                    <?php endif; ?>

                    <div class="p-6 text-left bg-black/60 backdrop-blur-md border-t border-white/5">
                        <h4 class="font-black italic uppercase text-lg leading-tight text-white">
                            <?= $pembina['nama_lengkap'] ?>
                        </h4>
                        <p class="text-[9px] text-gray-400 mt-2 font-bold uppercase tracking-wider">
                            Kakak Pembina (<?= $pembina['bidang'] ?: 'Staff' ?>)
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-4 py-20 opacity-30 italic">
                <p class="text-xs uppercase tracking-widest">Data pembina belum tersedia</p>
            </div>
        <?php endif; ?>
    </div>
</section>