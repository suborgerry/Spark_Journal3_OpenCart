jQuery(document).ready(function() {
	
	//Admin edit Image button
	jQuery('body').on("click",".CmEditProductImg", function(e){
		jQuery('#AjaxPopupCont_x').html('');
		jQuery('#AjaxPopup_x').fadeIn();
		var Pom = jQuery(this).data('pom');
		var aPath = jQuery(this).data('apath');
		jQuery('#AjaxPopupLoad_x').show();
		jQuery.ajax({url:aPath+'EditImage.php', type:'POST', dataType:'html', data:{Pom:Pom}})
            .done(function(Result){
			   jQuery('#AjaxPopupCont_x').html(Result);
			   jQuery('#AjaxPopupLoad_x').hide();
            });
	});
	
	//Admin Edit analogs group button
	jQuery('body').on("click",".CmEditProductLink", function(e){
		jQuery('#AjaxPopupCont_x').html('');
		jQuery('#AjaxPopup_x').fadeIn();
		var Pom = jQuery(this).data('pom');
		var aPath = jQuery(this).data('apath');
		jQuery('#AjaxPopupLoad_x').show();
		jQuery.ajax({url:aPath+'EditLink.php', type:'POST', dataType:'html', data:{Pom:Pom}})
            .done(function(Result){
			   jQuery('#AjaxPopupCont_x').html(Result);
			   jQuery('#AjaxPopupLoad_x').hide();
            });
	});
	
	//Admin Edit product Data button
	jQuery('body').on("click",".CmEditProductData", function(e){
		jQuery('#AjaxPopupCont_x').html('');
		jQuery('#AjaxPopup_x').fadeIn();
		var Pom = jQuery(this).data('pom');
		var aPath = jQuery(this).data('apath');
		jQuery('#AjaxPopupLoad_x').show();
		jQuery.ajax({url:aPath+'EditProduct.php', type:'POST', dataType:'html', data:{Pom:Pom}})
            .done(function(Result){
			   jQuery('#AjaxPopupCont_x').html(Result);
			   jQuery('#AjaxPopupLoad_x').hide();
            });
	});
	
	
});

jQuery(document).mousedown(function (e){
	jQuery("#AjaxPopup_x").on("click","", function(e){
		if(jQuery(".fxModal_adm").has(e.target).length === 0){
			jQuery(this).hide();
			jQuery('#AjaxPopupCont_x').html('');
		}
	});
});