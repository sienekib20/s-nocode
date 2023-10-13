
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
        }
    })

    reaveled.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateX(0)';
            element.style.opacity = 1;
        }
    })

    containRow.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateY(0)';
            element.style.opacity = 1;
        }
    })
});

