Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
	
	$( "#search_toggle_button" ).click(function() {
		  $( "#search_form" ).slideToggle("slow");
	});  
        
        $('body').on("change", ".set-filter", function(e){ //Kosárba tevés
            e.preventDefault();
            filter = $(this).val();
            console.log(filter);
            location.href = $('#upper-menu').attr('href') + filter;
        });

    },
};	