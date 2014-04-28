Cart = {    
    OLDVALUE : null,
    init: function(){
            this.bindUIActions();            
    },

    bindUIActions: function(){ 
        
        $('body').on("click", ".to-cart-button", function(e){ //Kosárba tevés
            e.preventDefault();
            id = $(this).attr('id').split("_");
            product_id = id[1];
            $this = $(this);
            $.ajax({
                url: $(this).attr("href"),
                data: {'productId' : product_id},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#cart-count').html(returnData.html);
               var i = $('.to-cart-button').index( $this );
               $('#cart-count').effect('transfer',{ to: $( ".to-cart-button" ).eq( i ), className: "ui-effects-transfer" }, 500);
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
        
        $('body').on("focus", ".change-product-cart-count", function(e){
            e.preventDefault();
            productId = $(this).attr('productId');
            $this = $(this);
            Cart.OLDVALUE = $(this).val();
            console.log(Cart.OLDVALUE);
        });
        $('body').on("change", ".change-product-cart-count", function(e){
            if($(this).val() < 0 || $(this).val() === ""){
               $this.val(Cart.OLDVALUE);
            }else{
                $.ajax({
                    url: $this.attr("link"),
                    data: {'productId' : productId, 'changeValue' : $(this).val()},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    $('#cart-box .top-section').html(data.html);
                    $('#cart-count').html(data.cartCount);
                    $('#order-button').attr('cartCount',data.cartCount);
                    Cart.initOrderButtons();
                    $('#cart-count').effect('highlight');
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                });
            }
        });
        
        $('body').on("click", ".delete-product-from-cart", function(e){ //Rendelés gombra kattintás
            productId = $(this).attr('productId');
            e.preventDefault();
             $.ajax({
                url: $(this).attr('link'),
                data:{'productId': productId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {    
                    $('#cart-count')
                            .effect('highlight')
                            .html(data.cartCount);
                     $('#cart-box .top-section').html(data.html);
                    $('#order-button').attr('cartCount',data.cartCount);
                    Cart.initOrderButtons();
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });  
        
        $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                $("#modal-empty-cart").trigger('reveal:close');
        }); 

    },
    initOrderButtons : function(){
        $('body').on("click", "#order-button", function(e){ //Rendelés gombra kattintás
            
            if($('#order-button').attr('cartCount') == "0"){ //Ha nincs bejelentkezve a felhasználó, feldobjuk a bejelentkezési ablakot           
                $("#modal-empty-cart").reveal();
                e.preventDefault();
            }
            else if($('#order-button').attr('cartCount') != "0" && $('#order-button').attr('isloggedin') != "1" ){
                $("#modal-sign-in").reveal();
                e.preventDefault();
            }
        });
    }
};	