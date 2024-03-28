<div class="alq-modal alq-modal-dark">
    <span class="d-flex my-5"></span>
    <span class="d-flex my-5"></span>
    <div class="alq-modal-contain col-11 col-sm-10 col-lg-8 mx-auto">
        <div class="alq-modal-top">
            <div class="container">
                <div class="row no-spacing">
                    <div class="col-10">
                        <span class="card-heading" id="modal-title"></span>
                        <small id="explain-content"></small>
                    </div>
                    <div class="col-2">
                        <span class="card-heading"></span>
                        <a href="" name="action-to-delete" class="btn btn-success w-100 w-md-35">
                            <span class="bi bi-check" style="pointer-events: none;"></span>
                        </a>
                        <button type="button" class="btn btn-orange mt-1 mt-md-0 w-100 w-md-35 ml-auto">
                            <span class="fas fa-close" style="pointer-events: none;"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('[name="action-to-delete"]').click(function() {
            $('.alq-modal').removeClass('show');
        });

        $('button').click(function() {
            $('.alq-modal').removeClass('show');
        });
    });
</script>