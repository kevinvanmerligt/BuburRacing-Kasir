<?php ?>
<div id="detail-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[380px] rounded-3xl shadow-2xl relative p-8 flex flex-col animate-pop max-h-[90vh] overflow-y-auto custom-scroll">
        
        <div class="text-center mb-6">
            <h2 class="font-bold text-xl tracking-wide">RIWAYAT ORDER</h2>
            <h3 class="font-bold text-lg" id="modal-order-id">#BR-0000</h3>
            <p class="font-semibold text-gray-800 text-sm mt-1" id="modal-tanggal">pada tanggal -</p>
        </div>

        <hr class="border-black border-[1px] mb-3">
        
        <div class="flex justify-between text-sm font-bold mb-3 px-1">
            <span>Produk</span>
            <span>Harga</span>
        </div>
        
        <div id="modal-produk-list" class="mb-4 px-1">
        </div>

        <hr class="border-black border-[1px] mb-3">

        <div class="flex flex-col gap-1 text-sm font-semibold text-gray-800 px-1">
            <div class="flex justify-between">
                <span>Total QTY : <span id="modal-qty">0</span></span>
            </div>
            <div class="flex justify-between mt-2">
                <span>Subtotal</span>
                <span id="modal-subtotal">Rp. 0</span>
            </div>
            <div class="flex justify-between">
                <span>Total (Inc. Pajak)</span>
                <span id="modal-total">Rp. 0</span>
            </div>
            <div class="flex justify-between mt-2 pt-2 border-t border-gray-200 text-gray-800 font-bold">
                <span>Metode</span>
                <span id="modal-metode">CASH</span>
            </div>
            <div class="flex justify-between text-black font-bold">
                <span>Bayar</span>
                <span id="modal-bayar">Rp. 0</span>
            </div>
            <div class="flex justify-between font-bold text-gray-500">
                <span>Kembali</span>
                <span id="modal-kembali">Rp. 0</span>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <button onclick="closeDetailModal()" class="border-2 border-gray-300 rounded-xl px-10 py-2.5 font-bold text-gray-800 hover:bg-gray-100 transition shadow-sm bg-white w-full">
                Selesai
            </button>
        </div>
    </div>
</div>
