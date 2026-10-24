<?php ?>
<main class="flex-1 flex flex-col gap-6 overflow-hidden">
    <div class="flex justify-between items-center shrink-0">
        <div class="flex gap-4">
            <button onclick="scrollToCategory('kategori-bubur', this)" class="cat-btn px-8 py-2 bg-[#e0dce1] text-gray-800 font-semibold rounded-lg shadow-sm transition border border-transparent">Bubur</button>
            <button onclick="scrollToCategory('kategori-toping', this)" class="cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300">Toping</button>
            <button onclick="scrollToCategory('kategori-minuman', this)" class="cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300">Minuman</button>
        </div>
        <button onclick="openFormModal('add')" class="px-6 py-2.5 bg-[#572458] text-white font-bold rounded-xl shadow-md hover:bg-[#3b173c] transition flex items-center gap-2 text-sm">
            + Tambah Produk
        </button>
    </div>

    <div id="product-container" class="overflow-y-auto custom-scroll pr-4 pb-20 flex flex-col gap-8">
        <?php 
        $first_section = true; 
        foreach ($categories as $cat): 
            if (empty($produk_by_jenis[$cat])) continue; 
        ?>
            
            <?php if (!$first_section): ?>
                <div class="mb-4 text-gray-400 -mt-4"><hr class="border-gray-300 border-2 rounded-full"></div>
            <?php endif; $first_section = false; ?>

            <div id="kategori-<?= strtolower($cat) ?>" class="scroll-mt-6">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-gray-800"><?= htmlspecialchars($cat) ?></h2>

                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 2xl:grid-cols-7 gap-4">
                    <?php foreach ($produk_by_jenis[$cat] as $p):
                        $tersedia = ($p['stok'] > 0 && (int)$p['status'] === 1);
                        $hargaAngka = (int)$p['harga_barang'];
                        $hargaText = ($hargaAngka === 0) ? 'Gratis' : 'Rp ' . number_format($hargaAngka, 0, ',', '.');
                    ?>
                    <div class="product-card bg-[#c9c5cb] rounded-2xl p-3 flex flex-col shadow-sm hover:shadow-md transition" data-name="<?= htmlspecialchars($p['nama_barang']) ?>">
                        <div class="bg-white aspect-square rounded-xl flex items-center justify-center relative mb-3 shadow-sm overflow-hidden">
                            <?php if(!empty($p['foto'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>" class="w-full h-full object-cover" alt="Foto Produk">
                            <?php else: ?>
                                <span class="text-gray-400 font-bold text-lg">IMG</span>
                            <?php endif; ?>

                            <?php if($tersedia): ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-[#4c3ce6] text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Tersedia</span>
                            <?php else: ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-red-500 text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Habis</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-base leading-tight mb-1 truncate"><?= htmlspecialchars($p['nama_barang']) ?></h3>
                        <p class="text-center text-sm text-gray-800 font-semibold mb-3">
                            <?= $hargaText ?> <span class="text-xs text-gray-500 font-normal block mt-0.5">Stok: <?= (int)$p['stok'] ?></span>
                        </p>

                        <div class="flex justify-center gap-5 mt-auto pb-1">
                            <button onclick="openFormModal('edit', <?= (int)$p['id_barang'] ?>, '<?= addslashes(htmlspecialchars($p['nama_barang'])) ?>', '<?= addslashes(htmlspecialchars($p['jenis'])) ?>', <?= $hargaAngka ?>, <?= (int)$p['stok'] ?>, <?= (int)$p['status'] ?>, '<?= !empty($p['foto']) ? addslashes(htmlspecialchars($p['foto'])) : '' ?>')" class="text-blue-700 hover:text-blue-900 hover:scale-110 transition" title="Edit Produk">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="openDeleteConfirmModal(<?= (int)$p['id_barang'] ?>)" class="text-red-600 hover:text-red-800 hover:scale-110 transition" title="Hapus Produk">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$ada_produk): ?>
        <div class="text-center text-gray-400 mt-16 font-medium">Belum ada produk. Klik "Tambah Produk" untuk mulai menambahkan.</div>
        <?php endif; ?>

    </div>
</main>
