Property = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
            this.setFormValidation();     
            
    },
    //kategória form validációs üzenetek
    customErrorMessages: {
        '.propertyName': {
            'required': {
                'message': "Addj meg egy tulajdonság nevet!"
            }
        },
        '.mainCategoryName': {
            'required': {
                'message': "Addj meg egy fő kategóriát!"
            }
        },  

        
    },
	
    setFormValidation: function() {
        $(".newProperty").validationEngine({ //új tulajdonság űrlap validáció
            promptPosition: "topLeft: 0",
            'custom_error_messages': Property.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });        
    },	
        

}	

