ProductValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
            this.setFormValidation();           
    },
    
    //Form validációs üzenetek
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
        '.inStock': {
            'required': {
                'message': "Addj meg egy értéket!"
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
        '.property-value': {
            'required': {
                'message': "Hibás adat!"
            }
        }, 
        
    },
	
    setFormValidation: function() {
        $(".newProduct").validationEngine({ //új termék validációja
            promptPosition: "topLeft: 0",
            'custom_error_messages': ProductValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
        $(".property-form").validationEngine({ //új termék validációja
            promptPosition: "topLeft: 0",
            'custom_error_messages': ProductValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
    },	
    
        

}

//Email cím ellenőrzése
function checkPropertyValue(field, rules, i, options){    
    var propertyPattern = new RegExp(/^(\d+)\s*(B|KB|MB|GB|TB)$/);

  if(!propertyPattern.test(field.val())){
     rules.push('required');
     return options.allrules.wrongPropertyValue.alertText;
  }
}

