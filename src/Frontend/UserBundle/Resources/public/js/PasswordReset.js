PasswordReset = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
        $('body').on('click', '#forget-password-link', function(e) {
            $('#sign-in-form').trigger('reveal:close');
            $('#modal-password-resetting').reveal();
        
        });
    },
        

        

}	

