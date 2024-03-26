// Selecionar elementos
const elementsToAnimate = document.querySelectorAll('.card-section-header, .contain-row, img.reaveled');
const ncodeBtnAction = document.querySelectorAll('.ncode-btn-action');
const cardPricingContain = document.querySelectorAll('.card-pricing-contain');
const faqsItems = document.querySelectorAll('.faqs-item');

// Função para animar elementos visíveis
function animateVisibleElements() {
    const windowHeight = window.innerHeight;

    elementsToAnimate.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;

        if (elementTop < windowHeight) {
            element.style.transform = 'translateY(0)';
            element.style.opacity = 1;
        }
    });
}

// Adicionar evento de rolagem
window.addEventListener('scroll', animateVisibleElements);

// Adicionar eventos de clique para botões de ação
ncodeBtnAction.forEach((btn, key) => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        cardPricingContain.forEach(card => card.classList.remove('active'));
        ncodeBtnAction[2].classList.remove('active');
        ncodeBtnAction[3].classList.remove('active');
        btn.classList.add('active');
        cardPricingContain[(key == 2 || key == 3) ? key - 2 : 0].classList.add('active');
    });
});

// Adicionar eventos de clique para FAQ
faqsItems.forEach(item => {
    const header = item.querySelector('.faqs-header');
    const contain = item.querySelector('.faqs-contain');
    const contentHeight = contain.scrollHeight + 'px';

    contain.style.height = '0';
    contain.style.overflow = 'hidden';

    header.addEventListener('click', () => {
        item.classList.toggle('active');
        contain.style.height = item.classList.contains('active') ? contentHeight : '0';
    });
});

// Executar animação uma vez na carga inicial
document.addEventListener('DOMContentLoaded', animateVisibleElements);
