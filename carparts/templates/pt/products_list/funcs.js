'use strict';

$(document).ready(function () {

    // Filter Sections

    // Open subsections on load if section is active
    /* const sectElements = document.querySelectorAll('.CmSubLevNameWrap');
    for (let i = 0; i < sectElements.length; i++) {
        const pmBtn = sectElements[i].querySelector('.CmPlusMinusBlock');
        if (sectElements[i].querySelector('.CmSecondLevelLink').dataset.exp == 'Expanded') {
            if(pmBtn){
                if(pmBtn.classList.contains('CmPlusImg')){
                    pmBtn.classList.add('CmShowHide');
                    sectElements[i].querySelector('.CmMinusImg').classList.remove('CmShowHide');
                }else if(pmBtn.classList.contains('CmMinusImg')){
                    pmBtn.classList.add('CmShowHide');
                    sectElements[i].querySelector('.CmPlusImg').classList.remove('CmShowHide');
                }
                sectElements[i].nextElementSibling.classList.toggle('CmShowHide');
            }
        }
    } */

    // Open subsections on click event
	jQuery(document).on('click','.CmLickToOpen',function(){
		jQuery(this).parent().children('.CmThirdLevelList').slideToggle(300);
		jQuery(this).children('.ShowMinus').toggle();
		jQuery(this).children('.ShowPlus').toggle();
    });
    /* const plusBtn = document.querySelectorAll('.CmLickToOpen');
    for (let i = 0; i < plusBtn.length; i++) {
        const secLev = plusBtn[i].parentNode.querySelector('.CmSecondLevelLink');
        plusBtn[i].addEventListener('click', () => {
            if(plusBtn[i].classList.contains('CmPlusImg')){
                plusBtn[i].classList.add('CmShowHide');
                plusBtn[i].parentNode.querySelector('.CmMinusImg').classList.remove('CmShowHide');
            }else if(plusBtn[i].classList.contains('CmMinusImg')){
                plusBtn[i].classList.add('CmShowHide');
                plusBtn[i].parentNode.querySelector('.CmPlusImg').classList.remove('CmShowHide');
            }
            plusBtn[i].parentNode.nextElementSibling.classList.toggle('CmShowHide');
        });
    } */

    // MOBILE VIEW SHOW & HIDE FILTER
    jQuery("#CmAjaxBox").on('click','.CmFilterShowButton',function(){
        var bodyHeig = jQuery('html').height();
        jQuery('html').append('<div class="CmBlockOverPage"></div>');
        jQuery('.CmBlockOverPage').show().height(bodyHeig);
        jQuery('.left_fil').css({right: '315px', transition:"right 0.3s"});
    });
    jQuery(document).mousedown(function(e) {
        e.stopPropagation();
        if(jQuery(".left_fil").has(e.target).length===0||jQuery(".CmHideFiltersBlock").has(e.target).length>0){
            jQuery('.left_fil').css({right: '0px', transition:"right 0.3s"});
            jQuery('.CmBlockOverPage').hide();
            // jQuery('body').css({overflow:'auto', marginRight:'0px'});
            jQuery('.CmBlockOverPage').remove();
        }
    });

    // More Block
    jQuery("#CmAjaxBox").on("mouseenter",'.cm_moreImg', function(){
        jQuery('.cm_moreText').addClass('c_Tx');
    });
    jQuery("#CmAjaxBox").on("mouseleave",'.cm_moreImg', function(){
        jQuery('.cm_moreText').removeClass('c_Tx');
    });
    jQuery("#CmAjaxBox").on("mouseenter",'.cm_moreImg', function(){
        jQuery('.cm_imgBlock').addClass('cm_RedoRotate');
    });
    jQuery("#CmAjaxBox").on("mouseleave",'.cm_moreImg', function(){
        jQuery('.cm_imgBlock').removeClass('cm_RedoRotate');
    });

    //More Prices
    jQuery("#CmAjaxBox").on("click",'.cm_ShowMorePr', function(){
        jQuery(this).find('.cm_HidePricetb').css('display','flex');
    });
    jQuery("#CmAjaxBox").on("click",'.cm_ShowMorePr_c', function(){
        jQuery(this).siblings('.cm_HidePrice_c').show();
        jQuery('.cm_HidePrice_c').css('margin', '-25px -2px 0px 0px');
    });
    jQuery("#CmAjaxBox").on("mouseleave",'.cm_HidePricetb, .cm_HidePrice_c', function(){
     jQuery('.cm_HidePricetb, .cm_HidePrice_c').css('display','none');
    });
    jQuery('.CmCloseTable').click(function(e){
        e.stopPropagation();
        jQuery('.cm_HidePricetb, .cm_HidePrice_c').css('display','none');
    });

    // SHOW & HIDE MORE PRODUCT INFO
    const windWidth = jQuery(window).width();
    if(windWidth>=648){
        let rowBl = '';
        jQuery(".CmPropsWrapBl").hover(function(e){
            rowBl =  jQuery(this).parent().parent().parent().parent().parent().parent();
            let rowBlH =  rowBl.height();
            rowBl.css('height', `${rowBlH}px`);
            e.stopPropagation();
            if(jQuery(this).data('props')=='Y'){
                jQuery(this).find('.CmHideOverBl').hide();
                jQuery(this).find('.CmListProps_2').stop(true, true).slideDown(100).css('display', 'grid');
                jQuery(this).addClass('CmNPBlockBorder');
            }
        },function(e){
            e.stopPropagation();
            jQuery(this).find('.CmHideOverBl').show();
            jQuery(this).find('.CmListProps_2').stop(true, true).slideUp(100);
            jQuery(this).removeClass('CmNPBlockBorder');
            setTimeout(() => rowBl.css('height','auto'), 100);
        });
    }

//    SHOW HIDE PRICES TABLE VIEW
    jQuery("#CmAjaxBox").on("click",'.cm_ShowMorePr_t', function(){
        var prcount = jQuery(this).data('countpr');
        var pkey = jQuery(this).data('pkey');
        var hid = jQuery(this).data('hide');
        jQuery(this).parent('.CmMorePriceTr').siblings('.'+pkey).show(300);
        jQuery(this).parent('.CmMorePriceTr').siblings('.CmAdmButsProduct, .CmProdTabRow').find('td.'+pkey).attr('rowspan', prcount+1);
        jQuery(this).find('.cm_mP').text(hid);
        jQuery(this).addClass('CmHidePrTr');
    });
    jQuery("#CmAjaxBox").on("click",'.CmHidePrTr', function(){
        var pkey = jQuery(this).data('pkey');
        var hid = jQuery(this).data('show');
        jQuery(this).parent('.CmMorePriceTr').siblings('.'+pkey).hide();
        jQuery(this).parent('.CmMorePriceTr').siblings('.CmAdmButsProduct, .CmProdTabRow').find('td.'+pkey).attr('rowspan', '');
        jQuery(this).find('.cm_mP').text(hid);
        jQuery(this).removeClass('CmHidePrTr');
    });

    jQuery("#CmAjaxBox").on("click",'.CmShowMorePrBut', function(){
        var prcount = jQuery(this).data('countpr');
        var pkey = jQuery(this).data('pkey');
        var hid = jQuery(this).data('hide');
        jQuery(this).siblings('.'+pkey).show().css('display','grid');
        jQuery(this).find('.cm_mP').text(hid);
        jQuery(this).addClass('CmHidePrTr');
    });
    jQuery("#CmAjaxBox").on("click",'.CmHidePrTr', function(){
        var pkey = jQuery(this).data('pkey');
        var hid = jQuery(this).data('show');
        jQuery(this).siblings('.'+pkey).hide().css('display','none');
        jQuery(this).find('.cm_mP').text(hid);
        jQuery(this).removeClass('CmHidePrTr');
    });

    // More brands
    jQuery("#CmAjaxBox").on("click",'.CMShowMoreBr', function(){
        jQuery('.l_filterBr').removeClass('CmLeftFilHeight');
        jQuery(this).hide();
    });

    // Remove color border right
    if(jQuery('.cm_Delivtd').data('suplstock')==''){
        jQuery('.CmListPrDelivery').css('borderRight','unset');
    }

    // Image to Popup window
    jQuery("#CmAjaxBox").on("click", '.ProductImg', function (e){
        e.preventDefault();
        var ChemaCoords = jQuery(this).html();
        var picType = jQuery(this).find('.CmProdIm').data('pictype');
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="fxClose"></div>'+ChemaCoords);
        // let windWidth = jQuery('#CmContent').width();
        let windHeight = document.body.clientHeight;
//        console.log(picType+'/'+windHeight);
        if(picType == 'schema'){
            jQuery('.fxCont').height(windHeight-80).css('width', 'auto');
        }

    });

    jQuery("#CmAjaxBox").on("click", '.CmPopUpImg', function (e){
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="fxClose"></div><img class="cmImgTablOv" src="'+jQuery(this).data('imgsrc')+'">');
        var img = jQuery('.fxCont').find('.cmImgTablOv');
        var windWidth = jQuery(window).width();
        if(img.height() > 800 && windWidth < 1600){
            jQuery('.fxCont').find('.cmImgTablOv').css('height','580px');
        }
         if(img.height() > 800 && windWidth >= 1600){
            jQuery('.fxCont').find('.cmImgTablOv').css('height','900px');
        }
    });

    jQuery("#CmAjaxBox").on("click",'.more_pr', function(){
        jQuery(this).parents('.desc_bl').find('.CmListProps_2').slideDown(200);
        jQuery(this).hide();
    });

    // SECTION NAME
    jQuery("#CmAjaxBox").on('click', '.cm_sectName', function (){
        jQuery('.cm_Namebl').show();
    });
    jQuery("#CmAjaxBox").on('mouseleave', '.cm_Namebl', function () {
        jQuery('.cm_Namebl').hide();
    });

    //LIST VIEW IMG
    if(screen.width <= 1024){
        jQuery('.img_bl').removeClass('img_blHov');
    }

    //FILTER BY BRANDS
    function ChangeTxtCol(){
        jQuery('.CmSearchNoResTxt').hide();
        jQuery('.filt_sect').addClass('CmColorBr CmColorTx').removeClass('CmBorderRed');
    }
    jQuery('#CmAjaxBox').on('keyup', '.filt_sect', function(){
        var bf = 0;
        if(jQuery('.filt_sect').val().length > 0){
            jQuery('.clearButt').show();
        }else if(jQuery('.filt_sect').val().length == 0){
            jQuery('.clearButt').hide();
            jQuery('.CmBrandFilter').show();
             ChangeTxtCol()
        }
        var val_inp = jQuery('.filt_sect').val();
        jQuery('.CmBrandFilter').each(function(){
            var CmBranName = jQuery(this).find('.CmBranName').text();
            if(RegExp('\^'+val_inp,'i').test(CmBranName)) {
                bf = 1;
            }
        });
        if(bf === 1){
            jQuery('.CmBrandFilter').each(function(){
                var CmBranName = jQuery(this).find('.CmBranName').text();
                if (RegExp('\^'+val_inp,'i').test(CmBranName)) {
                    jQuery(this).show();
                }else{
                    jQuery(this).hide();
                }
            });
             ChangeTxtCol()
        }else{
            jQuery('.CmSearchNoResTxt').show();
            jQuery('.filt_sect').removeClass('CmColorBr CmColorTx').addClass('CmBorderRed');
        }
    });
    jQuery("#CmAjaxBox").on('click', '.clearButt', function(){
        jQuery('.filt_sect').val('');
        jQuery(this).hide();
        jQuery('.CmBrandFilter').show();
         ChangeTxtCol()
    });
    //END FILTER BY BRANDS



    jQuery('body').on('click','.CmBrandHovBut',function(){
        jQuery('.CmBrandHovBut').each(function(){
           jQuery(this).removeClass('CmBrandActBut').find('span').removeClass('CmColorTx');
        });
        jQuery(this).addClass('CmBrandActBut').find('span').addClass('CmColorTx');
    });

    if(window.screen.width <= 480){
        jQuery('.CmBrArtTdCol').attr('colspan', 1);
    }

});
    //PRICE RANGE FUNCTION
    var priceFrom;
    var priceTo;
    function PriceRange(prFr,prTo){
        if(prFr&&prTo){
            var prF = prFr;
            var prT = prTo;
        }else{
            var prF = jQuery('#amount').data('pricefrom');
            var prT = jQuery('#amount').data('priceto');
        }
        // jQuery( "#slider-range" ).slider({
        //     range: true,
        //     min: jQuery('#amount').data('pricefrom'),
        //     max: jQuery('#amount').data('priceto'),
        //     values: [prF, prT],
        //     slide: function( event, ui ) {
        //         priceFrom = ui.values[0];
        //         priceTo = ui.values[1];
        //         jQuery("#amount").val(ui.values[0] + " - " + ui.values[1]);
        //         jQuery('.CmApplyBut, .CmResetBut').css('display','block').animate({
        //             opacity: 1
        //         }, 300);
        //     }
        // });
        // jQuery( "#amount" ).val(jQuery( "#slider-range" ).slider( "values", 0 ) +
        //     " - "+jQuery( "#slider-range" ).slider( "values", 1 ) );

    }
    // apply price range
    function getQueryVariable(queryString) { //string to associative array
        var vars = queryString.split("&");
        var arr = new Object();
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            arr[pair[0]] = pair[1];
        }
        return arr;
    }
    function eachArr(arr){ //associative array to array then to string
        var blkstr = [];
        var str;
        jQuery.each(arr,function(idx,val) {
            str = idx + "=" + val;
            blkstr.push(str);
        });
        return blkstr.join('&');
    }

    var locPath;
    jQuery('body').on('click','.CmApplyBut',function(){
        locPath = document.location.search;
        var locHref = document.location.href;
        if(locPath!='') {
            var sp = getQueryVariable(locPath);
            sp['PriceFrom'] = priceFrom;
            sp['PriceTo'] = priceTo;
            document.location.search = eachArr(sp);
        }else {
            locPath = ['?page=1','PriceFrom='+priceFrom, 'PriceTo='+priceTo];
            document.location.search = locPath.join('&');
        }
    });
    //reset filter button
    jQuery('body').on('click', '.CmResetBut', function(){
        locPath = document.location.search;
        var aSearch = getQueryVariable(locPath);
        delete aSearch.PriceFrom;
        delete aSearch.PriceTo;
        document.location.search = eachArr(aSearch);
    });
    jQuery(document).ready(function() {
        //PRICE RANGE
        locPath = document.location.search;
        var arrSear = getQueryVariable(locPath);
        var saveFr = Number(arrSear['PriceFrom']);
        var saveTo = Number(arrSear['PriceTo']);
        if(arrSear['PriceFrom']||arrSear['PriceTo']){
            jQuery('.CmApplyBut, .CmResetBut').css({display:'block', opacity:1});
        }
        PriceRange(saveFr, saveTo);
    });
