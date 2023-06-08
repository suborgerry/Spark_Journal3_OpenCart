//---//SHOW ELEMENTS FUNCTION 
function showElements (elements) {
 elements = elements.length ? elements : [elements];
  for (var index = 0; index < elements.length; index++) {
    elements[index].style.transition = '0.35s ease-in-out';
    elements[index].style.display = 'flex';
    elements[index].style.opacity = '1';
  }
}
//---//

document.addEventListener("DOMContentLoaded",function(){

	// SELECT MANUFACTURER TABS
	var tabCars = document.getElementsByClassName('CmTabSelManuf');
	for(i=0; i<tabCars.length; i++){
		if(tabCars[i].classList.contains('CmTabCars')){
			tabCars[i].classList.add('CmActiveTabManuf');
		}
		tabCars[i].addEventListener('click', function(e){
			Array.from(tabCars).forEach(tab=>{
				tab.classList.remove('CmActiveTabManuf');
			});
			this.classList.add('CmActiveTabManuf');
			let elemId = this.dataset.name,
			manBlock = document.getElementsByClassName('CmManufContBlock');
			for(i=0; i<manBlock.length; i++){
				if(manBlock[i].id != elemId){
                    manBlock[i].style.display = 'none';
				}else{
                    manBlock[i].style.display = 'grid';
				}
			}
		});
	}

    //Выпадающий список подразделов
    jQuery('.showAllSect').on('click', function(){
        jQuery(this).hide();
        var showLNext = jQuery(this).attr("showLNext");
        var widthBoxSect = jQuery('.boxSect_x').width();
        // alert(widthBoxSect);
        showLNext.toString();
        jQuery('.CmListNSectBl').css('width', '100%');
        jQuery(this).closest('.boxSect_x').css({boxShadow: '0px 0px 10px 1px #d0d0d0'});

        jQuery('#'+showLNext).slideDown(400);
        jQuery(this).parent().parent().parent().parent('.boxSect_x').mouseleave(function(){
            jQuery(this).find('.CmListNSectBl').slideUp(400);
            jQuery(this).find('.showAllSect').delay(500).fadeIn(200);
            jQuery(this).closest('.boxSect_x').css({boxShadow: 'none'});
        });
    });

    //Прятать подразделы
    jQuery('.hideAllSect').on('click', function(){
        jQuery(this).parent().slideUp(400);
        jQuery(this).closest('.boxSect_x').css({boxShadow: 'none'});
    });

    //Выпадающий список разделов
    var allSect = document.getElementsByClassName('butAllSec');
    for(i=0; i<allSect.length; i++){
        allSect[i].addEventListener('click', function(){
            showElements(document.querySelectorAll('.boxSect_x'));
            this.style.display='none';
        });
    }
	// Фильтр по разделам
    jQuery('.CmInputSect').keyup(function(){
        jQuery('.boxSect_x').mouseleave(function(e){
            e.preventDefault();
        });
        jQuery('.CmListSectBl').find('li').addClass('f_list');
        jQuery('.boxSect_x').find('.nameSect_x').addClass('f_title');
        jQuery('.CmListNSectBl').find('li').addClass('f_Hlist');
        if(jQuery('.CmInputSect').val().length == 0){
            jQuery('.clearButt').hide();
       }
        if(jQuery('.CmInputSect').val().length > 0){
             jQuery('.clearButt').show();
        }
        if(jQuery('.CmInputSect').val().length >= 3){
            var val_inp = jQuery('.CmInputSect').val();
            var sw_x = 0;
			var regexTitle = new RegExp(val_inp,'i');
            var regSubTitle = new RegExp('\\s' + val_inp,'i');
			jQuery('.f_title').each(function(){
                var f_title = jQuery(this).text();
                if (regexTitle.test(f_title)) {
                    sw_x = 1;
                }
            });
            jQuery('.f_list').each(function(){
                var f_list = jQuery(this).text();
                if (regexTitle.test(f_list)) {
                    sw_x = 1;
                }
            });
            jQuery('.f_Hlist').each(function(){
                var f_Hlist = jQuery(this).text();
                if (regexTitle.test(f_Hlist)) {
                    sw_x = 1;
                }
            });
			if(sw_x == 1){
                jQuery('.f_title').each(function(){
                    var val_title = jQuery(this).text();
                    if (regexTitle.test(val_title)) {
                        jQuery(this).show();
                    }else{
                        jQuery(this).hide().removeClass('f_title');
                    }
                });
                jQuery('.f_list').each(function(){
                    var val_list = jQuery(this).text();
                    if (regSubTitle.test(val_list)) {
                        jQuery(this).show();
                        jQuery(this).parents().siblings('.nameSect_x').show();
                    }else{
                        jQuery(this).hide().removeClass('f_list');
                    }
                });
                jQuery('.f_Hlist').each(function(){
                    var val_Hlist = jQuery(this).text();
                    if (regSubTitle.test(val_Hlist)) {
                        jQuery(this).show();
                        jQuery(this).parents().siblings().find('.nameSect_x').show();
                        jQuery('.hideAllSect').text('');
                    }else{
                        jQuery(this).hide().removeClass('f_Hlist');
                    }
                });
                jQuery('.boxSect_x').each(function(){
                    var titl = jQuery(this).find('.f_title').length;
                    var listN = jQuery(this).find('.f_list').length;
                    var hideL = jQuery(this).find('.f_Hlist').length;
                    if(titl > 0 || listN > 0 || hideL > 0){
                        jQuery(this).show();
                    }else{
                        jQuery(this).hide().removeClass('boxSel_x');
                    }
                    if(titl == 0 && listN == 0 && hideL > 0){
                        jQuery(this).find('.CmListNSectBl').show().addClass('f_sec_block');
                        jQuery(this).find('.showAllSect').hide();
                    }
                    if(hideL == 0){
                        jQuery(this).find('.CmListNSectBl').hide().removeClass('f_sec_block');
                        jQuery(this).find('.showAllSect').hide();
                    }
                    if(titl == 1 && (listN == 0 || hideL == 0)){
                        jQuery(this).find('.sh_list').show();
                        jQuery(this).find('.hi_list').show();
                        jQuery(this).find('.CmListNSectBl').hide().removeClass('f_sec_block');
                        jQuery(this).find('.showAllSect').show();
                    }
                });
            }
        }else{
			jQuery('.f_title, .f_list, .f_Hlist, .boxSect_x, .showAllSect').show();
			jQuery('.CmListSectBl').find('li').addClass('f_list');
			jQuery('.boxSect_x').find('.nameSect_x').addClass('f_title');
			jQuery('.CmListNSectBl').find('li').addClass('f_Hlist');
			jQuery('.CmListNSectBl').hide().removeClass('f_sec_block');
        }
    });
    jQuery('body').on('click', '.clearButt', function(){
        jQuery(this).hide();
        jQuery('.CmInputSect').val('');
		jQuery('.CmListSectBl').find('li').addClass('f_list');
		jQuery('.boxSect_x').find('.nameSect_x').addClass('f_title');
		jQuery('.CmListNSectBl').find('li').addClass('f_Hlist');
		jQuery('.f_title, .f_list, .f_Hlist, .boxSect_x, .showAllSect').show();
		jQuery('.CmListNSectBl').hide().removeClass('f_sec_block');
    });
});