ImageUpload = {    
   
    init: function(){
        this.bindUIfunctions();
    },
    
    bindUIfunctions: function(){
        $('body').on('click', '.delete-product-image-x-button', function(){     //Termékek képeinek törlése
            var productImageId = $(this).attr("productImageId");
            ImageUpload.deleteProductImageModalInit(productImageId);  
        });
    },
    
    /*
    ** Termékek képeinek törlésénél feljövő modális ablak
    */
    deleteProductImageModalInit : function(productImageId){        
        $('#modal-delete-product-image').reveal();
        $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                $("#modal-delete-product-image").trigger('reveal:close');
        }); 
        $('body').on('click', '.delete-product-image-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a terméket
            $.ajax({
                url: $(this).attr('href'),
                data:{'productImageId': productImageId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {
                    $('.modal-content h2').html("Sikeresen töröltük ezt a képet!");
                    $('#modal-delete-product-image .delete-product-image-button').hide();
                    $('#modal-delete-product-image .exit-reveal-modal').attr('value','Kilépés');
                    $('body').on('click', '.exit-reveal-modal', function(){
                        location.reload();
                    }); 
                } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });         
    },
};

