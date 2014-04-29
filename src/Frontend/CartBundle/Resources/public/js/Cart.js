/* Kosár funkciók */
Cart = {    
    OLDVALUE : null, //Régi érték tárolása frissítéshez, hibás érték esetén ez íródik vissza
    init: function(){ //Inicializálás
            this.bindUIActions();            
    },

    bindUIActions: function(){ 
        
        /*
         * Kosárba tevés eseménye
         * Lekérjük a termék azonosítóját, ajaxal továbbítjuk feldolgozásra
         */
        $('body').on("click", ".to-cart-button", function(e){
            e.preventDefault();
            id = $(this).attr('id').split("_");
            product_id = id[1]; //Termék azonosítója
            $this = $(this);
            $.ajax({
                url: $(this).attr("href"),
                data: {'productId' : product_id},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#cart-count').html(returnData.html); //Frissítjük a kosárban lévő termékek számát a fejlécben
               var i = $('.to-cart-button').index( $this ); //effect a kosárba tevéshez
               $('#cart-count').effect('transfer',{ to: $( ".to-cart-button" ).eq( i ), className: "ui-effects-transfer" }, 500);
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
        
        /*
         * Adott terméknél módosítás előtt lekérjük a régi értéket, hiba esetén ezt írjuk vissza
         */
        $('body').on("focus", ".change-product-cart-count", function(e){
            e.preventDefault();
            productId = $(this).attr('productId');
            $this = $(this);
            Cart.OLDVALUE = $(this).val();
        });
        
        /*
         * Kosárban lévő termék darabszámának megváltoztatása
         * 0-a esetén törlődik a termék
         */
        $('body').on("change", ".change-product-cart-count", function(e){
            if($(this).val() < 0 || $(this).val() === ""){ //ha hibás értéket vagy negatív számot írunk be, a régi érték íródik vissza
               $this.val(Cart.OLDVALUE);
            }else{ //darabszám frissítése
                $.ajax({
                    url: $this.attr("link"),
                    data: {'productId' : productId, 'changeValue' : $(this).val()},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    $('#cart-box .top-section').html(data.html); //frissítjük a kosár tartalmának listáját a nézeten
                    $('#cart-count').html(data.cartCount); //Frissítjük a kosárban lévő termékek számát a fejlécben
                    $('#order-button').attr('cartCount',data.cartCount); 
                    Cart.initOrderButtons();
                    $('#cart-count').effect('highlight');
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                });
            }
        });
        
        /*
         * Kosárból való törlés eseménye
         * Lekérjük a termék azonosítóját, majd pedig ajax-al elküldjük feldolgozásra
         */
        $('body').on("click", ".delete-product-from-cart", function(e){ //Rendelés gombra kattintás
            productId = $(this).attr('productId');
            e.preventDefault();
             $.ajax({
                url: $(this).attr('link'),
                data:{'productId': productId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {    //Sikeres küldés esetén frissítjük az adatokat
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
        
        /*
         * Modális ablak bezárása gombra kattintás után
         */
        $('body').on('click', '.exit-reveal-modal', function(){
                $("#modal-empty-cart").trigger('reveal:close');
        }); 

    },
    
    /*
     * Rendelési gomb inicializálása
     */
    initOrderButtons : function(){
        $('body').on("click", "#order-button", function(e){ //Rendelés gombra kattintás            
            if($('#order-button').attr('cartCount') == "0"){ //üres kosár esetén modális ablak feldobása
                $("#modal-empty-cart").reveal();
                e.preventDefault();
            }
            else if($('#order-button').attr('cartCount') != "0" && $('#order-button').attr('isloggedin') != "1" ){ //Ha nincs bejelentkezve a felhasználó, feldobjuk a bejelentkezési ablakot           
                $("#modal-sign-in").reveal();
                e.preventDefault();
            }
        });
    }
};	