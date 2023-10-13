
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


const ncodeBtnAction = document.querySelectorAll('.ncode-btn-action');
const cardPricingContain = document.querySelectorAll('.card-pricing-contain');

ncodeBtnAction.forEach((btn, key) => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        cardPricingContain.forEach(card => { card.classList.remove('active') });
        ncodeBtnAction.forEach(card => { card.classList.remove('active') });
        btn.classList.add('active');
        cardPricingContain[(key == 2 || key == 3) ? key - 2 : 0].classList.add('active');
    })
});
