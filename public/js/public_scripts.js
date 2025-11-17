document.addEventListener('DOMContentLoaded', function () {

    const myCarouselElement = document.querySelector('#featuredCarousel');
    const indicators = document.querySelectorAll('.custom-indicators button');

    if (myCarouselElement && indicators.length > 0) {

        const carousel = new bootstrap.Carousel(myCarouselElement, {
            interval: 7000,
            wrap: true,
            pause: 'hover'
        });

        myCarouselElement.addEventListener('slide.bs.carousel', function (event) {

            const newActiveIndex = event.to;

            indicators.forEach(indicator => {
                indicator.classList.remove('active');
                indicator.setAttribute('aria-current', 'false');
            });

            if (indicators[newActiveIndex]) {
                indicators[newActiveIndex].classList.add('active');
                indicators[newActiveIndex].setAttribute('aria-current', 'true');
            }
        });
    }
});