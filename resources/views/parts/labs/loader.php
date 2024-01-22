<div class="loader">
  <small class="spin"></small>
</div>

<script>
  $(document).ready(function() {
    setTimeout(() => {
      document.querySelector('.loader').classList.add('end-animation');
    }, 1500);
  });
</script>