<div class="loader">
    <small class="spin"></small>
</div>

<script>
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.querySelector('.loader').classList.add('end-animation');
        }, 1500);
    });
</script>