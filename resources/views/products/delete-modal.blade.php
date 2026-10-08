<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0">
        <div class="modal-header"><h2 class="modal-title fs-5" id="deleteModalLabel">Eliminar producto</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body">¿Quieres eliminar <strong id="deleteProductName"></strong>? Esta acción elimina el registro del inventario.</div>
        <div class="modal-footer">
            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
            <form id="deleteProductForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Sí, eliminar</button>
            </form>
        </div>
    </div></div>
</div>
