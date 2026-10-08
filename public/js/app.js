document.getElementById('deleteModal')?.addEventListener('show.bs.modal', (event) => {
    const button = event.relatedTarget;
    document.getElementById('deleteProductName').textContent = button.dataset.productName;
    document.getElementById('deleteProductForm').action = button.dataset.deleteUrl;
});

// Al volver desde el historial, consultar al servidor si la sesión sigue vigente.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) window.location.reload();
});
