<?
// Dropdown VIN list
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// $ModURL - the prefix part of car URL (may contain language part like /en/ or symbolic name of CarMod folder e.t.c..)
// $CarsCount - total number of cars in the list
// $aTypeNames - an array with cars Names for Grouping
// $aRes - main result array of car Models -> Types

?><table class="VinNumTab CmColorBr"><?
if($CarsCount>99){
	$Grouped='VinNumHidden';
	?><tr><?
	foreach($aTypeNames as $TypeName){
		?><td class="VinNumLit"><?=$TypeName?></td><?
	}?>
	<td id="VinNumClose"></td>
	</tr><?
}
foreach($aRes as $ModelID=>$aModel){?>
	<tr class="VinNumModel CmColorBgL ModId<?=$ModelID?>">
		<td colspan="9">
			<?if(!$Grouped AND !$Close){?><div id="VinNumClose" class="VinNumCloseM CmColorTx"></div> <?$Close=true;}?>
			<?=$aModel['Name']?>
		</td>
	</tr>
	<?foreach($aModel['Types'] as $TypeID=>$aType){?>
		<tr class="<?=$Grouped?> <?=$aType['WasSelected']?>" data-lit="<?=$aType['Lit']?>" data-modid="<?=$ModelID?>">
			<td class="VinNumType" colspan="99">
				<a class="VinNumSelector CmColorTx" data-typid="<?=$TypeID?>" href="<?=$ModURL.$aType['Link']?>"><?=$aType['Name']?></a>
			</td>
		</tr><?
	}
}
?></table>