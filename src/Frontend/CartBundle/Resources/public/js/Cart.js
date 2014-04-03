Cart = {    
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        if($('#order-button').attr('isloggedin') == "0"){ //Ha nincs bejelentkezve a felhasználó, feldobjuk a bejelentkezési ablakot
            $('body').on("click", "#order-button", function(e){ //Rendelés
                e.preventDefault();		
                $("#modal-sign-in").reveal();

            });
            
        }  
    },
};	