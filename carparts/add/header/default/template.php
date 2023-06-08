<link rel='stylesheet' href='/<?=CM_DIR?>/add/header/default/header.css' type='text/css' media='all' />
<div class="CmHeader">
	<?
	if($HeadSearch){
		$SearchPosition = 'Main';
		include_once(PATH_x.'/add/search/default/template.php');
	}
	
	if($HeadVIN){
		//$VinNum_Def_Lang = 'en'; //Will be session selected if in CarMod
		//$VinNum_Template = 'default';
		include_once(PATH_x.'/add/vinnum/controller.php');
	}
	
	if($HeadRegNum){
		$Pfx='';
		if(LANG_x=='fr'){$aRegNum_Countries = Array('FR');}
		if(LANG_x=='da'){$aRegNum_Countries = Array('DK');}
		if(LANG_x=='en'){$aRegNum_Countries = Array('GB');}
		if(LANG_x=='es'){$aRegNum_Countries = Array('ES');}
		if(LANG_x=='it'){$aRegNum_Countries = Array('IT');}
		if(LANG_x=='de'){$aRegNum_Countries = Array('AU');}
		if(LANG_x=='fi'){$aRegNum_Countries = Array('FI');}
		if(LANG_x=='sv'){$aRegNum_Countries = Array('SW');}
		if(LANG_x=='et'){$aRegNum_Countries = Array('EST'); $Pfx='_et';}
		//if(LANG_x=='ru'){$aRegNum_Countries = Array('EST'); $Pfx='_et';}
		
		//$RegNum_Def_Lang = 'en'; //Will be session selected if in CarMod
		//$RegNum_Template = 'default';
		include_once(PATH_x.'/add/regnum'.$Pfx.'/controller.php');
	}
	?>
</div>