<button class="wr-hamburguer">
    <span class="line"></span>
    <span class="line"></span>
    <span class="line"></span>
</button>

<script>
    $('.wr-hamburguer').click((e) => {
        $('body').css('overflow', 'hidden');
        $('.wr-sidebar').addClass('show');
        $('.wr-sidebar-overlay').addClass('show');
    });
    $('.wr-sidebar-overlay').click((e) => {
        $('body').css('overflow', 'auto');
        $('.wr-sidebar').removeClass('show');
        $('.wr-sidebar-overlay').removeClass('show');
    });
</script>