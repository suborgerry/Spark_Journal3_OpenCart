<?php
/*
$Selector_Position -> Main block position for CSS: Left / Right
SeLng_x() -> Function to show phrases at selected language. Phrases from /carparts/add/mselect/langs/..
$aManufGroups -> Array of Manufacturers by Groups: Pass, Comm, Moto
$aFavManuf -> Array of Favorite Manufacturers
$IsDisab.. -> String variables: contains CSS class "CmMSelectDisabled" or empty
$MfGroupsCnt -> Int variable: manufacturer groups count (Pass, Comm, Moto)
$ModelsMesg -> String variable, phrase: "No models of this year"
SeModelsAjaxStart() -> Function to cut top & bottom of AJAX response
$aModels -> Array of Models
FURL_x - Constant: real CarMod "Friendly URL" link, like "/en/carparts/" (from CarMod /config.php script)
MsLang - Constant: currently selected Language code (en,ru,..)
*/
if($_COOKIE['CarModGarage'] AND $vPicture AND $vTypID){
	if(!preg_match_all('/'.$vTypID.'/', $_COOKIE['CarModGarage']) && $vPicture){$DispFx = 'display:flex;';}
}else{$DispFx = 'display:flex;';}
?>
<div id="CmModSelector" class="CmMSelectTable" data-vehicle="<?if($vPicture){?>Y<?}?>">
    <div class="CmMSelectTableSeTD">
        <div class="CmMselBoxWrap">
            <div class="CmMSelectBox">
                <div class="CmMSelectBut <?=$IsDisabManuf?>" id="CmMS_BoxManuf">
                    <div class="CmSelManufNameBl">
                        <span class="CmColorTx" id="CmMS_Manuf" data-value="<?=$sManuf?>"><?=$vManuf?></span>
                    </div> &#9660;
                </div>
                <div class="CmMSelectDown CmMSelectDown<?=$Selector_Position?> CmMSelectDownManuf" id="CmMS_DdManuf">
                        <?if($MfGroupsCnt>1){?>
                            <div class="CmColorBr CmMSelectGroups">
                                <?$GrActive = 'CmMSelectGrActive CmColorTxi';
                                foreach($aManufGroups as $Group=>$aManufs){?>
                                    <div class="CmMSelectGrTab <?=$GrActive?>" data-group="<?=$Group?>"><?=SeLng_x('Group_'.$Group);?></div><?
                                    $GrActive = '';
                                }?>
                            </div><?
                        }
                        foreach($aManufGroups as $Group=>$aManufs){
                            ?><div id="Cm<?=$Group?>Tab" class="CmMSelectMfTab" <?=$IsDisplayTab?> >
                                <div class="CmManufInner">
                                    <?foreach($aManufs as $Code=>$Name){?>
                                        <div class="CmColorBgh CmMS_Option CmMSelectManuf <?if(in_array($Name,$aFavManuf)){?>CmColorBgL<?}?>" data-type="Manuf" data-option="<?=$Code?>"><?=$Name?></div><?
                                    }?>
                                </div>
                            </div>
                            <?
                            if($MfGroupsCnt>1){$IsDisplayTab='style="display:none;"';}
                        }?>
                </div>
            </div>
            <div class="CmMSelectBox">
                <div class="CmMSelectBut <?=$IsDisabModel?>" id="CmMS_BoxModel"><div class="CmSelModNameBl">
                        <span class="<?if($IsDisabModel){?>CmColorTxiA<?}else{?>CmColorTx<?}?>" id="CmMS_Model" data-value="<?=$sModel?>"><?=$vModel?>
                        </span>
                    </div> &#9660;</div>
                <div class="CmModelSelWrap CmMSelectDown CmMSelectDown<?=$Selector_Position?>" id="CmMS_DdModel">
                    <?SeModelsAjaxStart();
					//echo '<pre>'; print_r($aRangeYears); echo '</pre><br><br>';
					//echo implode(',',$aRangeYears);
					?>
					<div class="CmRYearBox">
						<div class="CmRYear CmColorBr"><span class="CmRYearTxt"><?=SeLng_x('Select_year')?></span>
                            <span class="CmRYearArrow">&#9660;</span>
                        </div>
						<div class="CmSelRYearBox CmColorBr">
							<?foreach($aRangeYears as $y){?>
								<div class="CmSelRYear" y="<?=$y?>"><?=$y?></div>
							<?}?>
						</div>
					</div>
					
					<?/* <select class="CmRangeYear CmColorBr" name="RYear">
						<option value="0">Year disable</option>
						<?foreach($aRangeYears as $y){?>
							<option value="<?=$y?>"><?=$y?></option>
						<?}?>
					</select> */?>
					<?echo $ModelsMesg;
                    if(count($aModels)>0){?>
                        <div class="CmMSelectInner">
                            <?foreach($aModels as $ModID=>$ModName){//echo '<pre>'; print_r($aYears); echo '</pre><br><br>';
                                ?><div class="CmColorBgh CmMS_Option CmMSelectList" data-type="Model" data-ystart="<?=$aYears[$ModID]['START']?>" data-yend="<?=$aYears[$ModID]['END']?>" data-option="<?=$ModID?>"><?=$ModName?></div><?
                            }?>
                        </div>
                    <?}SeModelsAjaxEnd();?>
                </div>
            </div>
            <div class="CmMSelectBox">
                <div class="CmMSelectBut <?=$IsDisabType?>" id="CmMS_BoxType"><div class="CmSelTypeNameBl"><span class="<?if($IsDisabModel){?>CmColorTxiA<?}else{?>CmColorTx<?}?>" id="CmMS_Type" data-value="<?=$sType?>"><?=$vType?></span></div> &#9660;</div>
                <div class="CmEngineSelWrap CmMSelectDown CmMSelectDown<?=$Selector_Position?>" id="CmMS_DdType">
                    <?SeTypesAjaxStart();
                    echo $TypesMesg;
                    if(count($aTypes)>0){//print_r($aTypes);?>
                        <div class="CmMSelectInner">
                            <?foreach($aTypes as $TypID=>$TypName){?>
                                <div class="CmColorBgh CmMS_Option CmMSelectList" data-type="Type" data-option="<?=$TypID?>"><?=$TypName?></div>
                            <?}?>
                        </div>
                    <?}SeTypesAjaxEnd();?>
                </div>
            </div>
        </div>

    </div>

</div>
<?//=$VehicleURL?>
<?//=$SelectedLink?>
<div class="CmMSelectLoading"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div>
<svg class="CmSelSvgImBlock">
    <symbol viewBox="0 0 16 16" id="vaf_icon">
        <path d="m12.853 16.0905c-.0688477 0-.138184-.014183-.203125-.0430384l-4.61816-2.05606-4.61816 2.05606c-.170898.0758066-.370117.0493956-.515625-.0670033-.145996-.116888-.214355-.30567-.177734-.489072l1.01855-5.10249-3.56055-3.5668c-.130371-.130582-.178223-.322299-.125-.499343.0532227-.176555.199707-.309583.380371-.345774l5.10352-1.02265 2.0376-4.59239c.161133-.361913.75293-.361913.914063 0l2.0376 4.59239 5.10352 1.02265c.180664.0361915.327148.169219.380371.345774.0532227.177044.0053711.368761-.125.499343l-3.56055 3.5668 1.01855 5.10249c.0366211.183402-.0317383.372184-.177734.489072-.0903321.072383-.201172.110042-.3125.110042zm-4.82129-3.14816c.0693359 0 .138672.014183.203125.0430384l3.93848 1.75381-.881836-4.41681c-.0327148-.164328.0185547-.334036.136719-.452392l3.08936-3.09436-4.44092-.890111c-.15918-.0317899-.292969-.138897-.358887-.287575l-1.68604-3.7996-1.68604 3.7996c-.065918.148678-.199707.255785-.358887.287575l-4.44092.890111 3.08936 3.09436c.118164.118356.169434.288064.136719.452392l-.881836 4.41681 3.93848-1.75381c.064453-.0288553.133789-.0430384.203125-.0430384z" transform="translate(-.032 -.09)"/>
    </symbol>
</svg>

<? //aprint_x($aRes, '$aRes');?>

<script type="text/javascript">
//SET COOKIE FUNCTION
function setCookie(key, value, expireDays, expireHours, expireMinutes, expireSeconds){
	var expireDate = new Date();
	if (expireDays) {
		expireDate.setDate(expireDate.getDate() + expireDays);
	}
	if (expireHours) {
		expireDate.setHours(expireDate.getHours() + expireHours);
	}
	if (expireMinutes) {
		expireDate.setMinutes(expireDate.getMinutes() + expireMinutes);
	}
	if (expireSeconds) {
		expireDate.setSeconds(expireDate.getSeconds() + expireSeconds);
	}
	var cleaned_host;
	if(location.host.indexOf('www.') === 0){
		cleaned_host = location.host.replace('www.','');
	}else{
		cleaned_host = window.location.hostname;
	}
	document.cookie = key +"="+ escape(value) +
		";domain="+ cleaned_host +
		";path=/"+
		";expires="+expireDate.toUTCString();
}



// DELETE COOKIE
function deleteCookie(name){
	setCookie(name, "", null , null , null, -1);
}
//GET COOKIE FUNC
function get_cookie ( cookie_name ){
	var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
	if ( results )
		return ( unescape ( results[2] ) );
	else
		return null;
}
$(document).ready(function($){
	/*GARAGE*/
	var modelItems = get_cookie('CarModGarage');
	if(modelItems != null){
		var resPars = JSON.parse(modelItems);
		var arMod = new Array();
		if(resPars != null){
			arMod = resPars;
		}else{
			arMod.push(resPars);
		}
		$('.CmModGarageWrap').html('');
		$.each(arMod,function(key,data) {
			let picMod_2 = data['pic'];
			let dataMod_2 = data['data'];
			let linkMod_2 = data['link'];
			let vIdMod_2 = data['id'];
			let manufModel_2 = data['manuf'];
			let garModel_2 = '<div class="CmSelGarItemWrap" data-vpid="'+vIdMod_2+'"><div class="CmSeModImg"><img src="'+picMod_2+'"></div><div class="CmSelImgNameBl CmColorBgLh"><a href="'+linkMod_2+'" class="CmSelModelLink CmColorTxh"><div class="CmSelManufTxt"><b>'+manufModel_2+'</b></div><div class="CmColorTxh">'+dataMod_2+'</div></a><svg id="CmDelSelMod" class="CmColorFih" data-vpid="'+vIdMod_2+'" viewBox="0 0 128 128"><path d="m109.5 16.917h-23.85v-10.417a1.75 1.75 0 0 0 -1.75-1.75h-39.8a1.751 1.751 0 0 0 -1.75 1.75v10.417h-23.85a1.75 1.75 0 0 0 -1.75 1.75v12.641a1.751 1.751 0 0 0 1.75 1.75h4.222l4.78 88.536a1.751 1.751 0 0 0 1.748 1.656h69.5a1.749 1.749 0 0 0 1.747-1.656l4.781-88.536h4.222a1.75 1.75 0 0 0 1.75-1.75v-12.641a1.749 1.749 0 0 0 -1.75-1.75zm-63.65-8.667h36.3v8.667h-36.3zm51.242 111.5h-66.184l-4.681-86.692h75.547zm10.658-90.192h-87.5v-9.141h23.816.034s.022 0 .034 0h39.733.033s.023 0 .034 0h23.816z"/><path d="m46.667 96.559a1.751 1.751 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.75 1.75 0 0 0 1.75 1.75z"/><path d="m64 96.559a1.751 1.751 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.751 1.751 0 0 0 1.75 1.75z"/><path d="m81.333 96.559a1.75 1.75 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.75 1.75 0 0 0 1.75 1.75z"/></svg></div></div>';
			$(garModel_2).appendTo('.CmModGarageWrap');
		});
	}
	// ADD MODEL TO GARAGE
	$('.CmSelAddGarage').on('click', function(){
		let itemsCookie = get_cookie('CarModGarage');
		let resP = JSON.parse(itemsCookie);
		let arModels = new Array();
		if(resP != null){
			arModels = resP;
		}
		arModels.push({manuf:'<?=$vManuf?>', link:'<?=$VehicleURL?>', pic:'<?=$vPicture?>', data:'<?=$vTypData?>', id:'<?=$vTypID?>', sort:10});
		surrModel = JSON.stringify(arModels);
		setCookie('CarModGarage', surrModel, 9999999, null, null, null);
		let par = JSON.parse(surrModel);
		let qwe = par.sort();
		$('.CmModGarageWrap').html('');
		$.each(qwe,function(key, data){
			let picMod = data['pic'];
			let dataMod = data['data'];
			let linkMod = data['link'];
			let vIdMod = data['id'];
			let manufModel = data['manuf'];
			let garModel = '<div class="CmSelGarItemWrap data-vpid="'+vIdMod+'"><div class="CmSeModImg"><img src="'+picMod+'"></div><div class="CmSelImgNameBl CmColorBgLh"><a href="'+linkMod+'" class="CmSelModelLink CmColorTxh"><div class="CmSelManufTxt"><b>'+manufModel+'</b></div><div class="CmColorTxh">'+dataMod+'</div></a><svg id="CmDelSelMod" class="CmColorFih" data-vpid="'+vIdMod+'" viewBox="0 0 128 128"><path d="m109.5 16.917h-23.85v-10.417a1.75 1.75 0 0 0 -1.75-1.75h-39.8a1.751 1.751 0 0 0 -1.75 1.75v10.417h-23.85a1.75 1.75 0 0 0 -1.75 1.75v12.641a1.751 1.751 0 0 0 1.75 1.75h4.222l4.78 88.536a1.751 1.751 0 0 0 1.748 1.656h69.5a1.749 1.749 0 0 0 1.747-1.656l4.781-88.536h4.222a1.75 1.75 0 0 0 1.75-1.75v-12.641a1.749 1.749 0 0 0 -1.75-1.75zm-63.65-8.667h36.3v8.667h-36.3zm51.242 111.5h-66.184l-4.681-86.692h75.547zm10.658-90.192h-87.5v-9.141h23.816.034s.022 0 .034 0h39.733.033s.023 0 .034 0h23.816z"/><path d="m46.667 96.559a1.751 1.751 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.75 1.75 0 0 0 1.75 1.75z"/><path d="m64 96.559a1.751 1.751 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.751 1.751 0 0 0 1.75 1.75z"/><path d="m81.333 96.559a1.75 1.75 0 0 0 1.75-1.75v-42.142a1.75 1.75 0 0 0 -3.5 0v42.142a1.75 1.75 0 0 0 1.75 1.75z"/></svg></div></div>';
			$(garModel).appendTo('.CmModGarageWrap');
		});
		$('.CmSelMyGarOpen').css('display','flex');
		$('.CmSelAddGarBlock').hide();
	});

	$('.CmSelMyGarOpen, .CmSelGarOpen').on('click', function(){
	  $('.CmGarDropDown').fadeIn(300);
	});

	//DELETE GARAGE MODEL ITEM
	$("body").on('click','#CmDelSelMod', function(){
		let vId = $(this).data('vpid'),
		vehic = $('.CmMSelectTable').data('vehicle');
		vpId = $(this).parents('.CmGarageBlock').find('.CmSelAddGarBlock').data('vpid'),
		delItem = get_cookie('CarModGarage'),
		resIt = JSON.parse(delItem);
		const idToRemove = vId;
		const filteredMod = resIt.filter((item) => item.id != idToRemove);
		if(filteredMod.length == 0 && vehic == 'Y'){
			deleteCookie('CarModGarage');
			$(this).parents('.CmSelGarItemWrap').html("<div class='CmSelGarEmpMess CmColorTx'><svg class='CmGarEmptySvg' viewBox='0 0 435.905 435.905'><path d='M430.902,120.789l-208.1-115.6c-3-1.7-6.7-1.7-9.7,0l-208,115.6c-3.2,1.8-5.1,5.1-5.1,8.7v292.7c-0.1,5.4,4.2,9.8,9.6,9.8c0.1,0,0.2,0,0.2,0h416.3c5.4,0.1,9.8-4.2,9.8-9.6c0-0.1,0-0.2,0-0.2v-292.6C436.002,125.889,434.102,122.589,430.902,120.789zM358.002,412.089h-280v-41h280V412.089z M358.002,351.089h-280v-38h280V351.089z M358.002,293.089h-280v-38h280V293.089zM358.002,235.089h-280v-38h280V235.089z M416.002,412.089h-38v-224.7c0-5.5-4.1-10.3-9.7-10.3h-300.6c-5.5,0-9.7,4.8-9.7,10.3v224.7h-38v-276.7l198-110.1l198,110.1V412.089z'/></svg><span class='CmSelEmptyTxt'><?=SeLng_x('Empty')?></span></div>").removeClass('CmSelGarItemWrap');
			$('.CmSelMyGarOpen').hide();
			$('.CmSelAddGarBlock').show().css('display','flex');
			$('.CmSelGarageFull').hide();
			$('.CmGarageSvg').show();
		}else if(filteredMod.length == 0 && vehic == ''){
			deleteCookie('CarModGarage');
			$(this).parents('.CmSelGarItemWrap').html("<div class='CmSelGarEmpMess CmColorTx'><svg class='CmGarEmptySvg' viewBox='0 0 435.905 435.905'><path d='M430.902,120.789l-208.1-115.6c-3-1.7-6.7-1.7-9.7,0l-208,115.6c-3.2,1.8-5.1,5.1-5.1,8.7v292.7c-0.1,5.4,4.2,9.8,9.6,9.8c0.1,0,0.2,0,0.2,0h416.3c5.4,0.1,9.8-4.2,9.8-9.6c0-0.1,0-0.2,0-0.2v-292.6C436.002,125.889,434.102,122.589,430.902,120.789zM358.002,412.089h-280v-41h280V412.089z M358.002,351.089h-280v-38h280V351.089z M358.002,293.089h-280v-38h280V293.089zM358.002,235.089h-280v-38h280V235.089z M416.002,412.089h-38v-224.7c0-5.5-4.1-10.3-9.7-10.3h-300.6c-5.5,0-9.7,4.8-9.7,10.3v224.7h-38v-276.7l198-110.1l198,110.1V412.089z'/></svg><span class='CmSelEmptyTxt'><?=SeLng_x('Empty')?></span></div>").removeClass('CmSelGarItemWrap');
		}else{
			leftModel = JSON.stringify(filteredMod);
			setCookie('CarModGarage', leftModel, 9999999, null, null, null);
			$(this).parents('.CmSelGarItemWrap').fadeOut(200);
			if(vId == vpId){
				$('.CmSelMyGarOpen').hide();
				$('.CmSelAddGarBlock').show().css('display','flex');
				if(filteredMod.length==0){
					$('.CmSelGarageFull').hide();
					$('.CmGarageSvg').show();
				}
			}
		}
	});

	//CLOSE GARAGE
	$(document).mousedown(function (e){ // событие клика по веб-документу
		var div = $(".CmGarDropDown"); // тут указываем ID элемента
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.CmGarDropDown').fadeOut();
		}
	});
	$('.CmSelCloseGar').on('click', function(e){
		e.stopPropagation();
		$('.CmGarDropDown').fadeOut();
	});
	/* Open Box */
	$("body").on("click", ".CmMSelectBut", function(){
		if(!$(this).hasClass('CmMSelectDisabled')){
			$('.CmMSelectDown').each(function(i,obj){ $(this).hide(); });
			$(this).next().show();
			$('.CmMSelectBox').each(function(i,obj){ $(this).removeClass('CmMSelectActive'); });
			$(this).addClass('CmMSelectActive');
		}
	});
	/* Option selected */
	$("body").on("click", ".CmMS_Option", function(){
		var Option = $(this).data('option').toString();
		var Type = $(this).data('type');
		var View = $(this).html();
		var View = View.split(' (')[0];
		$('#CmMS_'+Type).data('value',Option).html(View);

		var sYear = $('#CmMS_Year').data('value');
		var sManuf = $('#CmMS_Manuf').data('value');
		var sModel = $('#CmMS_Model').data('value');
		var sType = $('#CmMS_Type').data('value');

		$('.CmMSelectBut').each(function(i,obj){ $(this).removeClass('CmMSelectActive'); });
		$('.CmMSelectDown').each(function(i,obj){ $(this).hide(); });

		if(Type=='Year' && sManuf==''){
				CmMS_ButDd("Manuf");
		}else{
			if(Type=='Type'){
				$('.CmMSelectLoading').appendTo($(".CmMSelectVehicle")).show();
				$('.CmMSelectBut').each(function(i,obj){ $(this).addClass('CmMSelectDisabled'); });
			}else{
				var LoadingAt='';
				if(Type=='Model'){
					LoadingAt='Type';
				}else{
					LoadingAt='Model';
					$('#CmMS_BoxType').removeClass('CmMSelectActive').addClass('CmMSelectDisabled');
				}
				$('.CmMSelectLoading').appendTo($("#CmMS_Box"+LoadingAt)).show();
				// $('#CmMS_'+LoadingAt).html('');
			}
			let PostData = 'FURL_x=<?=FURL_x?>&MsLang=<?=MsLang?>&sYear='+sYear+'&sManuf='+sManuf+'&sModel='+sModel+'&sType='+sType+'&sSection=<?=$_GET['section_furl']?>';
			// console.log(PostData);
			$.ajax({
				url:'<?=SELECTOR_PROCESSOR?>', type:'POST', dataType:'html', data:PostData,
				statusCode:{
					200: function(Res){//alert(Res);
						$(location).attr('href',Res); //Redirect
					},
					201: function(Res){ //Next select Model
						$('#CmMS_DdModel').html('<div class="CmFilterWrapBl"><input class="CmSelModSearch CmColorBr" type="text" placeholder="<?=SeLng_x('Find',0)?>..."><svg class="CmSelClearBut material-icon CmColorBgh" viewBox="0 1.5 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></div><div class="CmSelModBlock">'+Res+'</div>');
						$('#CmMS_Model').html('<?=SeLng_x('Model')?>');
						CmMS_ButDd("Model");
						$('.CmSelModSearch').focus();
						ModelFilterByLet();
					},
					202: function(Res){ //Next select Type
						$('#CmMS_DdType').html(Res);
						$('#CmMS_Type').html('<?=SeLng_x('Engine')?>');
						CmMS_ButDd("Type");
					},
				},
				success: function(Res){
					$('.CmMSelectLoading').hide();
					// console.log('Debug: '+Res);
				},
			});
		}
	});

	/* Manufacturers Group select */
	$("body").on("click", ".CmMSelectGrTab", function(){
		$('.CmMSelectGrTab').each(function(i,obj){ $(this).removeClass('CmMSelectGrActive CmColorTxi'); });
		$(this).addClass('CmMSelectGrActive CmColorTxi');
		$('.CmMSelectMfTab').each(function(i,obj){ $(this).hide(); });
		var Group = $(this).data('group');
		$('#Cm'+Group+'Tab').show();
	});

	/* Close Box */
	$(document).mousedown(function (e){
		var Event = e;
		$('.CmMSelectBox').each(function(i,obj){
			if($(this).has(Event.target).length === 0){
				$(this).find(".CmMSelectBut").removeClass('CmMSelectActive');
				$(this).find(".CmMSelectDown").hide();
			}
		});
	});
	$('body').on('click', '.CmMSelectBox', function(){
		//$('.CmSelModSearch').focus();
	});
	function CmMS_ButDd(BoxIdName){
		$("#CmMS_Box"+BoxIdName).removeClass('CmMSelectDisabled').addClass('CmMSelectActive');
		$("#CmMS_Dd"+BoxIdName).show();
		if(BoxIdName=='Model'){
			$("#CmMS_Dd"+BoxIdName).css('display', 'flex');
		}
	}

	//Reset model selector
	//var el = document.getElementsByClassName('CmResetSettings');
    var el = document.getElementById('cm_clear_select');
	//for (i=0; i<el.length; i++){
		el.onclick = function(){
			deleteCookie('CarModVehicle');
		location.reload(true);
		}
	//}//
    var els = document.getElementById('cm_select_search');
    els.onclick = function(){
        location.replace('<?=$VehicleURL?>');
    }



	
	$('body').on('click', '.CmRYear', function(){
		$('.CmSelRYearBox').fadeIn(50);
	});
	
	$(document).mousedown(function (e){ // событие клика по веб-документу
		var div = $(".CmSelRYearBox"); // тут указываем ID элемента
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.CmSelRYearBox').fadeOut(50);
		}
	});
	
});

function ModelFilterByLet(){
    $('.CmSelModSearch').keyup(function(){
        if($('.CmSelModSearch').val().length == 0){
            $('.CmSelClearBut').hide();
        }
        if($('.CmSelModSearch').val().length > 0){
                $('.CmSelClearBut').show();
            var val_inp = $('.CmSelModSearch').val();
            var sw_x = 0;
            $('.CmSelTypeNAme').each(function(){
                var val_title = $(this).text();
                if (RegExp('\^'+val_inp,'i').test(val_title)||RegExp('\\s\\'+val_inp,'i').test(val_title)) {
                    $(this).parents('.CmMS_Option').removeClass('CmHideStr');
                }else{
                    $(this).parents('.CmMS_Option').addClass('CmHideStr');
                }
            });
        }else{
            $('.CmMS_Option').removeClass('CmHideStr');
        }
    });
    $('.CmSelClearBut').click(function(){
        $(this).hide();
        $('.CmSelModSearch').val('');
        $('.CmMS_Option').removeClass('CmHideStr');
    });
	
	$('.CmSelRYear').click(function(){
        $(this).parent().hide();
		var y = $(this).attr('y');
		$('.CmRYearTxt').text(y);
		
		$('.CmSelTypeNAme').each(function(){
			if(y < $(this).parents('.CmMS_Option').data('ystart') || y > $(this).parents('.CmMS_Option').data('yend')){
				$(this).parents('.CmMS_Option').addClass('CmHideYears');
			}else{
				$(this).parents('.CmMS_Option').removeClass('CmHideYears');
			}
		});
		
		
    });
	
	
	
}

</script>
