<span class="bi bi-chevron-up back-to-top"></span>

<script>
    $(document).ready(function () {
        // Monitora o evento de rolagem
        $(window).scroll(function () {
            // Verifica se a posição de rolagem é maior ou igual a 528 pixels
            if ($(this).scrollTop() >= 528) {
                // Adiciona a classe 'active' ao elemento '.back-to-top'
                $('.back-to-top').addClass('active');
            } else {
                // Remove a classe 'active' do elemento '.back-to-top'
                $('.back-to-top').removeClass('active');
            }
        });

        // Manipula o clique no elemento '.back-to-top'
        $('.back-to-top').click(function () {
            // Anima a rolagem até o topo
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });

</script>