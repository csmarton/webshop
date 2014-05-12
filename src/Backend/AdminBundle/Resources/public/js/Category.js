Category = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
            this.setFormValidation();     
            
    },
    //kategória form validációs üzenetek
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
        $(".newCategory, .newMainCategory").validationEngine({ //új kategória űrlap validáció
            promptPosition: "topLeft: 0",
            'custom_error_messages': Category.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });        
    },	
        

}	

