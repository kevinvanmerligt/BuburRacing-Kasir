function openDeleteConfirmModal(id) {
    document.getElementById('confirm-delete-btn').href = 'kelolaproduk.php?delete=' + id;
    document.getElementById('delete-confirm-modal').classList.remove('hidden');
}

function closeDeleteConfirmModal() {
    document.getElementById('delete-confirm-modal').classList.add('hidden');
}

function closeDeleteSuccessModal() {
    document.getElementById('delete-success-modal').classList.add('hidden');
    if (window.history.replaceState) {
        window.history.replaceState(null, '', 'kelolaproduk.php');
    }
}

function searchProduct() {
    let input = document.getElementById('search-input').value.toLowerCase();
    let cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        let name = card.getAttribute('data-name').toLowerCase();
        if (name.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function scrollToCategory(id, btnEl) {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    if (btnEl) {
        const allBtns = document.querySelectorAll('.cat-btn');
        allBtns.forEach(btn => {
            btn.className = "cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300";
        });
        btnEl.className = "cat-btn px-8 py-2 bg-[#e0dce1] text-gray-800 font-semibold rounded-lg shadow-sm transition border border-transparent";
    }
}

function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
}

function setStatus(status) {
    document.getElementById('input-status').value = status;
    const btnTersedia = document.getElementById('btn-tersedia');
    const btnTidakTersedia = document.getElementById('btn-tidak-tersedia');

    if (status === 1) {
        btnTersedia.classList.add('bg-green-500', 'text-white', 'border-green-500', 'shadow-md');
        btnTersedia.classList.remove('text-gray-500', 'border-gray-200', 'bg-white', 'hover:bg-green-50');
        btnTidakTersedia.classList.add('text-gray-500', 'border-gray-200', 'bg-white', 'hover:bg-red-50');
        btnTidakTersedia.classList.remove('bg-red-500', 'text-white', 'border-red-500', 'shadow-md');
    } else {
        btnTidakTersedia.classList.add('bg-red-500', 'text-white', 'border-red-500', 'shadow-md');
        btnTidakTersedia.classList.remove('text-gray-500', 'border-gray-200', 'bg-white', 'hover:bg-red-50');
        btnTersedia.classList.add('text-gray-500', 'border-gray-200', 'bg-white', 'hover:bg-green-50');
        btnTersedia.classList.remove('bg-green-500', 'text-white', 'border-green-500', 'shadow-md');
    }
}

function openFormModal(mode, id = '', nama = '', jenis = 'Bubur', harga = '', stok = '', status = 1, foto = '') {
    const modal = document.getElementById('form-modal');

    document.getElementById('form-action').value = mode; 
    document.getElementById('input-id').value = id;
    document.getElementById('input-nama').value = nama;
    document.getElementById('input-jenis').value = jenis;
    
    document.getElementById('input-harga').value = harga ? formatRupiah(harga.toString(), 'Rp ') : '';
    
    document.getElementById('input-stok').value = stok;
    document.getElementById('input-foto-b64').value = ''; 

    const preview = document.getElementById('preview-image');
    const dropzoneText = document.getElementById('dropzone-text');
    if (foto !== '') {
        preview.src = '../uploads/' + foto;
        preview.classList.remove('hidden');
        dropzoneText.classList.add('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        dropzoneText.classList.remove('hidden');
    }

    if (stok <= 0 && mode === 'edit') {
        setStatus(0);
    } else {
        setStatus(status);
    }
    
    modal.classList.remove('hidden');
}

function closeFormModal() {
    document.getElementById('form-modal').classList.add('hidden');
}

function closeSuccessModal() {
    document.getElementById('success-modal').classList.add('hidden');
    if (window.history.replaceState) {
        window.history.replaceState(null, '', 'kelolaproduk.php');
    }
}

let cropper = null;

function handleFile(file) {
    if (!file.type.startsWith('image/')) {
        alert("Harap pilih file gambar (JPG/PNG)");
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const imageToCrop = document.getElementById('image-to-crop');
        imageToCrop.src = e.target.result;
        
        document.getElementById('cropper-modal').classList.remove('hidden');

        setTimeout(() => {
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1, 
                viewMode: 1,
                autoCropArea: 1,
                checkOrientation: false, 
                rotatable: false,        
                scalable: false,         
            });
        }, 300); 
    };
    reader.readAsDataURL(file);
}

function applyCrop() {
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({
        width: 600,
        height: 600
    });
    
    const base64Data = canvas.toDataURL('image/jpeg', 0.8);
    
    document.getElementById('input-foto-b64').value = base64Data;
    
    const preview = document.getElementById('preview-image');
    preview.src = base64Data;
    preview.classList.remove('hidden');
    document.getElementById('dropzone-text').classList.add('hidden');
    
    closeCropper();
}

function closeCropper() {
    document.getElementById('cropper-modal').classList.add('hidden');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    const fileInput = document.getElementById('fileInput');
    if (fileInput) fileInput.value = ''; 
}

// Inisialisasi event listener dan modal setelah DOM siap
document.addEventListener('DOMContentLoaded', function () {
    const inputHarga = document.getElementById('input-harga');
    if (inputHarga) {
        inputHarga.addEventListener('keyup', function (e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });
    }

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-[#572458]', 'bg-gray-200');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-[#572458]', 'bg-gray-200');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-[#572458]', 'bg-gray-200');
            if (e.dataTransfer.files.length > 0) {
                handleFile(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
    }

    // Cek parameter URL untuk menampilkan modal status
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        const successModal = document.getElementById('success-modal');
        if (successModal) successModal.classList.remove('hidden');
    }

    if (urlParams.has('deleted')) {
        const deleteSuccessModal = document.getElementById('delete-success-modal');
        if (deleteSuccessModal) deleteSuccessModal.classList.remove('hidden');
    }
});
