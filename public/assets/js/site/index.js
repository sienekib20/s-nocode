
const scrollElement = document.querySelectorAll('.card-section-header');
const containRow = document.querySelectorAll('.contain-row');
const reaveled = document.querySelectorAll('img.reaveled');

window.addEventListener('scroll', () => {
    scrollElement.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateY(0)';
            element.style.opacity = 1;
        } else {
            element.style.transform = 'translateY(50px)';
            element.style.opacity = 0;
        }
    })

    reaveled.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateX(0)';
            element.style.opacity = 1;
        } else {
            element.style.transform = 'translateX(400px)';
            element.style.opacity = 0;
        }
    })

    containRow.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateY(0)';
            element.style.opacity = 1;
        } else {
            element.style.transform = 'translateY(40px)';
            element.style.opacity = 0;
        }
    })
});

