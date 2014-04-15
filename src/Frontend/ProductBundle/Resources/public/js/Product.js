Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        $('a.gallery').colorbox({
            rel:'gal'
        });
        $('.productImageSlider').flexslider({ //Termékek képeinek megjelenítése
            animation: "slide",
            slideshow: false,
            itemWidth: 210,
            itemMargin: 5,
            keyboard: false,
            minItems: 2,
            maxItems: 3
        });   
        
        $('.productOfferSlider').flexslider({ //Termékek képeinek megjelenítése
            animation: "slide",
            slideshow: true,
            itemWidth: 150,
            itemMargin: 5,
            keyboard: false,
        });  

    },
};	