Order = {    
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        $('body').on('click', '#my-order-table .ok-button', function(){
            $(this).parents('tr').next().toggle('slow');
            ($(this).val() == "+")?$(this).val("-"):$(this).val("+");
        });
        

    },
};	