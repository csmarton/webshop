SharedSearch = {
    availableProducts :[],
    searchKey : null,
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
        
         $("body").on('click', '#search_header', function(e){
             if(SharedSearch.searchKey == null){
                SharedSearch.searchKey = '';
                SharedSearch.autocompleteAjaxSubmit();
             }
	}); 
        
       
        $("body").on('keyup', '#search_header', function(e){
            e.preventDefault();
            SharedSearch.searchKey = $(this).val();
            
            SharedSearch.autocompleteAjaxSubmit();
            
	});  
        
        $('#searcher_box').submit(function() {
            console.log("Szia");       
           //return false;
	});
    },
    autocompleteAjaxSubmit:function(){
        $.ajax({
                url: $('#search_header').attr('href'),			
                data: {'key' : SharedSearch.searchKey},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
                if (returnData.success) {
                    SharedSearch.availableProducts = returnData.products;
                    $( "#search_header" ).autocomplete({
                        source: SharedSearch.availableProducts
                    });
                }else{
                    console.log("Nincs találat");
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
    }
};

