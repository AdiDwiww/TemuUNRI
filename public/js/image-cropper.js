/**
 * TemuUNRI - Image Cropper Module (Cropper.js)
 * Rasio Aspek Terkunci: 4:5 (Portrait)
 */
(function (window, document) {
    'use strict';

    function initImageCropper(config) {
        const fileInput         = document.getElementById(config.fileInputId || 'foto');
        const dropzone          = document.getElementById(config.dropzoneId || 'dropzone');
        const uploadPlaceholder = document.getElementById(config.uploadPlaceholderId || 'upload-placeholder');
        const previewContainer  = document.getElementById(config.previewContainerId || 'preview-container');
        const previewImg        = document.getElementById(config.previewImgId || 'foto-preview-img');
        const removePreviewBtn  = document.getElementById(config.removePreviewBtnId || 'remove-preview-btn');
        const reCropBtn         = document.getElementById(config.reCropBtnId || 'recrop-btn');

        // Modal Elements
        const modal        = document.getElementById(config.modalId || 'cropper-modal');
        const cropperImg   = document.getElementById(config.cropperImgId || 'cropper-modal-image');
        const confirmBtn   = document.getElementById(config.confirmBtnId || 'cropper-confirm-btn');
        const cancelBtn    = document.getElementById(config.cancelBtnId || 'cropper-cancel-btn');
        const closeBtn     = document.getElementById(config.closeBtnId || 'cropper-close-btn');
        const resetBtn     = document.getElementById(config.resetBtnId || 'cropper-reset-btn');
        const zoomInBtn    = document.getElementById(config.zoomInBtnId || 'cropper-zoom-in-btn');
        const zoomOutBtn   = document.getElementById(config.zoomOutBtnId || 'cropper-zoom-out-btn');
        const rotateBtn    = document.getElementById(config.rotateBtnId || 'cropper-rotate-btn');

        if (!fileInput || !modal || !cropperImg) {
            console.warn('[ImageCropper] Elemen penting tidak ditemukan pada halaman.');
            return;
        }

        let cropper = null;
        let originalImageSource = null;
        let originalFileName = 'foto_barang_4x5.jpg';
        let isCroppedConfirmed = false;

        const targetRatio  = config.aspectRatio || (4 / 5);
        const exportWidth  = config.exportWidth || 800;
        const exportHeight = config.exportHeight || 1000;
        const exportQuality = config.exportQuality || 0.85;

        function openModal(imageSrc) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            cropperImg.src = imageSrc;

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            // Inisialisasi Cropper.js dengan rasio 4:5 terkunci
            cropper = new Cropper(cropperImg, {
                aspectRatio: targetRatio,
                viewMode: 1, // Batasi crop box tidak melebihi kanvas
                dragMode: 'move', // Izinkan pengguna menggeser foto
                autoCropArea: 0.95,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                zoomable: true,
                rotatable: true,
                scalable: true,
                ready: function () {
                    // Pastikan rasio crop terkunci 4:5
                    cropper.setAspectRatio(targetRatio);
                }
            });
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        function handleFileSelection(file) {
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar yang valid (JPG, PNG, atau WEBP).');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file foto terlalu besar. Silakan pilih foto dengan ukuran maksimal 10 MB.');
                return;
            }

            originalFileName = file.name || 'foto_barang_4x5.jpg';

            const reader = new FileReader();
            reader.onload = function (e) {
                originalImageSource = e.target.result;
                openModal(originalImageSource);
            };
            reader.readAsDataURL(file);
        }

        // Listener Input File
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleFileSelection(this.files[0]);
            }
        });

        // Drag & Drop pada Dropzone
        if (dropzone) {
            dropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropzone.classList.add('border-navy-800', 'bg-navy-50/40');
            });

            dropzone.addEventListener('dragleave', function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-navy-800', 'bg-navy-50/40');
            });

            dropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-navy-800', 'bg-navy-50/40');
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                    handleFileSelection(e.dataTransfer.files[0]);
                }
            });
        }

        // Tombol Toolbar Cropper
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', function () {
                if (cropper) cropper.zoom(0.1);
            });
        }

        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', function () {
                if (cropper) cropper.zoom(-0.1);
            });
        }

        if (rotateBtn) {
            rotateBtn.addEventListener('click', function () {
                if (cropper) cropper.rotate(90);
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (cropper) {
                    cropper.reset();
                    cropper.setAspectRatio(targetRatio);
                }
            });
        }

        // Konfirmasi Hasil Crop
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (!cropper) return;

                // Ekspor kanvas beresolusi tinggi dengan rasio presisi 4:5 (800 x 1000 px)
                const canvas = cropper.getCroppedCanvas({
                    width: exportWidth,
                    height: exportHeight,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) {
                    alert('Gagal memproses crop foto.');
                    return;
                }

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        alert('Gagal menghasilkan file foto crop.');
                        return;
                    }

                    // Buat file baru berformat JPEG untuk efisiensi ukuran
                    const croppedFile = new File([blob], originalFileName.replace(/\.[^/.]+$/, "") + "_crop4x5.jpg", {
                        type: 'image/jpeg',
                        lastModified: Date.now(),
                    });

                    // Masukkan ke input[type="file"] via DataTransfer
                    try {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(croppedFile);
                        fileInput.files = dataTransfer.files;
                    } catch (err) {
                        console.error('[ImageCropper] DataTransfer tidak didukung:', err);
                    }

                    // Tampilkan preview foto 4:5 yang telah dipotong
                    if (previewImg) {
                        previewImg.src = canvas.toDataURL('image/jpeg', exportQuality);
                    }

                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');

                    isCroppedConfirmed = true;
                    closeModal();

                    if (typeof config.onSuccess === 'function') {
                        config.onSuccess(croppedFile);
                    }
                }, 'image/jpeg', exportQuality);
            });
        }

        // Batal Crop
        function handleCancel() {
            closeModal();
            // Jika belum ada foto yang berhasil dikonfirmasi sebelumnya, bersihkan input
            if (!isCroppedConfirmed) {
                fileInput.value = '';
                if (previewContainer) previewContainer.classList.add('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            }
        }

        if (cancelBtn) cancelBtn.addEventListener('click', handleCancel);
        if (closeBtn) closeBtn.addEventListener('click', handleCancel);

        // Tombol Atur Ulang / Crop Ulang dari area preview form
        if (reCropBtn) {
            reCropBtn.addEventListener('click', function () {
                if (originalImageSource) {
                    openModal(originalImageSource);
                } else if (fileInput.files && fileInput.files[0]) {
                    handleFileSelection(fileInput.files[0]);
                }
            });
        }

        // Hapus Foto Pilihan
        if (removePreviewBtn) {
            removePreviewBtn.addEventListener('click', function () {
                fileInput.value = '';
                originalImageSource = null;
                isCroppedConfirmed = false;

                if (previewImg) previewImg.src = '#';
                if (previewContainer) previewContainer.classList.add('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            });
        }
    }

    window.initImageCropper = initImageCropper;
})(window, document);

