<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>%title%</title>

	<link rel="stylesheet" href="<?= asset('css/general-sans.css') ?>">
	<link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">

	<link rel="stylesheet" href="<?= asset('lib/wp/grapes.min.css') ?>">

	<script src="<?= asset('lib/wp/grapes.min.js') ?>"></script>
	<script src="<?= asset('lib/wp/grapesjs-blocks-basic.js') ?>"></script>

	<link rel="stylesheet" href="<?= asset('webb/web_builder.css') ?>">


</head>

<body>

	<div class="web_builder_top">
		<div class="container">
			<div class="top_title">
				<span class="bold"> <small class="bi-code-slash"></small> S-nocode</span>
				<small class="tw-muted">Constructor de websites</small>
			</div>
		</div>
		<div class="web_builder_responsive_tabs">
			<div class="container">
				<div class="responsive_tabs_item rt1">
					<button class="tabs_item"><span class="bi bi-tv"></span></button>
					<div class="responsive_tabs_item rt2 active">
						<button class="tabs_item"><span class="bi bi-laptop"></span></button>
						<div class="responsive_tabs_item rt3">
							<button class="tabs_item"><span class="bi bi-tablet-landscape"></span></button>
							<div class="responsive_tabs_item rt4">
								<button class="tabs_item"><span class="bi bi-phone"></span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="separator"></div>

	<div class="web_builder">
		Basic devices
	</div>

	<div class="openclose">
		<span class="bi-code"></span>
	</div>
	<div class="web_builder_tools">
		<div class="builder_tools">
			<div class="builder_tools_header">
				<a href="" class="tools_header_item active" title="pages"><span class="bi bi-wallet2"></span></a>
				<a href="" class="tools_header_item" title="layers"><span class="bi bi-layers-fill"></span></a>
				<a href="" class="tools_header_item" title="style"><span class="bi bi-palette-fill"></span></a>
				<a href="" class="tools_header_item" title="property"><span class="bi bi-ui-radios-grid"></span></a>
				<a href="" class="tools_header_item" title="blocks"><span class="bi bi-grid-1x2-fill"></span></a>
			</div>

			<div class="builder_tools_contain">
				<div class="web_builder_pages active">
					<a href="" class="webb_page_add"> <small> + Adicionar página </small></a>
					<div class="webb_added_pages">
						<button class="webb_page_item">
							<span class="name">Home</span>
							<div class="actions">
								<a href="" title="editar"><span class="bi bi-pencil-square"></span></a>
								<a href="" title="remover"><span class="bi bi-trash"></span></a>
							</div>
						</button>
						<button class="webb_page_item">
							<span class="name">About</span>
							<div class="actions">
								<a href="" title="editar"><span class="bi bi-pencil-square"></span></a>
								<a href="" title="remover"><span class="bi bi-trash"></span></a>
							</div>
						</button>
					</div>
				</div>
				<div class="web_builder_layers">Layers</div>
				<div class="web_builder_style">Styles</div>
				<div class="web_builder_property">Property</div>
				<div class="web_builder_blocks">Blocks</div>
			</div>
		</div>
	</div>

	<script src="<?= asset('webb/web_builder.js') ?>"></script>

</body>

</html>

<script>
	// Open close toolbars
	const openclose = document.querySelector('.openclose');
	const web_builder = document.querySelector('.gjs-cv-canvas');
	const web_builder_tools = document.querySelector('.web_builder_tools');
	openclose.addEventListener('click', (e) => {
		//e.preventDefault();
		if (web_builder.classList.length == 1) {
			web_builder.classList.add('active');
			web_builder_tools.classList.add('active');
			web_builder.style.width = '100%';
			e.target.style.right = '0%';
			web_builder_tools.style.width = '0%';
		} else {

			web_builder.classList.remove('active');
			web_builder_tools.classList.remove('active');
			web_builder.style.width = '80%';
			web_builder_tools.style.width = '20%';
			e.target.style.right = '18.5%';
		}
	});

	// Tabs change
	const tabs_items = document.querySelectorAll('.tools_header_item');
	const webb_contents = document.querySelectorAll('.builder_tools_contain > div');

	tabs_items.forEach((item, key) => {
		item.addEventListener('click', (e) => {
			e.preventDefault();
			tabs_items.forEach((item) => item.classList.remove('active'));
			webb_contents.forEach((item) => item.classList.remove('active'));
			item.classList.add('active');
			webb_contents[key].classList.add('active');
		});
	});
</script>