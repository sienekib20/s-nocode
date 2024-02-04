<div class="cool-alert">
    <div class="container-sm p-0">
        <div class="col-12 d-flex align-items-start">
            <span class="cool-alert-icon bi bi-exclamation mt-1"></span>
            <div class="content">
                <span class="cool-alert-title mt-1">Sucesso</span>
                <small class="text-muted d-block cool-alert-text">Lorem ipsum dolor sit amet consectetur adipisicing</small>
            </div>
            <span class="cool-alert-close bi bi-x"></span>
        </div>
    </div>
</div>

<script>
    $('.cool-alert-close').click(() => {
        $('.cool-alert').removeClass('active');
    });
</script>