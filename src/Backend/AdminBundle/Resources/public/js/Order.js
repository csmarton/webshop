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
    },
}	

