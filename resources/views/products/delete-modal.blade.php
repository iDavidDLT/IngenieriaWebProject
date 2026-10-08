<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-describedby="deleteModalDescription" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h2 class="modal-title fs-5 fw-bold" id="deleteModalLabel">Eliminar producto</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body"><span class="delete-warning" aria-hidden="true">!</span><p id="deleteModalDescription">¿Quieres eliminar <strong id="deleteProductName"></strong> del inventario?</p><p class="small text-secondary mb-0">Esta acción no se puede deshacer. El registro de la operación permanecerá en tu historial.</p></div>
        <div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button><form id="deleteProductForm" method="POST" action="">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Sí, eliminar</button></form></div>
    </div></div>
</div>
