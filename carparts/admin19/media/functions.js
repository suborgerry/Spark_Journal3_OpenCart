var sendForm = 1;//Проверка формы (swich)
function OverlayToggle(){ /* Admin side only */
	if(jQuery("#Overlay").css('display') !== 'none'){
		jQuery("#Overlay").fadeOut(200);
	}else{
		jQuery("#Overlay").width(jQuery("#AdmContent").width()+40).height(jQuery("#AdmContent").height()+60).fadeIn(200);
	}
}

function SimplePost(Name,Value){
	jQuery("<form action='' id='SimplePost' method='post'><input type='hidden' name='"+Name+"' value='"+Value+"'/></form>").appendTo('body');
	jQuery("#SimplePost").submit();
}

function IMAddRngDisc(from,to){
	jQuery("<span>"+from+"</span> <input class='TextA rngFROM' type='text' name='RNG_FROM[]' value='' maxlength='9' style='min-width:80px; width:80px;' /> "+to+
	" <input class='TextA rngTO' type='text' name='RNG_TO[]' value='' maxlength='9' style='min-width:80px; width:80px;' /> &#9658; "+
	"<input class='TextA rngVAL' type='text' name='RNG_VALUE[]' value='' maxlength='6' style='min-width:60px; width:60px;' />% <div class='tclear'></div>").appendTo('#rngs');
	return false;
}
function AddTips(track){
	jQuery(function() {  jQuery( document ).tooltip({track:true, content:function(){return jQuery(this).prop('title');}});   });
}

//Попап вывод результата AJAX (ResType = 0 - ошибка, 1 - успех)
function ShowResult(ResText, ResType=0){
	if(ResType>0){
		jQuery('#ResMess').css({"color": "#001b00", "border": "3px solid #43a743"});
	}else{
		jQuery('#ResMess').css({"color": "#190000", "border": "3px solid #d62626"});
	}
	jQuery('#ResMess').html(ResText);
	jQuery('#BoxResMess').show();
}

//Функция подсветки незаполненных полей
function lightEmpty(id){
	$(id).css({'border':'1px solid #d8512d','background':'#fff1f1'});
	setTimeout(function(){
		$(id).css({'border':'','background':'none'});
	},1000);
}

function checkInput(inputC){
	var inpC = $('#'+inputC).val();
	var result = true;
	if(inpC.length<3 || inpC==''){
		lightEmpty('#'+inputC);
		sendForm = 0;
		result = false;
	}
	return result;
}

function chInEmpty(inputC){
	var inpC = $('#'+inputC).val();
	var result = true;
	if(inpC==''){
		lightEmpty('#'+inputC);
		sendForm = 0;
		result = false;
	}
	return result;
}

function checkIP(inpIP){
	var inpE = $('#'+inpIP).val();
	var cIP = /[^0-9\.]/g;
	var result = true;
	if(!cIP.test(inpE)){
		//$('.er_msg').html('E-Mail - неверный формат');
		lightEmpty('#'+inpIP);
		sendForm = 0;
		result = false;
	}
	return result;
}

function checkEmail(inpEmail){
	var inpE = $('#'+inpEmail).val();
	var checkE = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}jQuery/i;
	if(!checkE.test(inpE)){
		//$('.er_msg').html('E-Mail - неверный формат');
		lightEmpty('#'+inpEmail);
		sendForm = 0;
	}
}

function checkLink(inpLink){
	var inpE = $('#'+inpLink).val();
	var cL = /[^0-9]/g;
	if(!cL.test(inpE)){
		//$('.er_msg').html('E-Mail - неверный формат');
		lightEmpty('#'+inpLink);
		sendForm = 0;
	}
}

jQuery(document).ready(function(){
	
	// PHP:: aprint_x()
	jQuery(".PreTitle").on("click","", function(e){
		var PreID = jQuery(this).data('preid');
		jQuery("#Pre"+PreID).slideToggle();
	});

	// WebServices Reload by PKey
	jQuery("body").on("click",".WsReload", function(e){
		var PKey = jQuery(this).data('pkey');
		if(PKey!='' && PKey!='undefined'){
			jQuery("<form action='' id='FoWsReload' method='post'><input type='hidden' name='WsReload' value='"+PKey+"'/></form>").appendTo('body');
			jQuery("#FoWsReload").submit();
		}
	});
	
	jQuery(".fxOverlay").on("mousedown","", function(e){
		if(jQuery(".fxCont").has(e.target).length === 0){
 			jQuery(this).hide();
 		}
 	});
	
	jQuery(".fxOverlay_adm").on("mousedown","", function(e){
		if(jQuery(".fxModal_adm").has(e.target).length === 0){
 			jQuery(this).hide();
 		}
 	});

    /* if(jQuery(".fxModal").has(e.target).length === 0){
        jQuery(".fxOverlay").hide();
    } */


	//check fields when adding currency
	jQuery("#addCurr").on("click","", function(e){
		sendForm = 1;
		chInEmpty('RATADD');
		chInEmpty('TEMPLADD');
		if(sendForm==1){
			var CURADD = jQuery('#CURADD').val();
			var RATADD = jQuery('#RATADD').val();
			var TEMPLADD = jQuery('#TEMPLADD').val();
			var TRUNCADD = jQuery('#TRUNCADD').val();
			//alert(TRUNCADD);
			var cursb = jQuery('#addCurr').attr('cursb');
			//alert(attrCur);
			if (cursb !== undefined && cursb !== false && cursb !== ''){
				OverlayToggle();
				jQuery("<form action='' id='fAddCur' method='post'><input type='hidden' name='CURA' value='"+cursb+"'/><input type='hidden' name='RATA' value='"+RATADD+"'/><input type='hidden' name='TEMA' value='"+TEMPLADD+"'/><input type='hidden' name='TRUA' value='"+TRUNCADD+"'/><input type='hidden' name='curEdit' value='Y'/></form>").appendTo('body');
				jQuery("#fAddCur").submit();
			}else{
				OverlayToggle();
				jQuery("<form action='' id='fAddCur' method='post'><input type='hidden' name='CURA' value='"+CURADD+"'/><input type='hidden' name='RATA' value='"+RATADD+"'/><input type='hidden' name='TEMA' value='"+TEMPLADD+"'/><input type='hidden' name='TRUA' value='"+TRUNCADD+"'/><input type='hidden' name='curAddNew' value='Y'/></form>").appendTo('body');
				jQuery("#fAddCur").submit();
			}
		}
	});

	/* Admin side Tips */
	jQuery(".CloseTips").on("click","", function(e){
		var tipsid = jQuery(this).attr('tipsid');
		var parentCls = jQuery(this).parent();
		var objPostRL = {};
		objPostRL['HeadOff']='Y';
		objPostRL['HideTopTip']=tipsid;
		jQuery.post("", objPostRL, function(resClsTip){
			if(resClsTip=='TIP_HIDED'){
				parentCls.hide().end().remove();
			}
		});
	});
	jQuery(".TipMark").on("mouseover","", function(e){
		var tipid = jQuery(this).data('tipid');
		jQuery("#TipBox"+tipid).show();
	});
	jQuery(".TipMark").on("mouseleave","", function(e){
        var tipid = jQuery(this).data('tipid');
		jQuery("#TipBox"+tipid).hide();
    });

    /* Admin Tips for Publick side */
    jQuery("#CmContent, #AdmContent").on("mouseover",".CmATip", function(e){
        var title = jQuery(this).attr('title');
        if(title){
            jQuery(this).data('tipText', title).removeAttr('title');
            jQuery('<p class="CmATipBox"></p>').html(title).appendTo('body').show(); //alert('+'+title);
        }else{return false;}
    });
    jQuery("#CmContent, #AdmContent").on("mouseleave",".CmATip", function(e){
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.CmATipBox').remove();
    });
    jQuery("#CmContent, #AdmContent").on("mousemove",".CmATip", function(e){
		var wBody = jQuery('body').width();
		var mousey = e.pageY + 7; //Get Y coordinates
		var mousex = e.pageX; //Get X coordinates
		if(wBody - mousex < 300){
			var wTipBox = jQuery('.CmATipBox').width();
			mousex = e.pageX - wTipBox - 25; //Get X coordinates
			jQuery('.CmATipBox').css({ maxWidth:700 })
		}
        jQuery('.CmATipBox').css({ top:mousey, left:mousex })
    });

	//Edit Price
	jQuery("#EditPrice").click(function (e){
		//PopupForAjax();
		var aPath = jQuery(this).data('path');
		var objEP = {};
		objEP['HeadOff']='Y';
		objEP['AdminAjax']='Y';
		objEP['sea']='add';
		objEP['BRAND_VIEW']=jQuery(this).data('brand');
		objEP['PRID']=jQuery(this).data('type');
		objEP['ARTICLE_VIEW']=jQuery(this).data('art');
		jQuery.post('/'+aPath+'/Prices.php', objEP, function(ResEP){
			jQuery('#AjaxPopupCont_x').html(ResEP);
			jQuery('#AjaxPopup_x').css('display', 'block');
			jQuery('input').styler();
		});
	});
	jQuery("body").on("click", "#sendAEPrice", function(e){
		jQuery('input').trigger('refresh');
		//PopupForAjax();
		var sea = jQuery(this).attr('sea');
		var aPath = jQuery(this).attr('path');
		var objEP = {};
		if(sea == 'edit'){
			objEP['epk']=jQuery('#epk').val();
			objEP['ept']=jQuery('#ept').val();
			objEP['ecr']=jQuery('#ecr').val();
			objEP['edn']=jQuery('#edn').val();
			objEP['esn']=jQuery('#esn').val();
			objEP['ess']=jQuery('#ess').val();
		}
		objEP['HeadOff']='Y';
		objEP['AdminAjax']='Y';
		objEP['switchP']=sea;
		objEP['ARTICLE_VIEW']=jQuery('#ARTICLE_VIEW').val();
		objEP['BRAND_VIEW']=jQuery('#BRAND_VIEW').val();
		objEP['PRID']=jQuery('#PRID').val();
		objEP['PRICE_LOADED']=jQuery('#PRICE_LOADED').val();
		objEP['CURRENCY']=jQuery('#CURRENCY').val();
		objEP['PRICE_TYPE']=jQuery('#PRICE_TYPE').val();
		objEP['SUPPLIER_NAME']=jQuery('#SUPPLIER_NAME').val();
		objEP['SUPPLIER_STOCK']=jQuery('#SUPPLIER_STOCK').val();
		objEP['AVAILABLE_VIEW']=jQuery('#AVAILABLE_VIEW').val();
		objEP['AVAILABLE_NUM']=jQuery('#AVAILABLE_NUM').val();
		objEP['DELIVERY_VIEW']=jQuery('#DELIVERY_VIEW').val();
		objEP['DELIVERY_NUM']=jQuery('#DELIVERY_NUM').val();
		objEP['CODE']=jQuery('#CODE').val();
		jQuery(".cOpt_I").each(function(){
			var idInput = jQuery(this).attr('id');
			objEP[idInput]=jQuery(this).val();
		});
		jQuery(".cOpt_C").each(function(){
			var idInput = jQuery(this).attr('id');
			if(jQuery('#'+idInput).prop('checked')){
				//alert(idInput);
				objEP[idInput]=jQuery(this).val();
			}
		});
		jQuery.post('/'+aPath+'/Prices.php', objEP, function(ResEA){
			if(ResEA == 'ADD_PRICE' || ResEA == 'EDIT_PRICE'){
				location.reload(true);
			}else{alert(ResEA);}
		});
	});

	jQuery("body").on("click", ".EditPrice_x", function(e){
		//PopupForAjax();
		var aPath = jQuery(this).data('path');
		var objEP = {};
		objEP['HeadOff']='Y';
		objEP['AdminAjax']='Y';
		objEP['sea']='edit';
		objEP['eav']=jQuery(this).data('eav');
		objEP['epk']=jQuery(this).data('epk');
		objEP['ept']=jQuery(this).data('ept');
		objEP['ecr']=jQuery(this).data('ecr');
		objEP['edn']=jQuery(this).data('edn');
		objEP['esn']=jQuery(this).data('esn');
		objEP['ess']=jQuery(this).data('ess');
		jQuery.post('/'+aPath+'/Prices.php', objEP, function(ResEP){
			jQuery('#AjaxPopupCont_x').html(ResEP);
			jQuery('#AjaxPopup_x').css('display', 'block');
			jQuery('input').styler();
		});
	});

	//Delete price
	jQuery("body").on("click", ".DPrice_x", function(e){
		PopupForAjax();
		var aPath = jQuery(this).data('path');
		var objDel = {};
		objDel['HeadOff']='Y';
		objDel['AdminAjax']='Y';
		objDel['eav'] = jQuery(this).data('dav');
		objDel['dpk'] = jQuery(this).data('dpk');
		objDel['dpt'] = jQuery(this).data('dpt');
		objDel['dcr'] = jQuery(this).data('dcr');
		objDel['ddn'] = jQuery(this).data('ddn');
		objDel['dsn'] = jQuery(this).data('dsn');
		objDel['dss'] = jQuery(this).data('dss');

		jQuery.post('/'+aPath+'/Prices.php', objDel, function(ResDel){
			if(ResDel == 'DELETE_PRICE'){
				location.reload(true);
			}else{alert(ResDel);}
		});
	});


	//apanel
	jQuery('#CBrand_x').on('input',function(e){
		jQuery("#CArticle").prop("disabled", true);
		var aPath = jQuery(this).attr('pathx');
		//alert('red');
		jQuery("#lBV_x").show();
		jQuery("#CBrand_x").css({'color': 'red', 'border': '1px solid red'});
		var objPN = {};
		objPN['HeadOff']='Y';
		objPN['BV']=jQuery("#CBrand_x").val();
		jQuery.post("/"+aPath+"/Crosses.php", objPN, function(ResPN){
			jQuery("#lBV_x").html(ResPN);
		});
	});

	jQuery("body").on("click", ".optBV", function(e){
		jQuery("#CArticle").prop("disabled", false);
		jQuery('#BrBrand').val(jQuery(this).text());
		jQuery('#CBrand_x').val(jQuery(this).text());
		jQuery("#CBrand_x").css({'color': 'black', 'border': '1px solid #bebebe'});
		jQuery("#lBV_x").hide();
	});

	jQuery('#DLeft').on('click',function(e){
		if(jQuery(this).hasClass("direcActive_x")){
			if(jQuery('#DRight').hasClass("direcActive_x")){
				jQuery(this).removeClass("direcActive_x");
			}
		}else{
			jQuery(this).addClass("direcActive_x");
		}
	});

	jQuery('#DRight').on('click',function(e){
		if(jQuery(this).hasClass("direcActive_x")){
			if(jQuery('#DLeft').hasClass("direcActive_x")){
				jQuery(this).removeClass("direcActive_x");
			}
		}else{
			jQuery(this).addClass("direcActive_x");
		}
	});

	var isUpdCatID = false;
	jQuery("#CArticle").on("input","", function(e){
		var ResEl = jQuery(this).prev();
		ResEl.show();
		var CurEVal = jQuery(this).val();

		isUpdCatID = true;
		ResEl.addClass("InpResX"); ResEl.html('OK');
	});
	jQuery("#CArticle").on("focusout","", function(e){
		var aPath = jQuery(this).attr('pathx');
		if(isUpdCatID){
			OverlayToggle();
			var objPostFV = {};
			objPostFV['HeadOff']='Y';
			objPostFV['ArtCheck']=jQuery(this).val();
			objPostFV['BraCheck']=jQuery('#CBrand_x').val();
			jQuery.post("/"+aPath+"/Crosses.php", objPostFV, function(Res){
				OverlayToggle();
				if(Res=='SUCCESS_ART'){

				}else if(Res=='NO_ART'){

				}else{
					alert(Res);
				}
			});
			jQuery(this).prev().hide();
			isUpdCatID = false;
		}
	});
	
	//Закрыть попап окно при выборе раздела/бренда
	jQuery(document).click(function(e){
		var elem = jQuery(".boxAList");
		if(e.target!=elem[0]&&!elem.has(e.target).length){
			elem.fadeOut(100);
		}
	})


	// Font-size admin-panel main page
	// const admSect = document.getElementsByClassName('AdBtName');
	// for(i=0; i<admSect.length; i++){
	// 	const txt = jQuery(admSect[i]).text();
	// 	const edTxt = txt.replace(/\s/g, '');
	// 	const txtVal = edTxt.length;
	// 	if(txtVal > 19){
	// 		jQuery(admSect[i]).css('font-size','11px');
	// 	}else{
	// 		jQuery(admSect[i]).css('font-size','12px');
	// 	}
	// }


	// Show the lang block
	$(window).scroll(function(){
		if($(window).scrollTop()>=20){
			if ($('#topap').hasClass()==false) $('#topap').addClass('topfx');
		}else{$('#topap').removeClass('topfx');}
	});
	
	/* $('#lngbut').hover(function(){
		$('#lngsubmenu').css('display', 'block');
		const countEl = document.querySelectorAll('.AdLangElement');
		if(countEl.length==2){
			//document.getElementById('lngsubmenu').style = 'display:grid; grid-template-columns:1fr';
		}
	},
	function() {
		$('#lngsubmenu').css('display', 'none');
	}); */
	
	$('body').on('mouseenter', '#lngbut', function(){
		$('#cursubmenu').css('display', 'none');
		$('#lngsubmenu').css('display', 'block');
	});
	$('body').on('mouseenter', '#curbut', function(){
		$('#lngsubmenu').css('display', 'none');
		$('#cursubmenu').css('display', 'block');
	});
	
	$('body').on('mouseleave', '.boxBut50_x', function(){
		$('#lngsubmenu').css('display', 'none');
		$('#cursubmenu').css('display', 'none');
    });
	
	
	/* $('#curbut').hover(function(){ 
		$('#cursubmenu').show();}, function() { $('#cursubmenu').hide(); 
	}); */
	$('.AdmOpenB').click(function(){
		$(this).hide();
		$('.AdmCloseB').show();
		$('.AdmTopmenu').css('top','54px');
	});
	$('.AdmCloseB').click(function(){
		$(this).hide();
		$('.AdmOpenB').show();
		$('.AdmTopmenu').css('top','-200px');
	});
});
