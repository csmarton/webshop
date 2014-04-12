SharedSearch = {
    SEARCH_STRING: '',
    availableProducts :[],
    searchKey : null,
    SEARCHING_ENABLE: false,
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
        
        $("body").on('click', '.pages li a', function(e){
            
            if(!SharedSearch.SEARCHING_ENABLE){
                location.href = "?" + $(this).attr('href');
            }else{
                page = $(this).attr('href').split('&')[2].split('=')[1];
                SharedSearch.getFilteredProductByAjax(page,false,true);
            }
            e.preventDefault();
        });
        //Rendezés 
        $('body').on("change", ".set-filter", function(e){
            e.preventDefault();
            filter = "?"+$(this).val();
            if(!SharedSearch.SEARCHING_ENABLE){
                location.href = $('#searcher').attr('href') + filter;
            }
            else{                  
                SharedSearch.getFilteredProductByAjax(1,false,true);
            }
        });
        
       
        $("body").on('keyup', '#search_header', function(e){
            e.preventDefault();
            SharedSearch.searchKey = $(this).val();
            
            SharedSearch.autocompleteAjaxSubmit();
            
	});  
        
        $('#searcher_box').submit(function() {
	});
        
        $( "#search_toggle_button" ).click(function() {
            $("#search-box").slideToggle("slow");
	});  
        
        $("#searchTabs").organicTabs();
        
        //Laptop árszűrő slider
        $( "#laptop-price-slider" ).slider({
            range: true,
            min: 0,
            max: 800000,
            values: [ 0, 800000 ],
            slide: function( event, ui ) {
              $( "#laptop-price-amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ]);
              $( "#laptop-price-amount-copy" ).val(accounting.formatNumber(ui.values[ 0 ],0," ") + " Ft - " + accounting.formatNumber(ui.values[ 1 ],0," ")  + " Ft");
            }
        });
        $( "#laptop-price-amount" ).val("0 - 800000");
        $( "#laptop-price-amount-copy" ).val(accounting.formatNumber($( "#laptop-price-slider" ).slider( "values", 0 ), 0, " ") + " Ft - "
                                        + accounting.formatNumber($( "#laptop-price-slider" ).slider( "values", 1 ), 0, " ") + " Ft") ;
    
        $("body").on('click', '#laptop-filter-button', function(e){
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            
            laptopFilterManufacturer = $('#laptop-filter-manufacturer').val();
            laptopFilterWinchester = $('#laptop-filter-winchester').val();
            laptopFilterOperationSystem = $('#laptop-filter-operation-system').val();
            laptopFilterProcessor = $('#laptop-filter-processor').val();
            laptopFilterScreenSize = $('#laptop-filter-screen-size').val();
            laptopFilterMemory = $('#laptop-filter-memory').val();
            laptopFilterPrice = $('#laptop-price-amount').val();
            
            $.ajax({
                url: $('#searcher').attr('href'),			
                data: {'laptopFilterManufacturer':laptopFilterManufacturer,
                        'laptopFilterWinchester':laptopFilterWinchester,
                        'laptopFilterOperationSystem':laptopFilterOperationSystem,
                        'laptopFilterProcessor':laptopFilterProcessor,
                        'laptopFilterScreenSize':laptopFilterScreenSize,
                        'laptopFilterMemory':laptopFilterMemory,
                        'laptopFilterPrice':laptopFilterPrice,
                },
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
                if (returnData.success) {
                    $('.products_box').html(returnData.productHtml);
                    $('#upper-menu').html(returnData.upperMenu);
                }else{
                    console.log("Nincs találat");
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
            
            //SharedSearch.SEARCH_STRING = searchString;
            //location.href = $('#upper-menu').attr('href') + urlFilterString;
        });
        
        
    },
    getFilteredProductByAjax : function(page,upperMenuRefreshEnable,pagesMenuRefreshEnable){        
        order = $('.set-filter').val().split('&')[0].split('=')[1];
        by = $('.set-filter').val().split('&')[1].split('=')[1];
        laptopFilterManufacturer = $('#laptop-filter-manufacturer').val();
        laptopFilterWinchester = $('#laptop-filter-winchester').val();
        laptopFilterOperationSystem = $('#laptop-filter-operation-system').val();
        laptopFilterProcessor = $('#laptop-filter-processor').val();
        laptopFilterScreenSize = $('#laptop-filter-screen-size').val();
        laptopFilterMemory = $('#laptop-filter-memory').val();
        laptopFilterPrice = $('#laptop-price-amount').val();

        $.ajax({
        url: $('#searcher').attr('href'),			
        data: { 'order':order,
                'by':by,
                'page':page,
                'laptopFilterManufacturer':laptopFilterManufacturer,
                'laptopFilterWinchester':laptopFilterWinchester,
                'laptopFilterOperationSystem':laptopFilterOperationSystem,
                'laptopFilterProcessor':laptopFilterProcessor,
                'laptopFilterScreenSize':laptopFilterScreenSize,
                'laptopFilterMemory':laptopFilterMemory,
                'laptopFilterPrice':laptopFilterPrice,
        },
        type: 'POST',
        dataType: 'json'
        }).done(function(returnData) {
            if (returnData.success) {
                $('.products_box').html(returnData.productHtml);
                if(upperMenuRefreshEnable){
                    $('#upper-menu').html(returnData.upperMenu);
                }
                if(pagesMenuRefreshEnable){
                    $('.left-side').html(returnData.pagesMenu);
                }
            }else{
                console.log("Nincs találat");
            }
        }).fail(function(thrownError) {
            console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
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

