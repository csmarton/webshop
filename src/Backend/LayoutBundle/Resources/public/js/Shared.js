Shared = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       $('body').on('click', '.deleteProduct', function(){            
            var productId = $(this).attr("productId");
            Shared.deleteProductModalInit(productId);  
        });
        
       $('body').on('click', '.deleteCategory', function(){            
            var categoryId = $(this).attr("categoryId");
            Shared.deleteCategoryModalInit(categoryId);  
       });
       
    },
    
    /*
    ** Termékek törlésénél feljövő modális ablak
    */
    deleteProductModalInit : function(productId){        
        $('#modal-delete-product').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-product").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-product-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a terméket
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'productId': productId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a "+data.productName +" terméket!");
                        $('#modal-delete-product .delete-product-button').hide();
                        $('#modal-delete-product .exit-reveal-modal').attr('value','Kilépés');
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
    deleteCategoryModalInit : function(categoryId){        
        $('#modal-delete-category').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-category").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-category-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a kategóriát
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'categoryId': categoryId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a "+data.categoryName +" kategóriát!");
                        $('#modal-delete-category .delete-category-button').hide();
                        $('#modal-delete-category .exit-reveal-modal').attr('value','Kilépés');
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
        
    }  
};

