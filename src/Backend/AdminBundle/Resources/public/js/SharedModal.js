/*
 * 
 * Törlés modális ablakok
 */
SharedModal = {
    init: function(){
            this.bindUIActions();
    },

    bindUIActions: function(){
       $('body').on('click', '.deleteProduct', function(){     //Termékek törlésére modális ablak       
            var productId = $(this).attr("productId");
            SharedModal.deleteProductModalInit(productId);  
        });
        
       $('body').on('click', '.deleteCategory', function(){    //Kategóriák törlése modális ablak        
            var categoryId = $(this).attr("categoryId");
            SharedModal.deleteCategoryModalInit(categoryId);  
       });
       
       $('body').on('click', '.deleteMainCategory', function(){    //Főkategóriák törlése modális ablak        
            var mainCategoryId = $(this).attr("mainCategoryId");
            SharedModal.deleteMainCategoryModalInit(mainCategoryId);  
       });
       
       $('body').on('click', '.deleteUser', function(){  //felhasználó törlése modális ablak        
            var userId = $(this).attr("userId");
            SharedModal.deleteUserModalInit(userId);  
       });
        $('body').on('click', '.deleteProperty', function(){    //Tulajdonság törlése modális ablak                
            var propertyId = $(this).attr("propertyId");
            SharedModal.deletePropertyModalInit(propertyId);  
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
    
    /*
    ** Kategória törlésénél feljövő modális ablak
    */
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
    
    /*
    ** Főkategória törlésénél feljövő modális ablak
    */
    deleteMainCategoryModalInit : function(mainCategoryId){        
        $('#modal-delete-main-category').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-main-category").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-main-category-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a terméket
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'mainCategoryId': mainCategoryId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a következő főkategóriát: "+data.mainCategoryName + "!");
                        $('#modal-delete-main-category .delete-main-category-button').hide();
                        $('#modal-delete-main-category .exit-reveal-modal').attr('value','Kilépés');
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
     * Tulajdonságok törlésére megerősítő ablak
     */
    deletePropertyModalInit : function(propertyId){        
        $('#modal-delete-property').reveal();
            $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                    $("#modal-delete-property").trigger('reveal:close');
            }); 
            $('body').on('click', '.delete-property-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a terméket
                $.ajax({
                    url: $(this).attr('href'),
                    data:{'propertyId': propertyId},
                    type: 'POST',
                    dataType: 'json'
                }).done(function(data) {
                    if (data.success) {
                        $('.modal-content h2').html("Sikeresen töröltük a következő tulajdonságot: "+data.propertyName + "!");
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
    
};

