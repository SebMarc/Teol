$('.owl-carousel').owlCarousel({
    loop: true,
    margin: 10,
    autoplay: true,
    smartSpeed: 800,
    autoHeight: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000: {
            items: 5
        }
    }
})