<?VerifyAccess_x('ProductListTable.templ'); ?>
<?$aPrC=array(); // For show or hide title svg?>

<div class="CmPartTableView">
	<div class="CmTitleRowBl">
		<div class="CmBranArtWrap">
			<div class="CmImageIconTitlBl">
				<svg class="CmImageIconTitl" viewBox="0 0 24 24" data-imgsrc="<?=$aProd['Image']?>">
					<circle cx="12" cy="12" r="3.2"/><path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
				</svg>
			</div>
			<div class="CmBrArtTitl">
				<span><?=Lng_x('Brand')?>/<?=Lng_x('Article')?></span>
			</div>
			<div class="CmNameTitl">
				<span><?=Lng_x('Name')?></span>
			</div>
		</div>
		<?//if($aProd['PricesCount']){?>
			<div class="CmAvalDelStockWrap <?if(HIDE_PRODUCTS_COUNT){?>CmGrid1Fr<?}?>">
				<div class="CmAvalTitl <?if(HIDE_PRODUCTS_COUNT){?>CmBordRightN<?}?>">
					<span><?=Lng_x('Availability',0)?></span>
				</div>
				<?if(!HIDE_PRODUCTS_COUNT){?>
					<div class="CmDelivTitl">
						<span><?=Lng_x('Dtime_delivery',0)?></span>
					</div>
				<?}?>
				<div class="CmStockTitl">
					<span><?=Lng_x('Stock');?></span>
				</div>
			</div>
		<?//}?>
		<div class="CmPriceTitl"><?=Lng_x('Price')?></div>
	</div>
	<?foreach($aRes['PRODUCTS'] as $PKEY=>$aProd){?>
		<div class="CmProdTabRow CmAdmButsProduct <?if(!$aProd['PricesCount']){?>CmProdRowChange<?}?>" <?=isAdmButs_Product_x($aProd)?>>
			<div class="CmImBranArtWrap">
				<div class="CmProdImgBl <?if($aProd['Image']){?>CmPopUpImg<?}?> <?=$PKEY?>" data-imgsrc="<?=$aProd['Image']?>">
					<?if($aProd['Image']){?>
						<img class="CmPrIm" src="<?=$aProd['Image']?>" alt="<?=$aProd['Brand'].' '.$aProd['ArtNum'].' - '.$aProd['Name'].' '.$_SERVER['SERVER_NAME']?>">
					<?}?>
				</div>
				<div class="CmBrandArtBlock <?=$PKEY?>" rowspan="">
					<a class="cmBrandArt" href="<?=$aProd['Link']?>">
						<span class="brand_c" >
							<?=$aProd['Brand']?>
						</span>
						<span class="artic_c">

							<?=$aProd['ArtNum']?>
						</span>
					</a>
				</div>
				<div class="CmNameProdBlock <?=$PKEY?>" rowspan="">
					<a class="CmColorTxh" href="<?=$aProd['Link']?>"><?=$aProd['Name']?></a>
				</div>
			</div>
			
			
			
			
			
			
			
			
				<?if($aProd['PricesCount']){$aPrC[]='Y';?>
					<?$aProd['FirstPrice'] = array_shift($aProd['PRICES']);?>
					<div class="WsDataTb_x" <?if($aProd['WsAjax']){?>data-dir="<?=CM_DIR?>" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>"<?}?>>
						<div class="CmAvDelStWrap <?if(HIDE_PRODUCTS_COUNT){?>CmGrid1Fr<?}?>">
							<div class="CmAvailNumBlock CmColorTx CmTitShow <?if(HIDE_PRODUCTS_COUNT){?>CmBordRightN<?}?>" title="<?=Lng_x('Availability');?>">
								<?PrintProductAvailable_x($aProd['FirstPrice'], $aRes)?>
							</div>
							<?if(!HIDE_PRODUCTS_COUNT){?>
								<div class="CmDeliveryBlock <?if($aProd['FirstPrice']['DELIVERY_NUM']==0){echo 'CmInStockDelivery';}else{echo 'CmTimeDelivery';}?> CmTitShow" title="<?=Lng_x('Dtime_delivery',0)?>">
									<?if($aProd['FirstPrice']['DELIVERY_NUM']==0){?>
										<div class="CmInStockText">
											<?=Lng_x('In_stock')?>
										</div>
									<?}else{?>
										<span class="CmTextStock">
											<?=$aProd['FirstPrice']['DELIVERY_VIEW'];?>
										</span>
									<?}?>
								</div>
							<?}?>
							<div class="CmStockNameBl">
								<?if($aRes['SHOW_STOCK']&&$aProd['FirstPrice']['SUPPLIER_STOCK']!=''){?>
									<div class="CmTablePrStock CmTitShow" title="<?=Lng_x('Stock');?>">
										<?=$aProd['FirstPrice']['SUPPLIER_STOCK']?>
									</div>
								<?}?>
								<?if($aRes['SHOW_SUPPLIER']&&$aProd['FirstPrice']['SUPPLIER_NAME']!=''){?>
									<div class="CmTablePrName CmTitShow" title="<?=Lng_x('Stock');?>">
										<span>/</span>
										<span class="CmSupplNameText"><?=$aProd['FirstPrice']['SUPPLIER_NAME']?></span>
									</div>
								<?}?>
							</div>
						</div>
					
						<div class="CmPriceQuantBlWrap">
							<div class="CmDiscVatBlock">
								<?if($aProd['FirstPrice']['OLD_PRICE']){?>
									<div class="CmTableDiscPrice">
										<?if($aProd['FirstPrice']['PRICE_INCLUDE']){?>
											<div class="CmOldPrice"><i><span class="CmColorTx"><?=Lng_x('Including',1)?></span>&nbsp;<span class="CmVatTxt CmColorTx"><?=Lng_x($aProd['FirstPrice']['PRICE_RULE'],1)?></span></i>:&nbsp;</div>
											<div class="CmPercentDisc CmVatIncl"><?=$aProd['FirstPrice']['PRICE_INCLUDE']?></div>
										<?}else{?>
											<div class="CmOldPrice"><i><span class="CmOldPriceTable CmOldPr CmColorTx"><?=$aProd['FirstPrice']['OLD_PRICE']?></span></i>&ensp;</div>
											<div class="CmPercentDisc CmMinusPerc"><?=$aProd['FirstPrice']['DISCOUNT_VIEW']?></div>
										<?}?>
									</div>
								<?}?>
								<div class="CmTablePrCost" style="color:#<?if($aProd['FirstPrice']['AVAILABLE_NUM']==0 && $aRes['ALLOW_NOTAVAIL']==false){echo '808080';}?>;">
									<?=$aProd['FirstPrice']['PRICE_FORMATED']?>
								</div>
							</div>
							<div class="cm_qtyCart">
								<?if($aProd['FirstPrice']['AVAILABLE_NUM']>0 || $aRes['ALLOW_NOTAVAIL']){
								if($aProd['FirstPrice']['AVAILABLE_NUM']==0 && $aRes['ALLOW_NOTAVAIL']){
									$aProd['FirstPrice']['AVAILABLE_NUM'] = 99;
								}?>
									<?// "ADD TO CART" Class is: CmAddToCart / CmAddToCartQty (../includes.php)?>
									<?if($aSets['ONECLICK_EMAIL_ORDER']){?>
										<div class="CmMailOrder toCartButt CmColorBg" data-tab="AskPrice" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>"  data-link="<?=$ProductURL?>">
											<span>
												<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
												<span class="cartText"><?=Lng_x('Order')?></span>
											</span>
										</div>
									<?}else{?>
										<div class="CmQuantBlToCartBl">
											<div class="cm_qty_t">
												<div class="quantMinus_t c_TxHov cm_countButM">-</div>
												<input name="re_count" type="text" class="CmAddToCartQty quantProd_t cm_countRes" value="1" data-maxaval="<?=$aProd['FirstPrice']['AVAILABLE_NUM']?>">
												<div class="quantPlus_t c_TxHov cm_countButP">+</div>
											</div>
											<div class="CmAddToCart CmTablePrToCart CmColorBg" data-furl="<?=$aProd['Link']?>" data-priceid="<?=$aProd['FirstPrice']['PriceID']?>">
												<svg class="cm_HideCartImg" viewBox="-1 -3 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
											</div>
										</div>
									<?}?>
								<?}else{?>
									<div class="cm_NotAvailable_t">
										<svg class="cm_NotAvImg" viewBox="0 -2 24 24"><path d="M13.5 18c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-3.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm13.257-14.5h-1.929l-3.473 12h-13.239l-4.616-11h2.169l3.776 9h10.428l3.432-12h4.195l-.743 2zm-12.257 1.475l2.475-2.475 1.414 1.414-2.475 2.475 2.475 2.475-1.414 1.414-2.475-2.475-2.475 2.475-1.414-1.414 2.475-2.475-2.475-2.475 1.414-1.414 2.475 2.475z"/></svg>
										<span><?=Lng_x('Not_available')?></span>
									</div>
								<?}?>
							</div>
						</div>
						<?if(count($aProd['PRICES']) > 0 && !$aRes['FINDPRICE_BUTTON']){?>
							<?if($aSets['NOT_HIDE_PRICES'] == 0 || ($aSets['NOT_HIDE_PRICES'] == 1 && $_SESSION['CM_ACTIVE_TAB'] != 'LIST')){?>
								<div></div>
								<div class="CmMorePrices CmColorTx" <?if($_SESSION['CM_ACTIVE_TAB']=='TABLE'){?>style="margin-top:0px;"<?}?>>&#9660; <?=Lng_x('Show_more_prices')?> (<?=count($aProd['PRICES'])?>)
									<div class="morePricestab" style="position:absolute;">
										<table class="CmTablePriceWrap ">
											<thead>
												<tr class="CmTitleImg">
													<?if(!HIDE_PRODUCTS_COUNT){?>
														<th>
															<div id="cmdelnum" class="CmSvgDelivNotHide">
																<?if(count($aProd['PRICES']) > 0){?>
																	<div class="<?if($prSort[0]=='Delivery'){?>CmColorFi<?}?> CmSortBlock CmDescSort CmDelivery" data-sort="desc" data-val="Delivery" data-url="<?=$aRes['DETAIL_PAGE_URL']?>"><?if($aPageSVG['CmSort']){echo $aPageSVG['CmSort'];}else{echo $aListSVG['CmSort'];}?></div>
																<?}?>
																<svg class="<?if($prSort[0]=='Delivery'){?>CmColorFi<?}?> cm_deliv" viewBox="0 0 24 24" fill-rule="evenodd" clip-rule="evenodd"><path d="M2 11.741c-1.221-1.009-2-2.535-2-4.241 0-3.036 2.464-5.5 5.5-5.5 1.706 0 3.232.779 4.241 2h4.259c.552 0 1 .448 1 1v2h4.667c1.117 0 1.6.576 1.936 1.107.594.94 1.536 2.432 2.109 3.378.188.312.288.67.288 1.035v4.48c0 1.156-.616 2-2 2h-1c0 1.656-1.344 3-3 3s-3-1.344-3-3h-4c0 1.656-1.344 3-3 3s-3-1.344-3-3h-2c-.552 0-1-.448-1-1v-6.259zm6 6.059c.662 0 1.2.538 1.2 1.2 0 .662-.538 1.2-1.2 1.2-.662 0-1.2-.538-1.2-1.2 0-.662.538-1.2 1.2-1.2zm10 0c.662 0 1.2.538 1.2 1.2 0 .662-.538 1.2-1.2 1.2-.662 0-1.2-.538-1.2-1.2 0-.662.538-1.2 1.2-1.2zm-7.207-11.8c.135.477.207.98.207 1.5 0 3.036-2.464 5.5-5.5 5.5-.52 0-1.023-.072-1.5-.207v4.207h1.765c.549-.614 1.347-1 2.235-1 .888 0 1.686.386 2.235 1h5.53c.549-.614 1.347-1 2.235-1 .888 0 1.686.386 2.235 1h1.765v-4.575l-1.711-2.929c-.179-.307-.508-.496-.863-.496h-4.426v6h-2v-9h-2.207zm5.207 4v3h5l-1.427-2.496c-.178-.312-.509-.504-.868-.504h-2.705zm-10.5-6c1.932 0 3.5 1.568 3.5 3.5s-1.568 3.5-3.5 3.5-3.5-1.568-3.5-3.5 1.568-3.5 3.5-3.5zm.5 3h2v1h-3v-3h1v2z"/></svg>
															</div>
														</th>
													<?}?>
													<?if($noSuplStock){?>
														<th>
															<div class="CmSvgStockNotHide">
																<svg class="cm_stock" viewBox="0 0 24 24" fill-rule="evenodd" clip-rule="evenodd"><path d="M5 23h-2v-10l8.991-8.005c1.124.998 2.25 1.997 3.378 2.996l2.255 1.997c1.127.999 2.252 2.013 3.376 3.012v10h-2v-9.118l-7.009-6.215-6.991 6.22v9.113zm2-2h10v2h-10v-2zm0-3h10v2h-10v-2zm10-3v2h-10v-2h10zm-5-14l12 10.632-1.328 1.493-10.672-9.481-10.672 9.481-1.328-1.493 12-10.632z"/></svg>
															</div>
														</th>
													<?}?>
													<th style="">
														<div id="cmavnum" class="CmSvgAvalNotHide" >
															<?if(count($aProd['PRICES']) > 0){?>
																<div class="<?if($prSort[0]=='Available'){?>CmColorFi<?}?> CmSortBlock CmDescSort CmAvailable" data-sort="desc" data-val="Available" data-url="<?=$aRes['DETAIL_PAGE_URL']?>"><?if($aPageSVG['CmSort']){echo $aPageSVG['CmSort'];}else{echo $aListSVG['CmSort'];}?></div>
															<?}?>
															<svg class="<?if($prSort[0]=='Available'){?>CmColorFi<?}?> CmAvalOnPage fillBg" viewBox="0 0 24 24"><path d="M16.677 17.868l-.343.195v-1.717l.343-.195v1.717zm2.823-3.325l-.342.195v1.717l.342-.195v-1.717zm3.5-7.602v11.507l-9.75 5.552-12.25-6.978v-11.507l9.767-5.515 12.233 6.941zm-13.846-3.733l9.022 5.178 1.7-.917-9.113-5.17-1.609.909zm2.846 9.68l-9-5.218v8.19l9 5.126v-8.098zm3.021-2.809l-8.819-5.217-2.044 1.167 8.86 5.138 2.003-1.088zm5.979-.943l-2 1.078v2.786l-3 1.688v-2.856l-2 1.078v8.362l7-3.985v-8.151zm-4.907 7.348l-.349.199v1.713l.349-.195v-1.717zm1.405-.8l-.344.196v1.717l.344-.196v-1.717zm.574-.327l-.343.195v1.717l.343-.195v-1.717zm.584-.333l-.35.199v1.717l.35-.199v-1.717z"/></svg>
														</div>
													</th>
													<?if(count($firstPr['OPTIONS_VIEW']) > 0 || count($prOpt) > 0){?>
														<th class="CmOptionsTitl"></th>
													<?}?>
													<th>
														<div id="cmprnum" class="CmPriceTextTitle">
															<?if(count($aProd['PRICES']) > 0){?>
																<div class="<?if($prSort[0]=='Price'){?>CmColorFi<?}?> CmSortBlock CmDescSort CmPrice" data-sort="desc" data-val="Price" data-url="<?=$aRes['DETAIL_PAGE_URL']?>"><?if($aPageSVG['CmSort']){echo $aPageSVG['CmSort'];}else{echo $aListSVG['CmSort'];}?></div>
															<?}?>
															<div class="CmPrTitleTxt <?if($prSort[0]=='Price' && count($aProd['PRICES']) > 0){?>CmColorTx<?}?>">
																<?=Lng_x('Price')?>&nbsp;
																<span class="CmCurrPrice"><?=$firstPr['PRICE_CURRENCY']?></span>
															</div>
														</div>
													</th>
												</tr>
											</thead>
											<tbody class="CmTbodyPrice ">
												<?if(count($aProd['PRICES']) > 0){
													$tr = 0;
													foreach($aProd['PRICES'] as $k => $aPrice){?>
														<tr class="CmTablePriceValueRow <?if($aPrice['DELIVERY_NUM'] == 0){?>CmColorBgL<?}?>"  data-cmdelnum="<?=$aPrice['DELIVERY_NUM']?>" data-cmavnum="<?=$aPrice['AVAILABLE_NUM']?>" data-cmprnum="<?=strip_tags($aPrice['PRICE_VALUE'])?>">
															<?if(!HIDE_PRODUCTS_COUNT){?>
																<?if($aPrice['DELIVERY_NUM'] == 0){?>
																	<td>
																		<div class="CmInStockText CmTitShow CmColorFi" title="<?=Lng_x('In_stock')?>">
																			<svg width="16" height="16" viewBox="0 0 44 44" enable-background="new 0 0 44 44">
																				<path d="m22,0c-12.2,0-22,9.8-22,22s9.8,22 22,22 22-9.8 22-22-9.8-22-22-22zm12.7,15.1l0,0-16,16.6c-0.2,0.2-0.4,0.3-0.7,0.3-0.3,0-0.6-0.1-0.7-0.3l-7.8-8.4-.2-.2c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.4-0.4 1-0.4 1.4,0l.1,.1 5.5,5.9c0.2,0.2 0.5,0.2 0.7,0l13.4-13.9h0.1c0.4-0.4 1-0.4 1.4,0l1.4,1.4c0.4,0.3 0.4,0.9 0,1.3z"/>
																			</svg>
																		</div>
																	</td>
																<?}else{?>
																	<td>
																		<div class="CmListPrDelivery <?if($aPrice['DELIVERY_NUM'] == 0){?>CmInStockDelivery<?}else{?>CmTimeDelivery<?}?>" title="<?=Lng_x('Dtime_delivery');?>" data-suplstock="<?=$aPrice['SUPPLIER_STOCK']?>">
																			<?=$aPrice['DELIVERY_VIEW']?>
																		</div>
																	</td>
																<?}?>
															<?}?>
															<?if($noSuplStock){?>
																<td>
																	<div class="CmDelStockBlNotHide" <?if($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != ''){?>style="flex-direction: column;"<?}?>>
																		<?if($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != ''){?>
																			<div class="CmStockName CmTitShow" title="<?=Lng_x('Supplier');?>:<br><?=$aPrice['SUPPLIER_NAME']?>">
																				<div class="CmListPrStock CmColorTxh"><?=$aPrice['SUPPLIER_NAME']?></div>
																			</div>
																		<?}?>
																		<?if(($aRes['SHOW_STOCK'] && $aPrice['SUPPLIER_STOCK'] != '') && ($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != '')){?><span><?if($aSets['NOT_HIDE_PRICES'] != 1){?>&nbsp;&frasl;&nbsp;<?}?></span><?}?>
																		<?if($aRes['SHOW_STOCK'] && $aPrice['SUPPLIER_STOCK'] != ''){?>
																			<div class="CmStockNum CmTitShow" title="<?=Lng_x('Stock');?>:<br><?=$aPrice['SUPPLIER_STOCK']?>">
																				<div class="CmListPrStock"><?=$aPrice['SUPPLIER_STOCK']?></div>
																			</div>
																		<?}?>
																	</div>
																</td>
															<?}?>
															<td>
																<div class="cm_AvalNotHide CmTitShow" title="<?if($aPrice['AVAILABLE_NUM']==0){echo Lng_x('Not_available');}else{echo Lng_x('Availability');}?>">
																	<?PrintProductAvailable_x($aPrice)?>
																</div>
															</td>
															<?if(count($firstPr['OPTIONS_VIEW']) > 0 || count($prOpt)> 0){?>
																<td>
																	<div class="CmOptionsBlockInfo">
																		<?foreach($aPrice['OPTIONS_VIEW'] as $aOpt){?>
																			<div class="CmOptionView"><?=$aOpt['Value']?></div>
																		<?}?>
																	</div>
																</td>
															<?}?>
															<td>
																<div class="CmPriceChangeQuant">
																	<div class="CmWrapPriceDiscPage">
																		<?if($aPrice['OLD_PRICE']){?>
																			<div class="CmDiscPrNotHide">
																				<?if($aPrice['PRICE_INCLUDE']){?>
																					<div class="CmOldPrice"><i><span class="CmOldPrNotHide" style="font-size:10px;"><?=Lng_x('Including',1)?></span>&nbsp;<span class="CmVatTxt CmColorTx"><?=Lng_x($aPrice['PRICE_RULE'],1)?></span></i>:&nbsp;</div>
																					<div class="CmPerDiscNotHide CmVatIncl"><?=$aPrice['PRICE_INCLUDE']?></div>
																				<?}else{?>
																					<div class="CmOldPrice"><i><span class="CmOldPrNotHide" style="font-size:10px;"><?=$aPrice['OLD_PRICE']?></span></i>&ensp;</div>
																					<div class="CmPerDiscNotHide CmMinusPercNotHide"><?=$aPrice['DISCOUNT_VIEW']?></div>
																				<?}?>
																			</div>
																		<?}?>
																	</div>
																	<div class="CmPriceQuantWrap">
																		<div class="CmPriceNum" style="color:#<?if($aPrice['AVAILABLE_NUM'] == 0 && $aRes['ALLOW_NOTAVAIL'] == false){echo '808080';}?>;">
																			<span><nobr><?=$aPrice['PRICE_VALUE'];?></nobr></span>
																		</div>
																		<?if($aPrice['AVAILABLE_NUM'] > 0 || $aRes['ALLOW_NOTAVAIL']){?>
																			<div class="CmQuantBlToCartBl">
																				<?if($aPrice['AVAILABLE_NUM'] == 0 && $aRes['ALLOW_NOTAVAIL']){
																					$aPrice['AVAILABLE_NUM'] = 99;
																				}?>
																				<?// "ADD TO CART" Class is: CmAddToCart / CmAddToCartQty (../includes.php)?>
																				<?if($aSets['ONECLICK_EMAIL_ORDER']){?>
																					<div class="CmMailOrder toCartButt CmColorBg" data-tab="AskPrice" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>"  data-link="<?=$ProductURL?>">
																						<span>
																							<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																							<span class="cartText"><?=Lng_x('Order')?></span>
																						</span>
																					</div>
																				<?}else{?>
																					<div class="CmQuantPriceBlock">
																						<div class="CmQuantMinusBut CmColorTxh cm_countButM">-</div>
																						<input name="re_count" type="text" class="CmAddToCartQty CmQuantInputProd cm_countRes" value="<?if($aPrice['OPTIONS_VIEW']['Minimal_qnt']){ echo $aPrice['OPTIONS_VIEW']['Minimal_qnt']['Text'];}else{?>1<?}?>" data-maxaval="<?=$aPrice['AVAILABLE_NUM']?>" data-minimalqnt='<?=$aPrice['OPTIONS_VIEW']['Minimal_qnt']['Text']?>'>
																						<div class="CmQuantPlusBut CmColorTxh cm_countButP">+</div>
																					</div>
																					<?if($aRes['FINDPRICE_DISPLAY_PRICES']){?>
																						<a href="<?=$ProductURL?>" class="CmFPrNotHideLink CmColorBg" <?=$aRes['FindPrice_isBlank']?>>
																							<span>
																								<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																							</span>
																						</a>
																					<?}else{?>
																						<div class="CmAddToCart CmButtonToCart CmColorBg" data-furl="<?=$ProductURL?>" data-priceid="<?=$aPrice['PriceID']?>">
																							<svg class="cm_HideCartImg" viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																						</div>
																					<?}?>
																				<?}?>
																			</div>
																		<?}?>
																	</div>
																</div>
															</td>
														</tr>
														<tr class="CmTablePriceValueRow_2"  data-cmdelnum="<?=$aPrice['DELIVERY_NUM']?>" data-cmavnum="<?=$aPrice['AVAILABLE_NUM']?>" data-cmprnum="<?=strip_tags($aPrice['PRICE_VALUE'])?>">
															<?if(!HIDE_PRODUCTS_COUNT){?>
																<?if($aPrice['DELIVERY_NUM'] == 0){?>
																	<td>
																		<div class="CmInStockText CmTitShow CmColorFi" title="<?=Lng_x('In_stock')?>">
																			<svg width="16" height="16" viewBox="0 0 44 44" enable-background="new 0 0 44 44">
																			  <path d="m22,0c-12.2,0-22,9.8-22,22s9.8,22 22,22 22-9.8 22-22-9.8-22-22-22zm12.7,15.1l0,0-16,16.6c-0.2,0.2-0.4,0.3-0.7,0.3-0.3,0-0.6-0.1-0.7-0.3l-7.8-8.4-.2-.2c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.4-0.4 1-0.4 1.4,0l.1,.1 5.5,5.9c0.2,0.2 0.5,0.2 0.7,0l13.4-13.9h0.1c0.4-0.4 1-0.4 1.4,0l1.4,1.4c0.4,0.3 0.4,0.9 0,1.3z"/>
																			</svg>
																		</div>
																	</td>
																<?}else{?>
																	<td>
																		<div class="CmListPrDelivery <?if($aPrice['DELIVERY_NUM'] == 0){?>CmInStockDelivery<?}else{?>CmTimeDelivery<?}?>" title="<?=Lng_x('Dtime_delivery')?>" data-suplstock="<?=$aPrice['SUPPLIER_STOCK']?>">
																		<?=$aPrice['DELIVERY_VIEW']?></div>
																	</td>
																<?}?>
															<?}?>
															<?if($noSuplStock){?>
																<td>
																	<div class="CmDelStockBlNotHide" <?if($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != ''){?>style="flex-direction: column;"<?}?>>
																		<?if($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != ''){?>
																			<div class="CmStockName CmTitShow" title="<?=Lng_x('Supplier')?>:<br><?=$aPrice['SUPPLIER_NAME']?>">
																				<div class="CmListPrStock CmColorTxh"><?=$aPrice['SUPPLIER_NAME']?></div>
																			</div>
																		<?}?>
																		<?if(($aRes['SHOW_STOCK'] && $aPrice['SUPPLIER_STOCK'] != '') && ($aRes['SHOW_SUPPLIER'] && $aPrice['SUPPLIER_NAME'] != '')){?><span><?if($aSets['NOT_HIDE_PRICES'] != 1){?>&nbsp;&frasl;&nbsp;<?}?></span><?}?>
																		<?if($aRes['SHOW_STOCK'] && $aPrice['SUPPLIER_STOCK'] != ''){?>
																			<div class="CmStockNum CmTitShow" title="<?=Lng_x('Stock')?>:<br><?=$aPrice['SUPPLIER_STOCK']?>">
																				<div class="CmListPrStock"><?=$aPrice['SUPPLIER_STOCK']?></div>
																			</div>
																		<?}?>
																	</div>
																</td>
															<?}?>
															<td>
																<div class="cm_AvalNotHide CmTitShow" title="<?if($aPrice['AVAILABLE_NUM']==0){echo Lng_x('Not_available');}else{echo Lng_x('Availability');}?>">
																	<?PrintProductAvailable_x($aPrice)?>
																</div>
															</td>
															<?if(count($firstPr['OPTIONS_VIEW']) > 0 || count($prOpt)> 0){?>
																<td>
																	<div class="CmOptionsBlockInfo">
																		<?foreach($aPrice['OPTIONS_VIEW'] as $aOpt){?>
																			<div class="CmOptionView"><?=$aOpt['Value']?></div>
																		<?}?>
																	</div>
																</td>
															<?}?>
															<td>
																<div class="CmPriceChangeQuant">
																	<div class="CmWrapPriceDiscPage">
																		<?if($aPrice['OLD_PRICE']){?>
																			<div class="CmDiscPrNotHide">
																				<?if($aPrice['PRICE_INCLUDE']){?>
																					<div class="CmOldPrice"><i><span class="CmOldPrNotHide" style="font-size:10px;"><?=Lng_x('Including',1)?></span>&nbsp;<span class="CmVatTxt CmColorTx"><?=Lng_x($aPrice['PRICE_RULE'],1)?></span></i>:&nbsp;</div>
																					<div class="CmPerDiscNotHide CmVatIncl"><?=$aPrice['PRICE_INCLUDE']?></div>
																				<?}else{?>
																					<div class="CmOldPrice"><i><span class="CmOldPrNotHide" style="font-size:10px;"><?=$aPrice['OLD_PRICE']?></span></i>&ensp;</div>
																					<div class="CmPerDiscNotHide CmMinusPercNotHide"><?=$aPrice['DISCOUNT_VIEW']?></div>
																				<?}?>
																			</div>
																		<?}?>
																	</div>
																	<div class="CmPriceQuantWrap">
																		<div class="CmPriceNum" style="color:#<?if($aPrice['AVAILABLE_NUM'] == 0 && $aRes['ALLOW_NOTAVAIL'] == false){echo '808080';}?>;">
																			<span><nobr><?=$aPrice['PRICE_VALUE'];?></nobr></span>
																		</div>
																		<?if($aPrice['AVAILABLE_NUM'] > 0 || $aRes['ALLOW_NOTAVAIL']){?>
																			<div class="CmQuantBlToCartBl">
																				<?if($aPrice['AVAILABLE_NUM'] == 0 && $aRes['ALLOW_NOTAVAIL']){
																					$aPrice['AVAILABLE_NUM'] = 99;
																				}?>
																				<?// "ADD TO CART" Class is: CmAddToCart / CmAddToCartQty (../includes.php)?>
																				<?if($aSets['ONECLICK_EMAIL_ORDER']){?>
																					<div class="CmMailOrder toCartButt CmColorBg" data-tab="AskPrice" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>"  data-link="<?=$ProductURL?>">
																						<span>
																							<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																							<span class="cartText"><?=Lng_x('Order')?></span>
																						</span>
																					</div>
																				<?}else{?>
																					<div class="CmQuantPriceBlock">
																						<div class="CmQuantMinusBut CmColorTxh cm_countButM">-</div>
																						<input name="re_count" type="text" class="CmAddToCartQty CmQuantInputProd cm_countRes" value="<?if($aPrice['OPTIONS_VIEW']['Minimal_qnt']){ echo $aPrice['OPTIONS_VIEW']['Minimal_qnt']['Text'];}else{?>1<?}?>" data-maxaval="<?=$aPrice['AVAILABLE_NUM']?>" data-minimalqnt='<?=$aPrice['OPTIONS_VIEW']['Minimal_qnt']['Text']?>'>
																						<div class="CmQuantPlusBut CmColorTxh cm_countButP">+</div>
																					</div>
																					<?if($aRes['FINDPRICE_DISPLAY_PRICES']){?>
																						<a href="<?=$ProductURL?>" class="CmFPrNotHideLink CmColorBg" <?=$aRes['FindPrice_isBlank']?>>
																							<span>
																								<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																							</span>
																						</a>
																					<?}else{?>
																						<div class="CmAddToCart CmButtonToCart CmColorBg" data-furl="<?=$ProductURL?>" data-priceid="<?=$aPrice['PriceID']?>">
																							<svg class="cm_HideCartImg" viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
																						</div>
																					<?}?>
																				<?}?>
																			</div>
																		<?}?>
																	</div>
																</div>
															</td>
														</tr>
													<?}?>
												<?}?>
											</tbody>
										</table>
										<div class="CmMorePriceBlClose">
											<svg class="CmCloseMorePr CmColorFi" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>
										</div>
									</div>
								</div>
							<?}?>
						<?}?>
					</div>
				<?}else{?>
					<div class="WsDataTb_x" <?if($aProd['WsAjax']){?>data-dir="<?=CM_DIR?>" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>"<?}?>>
						<div></div>
						<div class="CmAvalAskPrBlock">
							<?if($aRes['FINDPRICE_BUTTON']){?>
								<a href="<?=$aProd['FindPriceLink']?>" class="CmPriceAskBut CmColorBg CmColorBr" <?=$aRes['FindPrice_isBlank']?> >
									<svg class="CmAskImg CmColorFi" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1.25 17c0 .69-.559 1.25-1.25 1.25-.689 0-1.25-.56-1.25-1.25s.561-1.25 1.25-1.25c.691 0 1.25.56 1.25 1.25zm1.393-9.998c-.608-.616-1.515-.955-2.551-.955-2.18 0-3.59 1.55-3.59 3.95h2.011c0-1.486.829-2.013 1.538-2.013.634 0 1.307.421 1.364 1.226.062.847-.39 1.277-.962 1.821-1.412 1.343-1.438 1.993-1.432 3.468h2.005c-.013-.664.03-1.203.935-2.178.677-.73 1.519-1.638 1.536-3.022.011-.924-.284-1.719-.854-2.297z"/></svg>
									<span class="CmColorTx"><?=Lng_x('Get_a_price',0)?></span>
								</a>
							<?}else{?>
								<?if($aRes['ASK_PRICE']){?>
									<div class="ListAskPrice_t CmAskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>">
										<svg class="cm_askImg_t" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1.25 17c0 .69-.559 1.25-1.25 1.25-.689 0-1.25-.56-1.25-1.25s.561-1.25 1.25-1.25c.691 0 1.25.56 1.25 1.25zm1.393-9.998c-.608-.616-1.515-.955-2.551-.955-2.18 0-3.59 1.55-3.59 3.95h2.011c0-1.486.829-2.013 1.538-2.013.634 0 1.307.421 1.364 1.226.062.847-.39 1.277-.962 1.821-1.412 1.343-1.438 1.993-1.432 3.468h2.005c-.013-.664.03-1.203.935-2.178.677-.73 1.519-1.638 1.536-3.022.011-.924-.284-1.719-.854-2.297z"/></svg>
										<span><?=Lng_x('Ask_price')?></span>
									</div>
								<?}
								if($aRes['ALLOW_ORDER']){?>
									<div class="CmAddToCart ListNotAvailable_t" data-furl="<?=$aProd['Link']?>" data-priceid="order">
										<svg class="cm_cartImg_t" viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
										<span><?=Lng_x('Order')?></span>
									</div>
								<?}?>
								<?if(!$aRes['ASK_PRICE'] && !$aRes['ALLOW_ORDER']){?>
									<div class="CmNoInStock_t">
										<svg class="CmNotInStockImg_t" viewBox="0 0 32 32">
											<path d="M23 1l-7 6 9 6 7-6z"></path>
											<path d="M16 7l-7-6-9 6 7 6z"></path>
											<path d="M25 13l7 6-9 5-7-6z"></path>
											<path d="M16 18l-9-5-7 6 9 5z"></path>
											<path d="M22.755 26.424l-6.755-5.79-6.755 5.79-4.245-2.358v2.934l11 5 11-5v-2.934z"></path>
										</svg>
										<span><?=Lng_x('No_in_stock',1)?></span>
									</div>
								<?}?>
							<?}?>
						</div>
					</div>
				<?}?>
				
			
		</div>
		<?if(count($aProd['PRICES']) > 1 && $aSets['NOT_HIDE_PRICES']==0){
			foreach ($aProd['PRICES'] as $aPrice){?>
				<div class="CmHidePriceRows <?=$PKEY?>">
					<div></div>
					<div class="CmHideAvDelStWrap">
						<div class="CmAvailNumHideBl <?if(HIDE_PRODUCTS_COUNT){?>CmBordRightN<?}?> CmColorTx">
							<span><?PrintProductAvailable_x($aPrice, $aRes)?></span>
						</div>
						<?if(!HIDE_PRODUCTS_COUNT){?>
							<div>
								<?if($aPrice['DELIVERY_NUM']===0){?>
									<div class="CmInStockText">
										<?=Lng_x('In_stock')?>
									</div>
								<?}else{?>
									<span class="CmTablePrDelivery_H"><?=$aPrice['DELIVERY_VIEW']?></span>
								<?}?>
							</div>
						<?}?>
						<div class="CmHideStockNameBl">
							<?if($aRes['SHOW_STOCK']&&$aProd['FirstPrice']['SUPPLIER_STOCK']!=''){?>
								<span><?=$aPrice['SUPPLIER_STOCK']?></span>
								<span class="CmSlash">/</span>
							<?}?>
							<?if($aRes['SHOW_SUPPLIER']&&$aProd['FirstPrice']['SUPPLIER_NAME']!=''){?>
								<span><?=$aPrice['SUPPLIER_NAME']?></span>
							<?}?>
						</div>
					</div>
					<div class="CmHidePriceQuantBlWrap">
						<div class="CmDiscVatBlock">
							<?if($aPrice['OLD_PRICE']){?>
								<div class="CmTableDiscPrice">
									<?if($aPrice['PRICE_INCLUDE']){?>
										<div class="CmOldPrice"><i><span class="CmColorTx"><?=Lng_x('Including',1)?></span>&nbsp;<span class="CmVatTxt CmColorTx"><?=Lng_x($aPrice['PRICE_RULE'],1)?></span></i>:&nbsp;</div>
										<div class="CmPercentDisc CmVatIncl"><?=$aPrice['PRICE_INCLUDE']?></div>
									<?}else{?>
										<div class="CmOldPrice"><i><span class="CmOldPr CmColorTx"><?=$aProd['FirstPrice']['OLD_PRICE']?></span></i>&ensp;</div>
										<div class="CmPercentDisc CmMinusPerc"><?=$aPrice['DISCOUNT_VIEW']?></div>
									<?}?>
								</div>
							<?}?>
							<div class="CmTablePrCost_H" style="color:#<?if($aPrice['AVAILABLE_NUM']==0 && $aRes['ALLOW_NOTAVAIL']==false){echo '808080';}?>"><?=$aPrice['PRICE_FORMATED']?></div>
						</div>
						<!--"ADD TO CART" Class is: CmAddToCart / CmAddToCartQty (../includes.php)-->
						<?if($aPrice['AVAILABLE_NUM']>0 || $aRes['ALLOW_NOTAVAIL']){
							if($aPrice['AVAILABLE_NUM']==0 && $aRes['ALLOW_NOTAVAIL']){
								$aPrice['AVAILABLE_NUM'] = 99;
							}?>
							<?if($aSets['ONECLICK_EMAIL_ORDER']){?>
								<div class="CmMailOrder toCartButt CmColorBg" data-tab="AskPrice" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>"  data-link="<?=$ProductURL?>">
									<span>
										<svg class="CmCartImgPp" viewBox="0 2 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
										<span class="cartText"><?=Lng_x('Order')?></span>
									</span>
								</div>
							<?}else{?>
								<div class="CmQuantBlockTable">
									<div class="cm_qty_tH" style="<?if($aSets['NOT_HIDE_PRICES']==1){?>/*display:flex;*/<?}?>">
										<div class="quantMinus_t CmColorTxh cm_countButM">-</div>
										<input name="re_count" type="text" class="CmAddToCartQty quantProd_t cm_countRes" value="1" data-maxaval="<?=$aPrice['AVAILABLE_NUM']?>">
										<div class="quantPlus_t CmColorTxh cm_countButP">+</div>
									</div>
									<div class="CmAddToCart CmTablePrToCart_H CmColorBg" data-furl="<?=$aProd['Link']?>" data-priceid="<?=$aPrice['PriceID']?>">
										<svg class="cm_HideCartImg" viewBox="-1 -3 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>
									</div>
								</div>
							<?}?>
						<?}else{?>
							<div class="cm_NotAvailable_tH">
								<svg class="cm_NotAvImg" viewBox="0 -2 24 24"><path d="M13.5 18c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-3.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm13.257-14.5h-1.929l-3.473 12h-13.239l-4.616-11h2.169l3.776 9h10.428l3.432-12h4.195l-.743 2zm-12.257 1.475l2.475-2.475 1.414 1.414-2.475 2.475 2.475 2.475-1.414 1.414-2.475-2.475-2.475 2.475-1.414-1.414 2.475-2.475-2.475-2.475 1.414-1.414 2.475 2.475z"/></svg>
								<span><?=Lng_x('Not_available')?></span>
							</div>
						<?}?>
					</div>
				</div>
			<?}
		}
		if(count($aProd['PRICES']) > 1 && $aSets['NOT_HIDE_PRICES']==0){?>
			<div class="CmShowMorePrBut cm_ShowMorePr_t CmColorTx" data-countpr="<?=count($aProd['PRICES'])?>" data-pkey="<?=$PKEY?>" data-hide="<?=Lng_x('Hide')?>" data-show="<?=Lng_x('More')?> (<?=count($aProd['PRICES'])?>)">
				<div></div>
				<div></div>
				<span class="cm_mP"><?=Lng_x('More')?> (<?=count($aProd['PRICES'])?>)</span>
			</div>
		<?}?>
	<?}?>
</div>
<style>
    /*.CmThSvg{<?if(count($aPrC)==0){?>display:none;<?}?>}*/
</style>
<?//aprint_x($aRes, 'aRes');
