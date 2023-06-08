function calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {
    var ratio = [maxWidth / srcWidth, maxHeight / srcHeight];
    ratio = Math.min(ratio[0], ratio[1]);
    var width = srcWidth*ratio;
    var height = srcHeight*ratio;
    jQuery(".CmSchPicture").css({width: width, height: height});
}

var scrollFix = function(e){
    if(e.keyCode == 38 || e.keyCode == 40 || e.type == 'mousewheel'){
        return false;
    }
    jQuery(this).scrollTop(position);

};

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

jQuery(document).ready(function() {
    //Product Prices block (Webservices AJAX updated)
    var ProdListBlocks;
    var WsPpNum;
    function WebServiceListBlocks(){
        ProdListBlocks = [];
        WsPpNum=0;
        jQuery('body .rightBlock, body .CmListPrTab_c, body .WsDataTb_x').each(function(){
            if(typeof jQuery(this).data('artnum')!== 'undefined' && typeof jQuery(this).data('brand')!== 'undefined'){
                if(jQuery(this).data('artnum')!='' && jQuery(this).data('brand')!=''){
                    ProdListBlocks.push(this);
                }
            }
        });
        return ProdListBlocks;
    }
    WebServiceListBlocks();

    if(ProdListBlocks.length>0){
        WsNextProdPrices();
    }

    function WsNextProdPrices(){
        var ePrlb = ProdListBlocks[WsPpNum];
        if(ePrlb){
            jQuery(ePrlb).find('.CmWsLoadBar').show();
            var Dir = jQuery(ePrlb).data('dir');
            var ArtNum = jQuery(ePrlb).data('artnum');
            var Brand = jQuery(ePrlb).data('brand');
            // console.log(Dir+'/ '+ArtNum+'/ '+Brand);
//            var pData = 'CarModAjaxProductPrices=Y&SearchWS=Y&ArtNum='+ArtNum+'&Brand='+Brand+'&Sets=List';
//            ReqFetch('/'+Dir+'/', pData)
//                .then(result => {
//                    jQuery(ePrlb).html(result);
//                    WsPpNum++;
//                    WsNextProdPrices();
//                });
            jQuery.ajax({url:'/'+Dir+'/', type:'POST', dataType:'html', data:{CarModAjaxProductPrices:'Y', SearchWS:'Y', ArtNum:ArtNum, Brand:Brand, Sets:'Grid'}})
               .done(function(Result){
                    //Check for WS Errors for admin
                    var aResult = Result.split('|CmWsErrors|');
                    if(aResult.length>1){
                        jQuery('.fxCont').html(aResult[1]).css('text-align','left');
                        jQuery('.fxOverlay').css('display', 'flex');
                    }else{
                        //Update Prices block
                        jQuery(ePrlb).html(Result);
                        WsPpNum++;
                        WsNextProdPrices(); //Next search (if block exists)
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    jQuery(ePrlb).html(jqXHR.responseText+' ['+textStatus+'] '+errorThrown);
                    WsPpNum++;
                    WsNextProdPrices();
                });
        }
    }


        // FILTERS
    $("#CmAjaxBox").on("click",'.CmFilterCheck', function () {
        $(this).find('.check_b').toggleClass('check_back');
        LoadingToggle('CmContent', $('#CmAjaxBox').offset().top-20);
        var oData = {};
        oData['CarModAjax']='Y';
        var prid = $(this).data('prid');
        var crcod = $(this).data('crcod');
        var bcode = $(this).data('bcode');
        if(prid){
            oData['ByProductID']=prid;
        }else if(crcod){
            oData['ByCriteriaCode']=crcod;
        }else if(bcode){
            oData['ByBrandCode']=bcode;
        }
		$('.CmTumButn').each(function(){
			if($(this).hasClass('CmTumPushed')){
				oData['Only']=$(this).data('tumb');
			}
        });
		$.post(window.location.href, oData, function(Result){
			//alert();
            $("#CmAjaxBox").html(Result);
            if($(window).width() <= 992) {
                $('.left_fil').css('right', '315px');
            }
            LoadingToggle();
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });


    //OE, Analog switch
    jQuery('#CmAjaxBox').on('click', '.CmTumButn', function(e){
		if(!jQuery(this).hasClass('CmTumPushed')){
			e.preventDefault();
			jQuery('.CmTumButn').each(function(){
				jQuery(this).removeClass('CmTumPushed');
			});
			jQuery(this).addClass('CmTumPushed');
			var titPosHrf = jQuery(this).attr('href');
			LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
			jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
				.done(function(Result){
					jQuery("#CmAjaxBox").html(Result);
					if(jQuery(window).width() <= 992) {
						jQuery('.left_fil').css('right', '315px');
					}
					LoadingToggle();
					WebServiceListBlocks();
					WsNextProdPrices();
				});
		}
    });

    // Select Products SubSection

    // const pickSect = document.querySelectorAll('.CmSectExpanBl');
    // const next = document.querySelector('.CmSectExpanBl').nextElementSibling;
    // console.log(next);
	// for(let i = 0; i < pickSect.length;i++){
	// 	if(pickSect[i].dataset.exp === 'Expanded'){
	// 		const childPick = pickSect[i].querySelector('.Down');
	// 		childPick.classList.add('DownActive');
    //         pickSect[i].nextElementSibling.style.display = 'block';
    //         // jQuery(pickSect[i]).next().slideToggle(400);
	// 	}
	// }

    jQuery("#CmAjaxBox").on("click",".PickSection", function(e){
        LoadingToggle('CmContent',1);
        window.history.pushState('object or string', 'Title', jQuery(this).attr('href'));
        e.preventDefault();
        var Code = jQuery(this).data('code');
        var pickHeight = jQuery(this).height();
        jQuery(this).parents('.cm_FsBlock').find('.FilterSection').css('border-top-width') == pickHeight / 2;
        jQuery(this).parents('.cm_FsBlock').find('.FilterSection').css('border-bottom-width') == pickHeight / 2;
        jQuery.post(window.location.href, {CarModAjax:'Y', PickSection:Code}, function(Result){
            jQuery("#CmAjaxBox").html(Result);
            if(jQuery(window).width() <= 992) {
                jQuery('.left_fil').css('right', '315px');
            }
            LoadingToggle('CmContent',1);
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });

    // SORT BY PRODUCT_LIST
    jQuery("#CmAjaxBox ").on("click", ".sort_bl", function(e){
        e.stopPropagation();
        jQuery('.hide_bl').toggleClass('hiBlbor');
        jQuery('.show_bl').toggleClass('shBlbor');
        jQuery('.hide_bl').toggle();
    });
    jQuery("#CmAjaxBox ").on("click", ".sort_list", function(e){
        e.stopPropagation();
        jQuery('.hide_bl').toggleClass('hiBlbor');
        jQuery('.show_bl').toggleClass('shBlbor');
        jQuery('.hide_bl').hide();
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.post(window.location.href, {CarModAjax:'Y', SortBy:jQuery(this).data('sort') }, function(Result){
            jQuery("#CmAjaxBox").html(Result);
            LoadingToggle('CmContent');
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });
    jQuery("#CmAjaxBox").on("mouseleave", ".hide_bl", function (){
        jQuery(this).hide();
    });
    jQuery('.CmSortBlockClose').on('click',function (e){
        jQuery(".hide_bl").hide();
    });


    // VIEW SWITCH PRODUCT_LIST
    jQuery("#CmAjaxBox").on("click",".cm_viewAct", function(e){
        var uri = jQuery(this).data('urix');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        var view = jQuery(this).data('view');
        if(view && view!=''){
            jQuery.post(uri, {CarModAjax:'Y', ActivateTab:view}, function(Result){
                jQuery("#CmAjaxBox").html(Result);
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
        }
    });

    // SELECT SETUP SIDE
    // front, rear
    jQuery('#CmAjaxBox').on('click', '.CmSelectCarSide', function(e){
        e.preventDefault();
        jQuery('.CmFrRr').each(function(){
            jQuery(this).find('.CmCarSide').css('fill','#909090');
            jQuery(this).find('.CmCarSideTxt').css('color','#909090');
            jQuery(this).removeClass('CmSelSideTogg');
            jQuery(this).addClass('CmSelectCarSide');
        });
        jQuery(this).find('.CmCarSide').css('fill','#f93a3a');
        jQuery(this).find('.CmCarSideTxt').css('color','#f93a3a');
        jQuery(this).removeClass('CmSelectCarSide');
        jQuery(this).addClass('CmSelSideTogg');
        var titPosHrf = jQuery(this).attr('href');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
            .done(function(Result){
                jQuery("#CmAjaxBox").html(Result);
                if(jQuery(window).width() <= 992) {
                    jQuery('.left_fil').css('right', '315px');
                }
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
    });
    //left, right
    jQuery('#CmAjaxBox').on('click', '.CmSelectBVSide', function(e){
        e.preventDefault();
        jQuery('.CmLfRt').each(function(){
            jQuery(this).find('.CmBackView').css('fill','#909090');
            jQuery(this).find('.CmBVTxt').css('color','#909090');
            jQuery(this).removeClass('CmSelBVTogg');
            jQuery(this).addClass('CmSelectBVSide');
        });
        jQuery(this).find('.CmBackView').css('fill','#f93a3a');
        jQuery(this).find('.CmBVTxt').css('color','#f93a3a');
        jQuery(this).removeClass('CmSelectBVSide');
        jQuery(this).addClass('CmSelBVTogg');
        var titPosHrf = jQuery(this).attr('href');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
            .done(function(Result){
                jQuery("#CmAjaxBox").html(Result);
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
    });


    /* Admin Tips for Public side */
    jQuery("#CmContent").on("mouseover", ".CmTitShow", function(){
        var title = jQuery(this).attr('title');
        if(title){
            jQuery(this).data('tipText', title).removeAttr('title');
            jQuery('<p class="CmTipBox CmColorBr CmColorTx"></p>').html(title).appendTo('body').show(); //alert('+'+title);
        }else{return false;}
    });
    jQuery("#CmContent").on("mouseleave", ".CmTitShow", function(){
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.CmTipBox').remove();
    });
    jQuery("#CmContent").on("mousemove", ".CmTitShow", function(e){
        var mousex = e.pageX - 80; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        jQuery('.CmTipBox').css({ top:mousey, left:mousex });
    });


    /* ====== SCHEMES ==== =============================================== */
    /* show more toggle */
    function ShemeHover() {
        let elBox;
        jQuery(".CmSchemaBox").hover(function(){
            elBox = jQuery(this),
            curHeight = elBox.height(),
            autoHeight = elBox.css('height', 'auto').height();
            elBox.height(curHeight).stop(true, true).animate({height: autoHeight}, 200, "linear");
        },function(){
            elBox.animate({height: '82px'}, 200, "linear");
        })
    }
    const schCount = jQuery('.CmSchemaBox').data('schcount');
    if(schCount > 5){
        ShemeHover();
        jQuery(document).ajaxComplete(function () {
            ShemeHover();
        });
    }

    jQuery("body").on("click", '.CmSchema', function (e){
        var SchPicID = jQuery(this).data('picid'),
        Lng = jQuery(this).data('lng');
        jQuery(this).find('.CmSchLoadWrap').show().css('display','flex');
        jQuery.ajax({url:window.location.href, type:'POST', dataType:'html', data:{SchPicID:SchPicID, Lng:Lng}})
        .done(function(Result){
            jQuery('.fxOverlay').css('display', 'flex');
            jQuery('.CmLoadWrap').css('display', 'flex');
            jQuery('.fxCont').height(window.innerHeight-100);
            jQuery('.fxCont').width(1180);
            jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
            jQuery('.CmLoadWrap').css('display', 'flex');
            var maxWidth = jQuery('.CmSchemeBlockWrap').width(),
            maxHeight = jQuery('.CmSchemeBlockWrap').height(),
            srcHeight = jQuery('.CmSchemeGridWrap').data('height'),
            srcWidth = jQuery('.CmSchemeGridWrap').data('width');
            calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight);
            jQuery('.CmLoadWrap').hide();
            jQuery('.CmSchLoadWrap').hide();
        });
    });


    //=========== PRICE BLOCK ===================================

       //sort
    if(jQuery('.CmTablePriceWrap').length){
        const descHtml = '<svg width="20px" height="20px" viewBox="0 0 32 32"><path d="M 4 5 L 4 7 L 16 7 L 16 5 Z M 21 5 L 21 23.6875 L 18.40625 21.09375 L 17 22.5 L 21.28125 26.8125 L 22 27.5 L 22.71875 26.8125 L 27 22.5 L 25.59375 21.09375 L 23 23.6875 L 23 5 Z M 4 9 L 4 11 L 14 11 L 14 9 Z M 4 13 L 4 15 L 12 15 L 12 13 Z M 4 17 L 4 19 L 10 19 L 10 17 Z M 4 21 L 4 23 L 8 23 L 8 21 Z M 4 25 L 4 27 L 6 27 L 6 25 Z"/></svg>';
        const ascHtml = '<svg width="20px" height="20px" viewBox="0 0 32 32"><path d="M 4 5 L 4 7 L 6 7 L 6 5 L 4 5 z M 22 5.5 L 21.279297 6.1894531 L 17 10.5 L 18.410156 11.910156 L 21 9.3105469 L 21 28 L 23 28 L 23 9.3105469 L 25.589844 11.910156 L 27 10.5 L 22.720703 6.1894531 L 22 5.5 z M 4 9 L 4 11 L 8 11 L 8 9 L 4 9 z M 4 13 L 4 15 L 10 15 L 10 13 L 4 13 z M 4 17 L 4 19 L 12 19 L 12 17 L 4 17 z M 4 21 L 4 23 L 14 23 L 14 21 L 4 21 z M 4 25 L 4 27 L 16 27 L 16 25 L 4 25 z"/></svg>';
        const sortArrow = '<svg width="15px" height="15px" viewBox="0 0 512 512">'+
                    '<path d="M123.177,505.18c0.545,0.543,1.117,1.058,1.713,1.547c0.264,0.219,0.546,0.408,0.818,0.613'+
                        'c0.334,0.251,0.661,0.51,1.008,0.742c0.33,0.222,0.675,0.413,1.013,0.616c0.312,0.186,0.616,0.383,0.937,0.554'+
                        'c0.349,0.186,0.704,0.343,1.06,0.51c0.334,0.158,0.662,0.324,1.004,0.467c0.346,0.143,0.697,0.258,1.049,0.383'+
                        'c0.366,0.132,0.728,0.272,1.1,0.385c0.352,0.105,0.706,0.185,1.061,0.273c0.382,0.098,0.76,0.203,1.15,0.281'+
                        'c0.408,0.081,0.816,0.129,1.227,0.188c0.341,0.048,0.678,0.113,1.022,0.147c0.765,0.074,1.531,0.115,2.298,0.115'+
                        's1.533-0.04,2.296-0.116c0.346-0.034,0.681-0.099,1.022-0.147c0.411-0.059,0.819-0.107,1.227-0.188'+
                        'c0.389-0.078,0.768-0.185,1.15-0.281c0.355-0.088,0.709-0.166,1.061-0.273c0.372-0.113,0.734-0.253,1.1-0.385'+
                        'c0.352-0.126,0.703-0.24,1.049-0.383c0.343-0.143,0.672-0.309,1.004-0.467c0.355-0.166,0.711-0.324,1.06-0.51'+
                        'c0.321-0.172,0.625-0.368,0.937-0.554c0.34-0.203,0.683-0.394,1.013-0.616c0.349-0.233,0.676-0.492,1.008-0.742'+
                        'c0.273-0.205,0.554-0.394,0.818-0.613c0.596-0.489,1.168-1.004,1.713-1.547l116.359-116.361c9.089-9.087,9.089-23.824,0-32.912'+
                        'c-9.087-9.089-23.824-9.089-32.912,0l-76.632,76.637V23.273C162.91,10.42,152.49,0,139.637,0s-23.273,10.42-23.273,23.273v409.27'+
                        'l-76.636-76.634c-9.087-9.089-23.824-9.089-32.912,0c-9.089,9.087-9.089,23.823,0,32.912L123.177,505.18z"/>'+
                    '<path d="M387.113,5.274c-0.261-0.216-0.538-0.402-0.807-0.604c-0.34-0.254-0.67-0.515-1.021-0.751'+
                        'c-0.327-0.219-0.667-0.408-1.002-0.608c-0.316-0.189-0.625-0.388-0.953-0.562c-0.343-0.183-0.694-0.338-1.046-0.504'+
                        'c-0.338-0.16-0.67-0.329-1.016-0.472c-0.341-0.141-0.689-0.256-1.035-0.379c-0.369-0.133-0.737-0.276-1.116-0.389'+
                        'c-0.346-0.104-0.694-0.18-1.043-0.268c-0.388-0.098-0.771-0.206-1.167-0.285c-0.399-0.079-0.802-0.126-1.202-0.183'+
                        'c-0.349-0.051-0.694-0.116-1.049-0.152c-0.74-0.073-1.482-0.11-2.225-0.112C372.409,0.003,372.389,0,372.364,0'+
                        's-0.045,0.003-0.07,0.003c-0.743,0.003-1.485,0.039-2.225,0.112c-0.355,0.036-0.7,0.101-1.049,0.152'+
                        'c-0.402,0.057-0.805,0.104-1.202,0.183c-0.396,0.078-0.779,0.186-1.167,0.285c-0.349,0.088-0.697,0.164-1.043,0.268'+
                        'c-0.379,0.115-0.745,0.256-1.116,0.389c-0.346,0.124-0.694,0.237-1.035,0.379c-0.348,0.143-0.68,0.312-1.016,0.472'+
                        'c-0.352,0.164-0.703,0.32-1.046,0.504c-0.327,0.174-0.636,0.372-0.953,0.562c-0.335,0.2-0.675,0.389-1.002,0.608'+
                        'c-0.352,0.236-0.681,0.496-1.021,0.751c-0.268,0.202-0.546,0.388-0.807,0.604c-0.596,0.489-1.168,1.004-1.713,1.547'+
                        'L239.542,123.179c-9.089,9.087-9.089,23.824,0,32.912c9.087,9.089,23.824,9.089,32.912,0l76.637-76.632v409.268'+
                        'c0,12.853,10.42,23.273,23.273,23.273s23.273-10.42,23.273-23.273V79.459l76.636,76.634c4.543,4.544,10.499,6.816,16.455,6.816'+
                        'c5.956,0,11.913-2.271,16.455-6.817c9.089-9.087,9.089-23.824,0-32.912L388.826,6.82C388.281,6.277,387.709,5.762,387.113,5.274z"'+
                        '/></svg>';

        const headers = document.querySelectorAll('.CmSortBlock');
        const tableBody = document.querySelector('.CmTablePriceWrap tbody');
        const rows = tableBody.querySelectorAll('.CmTbodyPrice tr');

        const directions = Array.from(headers).map(function(header) {
            return '';
        });

        const transform = function(index, content) {
            return parseFloat(content);
        }

        const sortCookie = get_cookie('PRICES_SORT');
        if(sortCookie){
            const resCookie = JSON.parse(sortCookie);
            // console.log(resCookie);
            if(resCookie[1] == 'asc'){
                jQuery('.'+resCookie[2]).html(ascHtml);
            }else{
                jQuery('.'+resCookie[2]).html(descHtml).attr('data-sort', 'asc');
            }
        }

        // function sortColumn(index,  elem = '', direct = '', type = '') {

        //     let direction = '';
        //     if(direct == '')direction = directions[index] || 'desc';
        //     if(direct != '')direction = direct;

        //     const multiplier = (direction === 'asc') ? 1 : -1;

        //     const newRows = Array.from(rows);

        //     newRows.sort(function(rowA, rowB) {
        //         let cellA = '',
        //         cellB = '';
        //         if(type == '' && elem != ''){
        //             if(elem.getAttribute('data-val') == 'aval'){
        //                 cellA = rowA.getAttribute('data-cmdelnum');
        //                 cellB = rowB.getAttribute('data-cmdelnum');
        //             }else if(elem.getAttribute('data-val') == 'deliv'){
        //                 cellA = rowA.getAttribute('data-cmavnum');
        //                 cellB = rowB.getAttribute('data-cmavnum');
        //             }else if(elem.getAttribute('data-val') == 'price'){
        //                 cellA = rowA.getAttribute('data-cmprnum');
        //                 cellB = rowB.getAttribute('data-cmprnum');
        //             }
        //         }else{
        //             if(type == 'aval'){
        //                 cellA = rowA.getAttribute('data-cmdelnum');
        //                 cellB = rowB.getAttribute('data-cmdelnum');
        //             }else if(type == 'deliv'){
        //                 cellA = rowA.getAttribute('data-cmavnum');
        //                 cellB = rowB.getAttribute('data-cmavnum');
        //             }else if(type == 'price'){
        //                 cellA = rowA.getAttribute('data-cmprnum');
        //                 cellB = rowB.getAttribute('data-cmprnum');
        //             }
        //         }

        //         const a = transform(index, cellA);
        //         const b = transform(index, cellB);

        //         switch (true) {
        //             case a > b: return 1 * multiplier;
        //             case a < b: return -1 * multiplier;
        //             case a === b: return 0;
        //         }
        //     });


        //     [].forEach.call(rows, function(row) {
        //         tableBody.removeChild(row);
        //     });


        //     directions[index] = (direction === 'asc') ? 'desc' : 'asc';
            // if(direct == ''){
            //     let elemAttr = elem.getAttribute('data-val');
            //     const aSortCook = [index, direction, elemAttr, `Cm${elemAttr}`];
            //     setCookie('PriceListSort', JSON.stringify(aSortCook), 9999999, null, null, null); //console.log(aSortCook);
            // }

        //     newRows.forEach(function(newRow) {
        //         // if(newRow.classList.contains('CmTablePriceValueRow_2')){
        //         //     newRow.style.display = 'table-row';
        //         // }
        //         tableBody.appendChild(newRow);
        //     });
        // };

        // Sort on page load
        const thElem = document.querySelector('.Cmprice');  // console.log(thElem);
        // sortColumn(0,  thElem, 'asc');

        [].forEach.call(headers, function(header, index) {
            header.addEventListener('click', function() {
               jQuery(this).parent().find('.CmSortBlock').each(function(){
                    const sortVal = jQuery(this).data('val');
                    const pageUrl = jQuery(this).data('url');

                    jQuery('.CmSortBlock').html(sortArrow);
                    if(jQuery(this).attr('data-sort') === 'desc'){
                        jQuery(this).html(descHtml).attr('data-sort', 'asc').attr('data-csort', 'desc');
                    }else{
                        jQuery(this).html(ascHtml).attr('data-sort', 'desc').attr('data-csort', 'asc');
                    }

                    const sortDir = this.dataset.csort;
                    const aSortCookie = [sortVal, sortDir, `Cm${sortVal}`];
                    setCookie('PRICES_SORT', JSON.stringify(aSortCookie), 9999999, null, null, null);
                    window.location.reload();
                    // sortColumn(index, this);
                });
            });
        });
    }

     //more prices
    jQuery("#CmAjaxBox, .blockProdPrice").on('click', '.CmMorePrices', function(){
		jQuery('#CmAjaxBox, .blockProdPrice').find('.morePricestab').slideUp(100);
        jQuery(this).find('.morePricestab').slideDown(200);
    });

    //close more prices
    jQuery('#CmAjaxBox, .blockProdPrice').on('click','.CmMorePriceBlClose', function(e){
        e.stopPropagation();
        jQuery('.morePricestab').slideUp(200);
        jQuery('.CmMoreHidePr').show();
    });
    // jQuery(document).mousedown(function (e){ // событие клика по веб-документу
    //     var div = jQuery(".morePricestab"); // тут указываем ID элемента
    //     if (!div.is(e.target) && div.has(e.target).length === 0) {
    //         jQuery('.morePricestab').slideUp(200);
    //     }
    // });

    //SHOW MORE PRICE IN HIDE BLOCK
    jQuery("#CmAjaxBox, .blockProdPrice").on('click', '.CmMoreHidePr', function(){
        jQuery('.CmWrapBlMorePrice ').removeClass('CmWrapBlHeight');
        jQuery(this).hide();
    });

   // PRICE QUANTITY
    jQuery("#CmAjaxBox, .blockProdPrice").on("click", ".cm_countButM", function () {
        const min_quant = jQuery(this).siblings('.cm_countRes').data('minimalqnt');
        const input = jQuery(this).parent().find('.cm_countRes');
        let count = '';
        if(min_quant){
            count = parseInt(input.val()) - min_quant;
            count = count <= 0 ? min_quant : count;
        }else{
            count = parseInt(input.val()) - 1;
        }
        count = count <= 0 ? 1 : count;
        input.val(count);
        input.change();
        return false;
    });
    jQuery("#CmAjaxBox, .blockProdPrice").on("click", ".cm_countButP", function () {
        const min_quant = jQuery(this).siblings('.cm_countRes').data('minimalqnt');
        const maxaval = jQuery(this).parent().find('.cm_countRes').data('maxaval');
        //var IsMore = maxaval.indexOf('+',0);
		//if(IsMore==0){
			const input = jQuery(this).parent().find('.cm_countRes');
			let count = '';
			if(min_quant){
				count = parseInt(input.val()) + min_quant;
			}else{
				count = parseInt(input.val()) + 1;
			}
			count = count > maxaval ? maxaval : count;
			input.val(count);
			input.change();
			return false;
		//}
    });
    jQuery("#CmAjaxBox, .blockProdPrice").on("keyup", '.cm_countRes', function () {
        checkSymb(this);
    });
    function checkSymb(input){
        var value = input.value;
        var maxav = jQuery(input).data('maxaval');
        var rep = /[-\.;":'a-zA-Zа-яА-Я]/;
        if (rep.test(value)){
            value = value.replace(rep, '');
            input.value = value;
        }
        if(value>maxav){
            value = value.replace(value, maxav);
            input.value = value;
        }
        if(value==0){
            value = 1;
            input.value = value;
        }
        return value;
    }

    // SLIDER FILTER
    const brandCount = document.querySelector('.CmBrandFiltWrap');
    if (brandCount) {
        function BrandFilterSl () {
            let position = 0;
            const slidesToShow = 8;
            const slidesToScroll = 1;

            const track = document.querySelector('.CmBrandSlTrack');
            const btnPrev = document.querySelector('.CmBrSlPrev');
            const btnNext = document.querySelector('.CmBrSlNext');
            const items = document.querySelectorAll('.CmBrandSlideCheck');
            const itemCount = items.length;
			if($(document).hasClass('CmBrFiltCont')){
				const container = document.querySelector('.CmBrFiltCont');
			}else{
				const container = 1;
			}
			const itemWidth = container.clientWidth / slidesToShow + 16;
            const movePosition = slidesToScroll * itemWidth

            items.forEach((item) => {
                item.style.minWidth = `${itemWidth - 15}px`;
            });

            function SlideTractPos () {
                const itemLeft = itemCount - (Math.abs(position) + slidesToShow * itemWidth) / itemWidth;
                position -= itemLeft >= slidesToScroll ? movePosition : itemLeft * itemWidth;
                SetPosition();
                CheckBtns();
            }

            btnNext.addEventListener('click', () => {
                SlideTractPos ();
            });

            btnPrev.addEventListener('click', () => {
                const itemLeft = Math.abs(position) / itemWidth;
                position += itemLeft >= slidesToScroll ? movePosition : itemLeft * itemWidth;
                SetPosition();
                CheckBtns();
            });

            const SetPosition = () => {
                track.style.transform = `translateX(${position}px)`;
            };

            const CheckBtns = () => {
                btnPrev.disabled = position === 0;
                btnNext.disabled = position <= - (itemCount - slidesToShow) * itemWidth;
            };


            track.classList.add('Cm-translate-3s');
            let intSlide = setInterval(SlideTractPos, 4000);
            brandCount.onmouseover = function () {
                for ( var i = 1; i <= intSlide; i++ ) {
                    clearInterval ( i );
                }
                // clearInterval(intSlide);
                track.classList.remove('Cm-translate-3s');
                track.classList.add('Cm-translate-05s');
            };
            brandCount.onmouseleave = function () {
                intSlide = setInterval(SlideTractPos, 4000);
                track.classList.add('Cm-translate-3s');
                track.classList.remove('Cm-translate-05s');
            }
        }
        BrandFilterSl ();

        jQuery(document).ajaxComplete(function () {
            if (brandCount !== null) {
                BrandFilterSl ();
            }
        });
        // END SLIDER



        jQuery("#CmAjaxBox").on("click",'.CmBrandSlideCheck', function () {
            let elem = jQuery(this);
            LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
            var oData = {};
            oData['CarModAjax']='Y';
            var bcode = jQuery(this).data('bcode');
            if(bcode){
                oData['ByBrandCode']=bcode;
            }
            jQuery.post(window.location.href, oData, function(Result){
                jQuery("#CmAjaxBox").html(Result);
                elem.toggleClass('CmActive');
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
        });
    }
});

// Modal window close
    var doc = jQuery(document);
    jQuery(document).mousedown(function (e){ // событие клика по веб-документу
        var div = jQuery(".fxCont"); // тут указываем ID элемента
        if (!div.is(e.target) // если клик был не по нашему блоку
        && div.has(e.target).length === 0) { // и не по его дочерним элементам
            jQuery('.fxOverlay').hide(); // скрываем его
            jQuery('.fxCont').html('').css({width:'unset', height:'unset'});
            doc.unbind('scroll keydown mousewheel', scrollFix);
        }
    });
    jQuery('body').on('click', '.fxClose', function(){
        jQuery('.fxOverlay').hide();
        jQuery('.fxCont').html('');//.css({width:'unset', height:'unset'});
        doc.unbind('scroll keydown mousewheel', scrollFix);
    });

// LOADING overlay
function LoadingToggle(IdContentBox, ScrollPx){
    IdContentBox=IdContentBox||'CmContent';
    ScrollPx=ScrollPx||0;
    if(jQuery("#Loading").css('display') !== 'none'){
        jQuery("#Loading").hide();
    }else{
        var Cont = jQuery("#"+IdContentBox);
        var CTop = Cont.position().top;
        if(ScrollPx==-1){ScrollPx=CTop;}
        var CLeft = Cont.position().left; //alert('Top:'+CTop+'; Left:'+CLeft); // .offset()
        if(ScrollPx>0){
            jQuery('html, body').animate({ scrollTop:ScrollPx }, 500);
        }
        var CWidth = Cont.outerWidth();
        if(!jQuery.isNumeric(CWidth)){CWidth = Cont.width() + (parseInt(Cont.css('padding-left')) + parseInt(Cont.css('padding-right')) );}
        var CHeight = Cont.outerHeight();
        if(!jQuery.isNumeric(CHeight)){CHeight = Cont.height() + (parseInt(Cont.css('padding-top')) + parseInt(Cont.css('padding-bottom')) );}
        if(IdContentBox!='CmContent'){ //Для элементов внитри контента CarMod
            var PadT = parseInt(jQuery("#CmContent").css('padding-top'));
            var PadR = parseInt(jQuery("#CmContent").css('padding-right'));
            var PadB = parseInt(jQuery("#CmContent").css('padding-bottom'));
            var PadL = parseInt( jQuery("#CmContent").css('padding-left'));
            jQuery("#Loading").css({top:CTop-PadT, left:CLeft-PadL});
            jQuery("#Loading").width(CWidth+PadL+PadR).height(CHeight+PadT+PadB).show();
        }else{ // Для #CmContent

            jQuery("#Loading").width(CWidth).height(CHeight).show();
        }
    }
}

// SHOW & HIDE MORE NOT HIDE PRICE
jQuery('body').on('click', '.CmShowMorePrice', function(){
    jQuery(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').show().css('display', 'table-row');
    var hide = jQuery(this).data('hide');
    jQuery(this).html(hide).addClass('CmHideMorePrice').removeClass('CmShowMorePrice');
});
jQuery('body').on('click', '.CmHideMorePrice', function(){
    jQuery(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').hide();
    var show = jQuery(this).data('show');
    jQuery(this).html(show).addClass('CmShowMorePrice').removeClass('CmHideMorePrice');

});


// SHOW MORE OE NUMBERS
 jQuery('body').on('click', '.CmShowHidOeNum', function(){
    jQuery('.CmHiddenOeNum').each(function(){
        jQuery(this).hide().parents('.CmOeNumsTd').find('.CmHideOeNum').hide();
        jQuery(this).parents('.CmOeNumsTd').find('.CmShowHidOeNum').show();
    });
    jQuery(this).hide();
    jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').show();
    jQuery(this).siblings('.CmHideOeNum').show().css('align-self','flex-end');
    if((jQuery(this).parent().siblings('.CmOeBrName').data('check')=='Y' && jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').length>2) || jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').length>6){
        jQuery('.CmOeBlockInside').removeClass('CmOeNumHeightToHide');
        jQuery('.CmHideOe').show();
        jQuery('.CmShowOe').hide();
    }
});
jQuery('body').on('click', '.CmHideOeNum', function(){
    jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').hide();
    jQuery(this).hide();
    jQuery(this).siblings('.CmShowHidOeNum').show();
});

//REDIRECT FROM PRODUCT_LIST
jQuery('body').on('click','.CmLookAnalogHook',function(){
    jQuery('.tabOeNum').addClass('activeSecTab CmColorBr CmColorBg');
    jQuery('.tabPartUse').removeClass('activeSecTab CmColorBr CmColorBg');
    jQuery('.centBlockInfo').addClass('CmAddClassFlex');
    jQuery('.cmSuitBlock').hide();
    jQuery('.tabOeNum').find('.cmSvgImg').css('fill','#ffffff');
    jQuery('.tabPartUse').find('.cmSvgImg').css('fill','#808080');
});

// TABS ON PRODUCT PAGE
jQuery(document).ready(function( jQuery ) {

    const urlPage = jQuery('.wrapBlTabsMenu').data('url'),
    cmDir = jQuery('.CmModelSuitBlock').data('cmdir'),
    aCarModels = new Array();
     //Open brand models function
    function OpenBrandModels(fxPlace='') {
        jQuery('body '+fxPlace).on('click', '.CmLogoBrandImg', function(){
            jQuery('.CmModifListBlock').html('');
            jQuery('.CmSelectModelTxt, .CmSelectModTitl').show();
            const brName = jQuery(this).data('brname');
            const hasClick = jQuery(this).attr("clicked");
            jQuery(fxPlace+' .CmSelectBrandTxt').hide();
            if (hasClick === "N") {
                jQuery(fxPlace+' .CmModelModif').html('');
                jQuery.each(aCarModels[brName], function(key, val) {
                    jQuery(fxPlace+' .CmModelModif').append(`<div class="CmModeItem CmColorBgLh" data-code="${key}" clicked="N">${val}</div>`);
                });
            }
            jQuery(fxPlace+' .CmLogoBrandImg').each(function(){
                jQuery(this).attr("clicked", "N").removeClass('CmBordForAct CmColorBr CmColorBgL CmColorOu');
            });
            jQuery(this).attr("clicked", "Y").addClass('CmBordForAct CmColorBr CmColorBgL CmColorOu');
        });
    }

    //Get models request
    function AjaxModelRequest(urlP, fxPlace='', modDir=''){
        jQuery.ajax({url:urlP, type:'POST', dataType:'html', data:{GetModels:'Y'}})
        .done(function(Res){
            const aResJson = JSON.parse(Res);// console.log(Res);

            if(aResJson!==null){
                if(aResJson[0]!=='None'){
                    jQuery(fxPlace+' .CmModBlockInner').show().css('display','flex');
                    jQuery.each(aResJson, function(key, val) {
                        aCarModels[key] = val['MODELS'];
                        jQuery(fxPlace+' .CmBrandNameBl').append(`<div class="CmLogoBrandImg CmTitShow" style="background-image:url(/${modDir}/media/brands/${val['CODE']}.png)" data-brname="${key}" clicked="N" title="${key}"></div>`);
                    });
                    const modCount = Object.keys(aCarModels).length;
                    if(modCount == 1){
                        OpenBrandModels();
                        AjaxModifRequest(urlPage);
                        jQuery('.CmLogoBrandImg').addClass('CmBordForAct CmColorBr CmColorBgL CmColorOu').trigger('click');
                        jQuery('.CmModeItem:first-child').trigger('click');
                    }
                }else{
                    jQuery(fxPlace+' .CmNotFoundInfo').css('display','flex');
                }
            }else{
                jQuery(fxPlace+' .CmNotFoundInfo').css('display','flex');
            }
            jQuery(fxPlace+' .CmModelWaitLoad').hide();
        });
    }

    //Request model after load page
    if(jQuery('.wrapBlTabsMenu').data('avail')==='N'){
        AjaxModelRequest(urlPage, '', cmDir);
        //Open brand models function call
        OpenBrandModels();
        jQuery('.tabPartUse').attr("clicked", "Y");
        //Open model modify
        AjaxModifRequest(urlPage);
    }

    //Get modifications
    function AjaxModifRequest(urlP, fxPlace='') {
        jQuery('body '+fxPlace).on('click','.CmModeItem',function(){
            jQuery(fxPlace+' .CmSelectModelTxt').hide();
            var modCode = jQuery(this).data('code');
            jQuery(fxPlace+' .CmModeItem').each(function(){
                jQuery(this).removeClass('CmColorBgL');
            });
            jQuery(this).addClass('CmColorBgL');
            const hasClick = jQuery(this).attr("clicked");
            if (hasClick === "N") {
                jQuery(fxPlace+' .CmSelectModTitl').hide();
                jQuery(fxPlace+' .CmModifListOverf').css({alignItems:'center'});
                jQuery(fxPlace+' .CmModifListBlock').html('');
                jQuery(fxPlace+' .CmModifListBlock').append('<div class="CmSmLoading"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div>');
                jQuery.ajax({url:urlP, type:'POST', dataType:'html', data:{GetVhApp:modCode}})
                    .done(function(Res){
                    var aJRes = JSON.parse(Res);
                    jQuery.each(aJRes, function(key, val) {
                        jQuery(fxPlace+' .CmModifListOverf').css({alignItems:'flex-start'});
                        jQuery(fxPlace+' .CmModifListBlock').append(`<div class="CmTypesList CmColorBgLh">${val}</div>`).css('width', '100%');
                    });
                    jQuery(fxPlace+' .CmSmLoading').css('display','none');
                });
            }
            jQuery(fxPlace+' .CmModeItem').each(function(){
                jQuery(this).attr("clicked", "N");
            });
            jQuery(this).attr("clicked", "Y");
        });
    }

    jQuery('body').on('click', '.tabPartUse', function(){
        let hasClick = jQuery(this).attr("clicked"),
        cmDir = jQuery('.CmModelSuitBlock').data('cmdir');
        if(hasClick === "N"){
            jQuery('.cmBlockInfo').append('<div class="CmModelWaitLoad"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div>');
            AjaxModelRequest(urlPage, '', cmDir);
        }
        jQuery('.tabPartUse').attr("clicked", "Y");

        //Open brand models function call
        OpenBrandModels();

        //Open model modify
        AjaxModifRequest(urlPage);
    });


    jQuery('body').on('click', '.CmtabSelBut', function(){
        jQuery('.CmtabSelBut').removeClass('activeSecTab c_boxShad CmColorTx');
        jQuery('.CmtabSelBut').find('.cmSvgImg').removeClass('CmColorFi');
        jQuery(this).addClass('activeSecTab CmColorTx');
        jQuery(this).find('.cmSvgImg').addClass('CmColorFi');
        if(jQuery(this).data('change')==='OeNum'){
            jQuery(this).addClass('CmTabShadRight');
        }else if(jQuery(this).data('change')==='Suite'){
            jQuery(this).addClass('CmTabShadLeft');
        }
        if(jQuery(this).data('change')==='OeNum'){
           jQuery('.centBlockInfo').css({height: 'auto', opacity: 1});
            jQuery('.cmSuitBlock').css({height: 0, opacity: 0});
        }
        if(jQuery(this).data('change')==='Suite'){
            jQuery('.centBlockInfo').css({height: 0, opacity: 0});
            jQuery('.cmSuitBlock').css({height: 'auto', opacity: 1});
        }
        if(jQuery(this).data('change')==='ProdInfo'){
            jQuery('.centBlockInfo').css({height: 0, opacity: 0});
            jQuery('.cmSuitBlock').css({height: 0, opacity: 0});
        }
    });

    //Request from view_list
    jQuery("#CmAjaxBox").on("click", '.ProductInfoOe', function (e){
        e.preventDefault();
        var thisEl = jQuery(this);
        var furl = jQuery(this).data('furl');
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="CmSchLoadWrap" style="display:flex; top:0; left:0;"><div class="CmSchLoading"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div></div>');
        jQuery.ajax({url:furl, type:'POST', dataType:'html', data:{ProdPrice:'Y', CarModAjaxOENumbers:'Y', IncludeFuncs:'Yes', ArtNum:jQuery(this).parent().data('artnum'), Brand:jQuery(this).parent().data('brand'), Tab:jQuery(this).data('tab'), HideStat:'Y', OENumbers:'Y'}})
            .done(function(Result){
                jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
                jQuery('.fxCont .centBlockInfo').css({height: 'auto', opacity: 1});
                jQuery('.fxCont .cmSuitBlock').css({height: 0, opacity: 0});
                const countBlock = document.querySelectorAll('.CmInfoInBlock');
                if(countBlock.length > 0) document.querySelector('.CmNoInfo').style.display = 'none';
            });
        });
    jQuery("#CmAjaxBox").on("click", '.ProductInfoSuit', function (e){
        e.preventDefault();
        let thisEl = jQuery(this);
        const furl = jQuery(this).data('furl'),
        cmDir = jQuery(this).data('moduledir');
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="CmSchLoadWrap" style="display:flex; top:0; left:0;"><div class="CmSchLoading"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div></div>');
        jQuery.ajax({url:furl, type:'POST', dataType:'html', data:{ProdPrice:'Y', CarModAjaxSuitVehicle:'Y', IncludeFuncs:'Yes', ArtNum:jQuery(this).parent().data('artnum'), Brand:jQuery(this).parent().data('brand'), Tab:jQuery(this).data('tab'), HideStat:'Y', ProdVehicle:'Y'}})
            .done(function(Result){
                jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
                AjaxModelRequest(furl, '.fxCont', cmDir);
                OpenBrandModels('.fxCont');
                AjaxModifRequest(furl, '.fxCont');
            });
        });
    //APPLIED TO MODEL
    //More brand models
    // jQuery('body').on('click', '.CmBrandNameBl', function(){
    //     jQuery('.CmSelectModelTxt').show();
    //     var bn = jQuery(this).data('brandname');
    //     jQuery('.CmBrandNameBl').each(function(){
    //         jQuery(this).removeClass('');
    //     });
    //     jQuery(this).addClass('CmColorBr CmColorBgL CmBordForAct CmColorOu');
    //     jQuery('.CmModelList').hide();
    //     jQuery('.CmTypesList').hide();
    //     jQuery('.CmModelList').each(function(){
    //         var mc = jQuery(this).data('modname');
    //         if(bn == mc){
    //             jQuery(this).show();
    //         }
    //     });
    //     jQuery('.CmSelectModTitl').show();
    // });
    // jQuery('.CmBrandNameBl:first-child').click();




    //MORE ANALOGS
    jQuery('body').on('click','.CmShowA',function(){
       jQuery('.CmAnalogBlockInside').removeClass('CmBlockHeightToHIde');
       jQuery('.CmHideA').show();
       jQuery('.CmHideTextBlock').hide();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideA',function(){
        jQuery('.CmAnalogBlockInside').addClass('CmBlockHeightToHIde');
        jQuery('.CmShowA, .CmHideTextBlock').show();
        jQuery(this).hide();

    });

    //MORE VEHICLES
    jQuery('body').on('click','.CmShowV',function(){
       jQuery('.CmVehicBlockWrap').removeClass('CmVehicleHeightBl');
       jQuery('.CmHideV').show();
       jQuery('.CmHideTextVehicBlock').hide();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideV',function(){
        jQuery('.CmVehicBlockWrap').addClass('CmVehicleHeightBl');
        jQuery('.CmShowV, .CmHideTextVehicBlock').show();
        jQuery(this).hide();
        jQuery(window).scrollTop(300);
    });

    //MORE OE NUMBERS
    jQuery('body').on('click','.CmShowOe',function(){
       jQuery('.CmOeBlockInside').removeClass('CmOeNumHeightToHide');
       jQuery('.CmHideOe').show();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideOe',function(){
        jQuery('.CmOeBlockInside').addClass('CmOeNumHeightToHide');
        jQuery('.CmShowOe, .CmShowHidOeNum').show();
        jQuery('.CmHiddenOeNum, .CmHideOeNum').hide();
        jQuery(this).hide();
        jQuery(window).scrollTop(500);
    });

    //Hover on price block
    jQuery('body').on('mouseenter', '.CmPriceProd', function(){
        var hintTxtA = jQuery(this).data('txta');
        var hintTxtD = jQuery(this).data('txtd');
        var hintBlockA = '<div class="CmShowHintBl CmAvalHintBl">'+hintTxtA+'</div>';
        var hintBlockD = '<div class="CmShowHintBl CmDelivHintBl">'+hintTxtD+'</div>';
        jQuery(this).find('.avalTd').append(hintBlockA);
        jQuery(this).find('.delivTd').append(hintBlockD);
        setTimeout(() =>
        jQuery('.CmShowHintBl').addClass('CmShowHintPopup'),
        jQuery('.CmAvalImgTextPage').css('border-radius', '0px 0px 0px 3px'),
        jQuery('.delivTd ').css('border-radius', '0px 0px 3px 0px'));
    });
    jQuery('body').on('mouseleave', '.CmPriceProd', function(){
        setTimeout(() => jQuery(this).find('.CmShowHintBl').removeClass('CmShowHintPopup'));
        setTimeout(() => jQuery(this).find('.CmShowHintBl').remove(), 200);
        jQuery('.CmAvalImgTextPage').css('border-radius', '3px 0px 0px 3px');
        jQuery('.delivTd ').css('border-radius', '0px 3px 3px 0px');
    });

});

// Fetch request
async function ReqFetch(url, data) {
    try{
        const response = await fetch(url, {
            method: 'POST',
            body: data,
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
            })
        });
        const resData = await response.text();
        return resData;
    } catch(error){
        console.log(error);
    }
}

//ASK PRICE AND MAIL ORDER POPUP BLOCK
function ToCartMailOrder(elem, addFolder, e){
    var Brand = jQuery(elem).data('brand'),
    Article = jQuery(elem).data('artnum'),
    ModuleDir = jQuery(elem).data('moduledir'),
    DataLang = jQuery(elem).data('lang'),
    Link = jQuery(elem).data('link'),
    pData = 'Brand='+Brand+'&Article='+Article+'&Lang='+DataLang+'&ModDir='+ModuleDir+'&Link='+Link;
    e.preventDefault();
    jQuery('.fxOverlay').css('display', 'flex');
    jQuery('.fxCont').html('<div id="tempSaver"></div><div class="CmSchLoadWrap" style="display:flex; margin:auto;"><div class="CmSchLoading"><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div><div class="CmColorBg"></div></div></div>');

    ReqFetch('/'+ModuleDir+'/add/'+addFolder+'/controller.php', pData)
        .then(result => jQuery('.fxCont').html('<div class="fxClose"></div>'+result));

    // DON'T DELETE

    // jQuery.post('/'+ModuleDir+'/add/'+addFolder+'/controller.php', {Brand:Brand, Article:Article, Lang:DataLang, ModDir:ModuleDir, Link:Link}, function(Result){
    //     jQuery('.fxCont').find('#tempSaver').html(Result);
    //     setTimeout(() => {
    //         jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
    //     }, 300);
    //     jQuery('.fxCont').find('#tempSaver').html('');
    // });
}
jQuery(document).ready(function(jQuery) {
    jQuery("#CmAjaxBox, .CmPriceProd, .blockProdPrice").on("click", '.ListAskPrice', function (e){
        var elem = jQuery(this);
        ToCartMailOrder(elem, 'askprice', e);
    });
    jQuery("#CmAjaxBox, .CmPriceProd").on("click", '.CmMailOrder', function (e){
        var elem = jQuery(this);
        ToCartMailOrder(elem, 'mail_order', e);
    });
});


