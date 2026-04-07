import './bootstrap';
import Swal from 'sweetalert2';
import { Html5Qrcode } from "html5-qrcode";

window.Swal = Swal;

const interactiveFieldSelector = [
    'input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"])',
    'select',
    'textarea',
].join(', ');

const setSidebarCollapsed = (sidebar, collapsed) => {
    if (!sidebar) {
        return;
    }

    const toggleIcon = sidebar.querySelector('[data-sidebar-toggle-icon]');
    const textNodes = sidebar.querySelectorAll('.sidebar-text');
    const items = sidebar.querySelectorAll('.desktop-nav-item');

    sidebar.classList.toggle('w-20', collapsed);
    sidebar.classList.toggle('w-72', !collapsed);
    sidebar.dataset.collapsed = collapsed ? 'true' : 'false';

    if (toggleIcon) {
        toggleIcon.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    textNodes.forEach((node) => node.classList.toggle('hidden', collapsed));
    items.forEach((item) => {
        item.classList.toggle('justify-center', collapsed);
        item.classList.toggle('gap-3', !collapsed);
        item.classList.toggle('gap-0', collapsed);
    });
};

const showToast = ({ icon = 'success', title = '' }) => {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2800,
        timerProgressBar: true,
        icon,
        title,
        customClass: {
            popup: 'rounded-2xl border-none shadow-xl font-body bg-white',
            title: 'text-sm font-bold text-on-surface',
        },
    });
};

const getFieldLabel = (field) => {
    if (field.dataset.label) {
        return field.dataset.label;
    }

    if (field.id) {
        const label = document.querySelector(`label[for="${field.id}"]`);
        if (label) {
            return label.textContent.replace(/\*/g, '').trim();
        }
    }

    return field.name.replaceAll('_', ' ').trim();
};

const getFieldErrorMessage = (field) => {
    const label = getFieldLabel(field);

    if (field.validity.valueMissing) {
        return `${label} wajib diisi.`;
    }

    if (field.validity.typeMismatch) {
        if (field.type === 'email') {
            return `Format ${label} tidak valid.`;
        }

        return `${label} tidak valid.`;
    }

    if (field.validity.tooShort) {
        return `${label} minimal ${field.minLength} karakter.`;
    }

    if (field.validity.tooLong) {
        return `${label} maksimal ${field.maxLength} karakter.`;
    }

    if (field.validity.rangeUnderflow || field.validity.rangeOverflow) {
        return `${label} berada di luar rentang yang diizinkan.`;
    }

    if (field.validity.patternMismatch) {
        return `${label} tidak sesuai format yang diharapkan.`;
    }

    if (field.validity.badInput) {
        return `${label} tidak valid.`;
    }

    return '';
};

const getOrCreateErrorNode = (field) => {
    let errorNode = field.parentElement?.querySelector('.form-error[data-generated-error="true"]');

    if (!errorNode) {
        errorNode = document.createElement('p');
        errorNode.className = 'form-error hidden';
        errorNode.dataset.generatedError = 'true';
        field.insertAdjacentElement('afterend', errorNode);
    }

    return errorNode;
};

const setFieldValidationState = (field, message) => {
    const errorNode = getOrCreateErrorNode(field);
    const hasError = Boolean(message);

    field.classList.toggle('field-invalid', hasError);
    errorNode.classList.toggle('hidden', !hasError);
    errorNode.textContent = message;
};

const validateField = (field) => {
    if (!field.willValidate || field.disabled) {
        return true;
    }

    field.setCustomValidity('');
    const message = getFieldErrorMessage(field);
    setFieldValidationState(field, message);

    return !message;
};

const getEffectiveMethod = (form) => {
    const spoofMethod = form.querySelector('input[name="_method"]')?.value;
    return (spoofMethod || form.method || 'GET').toUpperCase();
};

const getLoadingText = (button) => {
    if (button.dataset.loadingText) {
        return button.dataset.loadingText;
    }

    const label = button.textContent.replace(/\s+/g, ' ').trim().toLowerCase();

    if (label.includes('simpan')) {
        return 'Menyimpan...';
    }

    if (label.includes('ubah') || label.includes('perbarui') || label.includes('update')) {
        return 'Memperbarui...';
    }

    if (label.includes('masuk')) {
        return 'Memproses...';
    }

    return 'Memproses...';
};

const setButtonLoading = (button) => {
    if (!button || button.dataset.loadingApplied === 'true') {
        return;
    }

    button.dataset.loadingApplied = 'true';
    button.dataset.originalHtml = button.innerHTML;
    button.disabled = true;
    button.classList.add('opacity-80', 'cursor-not-allowed');
    button.innerHTML = `
        <span class="inline-flex items-center gap-2">
            <span class="material-symbols-outlined animate-spin text-base">progress_activity</span>
            <span>${getLoadingText(button)}</span>
        </span>
    `;
};

const enhanceForms = () => {
    const forms = document.querySelectorAll('form[data-enhanced-form]');

    forms.forEach((form) => {
        form.setAttribute('novalidate', 'novalidate');

        const fields = [...form.querySelectorAll(interactiveFieldSelector)].filter((field) => field.name);

        fields.forEach((field) => {
            const eventName = field.tagName === 'SELECT' ? 'change' : 'input';

            field.addEventListener(eventName, () => validateField(field));
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            const method = getEffectiveMethod(form);
            const invalidFields = fields.filter((field) => !validateField(field));

            if (invalidFields.length > 0) {
                event.preventDefault();
                invalidFields[0].focus();
                showToast({
                    icon: 'error',
                    title: 'Masih ada field yang perlu diperbaiki.',
                });
                return;
            }

            if (method !== 'GET') {
                const submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
                setButtonLoading(submitter);
            }
        });
    });
};

const initAdminShell = () => {
    const desktopSidebar = document.querySelector('[data-sidebar="desktop"]');
    const desktopToggle = document.querySelector('[data-sidebar-toggle]');
    const mobileSidebar = document.querySelector('[data-sidebar="mobile"]');
    const mobileOverlay = document.querySelector('[data-mobile-overlay]');
    const mobileContent = document.querySelector('[data-mobile-content]');
    const mobileOpen = document.querySelector('[data-mobile-drawer-toggle]');
    const mobileClose = document.querySelector('[data-mobile-drawer-close]');
    const profileMenu = document.querySelector('[data-profile-menu]');
    const profileToggle = document.querySelector('[data-profile-toggle]');
    const profilePanel = document.querySelector('[data-profile-panel]');
    const profileChevron = document.querySelector('[data-profile-chevron]');
    const mobileNavLinks = document.querySelectorAll('[data-mobile-nav-link]');
    const sidebarCollapsed = localStorage.getItem('smarschool-sidebar-collapsed') === 'true';

    if (desktopSidebar) {
        setSidebarCollapsed(desktopSidebar, sidebarCollapsed);
    }

    desktopToggle?.addEventListener('click', () => {
        const nextState = desktopSidebar?.dataset.collapsed !== 'true';
        setSidebarCollapsed(desktopSidebar, nextState);
        localStorage.setItem('smarschool-sidebar-collapsed', nextState ? 'true' : 'false');
    });

    const openMobileDrawer = () => {
        if (!mobileSidebar || !mobileOverlay || !mobileContent) {
            return;
        }

        mobileSidebar.classList.remove('pointer-events-none');
        mobileOverlay.classList.remove('opacity-0');
        mobileContent.classList.remove('-translate-x-full');
    };

    const closeMobileDrawer = () => {
        if (!mobileSidebar || !mobileOverlay || !mobileContent) {
            return;
        }

        mobileSidebar.classList.add('pointer-events-none');
        mobileOverlay.classList.add('opacity-0');
        mobileContent.classList.add('-translate-x-full');
    };

    mobileOpen?.addEventListener('click', openMobileDrawer);
    mobileClose?.addEventListener('click', closeMobileDrawer);
    mobileOverlay?.addEventListener('click', closeMobileDrawer);
    mobileNavLinks.forEach((link) => link.addEventListener('click', closeMobileDrawer));

    if (profileMenu && profileToggle && profilePanel) {
        const setProfileMenuState = (open) => {
            profilePanel.dataset.open = open ? 'true' : 'false';
            profileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');

            if (profileChevron) {
                profileChevron.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        };

        setProfileMenuState(false);

        profileToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = profilePanel.dataset.open === 'true';
            setProfileMenuState(!isOpen);
        });

        document.addEventListener('click', (event) => {
            if (!profileMenu.contains(event.target)) {
                setProfileMenuState(false);
            }
        });
    }
};

const initFlashMessage = () => {
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        showToast({
            icon: flash.dataset.flashType || 'success',
            title: flash.dataset.flashText || '',
        });
    });
};

const initDeleteConfirmations = () => {
    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const result = await Swal.fire({
                title: 'Hapus data ini?',
                text: 'Tindakan ini tidak bisa dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl border-none shadow-2xl font-body p-7',
                    confirmButton: 'bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-red-700 transition-all',
                    cancelButton: 'bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all',
                    title: 'headline-text font-black text-on-surface text-xl',
                    htmlContainer: 'text-on-surface-variant text-sm leading-relaxed mt-3',
                },
                buttonsStyling: false,
            });

            if (result.isConfirmed) {
                const submitButton = form.querySelector('button[type="submit"]');
                setButtonLoading(submitButton);
                form.submit();
            }
        });
    });
};


const playAudioTone = (type) => {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        
        const audioCtx = new AudioContext();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        const now = audioCtx.currentTime;

        if (type === 'success') {
            // High double chirp for success
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, now);
            oscillator.frequency.exponentialRampToValueAtTime(1760, now + 0.1);

            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.05);
            gainNode.gain.linearRampToValueAtTime(0, now + 0.15);

            oscillator.start(now);
            oscillator.stop(now + 0.15);
        } else if (type === 'info') {
            // Medium double beep for already scanned
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(659.25, now); // E5

            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.05);
            gainNode.gain.linearRampToValueAtTime(0, now + 0.1);

            gainNode.gain.setValueAtTime(0, now + 0.15);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.2);
            gainNode.gain.linearRampToValueAtTime(0, now + 0.25);

            oscillator.start(now);
            oscillator.stop(now + 0.3);
        } else if (type === 'error') {
            // Long low buzz for error
            oscillator.type = 'sawtooth';
            oscillator.frequency.setValueAtTime(150, now);

            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.05);
            gainNode.gain.linearRampToValueAtTime(0, now + 0.4);

            oscillator.start(now);
            oscillator.stop(now + 0.4);
        }
    } catch (e) {
        console.warn('Audio playback failed or not supported:', e);
    }
};

const initQrAttendance = () => {
    const scanners = document.querySelectorAll('[data-qr-attendance]');
    
    if (scanners.length === 0) {
        console.log('No QR attendance scanner found');
        return;
    }
    
    console.log(`Found ${scanners.length} QR scanner(s)`);

    scanners.forEach((container, index) => {
        const canScan = container.dataset.canScan === 'true';
        const endpoint = container.dataset.scanEndpoint;
        const csrfToken = container.dataset.csrfToken;
        const videoElement = container.querySelector('[data-qr-video]');
        const placeholder = container.querySelector('[data-qr-placeholder]');
        const startButton = container.querySelector('[data-qr-start]');
        const stopButton = container.querySelector('[data-qr-stop]');
        const manualForm = container.querySelector('[data-qr-manual-form]');
        const input = container.querySelector('[data-qr-input]');
        const statusNode = container.querySelector('[data-qr-status]');
        
        let html5QrCode = null;
        let isProcessing = false;
        let lastProcessedCode = '';
        let lastProcessedAt = 0;

        console.log(`Scanner ${index}: canScan=${canScan}, video element exists=${!!videoElement}`);

        const setStatus = (message, type = 'info') => {
            if (!statusNode) return;
            
            const toneMap = {
                info: 'text-on-surface-variant',
                success: 'text-emerald-700',
                error: 'text-rose-700',
            };
            
            statusNode.className = `mt-2 text-sm ${toneMap[type] || toneMap.info}`;
            statusNode.textContent = message;
            console.log(`Status: ${type} - ${message}`);
        };

        const stopScanner = async () => {
            console.log('Stopping scanner...');
            if (html5QrCode && html5QrCode.isScanning) {
                try {
                    await html5QrCode.stop();
                    await html5QrCode.clear();
                    console.log('Scanner stopped successfully');
                } catch (err) {
                    console.error('Error stopping scanner:', err);
                }
            }
            
            html5QrCode = null;
            if (stopButton) stopButton.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            setStatus('Kamera dihentikan. Anda masih bisa memakai input kode manual.', 'info');
        };

        const processCode = async (kode, submitButton = null) => {
            if (!kode || !endpoint || !csrfToken || !canScan || isProcessing) return;
            
            const trimmedCode = kode.trim();
            const nowTime = Date.now();
            
            if (trimmedCode === lastProcessedCode && nowTime - lastProcessedAt < 3000) return;
            
            isProcessing = true;
            console.log('Processing code:', trimmedCode);
            
            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ kode: trimmedCode }),
                });
                
                const payload = await response.json().catch(() => ({}));
                
                if (!response.ok) {
                    const message = payload.message || 'Scan QR gagal diproses.';
                    setStatus(message, 'error');
                    playAudioTone('error');
                    showToast({ icon: 'error', title: message });
                    return;
                }
                
                lastProcessedCode = trimmedCode;
                lastProcessedAt = nowTime;
                
                // Update student row
                const row = document.querySelector(`[data-siswa-row][data-siswa-id="${payload.detail?.siswa_id}"]`);
                if (row) {
                    const statusSelect = row.querySelector('[data-status-input]');
                    const checkedNode = row.querySelector('[data-checked-at]');
                    if (statusSelect && payload.detail) statusSelect.value = payload.detail.status;
                    if (checkedNode && payload.detail) checkedNode.textContent = payload.detail.checked_at || '-';
                }
                
                // Recalculate summary
                const counts = { Hadir: 0, Izin: 0, Sakit: 0, Alpa: 0 };
                document.querySelectorAll('[data-siswa-row] [data-status-input]').forEach((inputNode) => {
                    if (counts[inputNode.value] !== undefined) counts[inputNode.value] += 1;
                });
                Object.entries(counts).forEach(([label, count]) => {
                    const summaryNode = document.querySelector(`[data-summary-count="${label}"]`);
                    if (summaryNode) summaryNode.textContent = count;
                });
                
                setStatus(payload.message, 'success');
                
                if (payload.already_present) {
                    playAudioTone('info');
                } else {
                    playAudioTone('success');
                }
                
                showToast({
                    icon: payload.already_present ? 'info' : 'success',
                    title: payload.message,
                });
                
                if (input) {
                    input.value = '';
                    input.focus();
                }
            } catch (error) {
                console.error('Scan error:', error);
                setStatus('Terjadi gangguan saat memproses scan QR.', 'error');
                playAudioTone('error');
                showToast({ icon: 'error', title: 'Terjadi gangguan saat memproses scan QR.' });
            } finally {
                isProcessing = false;
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButton.dataset.originalHtml || 'Proses Scan';
                }
            }
        };

        startButton?.addEventListener('click', async () => {
            console.log('Start button clicked');
            
            if (!canScan) {
                setStatus('Scanner hanya aktif saat jadwal pelajaran berlangsung.', 'error');
                showToast({ icon: 'error', title: 'Scanner tidak aktif' });
                return;
            }
            
            if (!videoElement) {
                console.error('Video element not found!');
                setStatus('Error: Elemen video tidak ditemukan.', 'error');
                return;
            }
            
            // Stop existing scanner
            if (html5QrCode && html5QrCode.isScanning) {
                await stopScanner();
            }
            
            try {
                // Give video element a unique ID if it doesn't have one
                if (!videoElement.id) {
                    videoElement.id = `qr-video-${Date.now()}`;
                    console.log('Assigned ID to video element:', videoElement.id);
                }
                
                console.log('Creating Html5Qrcode instance with element:', videoElement.id);
                html5QrCode = new Html5Qrcode(videoElement.id);
                
                // Request camera permission first
                console.log('Requesting camera permission...');
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'environment' } 
                });
                
                // Stop the temporary stream
                stream.getTracks().forEach(track => track.stop());
                
                console.log('Camera permission granted, starting scanner...');
                
                // Start scanning
                await html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText) => {
                        console.log('QR Code detected:', decodedText);
                        if (!isProcessing) {
                            processCode(decodedText, null);
                        }
                    },
                    (errorMessage) => {
                        // Only log significant errors
                        if (errorMessage && !errorMessage.includes('NoMultiFormat')) {
                            console.debug('Scan error:', errorMessage);
                        }
                    }
                );
                
                console.log('Scanner started successfully');
                if (placeholder) placeholder.classList.add('hidden');
                if (stopButton) stopButton.classList.remove('hidden');
                setStatus('Kamera aktif. Arahkan QR code ke kamera.', 'success');
                showToast({ icon: 'success', title: 'Kamera berhasil diaktifkan' });
                
            } catch (err) {
                console.error('Camera start error:', err);
                
                let errorMessage = 'Gagal mengakses kamera. ';
                if (err.name === 'NotAllowedError') {
                    errorMessage += 'Izin kamera ditolak. Silakan izinkan akses kamera.';
                } else if (err.name === 'NotFoundError') {
                    errorMessage += 'Tidak ditemukan kamera di perangkat ini.';
                } else if (err.name === 'NotSupportedError') {
                    errorMessage += 'Browser tidak mendukung akses kamera.';
                } else {
                    errorMessage += err.message || 'Silakan coba lagi.';
                }
                
                setStatus(errorMessage, 'error');
                showToast({ icon: 'error', title: errorMessage });
                await stopScanner();
            }
        });

        stopButton?.addEventListener('click', async () => {
            console.log('Stop button clicked');
            await stopScanner();
        });

        manualForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log('Manual form submitted');
            
            if (!input?.value.trim()) {
                setStatus('Masukkan kode QR atau NIS siswa terlebih dahulu.', 'error');
                showToast({ icon: 'error', title: 'Kode wajib diisi' });
                input?.focus();
                return;
            }
            
            const submitButton = event.submitter || manualForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.dataset.originalHtml = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="inline-flex items-center gap-2"><span class="material-symbols-outlined animate-spin text-base">progress_activity</span><span>Memproses...</span></span>';
            }
            
            await processCode(input.value.trim(), submitButton);
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().catch(console.warn);
            }
        });
    });
};


document.addEventListener('DOMContentLoaded', () => {
    initAdminShell();
    initFlashMessage();
    initDeleteConfirmations();
    enhanceForms();
    initQrAttendance();
});
