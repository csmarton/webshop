CategoryValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
            this.setFormValidation();           
    },
	
    customErrorMessages: {
        '.categoryName': {
            'required': {
                'message': "Addj meg egy kategória nevet!"
            }
        },
        '.mainCategory': {
            'required': {
                'message': "Addj meg egy fő kategóriát!"
            }
        },  

        
    },
	
    setFormValidation: function() {
        $(".newCategory").validationEngine({
            promptPosition: "topLeft: 0",
            'custom_error_messages': CategoryValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });        
    },	
        

}	

