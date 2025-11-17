function initializeToast(toastId) {
    const toastEl = document.getElementById(toastId);
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 }); // 3 segundo auto-close
        toast.show();
    }
}