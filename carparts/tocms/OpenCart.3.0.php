<?VerifyAccess_x('NoCMS');

// User Group of OpenCart
$OCGroupID = $_SESSION['CM_CMS_USER_GROUP']; //CM_CMS_USER_GROUP defined in /catalog/controller/common/carmod.php
if($OCGroupID AND is_array($CPMod->aUserGroups)){
	foreach($CPMod->aUserGroups as $GpID=>$aGp){
		if($aGp['CMS_UID']==$OCGroupID AND $_SESSION['CM_USER_GROUP']!=$GpID){ 
			$_SESSION['CM_USER_GROUP'] = $GpID;
			Redirect_x();
		}
	}
}

// OC Language
if(!$_SESSION['CM_LANG_SWICH']){$OC_LNG = $_COOKIE['language'];}else{$_SESSION['CM_LANG_SWICH']=false;}
if(isset($_GET['ln']) AND strlen($_GET['ln'])>3){$OC_LNG=substr($_GET['ln'],0,4);}
if($OC_LNG!=''){
	$arOCLc = explode('-',$OC_LNG);
	$OC_LNG = $arOCLc[0];
	if(LANG_x!=$OC_LNG AND in_array($OC_LNG,$CPMod->arLangs)){
		$_SESSION['LANG_x'] = $OC_LNG;
		$_SESSION['CM_LANG_SWICH'] = true;
		Redirect_x(str_replace('?&ln='.$_GET['ln'],'',$_SERVER['REQUEST_URI']));
	}
}
// OC Currency
if(!$_SESSION['CM_CUR_SWICH']){$OC_CUR = strtoupper($_COOKIE['currency']);}else{$_SESSION['CM_CUR_SWICH']=false;}
if(isset($_GET['cr']) AND strlen($_GET['cr'])==3){$OC_CUR=strtoupper($_GET['cr']);}
if($OC_CUR!='' AND CURR_x!=$OC_CUR){
	$_SESSION['CURR_x']=$OC_CUR;
	$_SESSION['CM_CUR_SWICH'] = true;
	Redirect_x(str_replace('?&cr='.$_GET['cr'],'',$_SERVER['REQUEST_URI']));
}

//Add to cart
if(defined('CM_ADD_TO_CART')){
	global $aCmAddCart;
	global $aCmCartErrors;
	if(isset($_SESSION['cart']) AND isset($_SESSION['cart'][$aCmAddCart['PriceNum']])){
		$_SESSION['cart'][$aCmAddCart['PriceNum']]['quantity'] = $_SESSION['cart'][$aCmAddCart['PriceNum']]['quantity']+$aCmAddCart['Quantity'];
	}else{
		$aOc = array();
		$aOc['CarMod'] = "Y";
		//Defaults
		$aOc['product_id'] = $aCmAddCart['PriceNum']; // 9 digits
		$aOc['cart_id'] = $aOc['product_id'];
		$aOc['tax_class_id'] = intval($CPMod->arSettings["CMS_TAXID"]);
		$aOc['recurring'] = false;
		$aOc['shipping'] = 1;
		$aOc['reward'] = 0;
		$aOc['stock'] = true;
		$aOc['download'] = Array();
		$aOc['subtract'] = false;
		$aOc['model'] = $aCmAddCart['Brand'];
		//Fields
		$aOc['price'] = $aCmAddCart['Price'];
		$aOc['quantity'] = $aCmAddCart['Quantity'];
		//$aOc['stock'] = $aCmAddCart['Available_num'];
		$aOc['name'] = $aCmAddCart['Name']; //.'<br><span>'.$aCmAddCart['Meta'].'</span>';
		$aOc['image'] = $aCmAddCart['Image'];
		$aOc['image_mini_height'] = '50px';
		$aOc['image_full_width'] = '140px';
		$aOc['brand'] = $aCmAddCart['Brand'];
		$aOc['product_url'] = $aCmAddCart['URL'];
		$aOc['day'] = $aCmAddCart['Delivery_num'];
		$aOc['article'] = $aCmAddCart['ArtNum'];
		//Minimum
		$aOc['minimum'] = 1;
		//if($aCmAddCart['OPTIONS']['MINIMUM']>0){ $aOc['minimum']=$aCmAddCart['OPTIONS']['MINIMUM'];}
		//Weight
		$aOc['weight'] = 0;
		$aOc['weight_prefix'] = '';
		$aOc['weight_class_id'] = 2; //1-Kg. 2-Gr
		if($aCmAddCart['Options']['Weight_kg']){ 
			$aOc['weight'] = floatval($aCmAddCart['Options']['Weight_kg']['Text']);
			$aOc['weight_prefix'] = $aCmAddCart['Options']['Weight_kg']['Postfix'];
			$aOc['weight_class_id'] = 1;
		}
		if($aCmAddCart['Options']['Weight_gr']){ 
			$aOc['weight'] = intval($aCmAddCart['Options']['Weight_gr']['Text']);
			$aOc['weight_prefix'] = $aCmAddCart['Options']['Weight_gr']['Postfix'];
			$aOc['weight_class_id'] = 2;
		}
		//Points
		$aOc['points'] = '';
		$aOc['points_prefix'] = ''; //Lng_x('Pcs',1,false)
		//if($aCmAddCart['OPTIONS']['SET']>0){ $aOc['points']=$aCmAddCart['OPTIONS']['SET']; }
		//Options
		$aOc['pre_option'][] = Array('name'=>Lng_x('Article',0),'value'=>$aCmAddCart['ArtNum'],'type'=>'text');
		$aOc['pre_option'][] = Array('name'=>'Supplier','value'=>$aCmAddCart['Supplier_stock'],'type'=>'text');
		$aOc['pre_option'][] = Array('name'=>Lng_x('Dtime_delivery',0),'value'=>$aCmAddCart['Delivery_view'],'type'=>'text');
		$aOc['pre_option'][] = Array('name'=>Lng_x('Availability',0),'value'=>$aCmAddCart['Available_view'],'type'=>'text');
		$aOc['pre_option'][] = Array('name'=>'Source','value'=>$aCmAddCart['Source'],'type'=>'text');//$aCmAddCart['Price'].' '.$aCmAddCart['Currency']
		$aOc['pre_option'][] = Array('name'=>'Date','value'=>$aCmAddCart['PriceDate'],'type'=>'text');
		
		//Vehicle
		if($aCmAddCart['RegNum']!=''){
			$aOc['pre_option'][] = Array('name'=>'RegNum','value'=>$aCmAddCart['RegNum'],'type'=>'text'); 
		}
		if($aCmAddCart['VinNum']!=''){
			$aOc['pre_option'][] = Array('name'=>'VIN','value'=>$aCmAddCart['VinNum'],'type'=>'text');
		}
		if($aCmAddCart['Vehicle']!=''){
			$aOc['pre_option'][] = Array('name'=>'Vehicle','value'=>$aCmAddCart['Vehicle'],'type'=>'text');
		}
		
		//EAN
		if($aCmAddCart['EANS']){ 
			$aOc['pre_option'][] = Array('name'=>'EAN','value'=>$aCmAddCart['EANS'],'type'=>'text');
		}
		//$aOc['pre_option'][] = Array('name'=>'Code','value'=>$aCmAddCart['PriceCode'],'type'=>'text');
		/* if(is_array($aCmAddCart['Options']) AND count($aCmAddCart['Options'])>0){
			foreach($aCmAddCart['Options'] as $OpCode=>$OpValue){
				$OpName = $aCmAddCart['OPTIONS_NAMES'][$OpCode];
				if($OpName==''){$OpName=$OpCode;}
				$aOc['pre_option'][] = Array('name'=>$OpName,'value'=>$OpValue,'type'=>'text');
			}
		} */
		foreach($aOc['pre_option'] as $arPreOp){
			$arPreOp['product_option_id']=0;
			$arPreOp['product_option_value_id']=0;
			$arPreOp['option_id']=0;
			$arPreOp['option_value_id']=0;
			$aOc['option'][] = $arPreOp;
		}
		$_SESSION['cart'][$aCmAddCart['PriceNum']] = $aOc;
		
		
		//если нужно кидать товар с депозитом. настраиваем опцию цены - Pledge_amount и сохраняем туда депозит $
		/* if($aCmAddCart['Options']['Pledge_amount']['Value']!=''){
			$aOc['product_id'] = substr(filter_var($aCmAddCart['Options']['Pledge_amount']['Value'].$aCmAddCart['PriceNum'], FILTER_SANITIZE_NUMBER_INT),0,9); // 9 digits
			$aOc['cart_id'] = $aOc['product_id'];
			$aOc['name'] = '[DEPOSIT] '.$aCmAddCart['Name'];
			$aOc['price'] = floatval($aCmAddCart['Options']['Pledge_amount']['Text']);
			//echo $PledgeAmount.' - '.$aOc['price']; die();
			$_SESSION['cart'][$aOc['product_id']] = $aOc;
		} */
	}
}
//unset($_SESSION['cart']);


if(!defined('CM_INDEX_INCLUDED')){
	$_GET['route']='common/carmod';
	$_SERVER['SCRIPT_NAME']='/index.php';
	chdir($_SERVER["DOCUMENT_ROOT"].'/');

	AxajAddCartDOM(); //Show only Cart div if AddCart action was run
	require_once($_SERVER["DOCUMENT_ROOT"].'/index.php');
	AxajAddCartDOM();
}

?>