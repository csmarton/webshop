var emailPattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);

PasswordReset = {	
    
    init: function(){
            this.bindUIActions();
            this.setFormValidations();
    },	
		
    bindUIActions: function(){
        /*
         * Elfelejtette a jelszót linkre kattintva a modális ablak megjelenítése
         */
        $('body').on('click', '#forget-password-link, .new-password-link', function(e) {
            $('#sign-in-form').trigger('reveal:close');
            $('#my-profile-text').qtip('hide');
            $('#modal-password-resetting').reveal();
            $('#reset-email').focus();
        
        });
        
        /*
         * //Kilépés gombra kattintva a modális ablak bezárása
         */
        $('body').on('click', '.exit-reveal-modal', function(){ 
                $(".exit-reveal-modal").trigger('reveal:close');
        }); 

        /*
         * Elfelejtett jelszó esetén új jelszó kérése
         */
        $('body').on('submit', '#password-resetting-form', function(e) {
            e.preventDefault();
            email = $('#reset-email').val();
            $('#password-resetting-form .loading').show();
            $.ajax({
                    url: $('#password-resetting-form').last().attr('action'),
                    data:{'email': email},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        if(data.userExists){ //Email cím létezik
                            if(data.sendEmail){ //Email kiküldve
                                $('.errorSection').hide();
                                $('#password-resetting-button').hide();
                                $('#password-resetting-form .ok-button').val("Ok");
                                $('#password-resetting-form .information').html(data.html);
                                $('#password-resetting-form .loading').hide();
                            }
                        }else{
                            $('#password-resetting-form .errorSection').html("Nincs ilyen email címmel regisztrált felhasználó!")
                                    .slideDown('slow');
                            $('#password-resetting-form .loading').hide();
                        }
                    } else {
                        console.error('HIBA a szervertől:' + data.err);
                        $('#password-resetting-form .loading').hide();
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                    $('#password-resetting-form .loading').hide();
                });
        });
        
        if($('#password-resetting-with-token-form') != undefined){
            $('#password-resetting-with-token-form').reveal();
        }
        $('body').on('click', '.new-password-with-confirmation-link', function(e) {
            $('#password-resetting-with-token-form').reveal();
        });
        
        $('body').on('submit', '#password-resetting-with-token-form', function(e) {
            e.preventDefault();
            token = $('#reset-password-confirmaion-token').val();
            pass1 = $('#reset-password-1').val();
            pass2 = $('#reset-password-2').val();
            $.ajax({
                    url: $('#password-resetting-with-token-form').attr('action'),
                    data: {'confirmationToken': token, 'password':$.sha256(pass1)},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        html = "Jelszavadat sikeresen megváltoztattuk. Bejelentkezéshet kattins <a href=\"javascript:void(0)\" class=\"sing-in-link\">ide</a>";
                        $('#password-resetting-with-token-form .information').html(html);
                        $('#password-resetting-with-token-button').hide();
                        $('#password-resetting-with-token-form .ok-button').val("Ok");
                    } else {
                        console.error('HIBA a szervertől:' + data.err);
                    }
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                });
        });
        
        $('body').on('click', '.sing-in-link', function(e) {
            $('#password-resetting-with-token-form').trigger('reveal:close');
            $('#modal-sign-in').reveal();
        });
        
        
    },
    customErrorMessages: {
        '#reset-email': {
            'required': {
                'message': "Addj meg egy email címet"
            }
        },
        '#reset-password-1': {
            'required': {
                'message': "Addj meg egy jelszót!"
            }
        },
        '#reset-password-2': {
            'required': {
                'message': "Add meg a jelszót mégegyszer!"
            }
        }
        
        
    },
	
    setFormValidations: function() {
        $("#password-resetting-form").last().validationEngine({
            promptPosition: "centerRight: 0",
            'custom_error_messages': PasswordReset.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
        
        $("#password-resetting-with-token-form").last().validationEngine({
            promptPosition: "centerRight: 0",
            'custom_error_messages': PasswordReset.customErrorMessages,
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


function checkPassword(field, rules, i, options){
  pass2 = $('#password-resetting-with-token-form #reset-password-2').val();
  if(pass2 != ""){
    if(field.val() != pass2) {
        rules.push('required');
        return options.allrules.differentPass.alertText;
    }
  }
}