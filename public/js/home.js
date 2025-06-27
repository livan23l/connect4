const $arrow = document.querySelector('#arrow');

if (window.scrollY == 0) {
    $arrow.classList.remove('arrow--hidden');
}

window.addEventListener('scroll', () => {
    if (window.scrollY == 0) {
        $arrow.classList.remove('arrow--hidden');
    } else {
        $arrow.classList.add('arrow--hidden');
    }
})