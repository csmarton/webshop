Product = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
	
	$( "#search_toggle_button" ).click(function() {
		  $( "#search_form" ).slideToggle("slow");
	});
        
        $('.to_cart_button').click(function(){
            id = $(this).attr('id').split("_");
            product_id = id[1];
            $.ajax({
                url: $(this).attr("href"),
                data: {'productId' : product_id},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
               $('#cart_count').html(returnData.html);
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
    },
};	