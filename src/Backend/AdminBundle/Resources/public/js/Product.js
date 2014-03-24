Product = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
        $('body').on('change', '#main-category-type', function(){
            var mainCategoryId = $(this).val();
            $.ajax({
                url: $(this).attr('href'),
                data:{'mainCategoryId': mainCategoryId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {                    
                    $('#category-box').html(data.html);
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
        $('.textEditor').summernote({
            height:400
        });
    },
}	

