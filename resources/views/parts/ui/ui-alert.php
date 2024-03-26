<div class="ui-alert">
    <div class="container-sm p-0">
        <div class="content-ui-alert col-12 d-flex flex-direction-column h-100 align-items-center justify-content-center" id="uialert">
            <div class="__title text-center">
                <span class="ui-alert-icon"></span>
                <small class="ui-alert-title">Sucesso</small>
            </div>
            <div class="content text-center">
                <span class="text-muted d-block ui-alert-text">Lorem ipsum dolor sit amet consectetur adipisicing</span>
            </div>
            <button type="button" class="ui-alert-close btn btn-outline mt-2">ok</button>
        </div>
    </div>
</div>

<script>
    $('.ui-alert-close').click(() => {
        $('.ui-alert').removeClass('active');
    });

    $(document).ready(() => {
        
    });
</script>