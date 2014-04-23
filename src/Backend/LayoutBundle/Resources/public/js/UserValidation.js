var emailPattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);

UserValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
               
        this.loginInit(); 
        this.setFormValidations();
         
    },
    
        loginInit:function(){
        
            /*
             * Bejelentkezési modálsi ablak megjelenítése
             */   
            $('body').on('click', '.login-sign-in', function(){
                $("#modal-sign-in").reveal();
                $('#sign-in-form #email').focus();
                
                //$("#modal-registration").reveal();
                //$('#modal-password-resetting').reveal();
            });
        
        
            /*
             * Bejelentkezési modális ablak validálása és bejelentkezés
             */
            $('body').on('submit', '#sign-in-form', function(e) {
                e.preventDefault();
                email = $('#sign-in-form #email').last().val();
                password = $('#sign-in-form #password').last().val();
                $('#sign-in-form .loading').show();
                $.ajax({
                    url: $('#sign-in-form').last().attr('action'),
                    data:{'email': email, 'password' : $.sha256(password)},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) { 
                        if(data.exists){
                            window.location.href = $('#sign-in-form').attr('goto');
                        }else{
                            $('#sign-in-form .errorSection').html("Hibás email cím vagy jelszó!")
                                .slideDown('slow');
                            $('#sign-in-form .loading').hide();
                        }
                    } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                        $('#sign-in-form .loading').hide();
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                    $('#sign-in-form .loading').hide();
                });
            });

           
    },
    
    customErrorMessages: {
        '#email': {
            'required': {
                'message': "Addj meg egy email címet"
            }
        },
        '#password': {
            'required': {
                'message': "Addj meg egy jelszót!"
            }
        }
        
        
    },
	
    setFormValidations: function() {
        $("#sign-in-form").validationEngine({
            promptPosition: "centerRight: 0",
            'custom_error_messages': UserValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
        
    }	
}	

function checkEmailValidation(field, rules, i, options){
  if (field.val() == "") {
     rules.push('required');
     return options.allrules.wrongEmail.alertText;
  }else if(!emailPattern.test(field.val())){
      console.log("OK");
      rules.push('required');
      return options.allrules.email.alertText;
  }
}