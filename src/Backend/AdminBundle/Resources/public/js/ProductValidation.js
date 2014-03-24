ProductValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
            this.setFormValidation();           
    },
	
    customErrorMessages: {
        '.profileName': {
            'required': {
                'message': "Addj meg egy terméknevet!"
            }
        },
        '.grossSalary': {
            'required': {
                'message': "Add meg a termék bruttó értékét(Szám)!"
            }
        },  
        '.netSalary': {
            'required': {
                'message': "Add meg a termék nettó értékét(Szám)!"
            }
        },  
        '.productCategory': {
            'required': {
                'message': "Adj meg egy kategóriát!"
            }
        },
        '.propertyName': {
            'required': {
                'message': "Válassz ki egy tulajdonságot!"
            }
        },
        '.propertyValue': {
            'required': {
                'message': "Adj meg egy értéket!"
            }
        },
        '#main-category-type': {
            'required': {
                'message': "Válassz egy fő kategóriát!"
            }
        },
        '#category-type': {
            'required': {
                'message': "Válassz egy kategóriát!"
            }
        }, 
        
    },
	
    setFormValidation: function() {
        $(".newProduct").validationEngine({
            promptPosition: "topLeft: 0",
            'custom_error_messages': ProductValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
        
        $(".newProperty").validationEngine({
            promptPosition: "topLeft: 0",
            'custom_error_messages': ProductValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
    },	
        

}	

