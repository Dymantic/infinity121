function initNavbar() {
    const trigger = document.querySelector('.nav-trigger');

    trigger.addEventListener('click', () => {
        document.body.classList.toggle('show-nav');
    });

    const observer = new IntersectionObserver(handleIntersect, {
        root: null,
        rootMargin: '0px',
        threshold: [0,1],
    });
    const ghost = document.querySelector('.nav-shadow');
    observer.observe(ghost);
}

function handleIntersect([entry, ...rest]) {
    const navbar = document.querySelector('.main-nav');
    if(entry.isIntersecting) {
        return navbar.classList.remove('scrolled');
    }
    navbar.classList.add('scrolled');
}

export {initNavbar};
