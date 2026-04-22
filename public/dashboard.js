const bindDashboardModals = () => {
    const modalOpeners = document.querySelectorAll('[data-modal-open]');
    const modalClosers = document.querySelectorAll('[data-modal-close]');
    const copyButtons = document.querySelectorAll('[data-copy-text]');
    const confirmDialog = document.getElementById('app-confirm-dialog');
    const confirmTitle = document.getElementById('app-confirm-title');
    const confirmMessage = document.getElementById('app-confirm-message');
    const confirmAccept = document.getElementById('app-confirm-accept');
    const confirmCancel = document.getElementById('app-confirm-cancel');
    const flash = document.getElementById('app-flash');
    const toast = document.getElementById('app-toast');
    const toastTitle = document.getElementById('app-toast-title');
    const toastMessage = document.getElementById('app-toast-message');
    const toastClose = document.getElementById('app-toast-close');
    const toastIcon = document.getElementById('app-toast-icon');
    let pendingForm = null;
    let toastTimeout = null;
    let revealObserver = null;

    const openDialog = (dialog) => {
        if (!dialog?.showModal) {
            return;
        }

        dialog.showModal();
    };

    const closeDialog = (dialog) => {
        if (!dialog?.open) {
            return;
        }

        dialog.close();
    };

    const initScrollReveal = () => {
        const revealItems = document.querySelectorAll(
            '.stat-card, .form-section, .relation-card, .detail-card, .detail-logo, .auth-card, .auth-aside'
        );

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            revealItems.forEach((item) => item.classList.add('in-view'));

            return;
        }

        revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        revealObserver?.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.14,
                rootMargin: '0px 0px -6% 0px',
            }
        );

        revealItems.forEach((item, index) => {
            item.style.setProperty('--reveal-delay', `${Math.min(index * 45, 260)}ms`);
            item.classList.add('reveal-on-scroll');
            revealObserver?.observe(item);
        });
    };

    const bindImageDropzones = () => {
        const dropzones = document.querySelectorAll('[data-image-dropzone]');

        const syncFiles = (input, files) => {
            if (!input || !files?.length) {
                return;
            }

            const transfer = new DataTransfer();

            Array.from(files).forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        };

        const renderPreview = (dropzone, file) => {
            const preview = dropzone.querySelector('[data-image-preview]');

            if (!preview || !file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = () => {
                preview.innerHTML = `<img src="${reader.result}" alt="Vista previa" class="image-dropzone-preview-image" data-image-preview-image>`;
                dropzone.classList.add('has-image');
            };

            reader.readAsDataURL(file);
        };

        dropzones.forEach((dropzone) => {
            const input = dropzone.querySelector('[data-image-input]');
            const browseButton = dropzone.querySelector('[data-image-browse]');

            browseButton?.addEventListener('click', () => input?.click());

            dropzone.addEventListener('click', (event) => {
                if (event.target.closest('[data-image-browse]')) {
                    return;
                }

                input?.click();
            });

            input?.addEventListener('change', () => {
                const file = input.files?.[0];

                if (file) {
                    renderPreview(dropzone, file);
                }
            });

            dropzone.addEventListener('dragover', (event) => {
                event.preventDefault();
                dropzone.classList.add('is-dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('is-dragover');
            });

            dropzone.addEventListener('drop', (event) => {
                event.preventDefault();
                dropzone.classList.remove('is-dragover');

                const file = Array.from(event.dataTransfer?.files ?? []).find((item) => item.type.startsWith('image/'));

                if (file && input) {
                    syncFiles(input, [file]);
                }
            });

            dropzone.addEventListener('paste', (event) => {
                const items = Array.from(event.clipboardData?.items ?? []);
                const imageItem = items.find((item) => item.type.startsWith('image/'));
                const file = imageItem?.getAsFile();

                if (file && input) {
                    event.preventDefault();
                    syncFiles(input, [file]);
                }
            });
        });
    };

    const bindSocioTemplates = () => {
        const templateSelects = document.querySelectorAll('[data-socio-template-select]');

        templateSelects.forEach((select) => {
            select.addEventListener('change', () => {
                const option = select.selectedOptions[0];
                const card = select.closest('.relation-card');

                if (!option || !card) {
                    return;
                }

                const setField = (fieldName, value) => {
                    const field = card.querySelector(`[name$="[${fieldName}]"]`);

                    if (field) {
                        field.value = value ?? '';
                    }
                };

                if (option.value === '') {
                    setField('template_ine_pdf', '');
                    setField('template_csf_pdf', '');
                    setField('template_certificado_cer', '');
                    setField('template_llave_key', '');

                    return;
                }

                setField('puesto', option.dataset.puesto ?? '');
                setField('nombre', option.dataset.nombre ?? '');
                setField('direccion', option.dataset.direccion ?? '');
                setField('rfc', option.dataset.rfc ?? '');
                setField('contrasena', option.dataset.contrasena ?? '');
                setField('template_ine_pdf', option.dataset.inePdf ?? '');
                setField('template_csf_pdf', option.dataset.csfPdf ?? '');
                setField('template_certificado_cer', option.dataset.certificadoCer ?? '');
                setField('template_llave_key', option.dataset.llaveKey ?? '');
            });
        });
    };

    const hideToast = () => {
        if (!toast) {
            return;
        }

        toast.classList.remove('is-visible');
        window.setTimeout(() => {
            toast.hidden = true;
        }, 180);
    };

    const showToast = ({ title, message, type = 'success' }) => {
        if (!toast || !toastTitle || !toastMessage || !toastIcon) {
            return;
        }

        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toast.dataset.toastType = type;
        toastIcon.textContent = type === 'success' ? 'OK' : '!';
        toast.hidden = false;

        window.requestAnimationFrame(() => {
            toast.classList.add('is-visible');
        });

        if (toastTimeout) {
            window.clearTimeout(toastTimeout);
        }

        toastTimeout = window.setTimeout(hideToast, 4200);
    };

    modalOpeners.forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.modalOpen);

            openDialog(target);
        });
    });

    modalClosers.forEach((button) => {
        button.addEventListener('click', () => {
            closeDialog(button.closest('dialog'));
        });
    });

    document.querySelectorAll('[data-confirm-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!confirmDialog?.showModal) {
                const accepted = window.confirm(form.dataset.confirmMessage ?? 'Esta accion requiere confirmacion.');

                if (!accepted) {
                    event.preventDefault();
                }

                return;
            }

            event.preventDefault();
            pendingForm = form;

            if (confirmTitle) {
                confirmTitle.textContent = form.dataset.confirmTitle ?? 'Confirmar accion';
            }

            if (confirmMessage) {
                confirmMessage.textContent = form.dataset.confirmMessage ?? 'Esta accion requiere confirmacion.';
            }

            openDialog(confirmDialog);
        });
    });

    document.querySelectorAll('dialog').forEach((dialog) => {
        dialog.addEventListener('click', (event) => {
            const bounds = dialog.getBoundingClientRect();
            const isInDialog =
                bounds.top <= event.clientY &&
                event.clientY <= bounds.top + bounds.height &&
                bounds.left <= event.clientX &&
                event.clientX <= bounds.left + bounds.width;

            if (!isInDialog) {
                closeDialog(dialog);
            }
        });
    });

    confirmCancel?.addEventListener('click', () => {
        pendingForm = null;
        closeDialog(confirmDialog);
    });

    confirmAccept?.addEventListener('click', () => {
        const form = pendingForm;

        pendingForm = null;
        closeDialog(confirmDialog);
        form?.submit();
    });

    copyButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const originalText = button.textContent;

            try {
                await navigator.clipboard.writeText(button.dataset.copyText ?? '');
                button.textContent = 'Copiado';

                window.setTimeout(() => {
                    button.textContent = originalText;
                }, 1200);
            } catch {
                button.textContent = 'Error';

                window.setTimeout(() => {
                    button.textContent = originalText;
                }, 1200);
            }
        });
    });

    toastClose?.addEventListener('click', () => {
        if (toastTimeout) {
            window.clearTimeout(toastTimeout);
        }

        hideToast();
    });

    if (flash) {
        showToast({
            title: flash.dataset.flashTitle ?? 'Operacion completada',
            message: flash.dataset.flashMessage ?? '',
            type: flash.dataset.flashType ?? 'success',
        });
    }

    initScrollReveal();
    bindSocioTemplates();
    bindImageDropzones();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindDashboardModals);
} else {
    bindDashboardModals();
}
