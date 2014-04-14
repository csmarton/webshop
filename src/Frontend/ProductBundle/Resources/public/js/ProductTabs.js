ProductTabs = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){	
        this.setFormValidation(); 

        $("#productTabs").organicTabs();
        
       $('body').on("submit", "#newQuestions", function(e){
            e.preventDefault();		
            var productId = $('#newQuestions .sendQuestion').attr('productId');
            var name = $('#newQuestions .name').val();
            var email = $('#newQuestions .email').val();
            var question = $('#newQuestions .question').val();
            console.log(productId);
            $.ajax({
                url: $(this).attr("action"),
                data: {'productId' : productId,'name' : name, 'email':email, 'question' : question},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#questions-box').html(returnData.html);
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
            
        }); 
        
    },
    
    customErrorMessages: {
        '.name': {
            'required': {
                'message': "Add meg a nevedet!"
            }
        },
        '.email': {
            'required': {
                'message': "Add meg az email címedet!"
            }
        },  
        '.question': {
            'required': {
                'message': "Add meg a kérdésedet!"
            }
        }, 

        
    },
	
    setFormValidation: function() {
        
        $("#newQuestions").validationEngine({
            promptPosition: "centerRight: 0px",
            'custom_error_messages': ProductTabs.customErrorMessages,
            scroll: false,
            binded: false,
            validationEventTrigger: 'submit'
        });  
    },	            

};	