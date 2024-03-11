<a href="" class="btn btn-orange" id="open-dashboard-menu"> <span class="bi bi-list"></span> Menu </a>

<script>
    $('#open-dashboard-menu').click(function(e) {
        e.preventDefault();
        $('body').css('overflow', 'hidden');
        $('.__wr-sidebar-overlay').addClass('active');
        $('.__wr-sidebar').addClass('active');
    });

    $('.__wr-sidebar-overlay').click(function(e) { hide(e); });
    $('.__wr-sidebar-close').click(function(e) { hide(e); });

    function hide(e) {
        e.preventDefault();
        $('body').css('overflow', 'auto');
        $('.__wr-sidebar-overlay').removeClass('active');
        $('.__wr-sidebar').removeClass('active');
    }
</script>