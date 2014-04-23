Order = {	
    DELETE_LINK : null,
    init: function(){
            this.bindUIActions();		
    },	
		
    bindUIActions: function(){
        $('body').on('click', '.fulfill-button', function(){
            $('#modal-fulfill-order').reveal();
            $('#modal-fulfill-order h2').html("Biztosan teljesíti a rendelést?");
            $('#modal-fulfill-order .modal-information').html("");
            $('#modal-fulfill-order .first-inputs').show();
            $('#modal-fulfill-order .second-inputs').hide();
            Order.DELETE_LINK = $(this).attr('link');
        });
        $('body').on('click', '.fulfill-order-button', function(){
            $.ajax({
                url: Order.DELETE_LINK,
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
                        $('#modal-fulfill-order .modal-information').append(data.html)
                    }
                    $('#modal-fulfill-order .first-inputs').hide();
                    $('#modal-fulfill-order .second-inputs').show();
                     
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
        
        $('#filterDate').datepicker({ dateFormat: 'yy-mm-dd'});        
        
    },
}	

