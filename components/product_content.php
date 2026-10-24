<!-- ============ KONTEN PRODUK (KATEGORI TABS + GRID) ============ -->
<main class="flex-1 flex flex-col gap-6 overflow-hidden relative">
    <div class="flex gap-4 shrink-0">
        <button onclick="scrollToCategory('kategori-bubur', this)" class="cat-btn px-8 py-2 bg-[#e0dce1] text-gray-800 font-semibold rounded-lg shadow-sm transition border border-transparent">Bubur</button>
        <button onclick="scrollToCategory('kategori-toping', this)" class="cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300">Toping</button>
        <button onclick="scrollToCategory('kategori-minuman', this)" class="cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300">Minuman</button>
        <button onclick="scrollToCategory('kategori-snack', this)" class="cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300">Snack</button>
    </div>

    <div id="product-container" class="overflow-y-auto custom-scroll pr-4 pb-20">
        
        <!-- ===== KATEGORI: BUBUR ===== -->
        <div id="kategori-bubur" class="mb-8 scroll-mt-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-gray-800">Bubur</h2>
            <?php if(count($produk_bubur) > 0): ?>
                <div class="grid grid-cols-2 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                    <?php foreach($produk_bubur as $bubur): ?>
                    <div class="product-card bg-[#c9c5cb] rounded-2xl p-3 flex flex-col shadow-sm hover:shadow-md transition" data-name="<?= htmlspecialchars($bubur['nama_barang']) ?>">
                        <div class="bg-white aspect-square rounded-xl flex items-center justify-center relative mb-3 shadow-sm overflow-hidden">
                            <?php if(!empty($bubur['foto'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($bubur['foto']) ?>" alt="<?= htmlspecialchars($bubur['nama_barang']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-gray-400 font-bold text-lg">IMG</span>
                            <?php endif; ?>
                            <?php if($bubur['stok'] > 0): ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-[#4c3ce6] text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Tersedia</span>
                            <?php else: ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-red-500 text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Habis</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-base leading-tight mb-1 truncate"><?= htmlspecialchars($bubur['nama_barang']) ?></h3>
                        <p id="stock-<?= $bubur['id_barang'] ?>" data-stock="<?= $bubur['stok'] ?>" class="text-center text-xs text-gray-500 font-medium mb-1">Stok: <?= $bubur['stok'] ?></p>
                        <p class="text-center text-sm text-gray-800 font-semibold mb-3">Rp <?= number_format($bubur['harga_barang'], 0, ',', '.') ?></p>
                        <button onclick="addToCart(<?= $bubur['id_barang'] ?>, '<?= addslashes(htmlspecialchars($bubur['nama_barang'])) ?>', <?= $bubur['harga_barang'] ?>, <?=$bubur['stok'] ?>, 'bubur')" class="bg-[#111] text-white rounded-full py-2 flex justify-center items-center gap-1.5 hover:bg-gray-800 transition mx-1 text-sm font-bold shadow-md">
                            Tambah <span class="text-lg leading-none">+</span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/50 border border-gray-300 rounded-xl p-6 text-center text-gray-500 font-medium">
                    Produk belum tersedia
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4 text-gray-400"><hr class="border-gray-300 border-2 rounded-full"></div>
        
        <!-- ===== KATEGORI: TOPING ===== -->
        <div id="kategori-toping" class="scroll-mt-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-gray-800">Toping</h2>
            <?php if(count($produk_toping) > 0): ?>
                <div class="grid grid-cols-2 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                    <?php foreach($produk_toping as $toping): ?>
                    <div class="product-card bg-[#c9c5cb] rounded-2xl p-3 flex flex-col shadow-sm hover:shadow-md transition" data-name="<?= htmlspecialchars($toping['nama_barang']) ?>">
                        <div class="bg-white aspect-square rounded-xl flex items-center justify-center relative mb-3 shadow-sm overflow-hidden">
                            <?php if(!empty($toping['foto'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($toping['foto']) ?>" alt="<?= htmlspecialchars($toping['nama_barang']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-gray-400 font-bold text-lg">IMG</span>
                            <?php endif; ?>
                            <?php if($toping['stok'] > 0): ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-[#4c3ce6] text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Tersedia</span>
                            <?php else: ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-red-500 text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Habis</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-base leading-tight mb-1 truncate"><?= htmlspecialchars($toping['nama_barang']) ?></h3>
                        <p id="stock-<?= $toping['id_barang'] ?>" data-stock="<?= $toping['stok'] ?>" class="text-center text-xs text-gray-500 font-medium mb-1">Stok: <?= $toping['stok'] ?></p>
                        <p class="text-center text-sm text-gray-800 font-semibold mb-3">
                            <?= $toping['harga_barang'] > 0 ? 'Rp ' . number_format($toping['harga_barang'], 0, ',', '.') : 'Gratis' ?>
                        </p>
                        <button onclick="addToCart(<?= $toping['id_barang'] ?>, '<?= addslashes(htmlspecialchars($toping['nama_barang'])) ?>', <?= $toping['harga_barang'] ?>, <?=$toping['stok'] ?>, 'toping')" class="bg-[#111] text-white rounded-full py-2 flex justify-center items-center gap-1.5 hover:bg-gray-800 transition mx-1 text-sm font-bold shadow-md">
                            Tambah <span class="text-lg leading-none">+</span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/50 border border-gray-300 rounded-xl p-6 text-center text-gray-500 font-medium">
                    Produk belum tersedia
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4 text-gray-400 mt-8"><hr class="border-gray-300 border-2 rounded-full"></div>
        
        <!-- ===== KATEGORI: MINUMAN ===== -->
        <div id="kategori-minuman" class="scroll-mt-6">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-gray-800">Minuman</h2>
            <?php if(count($produk_minuman) > 0): ?>
                <div class="grid grid-cols-2 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                    <?php foreach($produk_minuman as$minum): ?>
                    <div class="product-card bg-[#c9c5cb] rounded-2xl p-3 flex flex-col shadow-sm hover:shadow-md transition" data-name="<?= htmlspecialchars($minum['nama_barang']) ?>">
                        <div class="bg-white aspect-square rounded-xl flex items-center justify-center relative mb-3 shadow-sm overflow-hidden">
                            <?php if(!empty($minum['foto'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($minum['foto']) ?>" alt="<?= htmlspecialchars($minum['nama_barang']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-gray-400 font-bold text-lg">IMG</span>
                            <?php endif; ?>
                            <?php if($minum['stok'] > 0): ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-[#4c3ce6] text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Tersedia</span>
                            <?php else: ?>
                                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-red-500 text-white text-[9px] px-2.5 py-0.5 rounded-full font-bold tracking-wide shadow-sm">Habis</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-base leading-tight mb-1 truncate"><?= htmlspecialchars($minum['nama_barang']) ?></h3>
                        <p id="stock-<?= $minum['id_barang'] ?>" data-stock="<?= $minum['stok'] ?>" class="text-center text-xs text-gray-500 font-medium mb-1">Stok: <?= $minum['stok'] ?></p>
                        <p class="text-center text-sm text-gray-800 font-semibold mb-3">Rp <?= number_format($minum['harga_barang'], 0, ',', '.') ?></p>
                        <button onclick="addToCart(<?= $minum['id_barang'] ?>, '<?= addslashes(htmlspecialchars($minum['nama_barang'])) ?>', <?= $minum['harga_barang'] ?>, <?=$minum['stok'] ?>, 'minuman')" class="bg-[#111] text-white rounded-full py-2 flex justify-center items-center gap-1.5 hover:bg-gray-800 transition mx-1 text-sm font-bold shadow-md">
                            Tambah <span class="text-lg leading-none">+</span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/50 border border-gray-300 rounded-xl p-6 text-center text-gray-500 font-medium">
                    Produk belum tersedia
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>
