Product = {	
    
    init: function(){
            this.bindUIActions();            
            this.setFormValidation($('.property-form'));
    },	
		
    bindUIActions: function(){
        /*
         * Kategória váltás esetén betöltjük az alkategóriákat újra
         */
        $('body').on('change', '#main-category-type', function(){
            var mainCategoryId = $(this).val();
            $.ajax({
                url: $(this).attr('href'),
                data:{'mainCategoryId': mainCategoryId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {                    
                    $('#category-box').html(data.html);
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });
        
        $('body').on('keyup', '.new_product_table input:text, .new_product_table input[type="number"]', function(e){
            e.preventDefault();
            if(e.keyCode == 13) {
                $('#new-product-save-button').click();
            }
        });
        /*
         * Szövegszerkesztő termék leírásának szerkesztésére
         */
        $('.textEditor').summernote({
            height:400
        });
        
        /*
         * Új tulajdonság, vagy tulajdonság szerkesztésének esemémye 
         */
        $('body').on('submit', '.property-form', function(e){
            e.preventDefault();  
            form = $(this);            
            form.find('.loading').show(); //Betöltés ikon 
            propertyValue = form.find('.property-value').val(); //tulajdonság értéke
            $.ajax({
                url: form.attr('action'),
                data:{'propertyValue': propertyValue},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) { //Sikeres küldés esete
                    if(data.edit == false){ //Új tulajdonságot adunk meg
                        $('.property-list').prepend(data.html);//hozzáfűzzük az adatot az elejére
                        appendForm = $('.property-list form').first(); 
                        Product.setFormValidation(appendForm); //a hozzáfűzött elemre is beállítjuk a form validációt
                        form.remove();
                    }else{ //Tulajdonságot szerkesztünk
                        propertyVal = form.find('.property-value');
                        propertyVal.attr('disabled',true); //Kikapcsoljuk a szerkesztési lehetőséget
                        propertyVal.attr('oldValue',propertyVal.val()); //beállítjuk a régi értéket az új értékre
                        box = form.find('.property-edit-box');
                        box.find('.edit-property-button, .delete-property-button').removeClass('hide'); //Szerkeszét és törlés gombok megjelenítése
                        box.find('.save-property-button, .cancel-property-save-button').addClass('hide');
                        
                    }
                    form.find('.loading').hide();
                                        
                } else {							  
                    console.error('HIBA a szervertől:' + data.err);
                    form.find('.loading').hide();
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
                form.find('.loading').hide();
            });
        });
        
        /*
         * Tulajdonság szerkesztése
         */
        $('body').on('click', '.edit-property-button', function(e){
            e.preventDefault();  
            parent = $(this).parents('.property-form');
            parent.find('.property-value').attr('disabled',false);
            box = parent.find('.property-edit-box');
            box.find('.edit-property-button, .delete-property-button').addClass('hide');
            box.find('.save-property-button, .cancel-property-save-button').removeClass('hide');
        });
        /*
         * Tulajdonság szerkesztésének visszavonása
         */
        $('body').on('click', '.cancel-property-save-button', function(e){
            e.preventDefault();  
            parent = $(this).parents('.property-form');
            parent.find('.property-value').attr('disabled',true);
            oldvalue = parent.find('.property-value').attr('oldValue');
            parent.find('.property-value').val(oldvalue); //visszaírjuk a régi értéket
            box = parent.find('.property-edit-box');
            box.find('.edit-property-button, .delete-property-button').removeClass('hide');
            box.find('.save-property-button, .cancel-property-save-button').addClass('hide');
        });
        
        /*
         * Tulajdonság törlése
         */
        $('body').on('click', '.delete-property-button', function(){ 
            form = $(this).parents('form');
            var productPropertyId = $(this).attr("productPropertyId");
            Product.deleteProductPropertyModalInit(productPropertyId, form);  
       });
        
    },
    
    /*
     * Törlés esetén megerősítő modális ablak 
     */
    deleteProductPropertyModalInit : function(productPropertyId,form){        
        $('#modal-delete-property').reveal();//INICIALIZÁLÁS
        $('#modal-delete-property .delete-property-modal-button').show();
        $('#modal-delete-property .exit-reveal-modal').attr('value','Mégse');
        $('.modal-content-box h2').html("Biztosan törölni szeretnéd ezt a tulajdonságot?"); 
        $('body').on('click', '.exit-reveal-modal', function(){ //Kilépés gombra a modális ablak bezárása
                $("#modal-delete-property").trigger('reveal:close');
        }); 
        $('body').on('click', '.delete-property-modal-button', function(){ //Törlés gombra kattintás után ajax kéréssel töröljük a kategóriát
            $.ajax({
                url: $(this).attr('href'),
                data:{'productPropertyId': productPropertyId},
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                if (data.success) {
                    $('.modal-content-box h2').html("Sikeresen töröltük a "+data.productProperty +" tulajdonságot!");
                    $('#modal-delete-property .delete-property-modal-button').hide();
                    $('#modal-delete-property .exit-reveal-modal').attr('value','Kilépés');
                    $('.new-property-list').prepend(data.html);
                    form.remove();
                    ProductValidation.setFormValidation();
                } else {							  
                        console.error('HIBA a szervertől:' + data.err);
                }
            }).fail(function(thrownError) {
                console.error('HIBA KELETKEZETT A KÜLDÉS SORÁN :' + thrownError);
            });
        });         
    }, 
    /*
     * Form validáció üzenetei
     */
     customErrorMessages: {
        '.property-value': {
            'required': {
                'message': "Addj meg egy értéket!"
            }
        },
        
    },
    /*
     * Form validáció
     */
    setFormValidation: function(form) {
        form.validationEngine({
            promptPosition: "topLeft: 0",
            'custom_error_messages': Product.customErrorMessages,
            scroll: false,
            maxErrorsPerField: 1,
            binded: false,
            validationEventTrigger: 'submit'
        });
    },	
}	

