












































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