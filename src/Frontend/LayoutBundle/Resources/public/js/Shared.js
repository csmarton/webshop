Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipInit();
       
        $('.sideRecomendedProducts').flexslider({ //Ajánlott termékek képeinek megjelenítése
            animation: "slide",
            slideshow: true,
            itemWidth: 80,
            slideshowSpeed:5000,
            keyboard: false,
            minItems: 1,
            maxItems: 1
        }); 
        
        $('body').on("click", ".delete-compare-product", function(e){
            $('.compareProductsDropDownQTip .loading').show();
            e.preventDefault();		
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {                    
                    $('.compareProductsDropDownContent').html(data.html);
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
        
        $('#compare-products-button').qtip({
            content: $("#compareProductsDropDown").html(),
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
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
                width:1000
            }
        });
        
        
    }      
         
};

