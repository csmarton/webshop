Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipInit();
       
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

