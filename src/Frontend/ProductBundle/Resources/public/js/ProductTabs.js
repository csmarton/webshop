ProductTabs = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){	
       this.setFormValidation(); //Form validáció beállítása

       $("#productTabs").organicTabs(); //Tabok 
        
       /*
        * új kérdés feltevése
        */ 
       $('body').on("submit", "#newQuestions", function(e){
            e.preventDefault();	
            $('#newQuestions .loading').show();
            var productId = $('#newQuestions .sendQuestion').attr('productId');
            var name = $('#newQuestions .name').val();
            var email = $('#newQuestions .email').val();
            var question = $('#newQuestions .question').val();
            $.ajax({
                url: $(this).attr("action"),
                data: {'productId' : productId,'name' : name, 'email':email, 'question' : question},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#questions-box').html(returnData.html);
               $('#newQuestions .loading').hide();
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('#newQuestions .loading').hide();
            });
            
        }); 
        
        //Képre kattintás esetén képnézegető megjelenítése
        $('a.gallery').colorbox({
            rel:'gal'
        });
        
        $('.productImageSlider').flexslider({ //Termékek képeinek megjelenítése
            animation: "slide", //animáció
            slideshow: false, //automatikus mozgatás
            itemWidth: 210, //elemek szélessége
            itemMargin: 5, //elemek közötti margó
            keyboard: false, //billenytűvel mozgatás
            minItems: 2, //minimális elemek száma
            maxItems: 3 //maximális elemek
        });   
        
        $('.productOfferSlider').flexslider({ //Ajánlott termékek képeinek megjelenítése
            animation: "slide",
            slideshow: true,
            itemWidth: 150,
            itemMargin: 5,
            keyboard: false,
        }); 
        
    },
    
    //Kérdés form validáció üzenetek
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
        //Kérdések form validáció
        $("#newQuestions").validationEngine({
            promptPosition: "centerRight: 0px",
            'custom_error_messages': ProductTabs.customErrorMessages,
            scroll: false,
            binded: false,
            validationEventTrigger: 'submit'
        });  
    },	            

};	