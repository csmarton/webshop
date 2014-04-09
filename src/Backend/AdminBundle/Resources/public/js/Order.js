Order = {	
    
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
        $('body').on('click', '.fulfill-button', function(){
            $.ajax({
                url: $(this).attr('link'),
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {                    
                    if(data.canFulfill){//Teljesíthető -e a rendelés
                        $('#modal-fulfill-order h2').html("Rendelés sikeresen teljesítve!");
                        $('#modal-fulfill-order .modal-information').html("");
                    }
                    else{
                        $('#modal-fulfill-order h2').html("Rendelés nem teljesíthető");
                        $('#modal-fulfill-order .modal-information').html("Ezekből a termékekből nincs elegendő mennyiség raktáron: <br/>");
                        for(var i=0;i<data.cantFullfillProduct.length;i++){
                            $('#modal-fulfill-order .modal-information').append('<a href="">'+ data.cantFullfillProduct[i] +'</a> ')
                        }
                    }
                    $('#modal-fulfill-order').reveal();
                     
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
        $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
            $(".exit-reveal-modal").trigger('reveal:close');
        }); 
        
        $('#filter-date').datepicker({ dateFormat: 'yy-mm-dd'});
        
         $('body').on('click', '#filter-order-button', function(){
             filterName = $('#filter-name').val();
             filterDate = $('#filter-date').val();
             filterFulfill = $('#filter-fulfill').val();
              $.ajax({
                url: $(this).attr('link'),
                data:{'filterName':filterName, 'filterDate':filterDate, 'filterFulfill':filterFulfill},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {    
                    $('#order-table').html(data.html);
                    console.log("SIKER");
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
         });
    },
}	

