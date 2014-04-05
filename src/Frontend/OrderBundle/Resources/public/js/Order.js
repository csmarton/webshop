Order = {    
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        $('#order-conditions').slimScroll({
            height: '200px',
            width: '770px',
            disableFadeOut : true
        });
    },
};	