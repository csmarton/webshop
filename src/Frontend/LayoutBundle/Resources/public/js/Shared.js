Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipInit();
       this.modalInit();
       
       
    },
    
    modalInit:function(){
        $('body').on('click', '#login-sign-in', function(){
            $("#modal-sign-in").reveal();
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
        
        
        
    },
    qtipInit : function(){
        $('#my-profile-text').qtip({
            content: $("#myprofileDropDown").html(),
            show: {
                when: {
                        event: 'click'
                },
                effect: {
                        type: 'slide'
                }
            },
            hide: {
                when: {
                        event: 'unfocus'
                },
                effect: {
                        type: 'slide'
                }
            },
            position: {
                corner: {
                        target: 'bottomMiddle',
                        tooltip: 'topMiddle'
                }
            },
            style: {
                width: 160,
                tip: {
                        corner: 'topMiddle',
                        color: '#e8e8e8',
                        size: {
                                x: 13, // Be careful that the x and y values refer to coordinates on screen, not height or width.
                                y: 10 // Depending on which corner your tooltip is at, x and y could mean either height or width!
                        }
                },
                border:{
                   // width:1,
                    radius:2,
                    color:'#e8e8e8'
                },
                classes: { 
                        tooltip: 'profileDropDownQTip'
                },
                background: 'white',
            }
        });
        
        $('.register_button').qtip({
           content: {
              title: {
                 text: 'Regisztráció',
                 button: 'Mégse'
              },
              text: $('#registrationContent')
           },
           position: {
              target: $(document.body), // Position it via the document body...
              corner: 'center' // ...at the center of the viewport
           },
           show: {
              when: 'click', // Show it on click
              solo: true // And hide all other tooltips
           },
           hide: false,
           style: {
              width: 400,
              height: 200,
              padding: '14px',
              border: {
                 width: 9,
                 radius: 9,
                 color: '#666666'
              },
              name: 'light'
           },
           api: {
              beforeShow: function()
              {
                 // Fade in the modal "blanket" using the defined show speed
                 $('#qtip-blanket').fadeIn(this.options.show.effect.length);
              },
              beforeHide: function()
              {
                 // Fade out the modal "blanket" using the defined hide speed
                 $('#qtip-blanket').fadeOut(this.options.hide.effect.length);
              }
           }
        });

        // Create the modal backdrop on document load so all modal tooltips can use it
        $('<div id="qtip-blanket">')
           .css({
              position: 'absolute',
              top: 0, // Use document scrollTop so it's on-screen even if the window is scrolled
              left: 0,
              height: $(document).height(), // Span the full document height...
              width: '100%', // ...and full width

              opacity: 0.7, // Make it slightly transparent
              backgroundColor: 'black',
              zIndex: 5000  // Make sure the zIndex is below 6000 to keep it below tooltips!
           })
           .appendTo(document.body) // Append to the document body
           .hide(); // Hide it initially
   
        $('.login_button').qtip({
           content: {
              title: {
                 text: 'Belépés',
                 button: 'Mégse'
              },
              text: $('#loginContent')
           },
           position: {
              target: $(document.body), // Position it via the document body...
              corner: 'center' // ...at the center of the viewport
           },
           show: {
              when: 'click', // Show it on click
              solo: true // And hide all other tooltips
           },
           hide: false,
           style: {
              width: 400,
              height: 200,
              padding: '14px',
              border: {
                 width: 9,
                 radius: 9,
                 color: '#666666'
              },
              name: 'light'
           },
           api: {
              beforeShow: function()
              {
                 // Fade in the modal "blanket" using the defined show speed
                 $('#qtip-blanket').fadeIn(this.options.show.effect.length);
              },
              beforeHide: function()
              {
                 // Fade out the modal "blanket" using the defined hide speed
                 $('#qtip-blanket').fadeOut(this.options.hide.effect.length);
              }
           }
        });   
    }      
         
};

