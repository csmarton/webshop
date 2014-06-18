SharedSearch = {
    SEARCH_STRING: '', //Keresési szöveg
    SEARCHING_ENABLE: false, //Keresés be van -e kapcsolva
    resultsSelected : false, 
    FILTER_TYPE : null, //Szűrés típusa: 1- laptop, 2- tablet, "" - általános
    init: function(){
            this.bindUIActions();
            this.initPriceSliders();
            this.initDetailedSearch();
            this.initMainSearch();

    },

    
    bindUIActions: function(){
        
        /*
         * Oldalak váltása AJAX-al
         */
        $("body").on('click', '.pages li a', function(e){
            e.preventDefault();
            if(!SharedSearch.SEARCHING_ENABLE){
                location.href = "?" + $(this).attr('href');
            }else{
                page = $(this).attr('href').split('&')[2].split('=')[1];
                SharedSearch.getFilteredProductByAjax(page,false,true);
            }
            e.preventDefault();
        });
        
        /*
         * Rendezés AJAX-al 
         */
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
        
        /*
         * Részletes keresésénél az általános fülön a input mező elküldése enter lenyomására 
         */
        $('body').on("keyup", "#general-search-string", function(e){
            e.preventDefault();
            if(e.keyCode == 13) {
                $('#general-filter-button').click();
            }
        });
        
        
    },
    
    /*
     * Termékek szűrése AJAX-al
     */
    getFilteredProductByAjax : function(page,upperMenuRefreshEnable,pagesMenuRefreshEnable){   
        SharedSearch.removeCategoryStyle();
        order = $('.set-filter').val().split('&')[0].split('=')[1];
        by = $('.set-filter').val().split('&')[1].split('=')[1];
        if(SharedSearch.FILTER_TYPE === "1"){ //Laptopok szűrése 
            laptopFilterManufacturer = $('#laptop-filter-manufacturer').val();
            laptopFilterWinchester = $('#laptop-filter-winchester').val();
            laptopFilterOperationSystem = $('#laptop-filter-operation-system').val();
            laptopFilterProcessor = $('#laptop-filter-processor').val();
            laptopFilterScreenSize = $('#laptop-filter-screen-size').val();
            laptopFilterMemory = $('#laptop-filter-memory').val();
            laptopFilterPrice = $('#laptop-price-amount').val();
            param = {
                'order':order,
                'by':by,
                'page':page,
                'laptopFilterManufacturer':laptopFilterManufacturer,
                'laptopFilterWinchester':laptopFilterWinchester,
                'laptopFilterOperationSystem':laptopFilterOperationSystem,
                'laptopFilterProcessor':laptopFilterProcessor,
                'laptopFilterScreenSize':laptopFilterScreenSize,
                'laptopFilterMemory':laptopFilterMemory,
                'laptopFilterPrice':laptopFilterPrice,
                'filterType' : SharedSearch.FILTER_TYPE
            };
        }else if(SharedSearch.FILTER_TYPE === "2"){ //Tabletek szűrése
            tabletFilterManufacturer = $('#tablet-filter-manufacturer').val();
            tabletFilterWinchester = $('#tablet-filter-winchester').val();
            tabletFilterOperationSystem = $('#tablet-filter-operation-system').val();
            tabletFilterProcessor = $('#tablet-filter-processor').val();
            tabletFilterScreenSize = $('#tablet-filter-screen-size').val();
            tabletFilterMemory = $('#tablet-filter-memory').val();
            tabletFilterPrice = $('#tablet-price-amount').val();
            param = {
                'order':order,
                'by':by,
                'page':page,
                'tabletFilterManufacturer':tabletFilterManufacturer,
                'tabletFilterWinchester':tabletFilterWinchester,
                'tabletFilterOperationSystem':tabletFilterOperationSystem,
                'tabletFilterProcessor':tabletFilterProcessor,
                'tabletFilterScreenSize':tabletFilterScreenSize,
                'tabletFilterMemory':tabletFilterMemory,
                'tabletFilterPrice':tabletFilterPrice,
                'filterType' : SharedSearch.FILTER_TYPE
            };
        }else if(SharedSearch.FILTER_TYPE === ""){//Általános szűrés
            searchHeader = SharedSearch.SEARCH_STRING;
            if(searchHeader === ""){ 
                generalSearchString = $('#general-search-string').val();
                generalFilterPrice = $('#general-price-amount').val();
                param = { 
                    'order':order,
                    'by':by,
                    'page':page,
                    'generalSearchString':generalSearchString,
                    'generalFilterPrice':generalFilterPrice,
                    'filterType' : SharedSearch.FILTER_TYPE
                };
            }else{
                generalSearchString = searchHeader;
                param = { 
                    'order':order,
                    'by':by,
                    'page':page,
                    'generalSearchString':generalSearchString,
                    'filterType' : SharedSearch.FILTER_TYPE
                };
            } 
        }else if(SharedSearch.FILTER_TYPE === "0"){ //Fejlécbeli keresés
            searchHeader = SharedSearch.SEARCH_STRING;
            if(searchHeader === ""){ 
                generalSearchString = $('#search-header').val();
                
            }else{
                generalSearchString = searchHeader;
            } 
            param = { 
                    'order':order,
                    'by':by,
                    'page':page,
                    'generalSearchString':generalSearchString,
                    'filterType' : SharedSearch.FILTER_TYPE,
                    'reloadPage' : true
            };
        }     
        //elküldjük az adatokat ajax-al
        $.ajax({
        url: $('#searcher').attr('href'),			
        data: param,
        type: 'POST',
        dataType: 'json'
        }).done(function(returnData) {
            if (returnData.success) {
                $('.products_box').html(returnData.productHtml); //termékek doboz frissítése
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
    
    /*
     * Árszűrők megjelenítése
     */
    initPriceSliders:function(){
        /*
         * Laptop árszűrő slider
         */
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
    
        /*
         * Tablet árszűrő slider
         */
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
        
        /*
         * Általános árszűrő megjelenítése
         */
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
        
    },
    
    initDetailedSearch : function(){
        /*
         * Részletes kereső elrejtése
         */
        $('body').on("blur", "#search-header", function(e){ 
             if (!SharedSearch.resultsSelected) {
               $('.search-header-content-box').hide();
            }
            
        });
        
        /*
         * Részletes kereső megjelenítése
         */
        $( "#search-toggle-button-content" ).click(function() {
            $("#search-box").slideToggle("slow");
	});  
        
        /*
         * Részletes kereső tabok váltása
         */
        $("#searchTabs").organicTabs();
        
        /*
         * Tabletek keresése
         */                        
        $("body").on('click', '#tablet-filter-button', function(e){
            $('#tabTabletSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            SharedSearch.SEARCH_STRING = "";
            SharedSearch.FILTER_TYPE = $('#tablet-filter-type').val();
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
                        'filterType' : SharedSearch.FILTER_TYPE
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
            SharedSearch.removeCategoryStyle();
        });
        
        $("body").on('click', '#general-filter-button', function(e){
            $('#tabGeneralSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            urlFilterString = $('#search-box').attr('url-filter-string');
            SharedSearch.SEARCH_STRING = "";
            SharedSearch.FILTER_TYPE = $('#general-filter-type').val();
            generalSearchString = $('#general-search-string').val();
            generalFilterPrice = $('#general-price-amount').val();
            
            $.ajax({
                url: $('#searcher').attr('href'),			
                data: { 'generalSearchString':generalSearchString,
                        'generalFilterPrice':generalFilterPrice,
                        'filterType' : SharedSearch.FILTER_TYPE
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
            SharedSearch.removeCategoryStyle();
        });
        
        /*
         * Laptopok szűrése
         */
        $("body").on('click', '#laptop-filter-button', function(e){
            $('#tabLaptopSearch .loading').show();
            SharedSearch.SEARCHING_ENABLE = true;
            SharedSearch.SEARCH_STRING = "";
            urlFilterString = $('#search-box').attr('url-filter-string');
            
            SharedSearch.FILTER_TYPE = $('#laptop-filter-type').val();
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
                        'filterType' : SharedSearch.FILTER_TYPE
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
            SharedSearch.removeCategoryStyle();
        });
    },
    
    /*
     * Fő kereső az oldal fejlécében
     */
    initMainSearch:function(){
        /*
         * Autocomplete
         */
        $('#search-header').autocomplete({
            source: function (request, response) { //AJAX-al kérjük le az adatokat
                $('#searcher-form .loading').show();
                $.ajax({
                    url: $('#search-header').attr('href'),	
                    data: { searchText: request.term, maxResults: 10 },
                    dataType: "json",
                    type: 'POST',
                    success: function (data) {
                        response($.map(data, function (item) { //feldolgozzuk az adatokat
                            return {
                                name: item.name,
                                image : item.image,
                                price : item.price
                            };
                        }))
                     $('#searcher-form .loading').hide();   
                    }
                })
            },
            select: function (event, ui) { //Termék kiválasztása
                if(ui.item)
                $('#search-header').val(ui.item.name); //Kiválasztás esetén berakjuk az input mezőbe-be az értéket
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) { //Adatok megjelenítése
            var name = "";
            if(item.name.length > 21){ //Levágjuk a neveket, ha hosszabbak mint 20 karakter
               name = item.name.substring(0,18) + "...";
            }else{
               name = item.name;
            }

            var inner_html = '<a><img src="/Shop/web/'+ item.image +'" class="search-product-image"/><div class="search-product-information"><div class="search-product-name">' + name +'</div><div class="search-product-price">' + accounting.formatNumber(item.price,0," ") + ' Ft' + '</div></div></a>';
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append(inner_html)
                    .appendTo(ul);
        };
        
        /*
         * Az autocomplete űrlap elküldése
         */
        if($('.products_box').length != 0){
            $("body").on('submit', '#searcher-form', function(e){ 
                e.preventDefault(); 
                $('#searcher-form .loading').hide();
                $('.products_box .loading').show();
                SharedSearch.SEARCHING_ENABLE = true;  
                SharedSearch.FILTER_TYPE = $('#searcher-form .general-filter-type').val();
                SharedSearch.SEARCH_STRING = $('#search-header').val();            
                $.ajax({
                    url: $('#searcher-form').attr('action'),			
                    data: { 'generalSearchString':SharedSearch.SEARCH_STRING,
                            'filterType' : SharedSearch.FILTER_TYPE,
                            'reloadPage' : false
                    },
                    type: 'POST',
                    dataType: 'json'
                }).done(function(returnData) {
                    if (returnData.success) {
                        if(returnData.reloadPage != ""){
                            console.log(returnData.reloadPage);
                            location.href = returnData.reloadPage;
                        }else{
                            $('.products_box').html(returnData.productHtml);
                            $('#upper-menu').html(returnData.upperMenu);    
                        }
                    }else{
                        console.log("Nincs találat");
                    }
                    $('.products_box .loading').hide();
                }).fail(function(thrownError) {
                    console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                    $('.products_box .loading').hide();
                });
                $('#search-header').autocomplete("close");
                SharedSearch.removeCategoryStyle();
            });
        }
            
            /*
             * Kereső form elküldése
             */
            /*$("body").on('submit', '#searcher-form', function(e){
                $('#searcher-form .loading').show();
                e.preventDefault();
                SharedSearch.SEARCHING_ENABLE = true;
                SharedSearch.FILTER_TYPE = $('.general-filter-type').val();
                SharedSearch.SEARCH_STRING = $('#search-header').val();
                $.ajax({
                url: $(this).attr('href'),			
                data: { 'generalSearchString':SharedSearch.SEARCH_STRING,
                        'filterType' : SharedSearch.FILTER_TYPE
                },
                type: 'POST',
                dataType: 'json'
            }).done(function(returnData) {
                if (returnData.success) {
                    $('.products_box').html(returnData.productHtml);
                    $('#upper-menu').html(returnData.upperMenu);    
                    $('#search-header').autocomplete('close');
                    $('#searcher-form .loading').hide();
                }else{
                    console.log("Nincs találat");
                }
                $('#tabTabletSearch .loading').hide();
                $('#searcher-form .loading').hide();
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                $('#tabTabletSearch .loading').hide();
                $('#searcher-form .loading').hide();
            });
            });*/
    },
    removeCategoryStyle:function(){
        $('#leftMenu .selected-category').removeClass('selected-category');
    }
};

