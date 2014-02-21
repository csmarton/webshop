Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       this.qtipInit();
    },
    qtipInit : function(){
        $('.myprofile_button').qtip({
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
                tip: {
                        corner: 'topMiddle',
                        color: '#e8e8e8',
                        size: {
                                x: 13, // Be careful that the x and y values refer to coordinates on screen, not height or width.
                                y: 7 // Depending on which corner your tooltip is at, x and y could mean either height or width!
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
    }
};

