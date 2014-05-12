/*
 * Rendelési űrlap validálása
 */
OrderValidation = {    
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        this.setFormValidation();           
    },
    
    setFormValidation: function() {
        /*
         * Rendelési űrlapra validáció beállítása
         */
        $("#order_edit_form").validationEngine({
            promptPosition: "centerLeft: -25",
            'custom_error_messages': OrderValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
    },	
    /*
     * Hibaüzenetek validáció esetén
     */
    customErrorMessages: {
        '.name': {
            'required': {
                'message': "Addj meg nevet!"
            }
        },
        '.email': {
            'required': {
                'message': "Addj meg egy email címet!"
            }
        },
        '.telephone': {
            'required': {
                'message': "Addj meg egy telefonszámot!"
            }
        },  
        '.shippingAddressCity': {
            'required': {
                'message': "Addj meg egy települést!"
            }
        },  
        '.shippingAddressStreet': {
            'required': {
                'message': "Addj meg egy közterületet!"
            }
        },  
        '.shippingAddressStreetNumber': {
            'required': {
                'message': "Addj meg egy házszámot!"
            }
        },  
        '.shippingAddressZipCode': {
            'required': {
                'message': "Addj meg egy irányító számot!"
            }
        },  
        '.shippingOption': {
            'required': {
                'message': "Válassz egy szállítási módot!"
            }
        },  
        '.paymentOption': {
            'required': {
                'message': "Válassz egy fizetési módot!"
            }
        },  
        '.acceptConditions': {
            'required': {
                'message': "El kell fogadnod a feltételeket!"
            }
        },  

        
    },
};	