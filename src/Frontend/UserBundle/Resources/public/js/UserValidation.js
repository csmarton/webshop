var emailPattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);

UserValidation = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
               
        this.modalInit(); 
        this.setFormValidations();
         
    },
    
        modalInit:function(){
        
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
                            window.location.reload();
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

            //Regisztrációs linkre kattintás
            $('body').on('click', '.registration-password-link', function(e) {
                $("#modal-sign-in").trigger('reveal:close');
                $("#modal-registration").reveal();
                $('#registration-email').focus();
            });

            //Regisztrációs form elküldése
            $('body').on('submit', '#registration-form', function(e) {
                e.preventDefault();
                email = $('#registration-form #registration-email').last().val();
                password1 = $('#registration-form #registration-password-1').last().val();
                password2 = $('#registration-form #registration-password-2').last().val();
                
                $('#registration-form .loading').show();
                $.ajax({
                    url: $('#registration-form').last().attr('action'),
                    data:{'email': email, 'password' : $.sha256(password1)},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) { 
                        if(!data.userExists){
                            window.location.reload();
                        }else{
                            $('#registration-form .errorSection').html("Ez az email cím már foglalt!")
                                .slideDown('slow');
                            $('#registration-form .loading').hide();
                        }
                    } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                        $('#registration-form .loading').hide();
                        return false;
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                    $('#registration-form .loading').hide();
                }); 
            });
    },
    
    //Űrlap validációs üzenetek
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
        },
        '#registration-email': {
            'required': {
                'message': "Addj meg egy email címet!"
            }
        },
        '#registration-password-1': {
            'required': {
                'message': "Addj meg egy jelszót!"
            }
        },
        '#registration-password-2': {
            'required': {
                'message': "Add meg a jelszót mégegyszer!"
            }
        }
        
        
    },
	
    setFormValidations: function() {
        $("#sign-in-form").last().validationEngine({ //bejelenetkezési form validáció
            promptPosition: "centerRight: 0", //felbukkanó ablak pozíciója
            'custom_error_messages': UserValidation.customErrorMessages, //üzenetek
            scroll: false, //görgetés kikapcsolása
            maxErrorsPerField: 1, //maximum hibaüzenet/mező
            binded: false, //csak küldésre validál
            validationEventTrigger: 'submit' //küldésre validál, alapérték blur
        });
        $("#registration-form").last().validationEngine({ //regisztrációs form validáció
            promptPosition: "centerRight: 0",
            'custom_error_messages': UserValidation.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
        
    }	
}	

//Email cím ellenőrzése
function checkEmailValidation(field, rules, i, options){
  if (field.val() == "") {
     rules.push('required');
     return options.allrules.wrongEmail.alertText;
  }else if(!emailPattern.test(field.val())){
      rules.push('required');
      return options.allrules.email.alertText;
  }
}

//Megegyező jelszavak ellenőrzése
function checkPassword(field, rules, i, options){
  pass2 = $('#registration-form #registration-password-2').val();
  if(pass2 != ""){
    if(field.val() != pass2) {
        rules.push('required');
        return options.allrules.differentPass.alertText;
    }
  }
}
