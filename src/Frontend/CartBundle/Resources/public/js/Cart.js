Cart = {    
    init: function(){
            Cart.initOrderButtons();
            this.bindUIActions();            
    },

    bindUIActions: function(){ 
        
        $('body').on("click", ".to-cart-button", function(e){ //Kosárba tevés
            e.preventDefault();
            id = $(this).attr('id').split("_");
            product_id = id[1];
            $.ajax({
                url: $(this).attr("href"),
                data: {'productId' : product_id},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#cart-count').html(returnData.html);
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
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
                    $('#cart-box .top-section').html(data.html);
                    $('#cart-count').html(data.cartCount);
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