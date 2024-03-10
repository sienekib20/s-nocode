<?= parts('nav.wr-navbar') ?>

<header class="wr-header">
    <div class="wr-overlay"></div>
    <!--<img src="<?= asset('img/pexels-jopwell-2422293.jpg') ?>" alt=""> -->
    <img src="<?= asset('img/shutterstock_307944350.jpg') ?>" alt="" class="wr-slide-item active slide-img">
    <img src="<?= asset('img/6-Signs-Its-Time-to-Update-Your-Website-min.dd0f7482.jpg') ?>" alt="" class="wr-slide-item slide-img">
    <div class="d-flex my-5"></div>
    <div class="wr-header-text">
        <div class="container-sm">
            <div class="col-12 col-md-10 col-lg-6 px-0">
                <div class="card-title text-white">
                    <h1 class="text-white wr-slide-item active slide-text">Cria um <span>negócio</span> plausível</h1>
                    <h1 class="text-white wr-slide-item slide-text">Feito para <span>conectar</span> pessoas</h1>
                    <span class="d-block" style="line-height: 1.5;">Ajudamos você a criar uma presença online, e gerar resultados tangíveis para o seu negócio.</span>
                    <a href="<?= route('browse') ?>" class="btn btn-outline-orange mt-5 w-100 w-md-60 w-lg-60">Explorar agora<span class="bi bi-arrow-right"></span> </a>
                </div>
            </div>
        </div>
    </div>




</header>

<script>
    changeWrNavbar();
    const allImgs = document.querySelectorAll('.slide-img');
    const allTitle = document.querySelectorAll('.slide-text');
    let indicator = 0;

    function slideItems() {
        addAllClass();
        slideAllItems();
        indicator++;
    }

    function addAllClass() {
        allImgs.forEach(item => {
            item.classList.remove('active');
        });
        allTitle.forEach(item => {
            item.classList.remove('active');
        });
    }

    function slideAllItems() {
        if (indicator == allImgs.length) {
            indicator = 0;
        }
        allImgs[indicator].classList.add('active');
        allTitle[indicator].classList.add('active');
    }

    setInterval(slideItems, 3500);
    
</script>