<!-- ============ PANEL PESANAN (CART SIDEBAR) ============ -->
<aside class="w-[420px] bg-white rounded-[2rem] shadow-xl flex flex-col shrink-0 overflow-hidden border border-gray-100">
    <div class="p-6 pb-4">
        <h2 class="text-2xl font-bold text-center mb-5 text-gray-800">PESANAN</h2>
        <div class="w-full flex justify-center gap-2">
            <input type="text" id="customer-name" required placeholder="Nama..." class="w-3/5 border-2 border-gray-200 rounded-xl px-4 py-2.5 text-center text-base focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition-all placeholder-gray-400">
            <select id="meja-selection" required class="w-2/5 border-2 border-gray-200 rounded-xl px-2 py-2.5 text-center text-sm focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition-all text-gray-700 font-semibold bg-white cursor-pointer">
                <option value="" disabled selected>Pilih Meja</option>
                <option value="1">Meja 1</option>
                <option value="2">Meja 2</option>
                <option value="3">Meja 3</option>
                <option value="4">Meja 4</option>
                <option value="5">Meja 5</option>
            </select>
        </div>
    </div>

    <div id="cart-items" class="flex-1 overflow-y-auto p-6 pt-2 custom-scroll flex flex-col gap-3">
        <div id="empty-cart-msg" class="text-center text-gray-400 mt-10 font-medium">Belum ada pesanan</div>
    </div>

    <div class="p-6 bg-white border-t border-gray-100 shadow-[0_-10px_30px_rgba(0,0,0,0.03)] z-10 rounded-b-[2rem]">
        <div class="flex justify-between items-center mb-2 px-2">
            <p class="font-bold text-gray-500 text-sm">Subtotal :</p>
            <p id="subtotal-price" class="font-bold text-gray-700 text-sm">Rp 0</p>
        </div>
        <div class="flex justify-between items-center mb-4 px-2">
            <p class="font-bold text-gray-500 text-sm">Pajak (11%) :</p>
            <p id="tax-price" class="font-bold text-gray-700 text-sm">Rp 0</p>
        </div>
        <div class="flex justify-between items-center mb-6 px-2 border-t border-gray-200 pt-3">
            <p class="font-bold text-gray-600 text-lg">Total :</p>
            <p id="total-price" class="font-black text-gray-900 text-xl">Rp 0</p>
        </div>

        <div class="flex gap-3 justify-center mb-6">
            <button onclick="selectPayment(this, 'Cash')" class="payment-btn flex-1 bg-white border-2 border-gray-200 rounded-xl py-3 flex justify-center items-center hover:border-gray-400 transition shadow-sm group">
                <svg class="w-8 h-8 text-gray-600 group-hover:text-black transition pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </button>
            <button onclick="selectPayment(this, 'QRIS')" class="payment-btn flex-1 bg-white border-2 border-gray-200 rounded-xl py-3 flex justify-center items-center shadow-md transition group">
                <span class="font-black text-2xl italic tracking-tighter text-gray-600 group-hover:text-black transition pointer-events-none">QRIS</span>
            </button>
            <button onclick="selectPayment(this, 'Debit')" class="payment-btn flex-1 bg-white border-2 border-gray-200 rounded-xl py-3 flex justify-center items-center hover:border-gray-400 transition shadow-sm group">
                <svg class="w-8 h-8 text-gray-600 group-hover:text-black transition pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </button>
        </div>

        <div class="flex gap-4">
            <button onclick="clearCart()" class="flex-1 bg-[#cc0000] text-white font-bold py-4 rounded-xl hover:bg-red-800 transition uppercase tracking-wider text-lg shadow-[0_4px_14px_0_rgba(204,0,0,0.39)]">BATAL</button>
            <button onclick="checkout()" class="flex-1 bg-[#0cc00c] text-white font-bold py-4 rounded-xl hover:bg-green-700 transition uppercase tracking-wider text-lg shadow-[0_4px_14px_0_rgba(12,192,12,0.39)]">BAYAR</button>
        </div>
    </div>
</aside>
