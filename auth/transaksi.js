function formatInputRupiah(el) {
    let value = el.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    el.value = rupiah;
    hitungKembalian();
}

function setNominal(amount) {
    const inputNominal = document.getElementById('input-nominal');
    if (inputNominal) {
        inputNominal.value = amount.toLocaleString('id-ID');
        hitungKembalian();
    }
}

function checkout() {
    if (cart.length === 0) {
        showAlert("Pesanan masih kosong!");
        return;
    }
    if (!selectedPaymentMethod) {
        showAlert("Silakan pilih metode pembayaran terlebih dahulu!");
        return;
    }

    if (selectedPaymentMethod === 'QRIS' || selectedPaymentMethod === 'Debit') {
        showAlert("Masih proses pengembangan");
        return;
    }

    const namaVal = document.getElementById('customer-name').value.trim();
    if (!namaVal) {
        showAlert("Silakan isi Nama pelanggan terlebih dahulu!");
        return;
    }

    const mejaVal = document.getElementById('meja-selection').value;
    if (!mejaVal) {
        showAlert("Silakan pilih Meja terlebih dahulu!");
        return;
    }

    renderModalContent('checkout');
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('payment-modal').classList.add('hidden');
    if (document.getElementById('success-icon')) {
        window.location.reload();
    }
}

function hitungKembalian() {
    const inputNominalRaw = document.getElementById('input-nominal').value.replace(/\./g, '');
    const inputNominal = parseInt(inputNominalRaw) || 0;
    const kembaliEl = document.getElementById('teks-kembali');

    if (inputNominal >= globalTotal) {
        const kembalian = inputNominal - globalTotal;
        kembaliEl.innerText = formatRupiah(kembalian);
    } else {
        kembaliEl.innerText = "Rp 0";
    }
}

async function processCheckoutToDB(uang, kembalian, nama, meja, metode) {
    let flatCart = {};
    for (let bundle of cart) {
        if (!flatCart[bundle.main.id]) {
            flatCart[bundle.main.id] = { price: bundle.main.price, qty: 0 };
        }
        flatCart[bundle.main.id].qty += bundle.main.qty;

        for (let addon of bundle.addons) {
            if (!flatCart[addon.id]) {
                flatCart[addon.id] = { price: addon.price, qty: 0 };
            }
            flatCart[addon.id].qty += addon.qty;
        }
    }

    try {
        const response = await fetch('produk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'checkout',
                uang: uang,
                kembalian: kembalian,
                pajak: globalTax,
                nama: nama,
                meja: meja,
                metode: metode,
                cart: flatCart
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            renderModalContent('success');
        } else {
            showAlert("Gagal memproses transaksi:\n" + result.message);
            const btn = document.getElementById('btn-verify');
            if (btn) btn.innerText = 'Coba Lagi';
        }
    } catch (error) {
        console.error("Error:", error);
        showAlert("Terjadi masalah jaringan.");
        const btn = document.getElementById('btn-verify');
        if (btn) btn.innerText = 'Coba Lagi';
    }
}

function verifyPayment() {
    let uang = 0;
    let kembalian = 0;
    const namaVal = document.getElementById('customer-name').value.trim();
    const mejaVal = document.getElementById('meja-selection').value;

    if (selectedPaymentMethod === 'Cash') {
        const inputNominalRaw = document.getElementById('input-nominal').value.replace(/\./g, '');
        const inputNominal = parseInt(inputNominalRaw) || 0;

        if (!inputNominal || inputNominal < globalTotal) {
            showAlert("Nominal pembayaran kurang!");
            return;
        }
        uang = inputNominal;
        kembalian = uang - globalTotal;
    } else {
        uang = globalTotal;
        kembalian = 0;
    }

    const btn = document.getElementById('btn-verify');
    if (btn) btn.innerText = 'Memproses...';

    processCheckoutToDB(uang, kembalian, namaVal, mejaVal, selectedPaymentMethod);
}

function renderModalContent(state) {
    const container = document.getElementById('modal-content');
    let itemsHtml = '';

    for (let i = 0; i < cart.length; i++) {
        let bundle = cart[i];
        itemsHtml += `
        <div class="flex justify-between items-center text-sm mb-1">
            <div class="flex-1 text-gray-800 font-bold">${bundle.main.name}</div>
            <div class="w-8 text-center text-gray-800 font-bold">${bundle.main.qty}</div>
            <div class="w-24 text-right text-gray-800 font-bold">${bundle.main.price === 0 ? 'Gratis' : formatRupiah(bundle.main.price * bundle.main.qty)}</div>
        </div>`;

        for (let j = 0; j < bundle.addons.length; j++) {
            let addon = bundle.addons[j];
            itemsHtml += `
            <div class="flex justify-between items-center text-xs mb-1 text-gray-500 pl-3">
                <div class="flex-1 font-medium">+ ${addon.name}</div>
                <div class="w-8 text-center font-medium">${addon.qty}</div>
                <div class="w-24 text-right font-medium">${addon.price === 0 ? 'Gratis' : formatRupiah(addon.price * addon.qty)}</div>
            </div>`;
        }
        itemsHtml += `<div class="mb-2"></div>`;
    }

    let html = '';
    if (state === 'checkout') {
        let iconHtml = '';
        let title = '';
        let inputHtml = '';
        let btnText = 'Cek Pembayaran';

        if (selectedPaymentMethod === 'Cash') {
            iconHtml = `<svg class="w-16 h-16 mx-auto mb-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>`;
            title = "PEMBAYARAN CASH";
            btnText = "Cetak Struk";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-3 text-gray-700 mt-4">
                    <span>Bayar (Cash)</span>
                    <div class="relative w-32">
                        <span class="absolute inset-y-0 left-2 flex items-center text-sm text-gray-500">Rp</span>
                        <input type="text" id="input-nominal" oninput="formatInputRupiah(this)" placeholder="0" class="w-full border border-gray-400 rounded px-2 pl-7 py-1 text-right text-sm focus:outline-none focus:border-black font-semibold">
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-1.5 mb-4">
                    <button onclick="setNominal(${globalTotal})" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">Uang Pas</button>
                    <button onclick="setNominal(50000)" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">50.000</button>
                    <button onclick="setNominal(100000)" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">100.000</button>
                </div>

                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span id="teks-kembali" class="w-32 border border-gray-300 bg-gray-100 rounded px-2 py-1 text-right text-sm text-gray-500">Rp 0</span>
                </div>
            `;
        } else if (selectedPaymentMethod === 'QRIS') {
            iconHtml = `<div class="w-24 h-24 mx-auto mb-3 bg-gray-200 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-lg"><span class="text-xs font-bold text-gray-500">QR CODE</span></div>`;
            title = "PEMBAYARAN QRIS";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-2 text-gray-700 mt-4">
                    <span>Bayar (QRIS)</span>
                    <span>${formatRupiah(globalTotal)}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span>Rp 0</span>
                </div>
            `;
        } else {
            iconHtml = `<svg class="w-20 h-20 mx-auto mb-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 4v10h16V8H4zm2 2h8v2H6v-2z"></path></svg>`;
            title = "PEMBAYARAN DEBIT";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-2 text-gray-700 mt-4">
                    <span>Bayar (Debit)</span>
                    <span>${formatRupiah(globalTotal)}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span>Rp 0</span>
                </div>
            `;
        }

        html = `
            <div class="text-center mb-4">
                ${iconHtml}
                <h3 class="font-bold text-lg tracking-wide uppercase">${title}</h3>
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-bold mb-3">
                <span>Produk</span>
                <span>Harga</span>
            </div>
            <div class="mb-2">
                ${itemsHtml}
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm mb-1 text-gray-600">
                <span>Total QTY : ${globalTotalQty}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Subtotal</span>
                <span>${formatRupiah(globalSubtotal)}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Pajak (11%)</span>
                <span>${formatRupiah(globalTax)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold mb-4 mt-2 text-gray-900 border-t border-gray-300 pt-2">
                <span>Total Bayar</span>
                <span>${formatRupiah(globalTotal)}</span>
            </div>
            ${inputHtml}
            <div class="mt-6 flex justify-center">
                <button id="btn-verify" onclick="verifyPayment()" class="border-2 border-gray-300 rounded-lg px-6 py-2.5 font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm w-full">${btnText}</button>
            </div>
        `;
    } else if (state === 'success') {
        html = `
            <div class="text-center mb-4 mt-2" id="success-icon">
                <div class="w-16 h-16 mx-auto bg-blue-500 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="font-bold text-lg tracking-wide uppercase">PEMBAYARAN BERHASIL</h3>
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-bold mb-3">
                <span>Produk</span>
                <span>Harga</span>
            </div>
            <div class="mb-2">
                ${itemsHtml}
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Subtotal</span>
                <span>${formatRupiah(globalSubtotal)}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Pajak (11%)</span>
                <span>${formatRupiah(globalTax)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold mb-4 mt-2 text-gray-900 border-t border-gray-300 pt-2">
                <span>Total Bayar</span>
                <span>${formatRupiah(globalTotal)}</span>
            </div>
            <div class="mt-6 flex justify-center">
                <button onclick="closeModal()" class="border-2 border-gray-300 rounded-lg px-6 py-2.5 font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm w-full">Tutup & Selesai</button>
            </div>
        `;
    }

    container.innerHTML = html;
}
