// Open close toolbars
const openclose = document.querySelector('.openclose');
const web_builder = document.querySelector('.web_builder');
const web_builder_tools = document.querySelector('.web_builder_tools');
openclose.addEventListener('click', (e) => {
	//e.preventDefault();
	if (web_builder.classList.length == 1) {
		web_builder.classList.add('active');
		web_builder_tools.classList.add('active');
		web_builder.style.width = '100%';
		e.target.style.right='0%';
		web_builder_tools.style.width = '0%';
	} else {
		
		web_builder.classList.remove('active');
		web_builder_tools.classList.remove('active');
		web_builder.style.width = '80%';
		web_builder_tools.style.width = '20%';
		e.target.style.right='18.5%';
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