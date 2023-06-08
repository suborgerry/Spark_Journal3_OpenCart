if($(window).width()<=1024){ var width  = ($(window).width() * 19 / 20); }
else{ var	width  = ($(window).width() * 3 / 4); }
if($(window).height()<1024){ var height = ($(window).height() * 14 / 20); }
else{ var height = 900; }
$(document).ready(function(){
	$('[name="shipping_method"]').removeAttr('checked');

	if (width < 1024) {
		$('#dpd-modal .dpd-modal-dialog').removeAttr('style');
	}
});

$.fn.dpdAutocomplete = function(option) {
	return this.each(function() {
		this.timer = null;
		this.items = new Array();

		$.extend(this, option);

		$(this).attr('autocomplete', 'off');

		// Focus
		$(this).on('focus', function() {
			this.request();
		});

		// Blur
		$(this).on('blur', function() {
			setTimeout(function(object) {
				object.hide();
			}, 500, this);
		});

		// Keydown
		$(this).on('keydown', function(event) {
			switch(event.keyCode) {
				case 27: // escape
					this.hide();
					break;
				default:
					this.request();
					break;
			}
		});

		// Click
		this.click = function(event) {
			event.preventDefault();

			value = $(event.target).parent().attr('data-value');

			if (value && this.items[value]) {
				this.select(this.items[value]);
			}
		}

		// Show
		this.show = function() {
			var pos = $(this).position();

			$(this).siblings('ul.dropdown-menu').css({
				top: pos.top + $(this).outerHeight(),
				left: pos.left
			});

			$(this).siblings('ul.dropdown-menu').show();
		}

		// Hide
		this.hide = function() {
			$(this).siblings('ul.dropdown-menu').hide();
		}

		// Request
		this.request = function() {
			clearTimeout(this.timer);

			this.timer = setTimeout(function(object) {
				object.source($(object).val(), $.proxy(object.response, object));
			}, 200, this);
		}

		// Response
		this.response = function(json) {
			html = '';

			if (json.length) {
				for (i = 0; i < json.length; i++) {
					this.items[json[i]['value']] = json[i];
				}

				for (i = 0; i < json.length; i++) {
					if (!json[i]['category']) {
						html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
					}
				}

				// Get all the ones with a categories
				var category = new Array();

				for (i = 0; i < json.length; i++) {
					if (json[i]['category']) {
						if (!category[json[i]['category']]) {
							category[json[i]['category']] = new Array();
							category[json[i]['category']]['name'] = json[i]['category'];
							category[json[i]['category']]['item'] = new Array();
						}

						category[json[i]['category']]['item'].push(json[i]);
					}
				}

				for (i in category) {
					html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

					for (j = 0; j < category[i]['item'].length; j++) {
						html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
					}
				}
			}

			if (html) {
				this.show();
			} else {
				this.hide();
			}

			$(this).siblings('ul.dropdown-menu').html(html);
		}

		// $('<ul class="dropdown-menu"></ul>')
		// 	.insertAfter(this)
		// 	.delegate('a', 'click', $.proxy(this.click, this))

		// console.log('asd')

		$(this).after('<ul class="dropdown-menu"></ul>');
		$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

	});
}

$(window).resize(function() {
	if($(window).width()<=1024){ var width  = ($(window).width() * 19 / 20); }
	else{ var	width  = ($(window).width() * 3 / 4); }
	if($(window).height()<1024){ var height = ($(window).height() * 14 / 20); }
	else{ var height = 900; }
	if (width < 1024) {
		$('#dpd-modal .dpd-modal-dialog').removeAttr('style');
	} else {
		$('#dpd-modal .dpd-modal-dialog').css('width', width + 'px');
	}
});

var dpdModel = '<div class="dpd-modal dpd-fade" id="dpd-modal" tabindex="-1" role="dialog">'
	+ '<div class="dpd-modal-dialog dpd-modal-lg">'
	+ '<div class="dpd-modal-content">'
	+ '<div class="dpd-modal-header">'
	+	'<button type="button" class="dpd-close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">×</span></button>'
	+	'<h4 class="dpd-modal-title">Доставка DPD, пункты самовывоза</h4>'
	+ '</div>'
	+ '<div class="dpd-modal-body">'
	+ '<div id="dpd-map"></div>'
	+ '</div></div></div></div>';
	
var inited = false;

function load_term(){
	$('#open-pvz').click(function(){
		$('#open-pvz').button('loading');
			$('body').append(dpdModel);
			$.post('index.php?route=extension/shipping/dpd/getTerminals', {}).done(function(data) {
				
				$('#open-pvz').button('reset');
				if(data.error){
					alert(data.error);
				}else{
					$('#dpd-modal').modal('show');
					if (inited)	 {
						$('#dpd-map').dpdMap('reload', data)
						.on('dpd.map.terminal.select', function(e, terminal, widget) {
								$('#dpd-adr-for-radio').remove();
								$('.dpd-courier').append('<b id="dpd-adr-for-radio"> - ' + terminal.ADDRESS_FULL + '</b>');
								$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
								if($('input[name=address_same]').is(':checked')){
									$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
								}
								
								$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
								$('.dpd-close').trigger('click');
						});
					} else {
						$('#dpd-map').dpdMap({}, data)
							.on('dpd.map.terminal.select', function(e, terminal, widget) {
								$('#dpd-adr-for-radio').remove();
								$('.dpd-courier').append('<b id="dpd-adr-for-radio"> - ' + terminal.ADDRESS_FULL + '</b>');
								$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
								if($('input[name=address_same]').is(':checked')){
									$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
								}
								
								$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
								$('.dpd-close').trigger('click');
							});
						
						inited = true;
					}
				}
				$(document).delegate('.DPD-button', 'click', function() {
					$('.dpd-close').trigger('click');
				});
				/* действие при закрытии всплывающего окна */
				$('#dpd-modal').on('hidden.bs.modal', function(e) {
					$(this).remove();
					
					if(typeof(reloadAll) == 'function'){
						setTimeout(function() {
							reloadAll()
						}, 500);
					}
				});
			});
	});	
}

$(document).on('click', '[name="payment_method"]', function(){
	if(($("input[name='shipping_method']:checked").val() == 'dpd.dpdterminal') || ($("input[name='shipping_method']:checked").val() == 'dpd.dpddoor')){
		$.post('index.php?route=extension/shipping/dpd/checkPayment', { payment_method: $("input[name='payment_method']:checked").val()  }).done(function(data) {
			if(data.success){
				$('#remove-success').remove();
				
				$('#button-payment-method').button('loading');
				$('#button-guest-shipping').trigger('click');
				$('#button-shipping-address').trigger('click');
				setTimeout(function() {
					if(data.shipping == 'dpd.dpdterminal'){
						if(document.getElementById('hide-pvz')){
							$('.dpd-courier').trigger('click');
							document.getElementById('hide-pvz').style.display = 'block';
							
							load_term();
							
							if(data.message){
								$('<div id="remove-success" style="margin-top:10px;" class="col-sm-12"><div class="alert alert-info alert-dismissible fade in" role="alert"><strong>Выбранный ранее терминал не удовлетворяет условиям заказа!</strong></div></div>').insertBefore("#collapse-shipping-method");
								$('[name="shipping_address[address_1]"], [name="address_1"]').val('');
								$('[name="payment_address[address_1]"]').val('');
							}
						}
					}
				}, 1500);
				
			}
		});
	}
});

$(document).on('click', '[name="shipping_method"]', function(){
	
	shipping_method = $("input[name='shipping_method']:checked").val();
	
	if(typeof(reloadAll) == 'function'){
		reloadAll()
	}
	
	if(shipping_method == 'dpd.dpdterminal'){
		if(document.getElementById('hide-pvz')){
			document.getElementById('hide-pvz').style.display = 'block';
			load_term();
		}else if(document.getElementById('hide-pvz-simple')){
			document.getElementById('hide-pvz-simple').style.display = 'block';
		}
	}else{
		if(document.getElementById('hide-pvz')){
			document.getElementById('hide-pvz').style.display = 'none';
		}else if(document.getElementById('hide-pvz-simple')){
			document.getElementById('hide-pvz-simple').style.display = 'none';
		}
		
		$.post('index.php?route=extension/shipping/dpd/save', { address_off: 'yes' });
	}
});

$(document).on('click', '#open-pvz-simple', function(){
	$('#open-pvz-simple').button('loading');
	$('body').append(dpdModel);
	$.post('index.php?route=extension/shipping/dpd/getTerminals', {}).done(function(data) {
				
		$('#open-pvz-simple').button('reset');
		if(data.error){
			alert(data.error);
		}else{
			$('#dpd-modal').modal('show');
			if (inited)	 {
				$('#dpd-map').dpdMap('reload', data)
				.on('dpd.map.terminal.select', function(e, terminal, widget) {
						$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
						if($('input[name=address_same]').is(':checked')){
							$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
						}
							
						$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
						$('.dpd-close').trigger('click');
				});
			} else {
				$('#dpd-map').dpdMap({}, data)
					.on('dpd.map.terminal.select', function(e, terminal, widget) {
						$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
						if($('input[name=address_same]').is(':checked')){
							$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
						}
								
						$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
							
						$('.dpd-close').trigger('click');
					});
					
				inited = true;
			}
		}
		$(document).delegate('.DPD-button', 'click', function() {
			$('.dpd-close').trigger('click');
		});
		/* действие при закрытии всплывающего окна */
		$('#dpd-modal').on('hidden.bs.modal', function(e) {
			$(this).remove();
					
			if(typeof(reloadAll) == 'function'){
				setTimeout(function() {
					reloadAll()
				}, 500);
			}
		});
	});
});

$(document).on("keyup", '[name="city"], [name="address[city]"], [name="payment_address[city]"], [name="shipping_address[city]"], [name="register[city]"]', function() {
	if (this.dataset.dpdAutocompleteInit) return ;

	this.dataset.dpdAutocompleteInit = 'Y';
	
	var $progress;

	$(this)
		.unbind('change keyup keydown keypress')
		.dpdAutocomplete({
			minLength: 3,
			appendTo: 'body',
			autoFocus: true,
			source: function(request, response) {
				$progress && $progress.abort();

				$progress = $.ajax({
					url: 'index.php?route=extension/shipping/dpd/autocomplete&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
								return {
									label     : item['name'],
									value     : item['city_id'],
									val       : item['value'],
									zone_id   : item['zone_id'],
									country_id: item['country_id'],
								}
						}));
					},
				});
			},

			select: function(item) {
				$.post("index.php?route=extension/shipping/dpd/save", {
					city_id   : item['value'],
					country_id: item['country_id'],
					zone_id   : item['zone_id']
				}).done(function(data) {	
					$('#input-payment-city').val(item['val']);
					$('#input-shipping-city').val(item['val']);
					$('#shipping_address_city').val(item['val']);
					$('#shipping_payment_city').val(item['val']);
					$('#input-city').val(item['val']);
					$('[name="address[city]"]').val(item['val']);
					$('[name="payment_address[city]"]').val(item['val']);
					
					if(item['country_id']){
						$('#shipping_address_country_id').val(item['country_id']);
						var array = [];
						var i = 0;
						$('#input-payment-country option').each(function() {
							array[i] = $(this).val();
							i++;
						});
						
						if(array.length != 0){
							document.getElementById('input-payment-country').selectedIndex = array.indexOf(item['country_id']);
							document.getElementById('input-payment-country').dispatchEvent(new Event('change'));
						}
					}
					
					if(item['zone_id']){
						$('#input-payment-zone').val(item['zone_id']);
						$('#input-shipping-zone').val(item['zone_id']);
					}
					
					reloadAll()
				});
			}
		});
});
