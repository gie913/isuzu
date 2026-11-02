/**
 * Dynamic File & Image Uploader Helper with Cropper.js & Canvas Compression
 * Supports:
 * - Direct upload on file input change
 * - Reading setup attributes: data-module, data-name, data-path, data-is-external,
 *   data-allowed-files, data-is-resized, data-pic-height, data-pic-width,
 *   data-is-cropped, data-ratio-height, data-ratio-width, data-max-size, data-type
 * - Client-side image compression (HTML5 Canvas)
 * - Cropper.js modal integration when data-is-cropped="1"
 * - Instant upload with progress/loading UI
 * - Auto-populating target hidden input and preview container
 */

(function (window, document) {
    'use strict';

    let activeCropper = null;
    let currentCroppingContext = null;

    // Helper: format file size
    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    // Helper: compress image file via canvas
    function compressImageFile(file, maxWidth, maxHeight, quality, callback) {
        if (!file.type.startsWith('image/') || file.type.includes('svg')) {
            callback(file);
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.onload = function () {
                let width = img.width;
                let height = img.height;

                maxWidth = maxWidth || 1920;
                maxHeight = maxHeight || 1920;

                if (width > maxWidth || height > maxHeight) {
                    if (width / height > maxWidth / maxHeight) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                const mimeType = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
                canvas.toBlob(
                    function (blob) {
                        if (!blob || blob.size >= file.size) {
                            // If compression didn't reduce size, use original
                            callback(file);
                        } else {
                            const compressedFile = new File([blob], file.name, {
                                type: mimeType,
                                lastModified: Date.now()
                            });
                            callback(compressedFile);
                        }
                    },
                    mimeType,
                    quality || 0.82
                );
            };
            img.onerror = function () {
                callback(file);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Ensure Cropper Modal DOM exists
    function ensureCropperModal() {
        let modalEl = document.getElementById('globalCropperModal');
        if (modalEl) return modalEl;

        const modalHtml = `
        <div id="globalCropperModal" class="cropper-modal-backdrop" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(15,23,42,0.75); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;">
            <div class="cropper-modal-dialog" style="background:var(--bg-card, #ffffff); border:1px solid var(--border, #e2e8f0); border-radius:12px; width:100%; max-width:640px; max-height:90vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                <div class="cropper-modal-head" style="padding:14px 18px; border-bottom:1px solid var(--border, #e2e8f0); display:flex; align-items:center; justify-content:space-between;">
                    <div style="font-weight:600; font-size:15px; color:var(--t-body, #1e293b);">Crop & Adjust Image</div>
                    <button type="button" id="closeCropperModalBtn" style="background:transparent; border:none; color:var(--t-muted, #64748b); font-size:20px; line-height:1; cursor:pointer; padding:4px 8px; border-radius:4px;">&times;</button>
                </div>
                <div class="cropper-modal-body" style="padding:16px; flex:1; min-height:280px; max-height:55vh; background:#0f172a; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                    <img id="globalCropperImage" src="" style="max-width:100%; max-height:100%; display:block;" alt="Crop image">
                </div>
                <div class="cropper-modal-foot" style="padding:12px 18px; border-top:1px solid var(--border, #e2e8f0); display:flex; align-items:center; justify-content:space-between; gap:12px; background:var(--bg-sub, #f8fafc);">
                    <div style="font-size:12px; color:var(--t-muted, #64748b);">Use mouse or gestures to move and scale crop box.</div>
                    <div style="display:flex; gap:8px;">
                        <button type="button" id="cancelCropBtn" class="btn btn--ghost" style="padding:6px 14px; font-size:13px; height:auto;">Cancel</button>
                        <button type="button" id="confirmCropBtn" class="btn btn--primary" style="padding:6px 16px; font-size:13px; height:auto;">Crop & Upload</button>
                    </div>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        modalEl = document.getElementById('globalCropperModal');

        // Close listeners
        document.getElementById('closeCropperModalBtn').addEventListener('click', closeCropperModal);
        document.getElementById('cancelCropBtn').addEventListener('click', closeCropperModal);
        document.getElementById('confirmCropBtn').addEventListener('click', handleCroppedUpload);

        return modalEl;
    }

    function openCropperModal(file, context) {
        ensureCropperModal();
        currentCroppingContext = context;

        const reader = new FileReader();
        reader.onload = function (e) {
            const modalEl = document.getElementById('globalCropperModal');
            const imgEl = document.getElementById('globalCropperImage');

            if (activeCropper) {
                activeCropper.destroy();
                activeCropper = null;
            }

            imgEl.src = e.target.result;
            modalEl.style.display = 'flex';

            const ratioWidth = parseFloat(context.ratioWidth) || 1;
            const ratioHeight = parseFloat(context.ratioHeight) || 1;
            const aspectRatio = ratioHeight > 0 ? ratioWidth / ratioHeight : NaN;

            // Initialize Cropper.js
            if (typeof Cropper !== 'undefined') {
                activeCropper = new Cropper(imgEl, {
                    aspectRatio: aspectRatio,
                    viewMode: 1,
                    autoCropArea: 0.9,
                    responsive: true,
                    guides: true,
                    background: false,
                    movable: true,
                    rotatable: true,
                    scalable: true,
                    zoomable: true,
                });
            } else {
                console.warn('Cropper.js library is not loaded. Fallback to direct upload.');
                closeCropperModal();
                performDirectUpload(file, context);
            }
        };
        reader.readAsDataURL(file);
    }

    function closeCropperModal() {
        const modalEl = document.getElementById('globalCropperModal');
        if (modalEl) {
            modalEl.style.display = 'none';
        }
        if (activeCropper) {
            activeCropper.destroy();
            activeCropper = null;
        }
        currentCroppingContext = null;
    }

    function handleCroppedUpload() {
        if (!activeCropper || !currentCroppingContext) {
            closeCropperModal();
            return;
        }

        const context = currentCroppingContext;
        const targetWidth = parseInt(context.picWidth) || 600;
        const targetHeight = parseInt(context.picHeight) || 600;

        const canvasOptions = {};
        if (targetWidth > 0 && targetHeight > 0) {
            canvasOptions.width = targetWidth;
            canvasOptions.height = targetHeight;
        }

        const croppedCanvas = activeCropper.getCroppedCanvas(canvasOptions);
        const base64Data = croppedCanvas.toDataURL('image/jpeg', 0.88);

        closeCropperModal();

        // Send base64 cropped image to API
        uploadCroppedApi(base64Data, context);
    }

    function uploadCroppedApi(base64Data, context) {
        const previewEl = document.querySelector(context.preview);
        const setValueEl = document.querySelector(context.setValue);
        const submitBtn = document.querySelector('button[type="submit"]');

        if (previewEl) {
            previewEl.innerHTML = `
                <div class="upload-loading-state" style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:var(--t-muted, #64748b);">
                    <svg class="spin-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>
                    <span>Uploading cropped image...</span>
                </div>`;
        }

        if (submitBtn) submitBtn.disabled = true;

        const uploadUrl = context.apiUrl || '/api/v1/upload-cropped-image';

        fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                image: base64Data,
                m_module_id: context.moduleId,
                name: context.name
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (submitBtn) submitBtn.disabled = false;

            if (status >= 200 && status < 300 && (body.success || body.status === 'success')) {
                const resData = body.data || {};
                const fileUrl = resData.file_source || resData.url;
                const filePath = resData.path || fileUrl;

                if (setValueEl) {
                    setValueEl.value = filePath;
                    setValueEl.dispatchEvent(new Event('change', { bubbles: true }));
                }

                renderUploadPreview(previewEl, fileUrl, resData.file_extension || 'jpg', filePath, setValueEl);
            } else {
                const errMsg = body.message || 'Failed to upload cropped image.';
                if (previewEl) {
                    previewEl.innerHTML = `<span style="color:var(--danger, #ef4444); font-size:12px;">✕ ${errMsg}</span>`;
                }
            }
        })
        .catch(err => {
            if (submitBtn) submitBtn.disabled = false;
            console.error('Cropped upload error:', err);
            if (previewEl) {
                previewEl.innerHTML = `<span style="color:var(--danger, #ef4444); font-size:12px;">✕ Upload failed. Please try again.</span>`;
            }
        });
    }

    function performDirectUpload(file, context) {
        const previewEl = document.querySelector(context.preview);
        const setValueEl = document.querySelector(context.setValue);
        const submitBtn = document.querySelector('button[type="submit"]');

        if (previewEl) {
            previewEl.innerHTML = `
                <div class="upload-loading-state" style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:var(--t-muted, #64748b);">
                    <svg class="spin-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>
                    <span>Uploading ${file.name}...</span>
                </div>`;
        }

        if (submitBtn) submitBtn.disabled = true;

        // Compress image before uploading
        compressImageFile(
            file,
            parseInt(context.picWidth) || 1600,
            parseInt(context.picHeight) || 1600,
            0.82,
            function (processedFile) {
                const formData = new FormData();
                formData.append('file', processedFile);
                formData.append('m_module_id', context.moduleId);
                formData.append('name', context.name);

                const uploadUrl = context.apiUrl || '/api/v1/upload-file';

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (submitBtn) submitBtn.disabled = false;

                    if (status >= 200 && status < 300 && (body.success || body.status === 'success')) {
                        const resData = body.data || {};
                        const fileUrl = resData.file_source || resData.url;
                        const filePath = resData.path || fileUrl;

                        if (setValueEl) {
                            setValueEl.value = filePath;
                            setValueEl.dispatchEvent(new Event('change', { bubbles: true }));
                        }

                        renderUploadPreview(previewEl, fileUrl, resData.file_extension, filePath, setValueEl);
                    } else {
                        const errMsg = body.message || 'Upload failed.';
                        if (previewEl) {
                            previewEl.innerHTML = `<span style="color:var(--danger, #ef4444); font-size:12px;">✕ ${errMsg}</span>`;
                        }
                    }
                })
                .catch(err => {
                    if (submitBtn) submitBtn.disabled = false;
                    console.error('File upload error:', err);
                    if (previewEl) {
                        previewEl.innerHTML = `<span style="color:var(--danger, #ef4444); font-size:12px;">✕ Network error during upload.</span>`;
                    }
                });
            }
        );
    }

    function renderUploadPreview(container, url, ext, pathValue, setValueEl) {
        if (!container) return;

        const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        const isImage = imageExtensions.includes((ext || '').toLowerCase()) || url.match(/\.(jpg|jpeg|png|gif|webp|svg)($|\?)/i);

        let previewHtml = '';
        if (isImage) {
            previewHtml = `
            <div class="upload-preview-card" style="display:inline-flex; align-items:center; gap:12px; background:var(--bg-sub, #f8fafc); border:1px solid var(--border, #e2e8f0); border-radius:8px; padding:8px 12px; margin-top:8px;">
                <img src="${url}" alt="Preview" style="width:48px; height:48px; object-fit:cover; border-radius:6px; border:1px solid var(--border, #e2e8f0); cursor:pointer;" onclick="window.open('${url}','_blank')">
                <div style="font-size:12px; line-height:1.4;">
                    <div style="font-weight:600; color:var(--success, #10b981); display:flex; align-items:center; gap:4px;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Uploaded Successfully</span>
                    </div>
                    <a href="${url}" target="_blank" style="color:var(--t-body, #1e293b); text-decoration:underline;">View full image</a>
                </div>
                <button type="button" class="btn-remove-upload" style="background:transparent; border:none; color:var(--t-muted, #64748b); font-size:16px; cursor:pointer; padding:2px 6px; margin-left:auto; border-radius:4px;" title="Remove image">&times;</button>
            </div>`;
        } else {
            previewHtml = `
            <div class="upload-preview-card" style="display:inline-flex; align-items:center; gap:12px; background:var(--bg-sub, #f8fafc); border:1px solid var(--border, #e2e8f0); border-radius:8px; padding:8px 12px; margin-top:8px;">
                <div style="width:40px; height:40px; border-radius:6px; background:var(--bg-hover, #f1f5f9); display:flex; align-items:center; justify-content:center; color:var(--t-muted, #64748b);">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
                <div style="font-size:12px; line-height:1.4;">
                    <div style="font-weight:600; color:var(--success, #10b981);">File uploaded</div>
                    <a href="${url}" target="_blank" style="color:var(--t-body, #1e293b); text-decoration:underline;">Download file</a>
                </div>
                <button type="button" class="btn-remove-upload" style="background:transparent; border:none; color:var(--t-muted, #64748b); font-size:16px; cursor:pointer; padding:2px 6px; margin-left:auto; border-radius:4px;" title="Remove file">&times;</button>
            </div>`;
        }

        container.innerHTML = previewHtml;

        const removeBtn = container.querySelector('.btn-remove-upload');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                container.innerHTML = '';
                if (setValueEl) {
                    setValueEl.value = '';
                    setValueEl.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    }

    // Attach handler to input element
    function bindUploadInput(input) {
        if (input.dataset.uploadBound) return;
        input.dataset.uploadBound = 'true';

        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (!files || files.length === 0) return;

            const file = files[0];

            const context = {
                moduleId: input.getAttribute('data-module'),
                name: input.getAttribute('data-name'),
                preview: input.getAttribute('data-preview'),
                setValue: input.getAttribute('data-setvalue'),
                path: input.getAttribute('data-path'),
                isExternal: input.getAttribute('data-is-external') === '1',
                allowedFiles: input.getAttribute('data-allowed-files') || '',
                isResized: input.getAttribute('data-is-resized') === '1',
                picHeight: input.getAttribute('data-pic-height') || 0,
                picWidth: input.getAttribute('data-pic-width') || 0,
                isCropped: input.getAttribute('data-is-cropped') === '1',
                ratioHeight: input.getAttribute('data-ratio-height') || 1,
                ratioWidth: input.getAttribute('data-ratio-width') || 1,
                maxSize: parseInt(input.getAttribute('data-max-size')) || 2048,
                type: input.getAttribute('data-type') || 'image',
                apiUrl: input.getAttribute('data-api-url') || null,
            };

            // 1. Client-side extension validation
            if (context.allowedFiles) {
                const ext = file.name.split('.').pop().toLowerCase();
                const allowedList = context.allowedFiles.toLowerCase().split(',').map(s => s.trim());
                if (!allowedList.includes(ext)) {
                    alert('Invalid file format. Allowed extensions: ' + context.allowedFiles);
                    input.value = '';
                    return;
                }
            }

            // 2. Client-side max-size validation (maxSize is in KB)
            if (context.maxSize > 0 && file.size > context.maxSize * 1024) {
                alert('File size (' + formatBytes(file.size) + ') exceeds maximum limit of ' + context.maxSize + ' KB.');
                input.value = '';
                return;
            }

            // 3. If image & isCropped = 1, open Cropper.js modal
            if (context.isCropped && file.type.startsWith('image/')) {
                openCropperModal(file, context);
            } else {
                performDirectUpload(file, context);
            }
        });
    }

    // Auto-init on DOMContentLoaded and on any dynamic elements
    function initAllUploadInputs() {
        document.querySelectorAll('input.upload_file, input[data-module]').forEach(bindUploadInput);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllUploadInputs);
    } else {
        initAllUploadInputs();
    }

    // Expose global helper API
    window.AppUploader = {
        init: initAllUploadInputs,
        bind: bindUploadInput,
        openCropper: openCropperModal,
        compress: compressImageFile,
    };

})(window, document);
