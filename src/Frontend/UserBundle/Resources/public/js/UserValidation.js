var emailPattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);

UserValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
               
        this.modalInit(); 
        this.setRegistrationFormValidation();  
         
    },
    
        modalInit:function(){
        $('body').on('click', '#login-sign-in', function(){
            //$("#modal-sign-in").reveal();
            $('#modal-password-resetting').reveal();
        });
        
        
        //Bejelentkezési form elküldése
        $('body').on('click', '#submit-login-form-button', function(e) {
            email = $('#sign-in-form #email').last().val();
            password = $('#sign-in-form #password').last().val();
            $.ajax({
                url: $('#sign-in-form').last().attr('checklink'),
                data:{'email': email, 'password' : password},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) { 
                    if(data.exists){
                        window.location.reload();
                    }else{
                        var v = $("#sign-in-form").validationEngine('validate');  
                        if(v){
                            $('#sign-in-form .errorSection')
                                .html("Hibás email cím vagy jelszó!")
                                .slideDown('slow');
                        }
                        
                    }
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                    return false;
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
            return false;
        });

        $('body').on('click', '#registration-password-link', function(e) {
            $("#modal-sign-in").trigger('reveal:close');
            $("#modal-registration").reveal();
        });

        $('body').on('keyup', '.regEmail', function(e) {
            $this = $(this);
            if(emailPattern.test($(this).val())){
                $.ajax({
                    url: $('#registration-form-button').last().attr('checkLink'),
                    data:{'email': $(this).val()},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {  
                        $this.attr('used', data.userExists);
                    } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                });
            }else{
                $('.regEmail').attr('used', false);
            }    
        });
        
        //Új jelszó kérése
        $('body').on('click', '#password-resetting-button', function(e) {
            email = $('#reset-email').val();
            $.ajax({
                    url: $('#password-resetting-form').last().attr('checkLink'),
                    data:{'email': email},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {  
                        if(data.userExists){
                            console.log("OK");
                        }
                    } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                });
        });
        
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
        '.regUserName': {
            'required': {
                'message': "Addj meg egy felhasználói nevet!"
            }
        }, 
        '.regEmail': {
            'required': {
                'message': "Addj meg egy email címet!"
            }
        }, 
        '.regPass1': {
            'required': {
                'message': "Addj meg egy jelszót!"
            }
        }, 
        '.regPass2': {
            'required': {
                'message': "Jelszó megerősítése!"
            }
        }, 
        
    },
	
    setRegistrationFormValidation: function() {
        $("#registration-form").validationEngine({
            promptPosition: "centerRight:0",
            'custom_error_messages': UserValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
    },
        

}	

function RegSamePassword(field, rules, i, options){  
    if($('.regPass1').val() != $('.regPass2').val()){
        rules.push('required');
        return options.allrules.differentPass.alertText;
    }
}

function RegEmailCheck(field, rules, i, options){
    rules.push('required');
    if(emailPattern.test(field.val())){
        if($('.regEmail').attr('used') == "true")     
            return options.allrules.emailExists.alertText;
    }   
    else{
        return options.allrules.email.alertText;
    }
    
}
