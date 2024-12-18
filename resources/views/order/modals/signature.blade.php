<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('order.signature.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="signatureModalLabel">Firma de la orden</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                
                <div class="col m-3">
                    <label for="signature_name" class="form-label is-required">
                        Nombre de la firma:
                    </label>
                    <div class="col">
                        <input type="text" class="form-control border-secondary border-opacity-25" name="signature_name"
                        id="signature_name" />
                    </div>
                </div>
                <div class="col-12">
                    <label for="signature_name" class="form-label mx-3 is-required">
                        {{ __('customer.customer_table.firma')}}
                    </label>
                    <input type="hidden" id="order-id" name="order_id" value="">
                    <div class="row border  rounded shadow-sm p-3 m-0 mb-3">
                        <h5 class="fw-bold">Arrastra y suelta o elige tus archivos haciendo clic aquí: </h5>
                        <p class="text-danger mb-0">
                            Solo se permiten archivos con formato .JPG .JPEG .PNG
                        </p>
                        <p class="text-danger">
                            Los archivos deben ser menores a 5MB.
                        </p>
                        
                        <input accept=".png, .jpg, .jpeg, .pdf" type="file" name="image" required>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<script>
    function setSignatureId(order_id) {
        $('#order-id').val(order_id);
    }
</script>
