<?php

if(count($aTypes)){
	$aSRes=Array(
		'Types' => $aTypesR,
	);
}elseif(count($aModels)){
	$aSRes=Array(
		'Models' => $aModelsR,
	);
}else{
	$aSRes=Array(
		'Manufs' => $aManufGroups,
	);
}

$jRes = json_encode($aSRes);
echo $jRes;
die();

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
?>