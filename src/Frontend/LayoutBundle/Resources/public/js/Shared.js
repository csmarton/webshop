/* Közös funkciók s*/
Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipProfileInit();
       this.qtipCompareInit();
        /*
         * Ajánlott termékek képeinek megjelenítése
         */
        $('.sideRecomendedProducts').flexslider({ 
            animation: "slide",
            slideshow: true,
            itemWidth: 80,
            slideshowSpeed:5000,
            keyboard: false,
            minItems: 1,
            maxItems: 1,
            directionNav: false
        });  
        
        /*
         * Összehasonlításnál termék törlése
         */
        $('body').on("click", ".delete-compare-product", function(e){
            e.preventDefault();
            $('.compareProductsDropDownQTip .loading').show(); //Betöltő ikon megjelenítése
            e.preventDefault();		
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {                    
                    $('.compareProductsDropDownContent').html(data.html);//Összehasonlítandó termékek listájának módosítása
                    $('.compareProductsDropDownQTip .loading').hide();
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                    $('.compareProductsDropDownQTip .loading').hide();
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('.compareProductsDropDownQTip .loading').hide();
            });
        }); 
        
    },
    
    /*
     * Buborékablakok inicializálása
     */
    qtipProfileInit : function(){
        /*
         * Profilom qTip
         */
        $('#my-profile-text').qtip({
            content: $("#myprofileDropDown").html(), //Tartalom
            position: { //pozíció
                my: 'top center',  // buburokéablak 
                at: 'bottom center' // elem, amihez igazítjuk
            },
            show: { //megjelenítés kattintás hatására
                event: 'click',
                solo: true //egyszerre csak egy buborék jelenhet meg
            },
            hide: { //elrejtés kattintásra vagy fókusz elvesztése esetén
                event: 'click unfocus'
            },
            style: { 
                classes: 'profileDropDownQTip' //Osztály, ezzel fogunk tudni hivatkozni rá
            }
        });
    },
    
    /*
     * Összehasonlítás qTip
     */
    qtipCompareInit : function(){   

        $('#compare-products-button').qtip({
            content: $("#compareProductsDropDown").html(),
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
                adjust: {
                    y: 0,
                    x: 0,
                }
            },
            show: {
                event: 'click',
                solo: true
            },
            hide: {
                event: 'click unfocus'
            },
            style: { 
                classes: 'compareProductsDropDownQTip profileDropDownQTip',
                width:1000,
                tip: {
                    corner: true,
                    offset: 180
                 }
            }
        });
        
        
    }      
         
};

