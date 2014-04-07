SharedModal = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       $('body').on('click', '.deleteProduct', function(){     //Termékek törléseére modális ablak       
            var productId = $(this).attr("productId");
            SharedModal.deleteProductModalInit(productId);  
        });
        
       $('body').on('click', '.deleteCategory', function(){    //Kategóriák törlése modális ablak        
            var categoryId = $(this).attr("categoryId");
            SharedModal.deleteCategoryModalInit(categoryId);  
       });
       
       $('body').on('click', '.deleteProperty', function(){            
            var productPropertyId = $(this).attr("productPropertyId");
            SharedModal.deleteProductPropertyModalInit(productPropertyId);  
       });
       
       $('body').on('click', '.deleteUser', function(){            
            var userId = $(this).attr("userId");
            SharedModal.deleteUserModalInit(userId);  
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
                    $(".exit-reveal-modal").trigger('reveal:close');
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
        
    },
    
    deleteProductPropertyModalInit : function(productPropertyId){        
        $('#modal-delete-property').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-property").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-property-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a kategóriát
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'productPropertyId': productPropertyId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a "+data.productProperty +" tulajdonságot!");
                        $('#modal-delete-property .delete-property-button').hide();
                        $('#modal-delete-property .exit-reveal-modal').attr('value','Kilépés');
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
    
   /*
    ** Felhasználók törlésénél feljövő modális ablak
   */
    deleteUserModalInit : function(userId){        
        $('#modal-delete-user').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-user").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-user-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a terméket
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'userId': userId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a következő felhasználót: "+data.userName + "!");
                        $('#modal-delete-user .delete-user-button').hide();
                        $('#modal-delete-user .exit-reveal-modal').attr('value','Kilépés');
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

