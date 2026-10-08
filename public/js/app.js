document.getElementById('deleteModal')?.addEventListener('show.bs.modal', (event) => {
    const button = event.relatedTarget;
    if (!button) return;
    document.getElementById('deleteProductName').textContent = button.dataset.productName;
    document.getElementById('deleteProductForm').action = button.dataset.deleteUrl;
});

document.querySelector('[data-password-toggle]')?.addEventListener('click', (event) => {
    const button = event.currentTarget;
    const input = document.getElementById(button.getAttribute('aria-controls'));
    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    button.textContent = visible ? 'Ocultar' : 'Mostrar';
    button.setAttribute('aria-label', visible ? 'Ocultar contraseña' : 'Mostrar contraseña');
    button.setAttribute('aria-pressed', String(visible));
});

// Al volver desde el historial, consultar al servidor si la sesión sigue vigente.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) window.location.reload();
});
