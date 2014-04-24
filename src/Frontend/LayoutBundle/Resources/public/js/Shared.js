Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipInit();
       
        $('.sideRecomendedProducts').flexslider({ //Termékek képeinek megjelenítése
            animation: "slide",
            slideshow: true,
            itemWidth: 80,
            slideshowSpeed:5000,
            keyboard: false,
            minItems: 1,
            maxItems: 1
        }); 
        
    },
    

    qtipInit : function(){
        $('#my-profile-text').qtip({
            content: $("#myprofileDropDown").html(),
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center' // at the bottom right of...
            },
            show: {
                event: 'click',
                solo: true
            },
            hide: {
                event: 'click unfocus'
            },
            style: { 
                classes: 'profileDropDownQTip'
            }
        });
        
        
    }      
         
};

