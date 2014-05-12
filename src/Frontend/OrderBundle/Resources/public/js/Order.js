Order = {    
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        /*
         * Saját rendeléseimnél az egyes rendeléseknél a lenyíló gombok váltása "+"-ból "-"-ba és fordítva 
         */
        $('body').on('click', '#my-order-table .ok-button', function(){
            $(this).parents('tr').next().toggle('slow');
            ($(this).val() == "+")?$(this).val("-"):$(this).val("+");
        });  
    },
};	