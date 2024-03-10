const ask_header = document.querySelectorAll('.ask-header');

ask_header.forEach(ask => {
    ask.addEventListener('click', function (e) {
        ask_header.forEach(asks => {
            asks.nextElementSibling.classList.remove('active');
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