<div class="wr-popup wr-popup-white">
    <div class="container">
        <div class="row mt-4">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="wr-popup-contain">
                    <div class="row">
                        <div class="col-12 px-4 d-flex ai-center jc-between">
                            <div class="wr-popup-top">
                                <span class="card-heading">Templates favoritos</span>
                                <small class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, repellat.</small>
                            </div>
                            <button type="button" class="btn btn-close wr-popup-dismiss">
                                <span class="fas fa-x"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-10 mx-auto">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <a href="<?= route('view') ?>" class="model">
                            <div class="model-img">
                                <img src="<?= "/html-templates/" ?>" alt="">
                            </div>
                            <span class="title"><?= 'Title' ?></span>
                        </a>
                        <a href="" class="text-black d-flex ml-3 mt-2" name="add-to-favorite" id="favorito|<?= '' ?>" style="text-decoration: underline;">
                            <small> - remover</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>