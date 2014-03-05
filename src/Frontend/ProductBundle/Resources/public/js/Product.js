Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
	
	$( "#search_toggle_button" ).click(function() {
		  $( "#search_form" ).slideToggle("slow");
	});
    },
};	