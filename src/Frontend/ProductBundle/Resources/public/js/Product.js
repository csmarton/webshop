Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        $('a.gallery').colorbox({
                    rel:'gal'
        });
        $('.flexslider').flexslider({ //Termékek képeinek megjelenítése
            animation: "slide",
            slideshow: false,
            itemWidth: 210,
            itemMargin: 5,
            keyboard: false,
            minItems: 2,
            maxItems: 3
        });    

    },
};	