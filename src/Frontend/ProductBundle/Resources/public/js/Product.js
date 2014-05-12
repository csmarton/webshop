Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        /*
         * Összehasonlítás gombra való kattintás a termékek dobozában
         */
        $('body').on("click", ".compare-product-button", function(e){
            e.preventDefault();	
            $this = $(this);
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {    
                    $('.compareProductsDropDownContent').html(data.html); //Összehasonlítandó termékek listájának módosítása
                    Shared.qtipCompareInit();
                    console.log(data.html);
                    var i = $('.compare-product-button').index( $this );
                    $('#compare-products-button').effect('transfer',{ to: $( ".compare-product-button" ).eq( i ), className: "ui-effects-transfer" }, 500); //Effekt a kattintás után
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        }); 
        


    },
};	