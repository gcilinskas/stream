$('.owl-carousel').owlCarousel({
    nav: true,
    loop: false,
    margin: 30,
    items : 1,
    responsive : {
        480 : { items : 1  },
        768 : { items : 2  },
        1024 : { items : 3 },
        1500: {items : 4},
    },
});
