<?VerifyAccess_x('Models.templ');
//Filter - Years groups
if(count($aRes['YEARS_FULL'])>0){
	foreach($aRes['YEARS_FULL'] as $Year){
		$gY = substr($Year,0,3);
		$aY[$gY.'0'][] = $Year;
	}
	// if(IsADMIN_x){
	// 	echo '<pre>';
	// 	print_r($aYGroup);
	// 	echo '</pre>';
	// }
}
//Template Block setting
$BLOCK = $aSets['TEMPLATE_BLOCK'];
?>

<?php AjaxCut_x(); //Makes: <div id="CmAjaxBox"> ?>

<div class="CmHeadBox">
	
	<div class="boxInOver" id="sectionBox" style="display: none;">
		<div class="bxIOPosit" style="max-width:500px;">
			<div class="CmTitleBox c_BrTop3px">
				<a href="<?=FURL_x?>"><div class="cmProdLogo" title="<?=$aRes['BRAND_CODE']?>" style="background:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div></a>
				<!-- <a href="javascript:void(0)" onclick="jQuery('#sectionBox').fadeIn(400);"><div class="cmH1"><h1 class="c_H1b"><?=H1_x?></h1></div></a> -->
			</div>
		</div>
	</div>
	
    <div class="CmFilterSwitchWrap">
        <div class="CmTitleBox CmColorBr">
			<div class="CmLetYearFilterBl">
				<div class="cmProdLogo" title="<?=$aRes['BRAND_CODE']?>" style="background:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div>
				<?if($aRes['MODELS_COUNT']>7){
					foreach ($aRes['MODELS'] as $aMod) {
						$aModLet[] = substr($aMod['MOD_TITLE'], 0, 1);
					}
					$aModUn = array_unique($aModLet);?>
					<div class="CmFiltersWrap">
						<div class="CmLettNameFilt">
							<div class="CmFiltersInner">
								<div class="fByName">
									<div class="fByNameButs">
										<div class="CmActFB CmColorBgLh" data-alllang="<?=Lng_x('All')?>"><?=Lng_x('All')?></div>
										<?foreach($aModUn as $firstLett){?>
											<div class="CmModFirstLett CmColorBgLh"><?=$firstLett?></div>
										<?}?>
									</div>
									<?=ShowSEOText_x("TOP")?>
								</div>
								<div id="yearBox" class="yearBox">
									<div class="CmYearFiltBlock">
										<?foreach($aY as $YGroupe=>$aYears){?>
											<div class="CmYearGrBl CmColorBgLh">
												<div class="fYGroupe"><?=$YGroupe?></div>
												<span class="CmYearArrow">
													<svg class="CmYArrSvg" viewBox="0 0 24 24"><path d="M3 12l18-12v24z"/></svg>
												</span>
												<div class="CmYearItem" data-yearg="<?=$YGroupe?>">
													<?foreach($aYears as $Year){?>
														<div class="fYear CmColorBgLh">
															<span class="CmYearTxt"><?=$Year?></span>
															<span>
																<svg class='CmYearSvg' viewBox="0 0 24 24"><path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z"/></svg>
															</span>
														</div>
													<?}?>
												</div>
											</div>
										<?}?>
									</div>
								</div>
							</div>
							<?if($aRes['TOTAL_COUNT']>$aRes['ACTUAL_COUNT']){?>
								<div class="boxTAuto">
									<div class="slLeft sliderTg <?if(!isset($_POST['All']) OR (isset($_POST['All']) AND $_POST['All']!='Y')){echo 'activeTg';}?>" selactual="<?if(isset($_POST['All']) AND $_POST['All']=='Y'){?>N<?}?>">
										<?=Lng_x('Actual');?>
										<?if(IsADMIN_x){?>
											<sup><?=$aRes['ACTUAL_COUNT']?></sup>
										<?}?>
									</div>
									<div class="slRight CmColorTxh sliderTg <?if(isset($_POST['All']) AND $_POST['All']=='Y'){echo 'activeTg';}?>" selactual="<?if(!isset($_POST['All']) OR (isset($_POST['All']) AND $_POST['All']!='Y')){?>Y<?}?>">
										<?=Lng_x('All');?>
										<?if(IsADMIN_x){?>
											<sup><?=$aRes['TOTAL_COUNT']?></sup>
										<?}?>
									</div>
								</div>
							<?}?>
						</div>
						<div class="CmNameFiltBlock">					
							<div class="CmAllItemBut CmColorTxh"><?=Lng_x('All')?></div>
							<ul class="CmNameFItems">
								<?foreach($aRes['MODELS'] as $k=>$aModF){
									$aTitl[] = $aModF['MOD_TITLE'];
								}
								$aResTitle = array_unique($aTitl);
								$i=0;
								foreach($aResTitle as $titleTxt){$i++;
									if($i<=30){?>
										<li class="CmModelTitl CmNFiltItem CmColorTxh"><?=$titleTxt?></li>
									<?continue;}?>
									<li class="CmModelTitl CmNFiltItem2 CmColorTxh"><?=$titleTxt?></li>
								<?}?>
							</ul>
							<div class="CmShowNames" data-hide="<?=Lng_x('Hide')?>&nbsp;&#9650;" data-show="<?=Lng_x('Show_more')?>&nbsp;&#9660;"><?=Lng_x('Show_more')?>&nbsp;&#9660;</div>
						</div>
					</div>
				<?}?>
			</div>
		</div>
        <?//TDMShowBreadCumbs()?>
        
    </div>
</div>
<div class="CmBrTitleSearchWrap CmColorBgL CmColorBr">
    <div id="CmTitlH1Page"><h1 class="CmColorTx"><?=H1_x?></h1></div>
</div>
<?BreadCrumbs_x(); // Edit in: ../templates/default/includes.php ?>
<div class="CmTitleModelBlock">
	<div class="boxMod" data-typespopup="<?=$aSets['TYPES_POPUP']?>">
		<?if($aRes['MODELS_COUNT']>0){
			foreach($aRes['MODELS'] as $aModel){
				// $l = mb_substr($aModel['MOD_TITLE'], 0, 1);
					$aVds = explode(',',$aModel['VDS']);
				?><a href="<?=$aModel['FURL']?>" class="ModBox CmColorBrh CmColorTx" data-mname="<?=$aModel['MOD_TITLE']?>" data-yfrom="<?=$aModel['YEAR_START']?>" data-yto="<?=$aModel['YEAR_END']?>" title="<?=$aModel['ID']?>" style="background-image:url(<?=$aModel['IMAGE_PATH']?>);">
					<div class="ModName"><span class="CmModTitleN"><?=$aModel['MOD_TITLE']?></span><br><?if($aModel['BODY']!=''){?><i><?=$aModel['BODY']?></i><?}?></div>
					<div class="CmModYVDS" <?if(count($aVds)>4){?>style="top:100px;"<?}?>>
						<div class="ModYear"><?=$aModel['YEAR_START']?><?if($aModel['YEAR_END']>0){echo '-'.$aModel['YEAR_END'];}else{echo '-'.$aModel['YEAR_TO'];}?></div>
						<div class="ModVDS"><?=$aModel['VDS']?></div>
					</div>
					<?/* if(IsADMIN_x){?>
					<div id="Disp<?=$aModel['ID']?>" class="ModDisp <?if($aModel['sDISP']){echo 'ActualMod';}else{echo 'HiddenMod';}?>" data-modid="<?=$aModel['ID']?>"></div>
					<?}?>
					*/?>
				</a>
			<?}
		}else{
			echo Lng_x('No_models',1).'...';
		}?>
		<?//echo '<pre>'; print_r($arY); echo '</pre><br><br>';
		//echo max($arY);?>
	</div>
</div>
<?php AjaxCut_x(); //Makes: </div> ?>
<?aprint_x($aRes, '$aRes');?>
<div class="tclear"></div>
<link rel="stylesheet" type="text/css" href="<?=TEMPLATE_PAGE_DIR?>blocks/<?=$BLOCK?>.css" />

<style>
	.ModDisp{position:absolute; right:10px; bottom:30px; padding:15px; color:#fff;}
	.ModDisp:hover{border:2px solid #ff0000;}
	.ActualMod{background:#8cd686;}
	.HiddenMod{background:#9e9e9e;}
</style>
<script>
var LastMod = '';
jQuery(document).ready(function (){
	$(".ModDisp").on("click","", function(e){
		e.preventDefault(); e.stopPropagation();
		if($(this).hasClass('ActualMod')){var SetTo=0;}else{var SetTo=1;}
		var LastMod = $(this).data('modid');
		var obPostCH = {};
		obPostCH['HeadOff']='Y';
		obPostCH['ModId']=LastMod;
		obPostCH['SetTo']=SetTo;
		LoadingToggle();
		$.post("<?=$_SERVER['REQUEST_URI']?>", obPostCH, function(ResCH){
			if(ResCH=='CHANGED'){
				$("#Disp"+LastMod).toggleClass('ActualMod').toggleClass('HiddenMod');
			}else{
				alert(ResCH);
			}
			LoadingToggle();
		});
	});
});
</script>
<?=ShowSEOText_x("BOT")?>
