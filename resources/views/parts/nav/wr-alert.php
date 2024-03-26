<div class="mkalert">
    <div class="container">
        <div class="row">
            <div class="col-10">
                <p class="text-white" id="__msg">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </p>
            </div>
            <div class="col-2 px-0 px-md-auto">
                <button class="btn btn-outline-light alert-dismiss">
                    <span class="bi bi-x"></span>
                </button>
            </div>
        </div>
        <div class="alert-timing alert-timing-light">
            <small class="alert-timing-progress"></small>
        </div>
    </div>
</div>

<script>
    $('button').click(function() {
        $('.mkalert').removeClass('show');
    });
</script>