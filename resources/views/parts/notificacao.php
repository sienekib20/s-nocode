<div class="notification">
	<div class="notification-header">
		<div class="title-notification">
			<span class="bi bi-arrow-left"></span>
			<span class="text-notification">Notificações</span>
		</div>
		<div class="filter-notification">
			<select class="form-input" id="">
				<option value="all">Apresentar todas</option>
				<option value="read">Apenas lídas</option>
				<option value="unread">Não lídas</option>
			</select>
		</div>
	</div> <!--/.notification-header-->
	<div class="content-notification">
		<a href="" class="notification-item">
			<div class="__title">
				<span class="bi bi-info"></span>
				<span class="title">Mensagem de encomenda</span>
			</div>
			<span class="notification-text">Lorem ipsum, dolor sit, amet consectetur adipisicing elit. Voluptas omnis qui, dignissimos necessitatibus laboriosam corrupti?</span>
		</a>

		<a href="" class="notification-item readed">
			<div class="__title">
				<span class="bi bi-info"></span>
				<span class="title">Mensagem de encomenda</span>
			</div>
			<span class="notification-text">Lorem ipsum, dolor sit, amet consectetur adipisicing elit. Voluptas omnis qui, dignissimos necessitatibus laboriosam corrupti?</span>
		</a>
	</div>
</div> <!--/.notificacao-->

<script>
	$('.title-notification').click((e) => {
		e.preventDefault();
		$('body').css('overflow', 'auto');
		$('.overlay').removeClass('active');
		$('.notification').removeClass('active');
	});	
</script>