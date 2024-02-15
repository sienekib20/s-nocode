<aside class="aside">
	<div class="aside-header">
		<span>SP</span>
		<span class="title">Webuilder</span>
	</div>
	<div class="contain-aside-menu d-flex flex-direction-column pt-3">
		<div class="aside-item active">
			<a href="<?= route('home') ?>" class="aside-link <?= request()->path() == '/home' ? 'active' : '' ?>">
				<i class="bi bi-collection"></i>
				<p>Visão geral</p>
			</a>
		</div>
		<div class="aside-item active">
			<a href="" class="aside-link">
				<i class="bi bi-layers-fill"></i>
				<p>Templates</p>
			</a>
		</div>
		<div class="aside-item">
			<a href="<?= route('websites') ?>" class="aside-link <?= request()->path() == '/websites' ? 'active' : '' ?>">
				<i class="bi bi-grid-fill"></i>
				<p>Meus websites</p>
			</a>
		</div>
		<div class="aside-item">
			<a href="" class="aside-link">
				<i class="bi bi-upload"></i>
				<p>Encomendar website</p>
			</a>
		</div>
		<div class="aside-item">
			<a href="" class="aside-link">
				<i class="bi bi-megaphone-fill"></i>
				<p>Minhas campanhas</p>
				<span class="aside-brand">0</span>
			</a>
		</div>
		<div class="aside-item mt-auto">
			<a href="" class="aside-link">
				<span class="letter">S</span>
				<p class="d-flex flex-direction-column">
					<span>Fulano de tal</span>
					<small class="text-muted">Terminar sessão</small>
				</p>
				<span class="no-bg aside-brand bi bi-arrow-right"></span>
			</a>
		</div>
	</div><!--/aside-menu-->
</aside> <!--/.aside-->

<script>
	function ola() {
		alert(1);
	}
</script>