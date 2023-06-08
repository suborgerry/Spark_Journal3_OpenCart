//function floatToString(t,r){var e=t.toFixed(r).toString();return e.match(/^\.\d+/)?"0"+e:e}function attributeToString(t){return"string"!=typeof t&&"undefined"===(t+="")&&(t=""),jQuery.trim(t)}"undefined"==typeof window.Shopify&&(window.Shopify={}),Shopify.money_format="${{amount}}",Shopify.onError=function(t,r){var e=eval("("+t.responseText+")");e.message?alert(e.message+"("+e.status+"): "+e.description):alert("Error : "+Shopify.fullMessagesFromErrors(e).join("; ")+".")},Shopify.fullMessagesFromErrors=function(t){var o=[];return jQuery.each(t,function(e,t){jQuery.each(t,function(t,r){o.push(e+" "+r)})}),o},Shopify.onCartUpdate=function(t){alert("There are now "+t.item_count+" items in the cart.")},Shopify.onCartShippingRatesUpdate=function(t,r){var e="";r.zip&&(e+=r.zip+", "),r.province&&(e+=r.province+", "),e+=r.country,alert("There are "+t.length+" shipping rates available for "+e+", starting at "+Shopify.formatMoney(t[0].price)+".")},Shopify.onItemAdded=function(t){alert(t.title+" was added to your shopping cart.")},Shopify.onProduct=function(t){alert("Received everything we ever wanted to know about "+t.title)},Shopify.formatMoney=function(t,r){function n(t,r){return void 0===t?r:t}function e(t,r,e,o){if(r=n(r,2),e=n(e,","),o=n(o,"."),isNaN(t)||null==t)return 0;var a=(t=(t/100).toFixed(r)).split(".");return a[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1"+e)+(a[1]?o+a[1]:"")}"string"==typeof t&&(t=t.replace(".",""));var o="",a=/\{\{\s*(\w+)\s*\}\}/,i=r||this.money_format;switch(i.match(a)[1]){case"amount":o=e(t,2);break;case"amount_no_decimals":o=e(t,0);break;case"amount_with_comma_separator":o=e(t,2,".",",");break;case"amount_with_space_separator":o=e(t,2," ",",");break;case"amount_with_period_and_space_separator":o=e(t,2," ",".");break;case"amount_no_decimals_with_comma_separator":o=e(t,0,".",",");break;case"amount_no_decimals_with_space_separator":o=e(t,0,".","");break;case"amount_with_space_separator":o=e(t,2,",","");break;case"amount_with_apostrophe_separator":o=e(t,2,"'",".")}return i.replace(a,o)},Shopify.resizeImage=function(t,r){try{if("original"==r)return t;var e=t.match(/(.*\/[\w\-\_\.]+)\.(\w{2,4})/);return e[1]+"_"+r+"."+e[2]}catch(o){return t}},Shopify.addItem=function(t,r,e){var o={type:"POST",url:"/cart/add.js",data:"quantity="+(r=r||1)+"&id="+t,dataType:"json",success:function(t){"function"==typeof e?e(t):Shopify.onItemAdded(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(o)},Shopify.addItemFromForm=function(t,r){var e={type:"POST",url:"/cart/add.js",data:jQuery("#"+t).serialize(),dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onItemAdded(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(e)},Shopify.getCart=function(r){jQuery.getJSON("/cart.js",function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)})},Shopify.pollForCartShippingRatesForDestination=function(o,a,t){t=t||Shopify.onError;var n=function(){jQuery.ajax("/cart/async_shipping_rates",{dataType:"json",success:function(t,r,e){200===e.status?"function"==typeof a?a(t.shipping_rates,o):Shopify.onCartShippingRatesUpdate(t.shipping_rates,o):setTimeout(n,500)},error:t})};return n},Shopify.getCartShippingRatesForDestination=function(t,r,e){e=e||Shopify.onError;var o={type:"POST",url:"/cart/prepare_shipping_rates",data:Shopify.param({shipping_address:t}),success:Shopify.pollForCartShippingRatesForDestination(t,r,e),error:e};jQuery.ajax(o)},Shopify.getProduct=function(t,r){jQuery.getJSON("/products/"+t+".js",function(t){"function"==typeof r?r(t):Shopify.onProduct(t)})},Shopify.changeItem=function(t,r,e){var o={type:"POST",url:"/cart/change.js",data:"quantity="+r+"&id="+t,dataType:"json",success:function(t){"function"==typeof e?e(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(o)},Shopify.removeItem=function(t,r){var e={type:"POST",url:"/cart/change.js",data:"quantity=0&id="+t,dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(e)},Shopify.clear=function(r){var t={type:"POST",url:"/cart/clear.js",data:"",dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(t)},Shopify.updateCartFromForm=function(t,r){var e={type:"POST",url:"/cart/update.js",data:jQuery("#"+t).serialize(),dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(e)},Shopify.updateCartAttributes=function(t,r){var o="";jQuery.isArray(t)?jQuery.each(t,function(t,r){var e=attributeToString(r.key);""!==e&&(o+="attributes["+e+"]="+attributeToString(r.value)+"&")}):"object"==typeof t&&null!==t&&jQuery.each(t,function(t,r){o+="attributes["+attributeToString(t)+"]="+attributeToString(r)+"&"});var e={type:"POST",url:"/cart/update.js",data:o,dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(e)},Shopify.updateCartNote=function(t,r){var e={type:"POST",url:"/cart/update.js",data:"note="+attributeToString(t),dataType:"json",success:function(t){"function"==typeof r?r(t):Shopify.onCartUpdate(t)},error:function(t,r){Shopify.onError(t,r)}};jQuery.ajax(e)},"1.4"<=jQuery.fn.jquery?Shopify.param=jQuery.param:(Shopify.param=function(t){var e=[],r=function(t,r){r=jQuery.isFunction(r)?r():r,e[e.length]=encodeURIComponent(t)+"="+encodeURIComponent(r)};if(jQuery.isArray(t)||t.jquery)jQuery.each(t,function(){r(this.name,this.value)});else for(var o in t)Shopify.buildParams(o,t[o],r);return e.join("&").replace(/%20/g,"+")},Shopify.buildParams=function(e,t,o){jQuery.isArray(t)&&t.length?jQuery.each(t,function(t,r){rbracket.test(e)?o(e,r):Shopify.buildParams(e+"["+("object"==typeof r||jQuery.isArray(r)?t:"")+"]",r,o)}):null!=t&&"object"==typeof t?Shopify.isEmptyObject(t)?o(e,""):jQuery.each(t,function(t,r){Shopify.buildParams(e+"["+t+"]",r,o)}):o(e,t)},Shopify.isEmptyObject=function(t){for(var r in t)return!1;return!0});
'use strict';
/**
 * @param {number} numeric
 * @param {?} decimals
 * @return {?}
 */
function floatToString(numeric, decimals) {
    var searcher_name = numeric.toFixed(decimals).toString();
    return searcher_name.match(/^\.\d+/) ? "0" + searcher_name : searcher_name;
}
/**
 * @param {string} value
 * @return {?}
 */
function attributeToString(value) {
    return "string" != typeof value && "undefined" === (value = value + "") && (value = ""), jQuery.trim(value);
}
"undefined" == typeof window.Shopify && (window.Shopify = {}), Shopify.money_format = "${{amount}}", Shopify.onError = function(t$jscomp$2, r$jscomp$2) {
    /** @type {*} */
    var e$jscomp$8 = eval("(" + t$jscomp$2.responseText + ")");
    if (e$jscomp$8.message) {
        alert(e$jscomp$8.message + "(" + e$jscomp$8.status + "): " + e$jscomp$8.description);
    } else {
        alert("Error : " + Shopify.fullMessagesFromErrors(e$jscomp$8).join("; ") + ".");
    }
}, Shopify.fullMessagesFromErrors = function(errors) {
    /** @type {!Array} */
    var ret = [];
    return jQuery.each(errors, function(tsPath, downloadDataParameters) {
        jQuery.each(downloadDataParameters, function(canCreateDiscussions, fileName) {
            ret.push(tsPath + " " + fileName);
        });
    }), ret;
}, Shopify.onCartUpdate = function(cart) {
    alert("There are now " + cart.item_count + " items in the cart.");
}, Shopify.onCartShippingRatesUpdate = function(rates, shipping_address) {
    /** @type {string} */
    var readable_address = "";
    if (shipping_address.zip) {
        /** @type {string} */
        readable_address = readable_address + (shipping_address.zip + ", ");
    }
    if (shipping_address.province) {
        /** @type {string} */
        readable_address = readable_address + (shipping_address.province + ", ");
    }
    /** @type {string} */
    readable_address = readable_address + shipping_address.country;
    alert("There are " + rates.length + " shipping rates available for " + readable_address + ", starting at " + Shopify.formatMoney(rates[0].price) + ".");
}, Shopify.onItemAdded = function(line_item) {
    alert(line_item.title + " was added to your shopping cart.");
}, Shopify.onProduct = function(product) {
    alert("Received everything we ever wanted to know about " + product.title);
}, Shopify.formatMoney = function(s, format) {
    /**
     * @param {number} key
     * @param {!Object} defaultValue
     * @return {?}
     */
    function defaultOption(key, defaultValue) {
        return void 0 === key ? defaultValue : key;
    }
    /**
     * @param {number} value
     * @param {number} precision
     * @param {string} thousands
     * @param {string} decimal
     * @return {?}
     */
    function formatWithDelimiters(value, precision, thousands, decimal) {
        if (precision = defaultOption(precision, 2), thousands = defaultOption(thousands, ","), decimal = defaultOption(decimal, "."), isNaN(value) || null == value) {
            return 0;
        }
        /** @type {!Array<string>} */
        var parts = (value = (value / 100).toFixed(precision)).split(".");
        return parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1" + thousands) + (parts[1] ? decimal + parts[1] : "");
    }
    if ("string" == typeof s) {
        /** @type {string} */
        s = s.replace(".", "");
    }
    /** @type {string} */
    var value = "";
    /** @type {!RegExp} */
    var patt = /\{\{\s*(\w+)\s*\}\}/;
    var formatString = format || this.money_format;
    switch(formatString.match(patt)[1]) {
        case "amount":
            value = formatWithDelimiters(s, 2);
            break;
        case "amount_no_decimals":
            value = formatWithDelimiters(s, 0);
            break;
        case "amount_with_comma_separator":
            value = formatWithDelimiters(s, 2, ".", ",");
            break;
        case "amount_with_space_separator":
            value = formatWithDelimiters(s, 2, " ", ",");
            break;
        case "amount_with_period_and_space_separator":
            value = formatWithDelimiters(s, 2, " ", ".");
            break;
        case "amount_no_decimals_with_comma_separator":
            value = formatWithDelimiters(s, 0, ".", ",");
            break;
        case "amount_no_decimals_with_space_separator":
            value = formatWithDelimiters(s, 0, ".", "");
            break;
        case "amount_with_space_separator":
            value = formatWithDelimiters(s, 2, ",", "");
            break;
        case "amount_with_apostrophe_separator":
            value = formatWithDelimiters(s, 2, "'", ".");
    }
    return formatString.replace(patt, value);
}, Shopify.resizeImage = function(src, w) {
    try {
        if ("original" == w) {
            return src;
        }
        var changedCollections = src.match(/(.*\/[\w\-_\.]+)\.(\w{2,4})/);
        return changedCollections[1] + "_" + w + "." + changedCollections[2];
    } catch (o) {
        return src;
    }
}, Shopify.addItem = function(value, cover, callback) {
    var params = {
        type : "POST",
        url : "/cart/add.js",
        data : "quantity=" + (cover = cover || 1) + "&id=" + value,
        dataType : "json",
        success : function(line_item) {
            if ("function" == typeof callback) {
                callback(line_item);
            } else {
                Shopify.onItemAdded(line_item);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.addItemFromForm = function(form_id, callback) {
    var params = {
        type : "POST",
        url : "/cart/add.js",
        data : jQuery("#" + form_id).serialize(),
        dataType : "json",
        success : function(line_item) {
            if ("function" == typeof callback) {
                callback(line_item);
            } else {
                Shopify.onItemAdded(line_item);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.getCart = function(callback) {
    jQuery.getJSON("/cart.js", function(cart) {
        if ("function" == typeof callback) {
            callback(cart);
        } else {
            Shopify.onCartUpdate(cart);
        }
    });
}, Shopify.pollForCartShippingRatesForDestination = function(shipping_address, callback, onerror) {
    onerror = onerror || Shopify.onError;
    /**
     * @return {undefined}
     */
    var poller = function() {
        jQuery.ajax("/cart/async_shipping_rates", {
            dataType : "json",
            success : function(response, xml, t) {
                if (200 === t.status) {
                    if ("function" == typeof callback) {
                        callback(response.shipping_rates, shipping_address);
                    } else {
                        Shopify.onCartShippingRatesUpdate(response.shipping_rates, shipping_address);
                    }
                } else {
                    setTimeout(poller, 500);
                }
            },
            error : onerror
        });
    };
    return poller;
}, Shopify.getCartShippingRatesForDestination = function(shipping_address, form, callback) {
    callback = callback || Shopify.onError;
    var data = {
        type : "POST",
        url : "/cart/prepare_shipping_rates",
        data : Shopify.param({
            shipping_address : shipping_address
        }),
        success : Shopify.pollForCartShippingRatesForDestination(shipping_address, form, callback),
        error : callback
    };
    jQuery.ajax(data);
}, Shopify.getProduct = function(handle, callback) {
    jQuery.getJSON("/products/" + handle + ".js", function(product) {
        if ("function" == typeof callback) {
            callback(product);
        } else {
            Shopify.onProduct(product);
        }
    });
}, Shopify.changeItem = function(variant_id, quantity, callback) {
    var params = {
        type : "POST",
        url : "/cart/change.js",
        data : "quantity=" + quantity + "&id=" + variant_id,
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.removeItem = function(url, callback) {
    var params = {
        type : "POST",
        url : "/cart/change.js",
        data : "quantity=0&id=" + url,
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.clear = function(callback) {
    var params = {
        type : "POST",
        url : "/cart/clear.js",
        data : "",
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.updateCartFromForm = function(form_id, callback) {
    var params = {
        type : "POST",
        url : "/cart/update.js",
        data : jQuery("#" + form_id).serialize(),
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.updateCartAttributes = function(arg, callback) {
    /** @type {string} */
    var order = "";
    if (jQuery.isArray(arg)) {
        jQuery.each(arg, function(canCreateDiscussions, valueOfElement) {
            var key = attributeToString(valueOfElement.key);
            if ("" !== key) {
                order = order + ("attributes[" + key + "]=" + attributeToString(valueOfElement.value) + "&");
            }
        });
    } else {
        if ("object" == typeof arg && null !== arg) {
            jQuery.each(arg, function(key, value) {
                order = order + ("attributes[" + attributeToString(key) + "]=" + attributeToString(value) + "&");
            });
        }
    }
    var params = {
        type : "POST",
        url : "/cart/update.js",
        data : order,
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, Shopify.updateCartNote = function(note, callback) {
    var params = {
        type : "POST",
        url : "/cart/update.js",
        data : "note=" + attributeToString(note),
        dataType : "json",
        success : function(cart) {
            if ("function" == typeof callback) {
                callback(cart);
            } else {
                Shopify.onCartUpdate(cart);
            }
        },
        error : function(XMLHttpRequest, textStatus) {
            Shopify.onError(XMLHttpRequest, textStatus);
        }
    };
    jQuery.ajax(params);
}, "1.4" <= jQuery.fn.jquery ? Shopify.param = jQuery.param : (Shopify.param = function(a) {
    /** @type {!Array} */
    var displayUsedBy = [];
    /**
     * @param {?} t
     * @param {?} value
     * @return {undefined}
     */
    var add = function(t, value) {
        value = jQuery.isFunction(value) ? value() : value;
        /** @type {string} */
        displayUsedBy[displayUsedBy.length] = encodeURIComponent(t) + "=" + encodeURIComponent(value);
    };
    if (jQuery.isArray(a) || a.jquery) {
        jQuery.each(a, function() {
            add(this.name, this.value);
        });
    } else {
        var prefix;
        for (prefix in a) {
            Shopify.buildParams(prefix, a[prefix], add);
        }
    }
    return displayUsedBy.join("&").replace(/%20/g, "+");
}, Shopify.buildParams = function(prefix, obj, add) {
    if (jQuery.isArray(obj) && obj.length) {
        jQuery.each(obj, function(i, v) {
            if (rbracket.test(prefix)) {
                add(prefix, v);
            } else {
                Shopify.buildParams(prefix + "[" + ("object" == typeof v || jQuery.isArray(v) ? i : "") + "]", v, add);
            }
        });
    } else {
        if (null != obj && "object" == typeof obj) {
            if (Shopify.isEmptyObject(obj)) {
                add(prefix, "");
            } else {
                jQuery.each(obj, function(unitPlural, v) {
                    Shopify.buildParams(prefix + "[" + unitPlural + "]", v, add);
                });
            }
        } else {
            add(prefix, obj);
        }
    }
}, Shopify.isEmptyObject = function(object) {
    var name;
    for (name in object) {
        return false;
    }
    return true;
});
