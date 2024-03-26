const ask_header = document.querySelectorAll('.how_to .ask-header');
const ask_header__ = document.querySelectorAll('.frequents .ask-header');

ask_header.forEach(ask => {
    ask.addEventListener('click', function (e) {
        ask_header.forEach(asks => {
            asks.nextElementSibling.classList.remove('active');
            var icons = asks.querySelector('.ask-icon');
            if (icons.className.includes('fa-minus')) {
                icons.classList.remove('fa-minus');
                icons.classList.add('fa-plus');
            }
        });

        var icon = e.target.querySelector('.ask-icon');

        if (icon.className.includes('fa-plus')) {
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');

        } else {
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
        }
        var next = e.target.nextElementSibling;
        next.classList.toggle('active');
    });

});

ask_header__.forEach(ask__ => {
    ask__.addEventListener('click', function (e) {
        ask_header__.forEach(asks__ => {
            asks__.nextElementSibling.classList.remove('active');
            var icons = asks__.querySelector('.ask-icon');

            if (icons.className.includes('fa-minus')) {
                icons.classList.remove('fa-minus');
                icons.classList.add('fa-plus');
            }
        });

        var icon = e.target.querySelector('.ask-icon');
        
        if (icon.className.includes('fa-plus')) {
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');

        } else {
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
        }
        var next = e.target.nextElementSibling;
        next.classList.toggle('active');
    });

});


function animateAuto(content) {
    setTimeout(() => {
        content.classList.toggle('active')
    }, 0);
    setTimeout(animateAuto, 1000);
}