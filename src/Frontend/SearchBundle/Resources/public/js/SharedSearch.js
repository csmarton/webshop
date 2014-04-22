SharedSearch = {
    SEARCH_STRING: '',
    availableProducts :[],
    SEARCHING_ENABLE: false,
    resultsSelected : false,
    init: function(){
            this.bindUIActions();
    },

    
    bindUIActions: function(){
        $(".search-header-content-box").hover(
            function () { SharedSearch.resultsSelected = true; },
            function () { SharedSearch.resultsSelected = false; }
        );

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
            $('body').on("click", ".product-box a", function(e){     
                console.log("click");
            });
            
        $('body').on("blur", "#search-header", function(e){ 
             if (!SharedSearch.resultsSelected) {
               $('.search-header-content-box').hide();
            }
            
        });
        $("body").on('keyup', '#search-header', function(e){
            e.preventDefault();
            searchKey = $(this).val();            
            SharedSearch.autocompleteAjaxSubmit(searchKey);
            
	});  
        
        $('#searcher_box').submit(function() {
	});
        
        $( "#search-toggle-button-content" ).click(function() {
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
    
            //Tablet árszűrő slider
        $( "#tablet-price-slider" ).slider({
            range: true,
            min: 0,
            max: 500000,
            values: [ 0, 500000 ],
            slide: function( event, ui ) {
              $( "#tablet-price-amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ]);
              $( "#tablet-price-amount-copy" ).val(accounting.formatNumber(ui.values[ 0 ],0," ") + " Ft - " + accounting.formatNumber(ui.values[ 1 ],0," ")  + " Ft");
            }
        });
        $( "#tablet-price-amount" ).val("0 - 500000");
        $( "#tablet-price-amount-copy" ).val(accounting.formatNumber($( "#tablet-price-slider" ).slider( "values", 0 ), 0, " ") + " Ft - "
                                        + accounting.formatNumber($( "#tablet-price-slider" ).slider( "values", 1 ), 0, " ") + " Ft") ;
        
        $( "#general-price-slider" ).slider({
            range: true,
            min: 0,
            max: 500000,
            values: [ 0, 500000 ],
            slide: function( event, ui ) {
              $( "#general-price-amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ]);
              $( "#general-price-amount-copy" ).val(accounting.formatNumber(ui.values[ 0 ],0," ") + " Ft - " + accounting.formatNumber(ui.values[ 1 ],0," ")  + " Ft");
            }
        });
        $( "#general-price-amount" ).val("0 - 500000");
        $( "#general-price-amount-copy" ).val(accounting.formatNumber($( "#general-price-slider" ).slider( "values", 0 ), 0, " ") + " Ft - "
                                        + accounting.formatNumber($( "#general-price-slider" ).slider( "values", 1 ), 0, " ") + " Ft") ;
        
        /*
         * Tabletek keresése
         */                        
        $("body").on('click', '#tablet-filter-button', function(e){
            $('#tabTabletSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            
            tabletFilterType = $('#tablet-filter-type').val();
            tabletFilterManufacturer = $('#tablet-filter-manufacturer').val();
            tabletFilterWinchester = $('#tablet-filter-winchester').val();
            tabletFilterOperationSystem = $('#tablet-filter-operation-system').val();
            tabletFilterProcessor = $('#tablet-filter-processor').val();
            tabletFilterScreenSize = $('#tablet-filter-screen-size').val();
            tabletFilterMemory = $('#tablet-filter-memory').val();
            tabletFilterPrice = $('#tablet-price-amount').val();
            
            $.ajax({
                url: $('#searcher').attr('href'),			
                data: { 'tabletFilterManufacturer':tabletFilterManufacturer,
                        'tabletFilterWinchester':tabletFilterWinchester,
                        'tabletFilterOperationSystem':tabletFilterOperationSystem,
                        'tabletFilterProcessor':tabletFilterProcessor,
                        'tabletFilterScreenSize':tabletFilterScreenSize,
                        'tabletFilterMemory':tabletFilterMemory,
                        'tabletFilterPrice':tabletFilterPrice,
                        'filterType' : tabletFilterType
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
                $('#tabTabletSearch .loading').hide();
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('#tabTabletSearch .loading').hide();
            });
        });
        
        $("body").on('click', '#general-filter-button', function(e){
            $('#tabGeneralSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            
            generalFilterType = $('#general-filter-type').val();
            generalSearchString = $('#general-search-string').val();
            generalFilterPrice = $('#general-price-amount').val();
            
            $.ajax({
                url: $('#searcher').attr('href'),			
                data: { 'generalSearchString':generalSearchString,
                        'generalFilterPrice':generalFilterPrice,
                        'filterType' : generalFilterType
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
                $('#tabGeneralSearch .loading').hide();
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('#tabGeneralSearch .loading').hide();
            });
        });
        
        
        $("body").on('click', '#laptop-filter-button', function(e){
            $('#tabLaptopSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            
            laptopFilterType = $('#laptop-filter-type').val();
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
                        'filterType' : laptopFilterType
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
                $('#tabLaptopSearch .loading').hide();
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('#tabLaptopSearch .loading').hide();
            });
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
                'laptopFilterPrice':laptopFilterPrice
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
    
    autocompleteAjaxSubmit:function(searchKey){
        $.ajax({
                url: $('#search-header').attr('href'),			
                data: {'key' : searchKey},
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
                if (returnData.success) {
                    $('.search-header-content').html(returnData.html);
                    $('.search-header-content-box').show();
                    
                }else{
                    console.log("Nincs találat");
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
    }
};

