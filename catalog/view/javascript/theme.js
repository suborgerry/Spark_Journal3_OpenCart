'use strict';
/**
 * @return {undefined}
 */
// function onYouTubeIframeAPIReady() {
//     theme.Video.loadVideos();
// }
window.theme = window.theme || {}, window.theme = window.theme || {}, theme.Sections = function() {
    this.constructors = {};
    /** @type {!Array} */
    this.instances = [];
    $(document).on("shopify:section:load", this._onSectionLoad.bind(this)).on("shopify:section:unload", this._onSectionUnload.bind(this)).on("shopify:section:select", this._onSelect.bind(this)).on("shopify:section:deselect", this._onDeselect.bind(this)).on("shopify:block:select", this._onBlockSelect.bind(this)).on("shopify:block:deselect", this._onBlockDeselect.bind(this));
}, theme.Sections.prototype = _.assignIn({}, theme.Sections.prototype, {
    _createInstance : function(options, view) {
        var node = $(options);
        var section_id = node.attr("data-section-id");
        var type = node.attr("data-section-type");
        if (view = view || this.constructors[type], !_.isUndefined(view)) {
            var $el = _.assignIn(new view(options), {
                id : section_id,
                type : type,
                container : options
            });
            this.instances.push($el);
        }
    },
    _onSectionLoad : function(l) {
        var request = $("[data-section-id]", l.target)[0];
        if (request) {
            this._createInstance(request);
        }
    },
    _onSectionUnload : function(e) {
        this.instances = _.filter(this.instances, function(config) {
            /** @type {boolean} */
            var firstLI = config.id === e.detail.sectionId;
            return firstLI && _.isFunction(config.onUnload) && config.onUnload(e), !firstLI;
        });
    },
    _onSelect : function(e) {
        var set = _.find(this.instances, function(timeline_mode) {
            return timeline_mode.id === e.detail.sectionId;
        });
        if (!_.isUndefined(set) && _.isFunction(set.onSelect)) {
            set.onSelect(e);
        }
    },
    _onDeselect : function(e) {
        var defaults = _.find(this.instances, function(timeline_mode) {
            return timeline_mode.id === e.detail.sectionId;
        });
        if (!_.isUndefined(defaults) && _.isFunction(defaults.onDeselect)) {
            defaults.onDeselect(e);
        }
    },
    _onBlockSelect : function(sender) {
        var command = _.find(this.instances, function(timeline_mode) {
            return timeline_mode.id === sender.detail.sectionId;
        });
        if (!_.isUndefined(command) && _.isFunction(command.onBlockSelect)) {
            command.onBlockSelect(sender);
        }
    },
    _onBlockDeselect : function(sender) {
        var command = _.find(this.instances, function(timeline_mode) {
            return timeline_mode.id === sender.detail.sectionId;
        });
        if (!_.isUndefined(command) && _.isFunction(command.onBlockDeselect)) {
            command.onBlockDeselect(sender);
        }
    },
    register : function(name, obj) {
        /** @type {!Function} */
        this.constructors[name] = obj;
        $("[data-section-type=" + name + "]").each(function(canCreateDiscussions, request) {
            this._createInstance(request, obj);
        }.bind(this));
    }
}), window.slate = window.slate || {}, slate.utils = {
    getParameterByName : function(name, url) {
        if (!url) {
            /** @type {string} */
            url = window.location.href;
        }
        name = name.replace(/[[\]]/g, "\\$&");
        /** @type {(Array<string>|null)} */
        var m = (new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)")).exec(url);
        return m ? m[2] ? decodeURIComponent(m[2].replace(/\+/g, " ")) : "" : null;
    },
    keyboardKeys : {
        TAB : 9,
        ENTER : 13,
        ESCAPE : 27,
        LEFTARROW : 37,
        RIGHTARROW : 39
    }
}, window.slate = window.slate || {}, slate.rte = {
    wrapTable : function(s) {
        s.$tables.wrap('<div class="' + s.tableWrapperClass + '"></div>');
    },
    wrapIframe : function(objectData) {
        objectData.$iframes.each(function() {
            $(this).wrap('<div class="' + objectData.iframeWrapperClass + '"></div>');
            this.src = this.src;
        });
    }
}, window.slate = window.slate || {}, slate.a11y = {
    pageLinkFocus : function(cbCollection) {
        /** @type {string} */
        var cls = "js-focus-hidden";
        cbCollection.first().attr("tabIndex", "-1").focus().addClass(cls).one("blur", function() {
            cbCollection.first().removeClass(cls).removeAttr("tabindex");
        });
    },
    focusHash : function() {
        /** @type {string} */
        var testString = window.location.hash;
        if (testString && document.getElementById(testString.slice(1))) {
            this.pageLinkFocus($(testString));
        }
    },
    bindInPageLinks : function() {
        $("a[href*=#]").on("click", function(event) {
            this.pageLinkFocus($(event.currentTarget.hash));
        }.bind(this));
    },
    trapFocus : function(self) {
        var events = {
            focusin : self.namespace ? "focusin." + self.namespace : "focusin",
            focusout : self.namespace ? "focusout." + self.namespace : "focusout",
            keydown : self.namespace ? "keydown." + self.namespace : "keydown.handleFocus"
        };
        var input = self.$container.find($('button, [href], input, select, textarea, [tabindex]:not([tabindex^="-"])').filter(":visible"));
        var first = input[0];
        var elem = input[input.length - 1];
        if (!self.$elementToFocus) {
            self.$elementToFocus = self.$container;
        }
        self.$container.attr("tabindex", "-1");
        self.$elementToFocus.focus();
        $(document).off("focusin");
        $(document).on(events.focusout, function() {
            $(document).off(events.keydown);
        });
        $(document).on(events.focusin, function(layer) {
            if (!(layer.target !== elem && layer.target !== first)) {
                $(document).on(events.keydown, function(alreadyFailedWithException) {
                    !function(e) {
                        if (e.keyCode === slate.utils.keyboardKeys.TAB) {
                            if (!(e.target !== elem || e.shiftKey)) {
                                e.preventDefault();
                                first.focus();
                            }
                            if (e.target === first && e.shiftKey) {
                                e.preventDefault();
                                elem.focus();
                            }
                        }
                    }(alreadyFailedWithException);
                });
            }
        });
    },
    removeTrapFocus : function(data) {
        /** @type {string} */
        var type = data.namespace ? "focusin." + data.namespace : "focusin";
        if (data.$container && data.$container.length) {
            data.$container.removeAttr("tabindex");
        }
        $(document).off(type);
    },
    accessibleLinks : function(result) {
        /** @type {(Element|null)} */
        var b = document.querySelector("body");
        var opts = {
            newWindow : "a11y-new-window-message",
            external : "a11y-external-message",
            newWindowExternal : "a11y-new-window-external-message"
        };
        if (!(void 0 !== result.$links && result.$links.jquery)) {
            result.$links = $("a[href]:not([aria-describedby])");
        }
        $.each(result.$links, function() {
            var el = $(this);
            var dropdown = el.attr("target");
            var rels = el.attr("rel");
            var offsetFromParent = function(array) {
                /** @type {string} */
                var hostname = window.location.hostname;
                return array[0].hostname !== hostname;
            }(el);
            /** @type {boolean} */
            var isTigger = "_blank" === dropdown;
            if (offsetFromParent) {
                el.attr("aria-describedby", opts.external);
            }
            if (isTigger) {
                if (!(void 0 !== rels && -1 !== rels.indexOf("noopener"))) {
                    el.attr("rel", function(canCreateDiscussions, b) {
                        return (void 0 === b ? "" : b + " ") + "noopener";
                    });
                }
                el.attr("aria-describedby", opts.newWindow);
            }
            if (offsetFromParent && isTigger) {
                el.attr("aria-describedby", opts.newWindowExternal);
            }
        });
        (function(data) {
            if ("object" != typeof data) {
                data = {};
            }
            var i = $.extend({
                newWindow : "Opens in a new window.",
                external : "Opens external website.",
                newWindowExternal : "Opens external website in a new window."
            }, data);
            /** @type {!Element} */
            var d = document.createElement("ul");
            /** @type {string} */
            var message = "";
            var p;
            for (p in i) {
                /** @type {string} */
                message = message + ("<li id=" + opts[p] + ">" + i[p] + "</li>");
            }
            d.setAttribute("hidden", true);
            /** @type {string} */
            d.innerHTML = message;
            b.appendChild(d);
        })(result.messages);
    }
}, theme.Images = {
    preload : function(data, callback) {
        if ("string" == typeof data) {
            /** @type {!Array} */
            data = [data];
        }
        /** @type {number} */
        var i = 0;
        for (; i < data.length; i++) {
            var rowToRemove = data[i];
            this.loadImage(this.getSizedImageUrl(rowToRemove, callback));
        }
    },
    loadImage : function(url) {
        /** @type {string} */
        (new Image).src = url;
    },
    switchImage : function(event, next, middlewareInstance) {
        var responseHandler = this.imageSize(next.src);
        var req = this.getSizedImageUrl(event.src, responseHandler);
        if (middlewareInstance) {
            middlewareInstance(req, event, next);
        } else {
            next.src = req;
        }
    },
    imageSize : function(size) {
        var value = size.match(/.+_((?:pico|icon|thumb|small|compact|medium|large|grande)|\d{1,4}x\d{0,4}|x\d{1,4})[_\\.@]/);
        return null !== value ? void 0 !== value[2] ? value[1] + value[2] : value[1] : null;
    },
    getSizedImageUrl : function(m, name) {
        if (null === name) {
            return m;
        }
        if ("master" === name) {
            return this.removeProtocol(m);
        }
        var keys = m.match(/\.(jpg|jpeg|gif|png|bmp|bitmap|tiff|tif)(\?v=\d+)?$/i);
        if (null !== keys) {
            var changedCollections = m.split(keys[0]);
            var plugin = keys[0];
            return this.removeProtocol(changedCollections[0] + "_" + name + plugin);
        }
        return null;
    },
    removeProtocol : function(a) {
        return a.replace(/http(s)?:/, "");
    }
}, theme.Currency = function() {
    var name = theme.moneyFormat;
    return {
        formatMoney : function(num, prefix) {
            /**
             * @param {!Object} value
             * @param {number} decimals
             * @param {string} thousand
             * @param {string} decimal
             * @return {?}
             */
            function formatNumber(value, decimals, thousand, decimal) {
                if (thousand = thousand || ",", decimal = decimal || ".", isNaN(value) || null === value) {
                    return 0;
                }
                /** @type {!Array<string>} */
                var parts = (value = (value / 100).toFixed(decimals)).split(".");
                return parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1" + thousand) + (parts[1] ? decimal + parts[1] : "");
            }
            if ("string" == typeof num) {
                /** @type {string} */
                num = num.replace(".", "");
            }
            /** @type {string} */
            var value = "";
            /** @type {!RegExp} */
            var reHelperFuncHead = /\{\{\s*(\w+)\s*\}\}/;
            var str = prefix || name;
            // switch(str.match(reHelperFuncHead)[1]) {
            //     case "amount":
            //         value = formatNumber(num, 2);
            //         break;
            //     case "amount_no_decimals":
            //         value = formatNumber(num, 0);
            //         break;
            //     case "amount_with_comma_separator":
            //         value = formatNumber(num, 2, ".", ",");
            //         break;
            //     case "amount_no_decimals_with_comma_separator":
            //         value = formatNumber(num, 0, ".", ",");
            //         break;
            //     case "amount_no_decimals_with_space_separator":
            //         value = formatNumber(num, 0, " ");
            //         break;
            //     case "amount_with_apostrophe_separator":
            //         value = formatNumber(num, 2, "'");
            // }
            return str.replace(reHelperFuncHead, value);
        }
    };
}(), slate.Variants = function() {
    /**
     * @param {!Object} options
     * @return {undefined}
     */
    function render(options) {
        this.$container = options.$container;
        this.product = options.product;
        this.singleOptionSelector = options.singleOptionSelector;
        this.originalSelectorId = options.originalSelectorId;
        this.enableHistoryState = options.enableHistoryState;
        this.currentVariant = this._getVariantFromOptions();
        this._firstupdateVariant(this.currentVariant);
        $(this.singleOptionSelector, this.$container).on("change", this._onSelectChange.bind(this));
        this.sticky_atc = $(".pr-selectors .pr-swatch");
        $(this.sticky_atc, this.$container).on("click", this._onSelectChange2.bind(this));
    }
    return render.prototype = _.assignIn({}, render.prototype, {
        _getCurrentOptions : function() {
            var entitiesToPull = _.map($(this.singleOptionSelector, this.$container), function(t) {
                var e = $(t);
                var type = e.attr("type");
                var a = {};
                return "radio" === type || "checkbox" === type ? !!e[0].checked && (a.value = e.val(), a.index = e.data("index"), a) : (a.value = e.val(), a.index = e.data("index"), a);
            });
            return entitiesToPull = _.compact(entitiesToPull);
        },
        _getVariantFromOptions : function() {
            var n;
            var r;
            var year;
            var smallSet = this._getCurrentOptions();
            var variants = this.product.variants;
            /** @type {boolean} */
            var ReloadPagePopup = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch")) {
                /** @type {boolean} */
                ReloadPagePopup = true;
            }
            var res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            });
            return null != res && 0 != res.available || (1 == ReloadPagePopup ? (n = $(".selector-wrapper-1", this.$container).find("input:checked").val(), r = $(".selector-wrapper-2", this.$container).find("input:checked").val(), year = $(".selector-wrapper-3", this.$container).find("input:checked").val(), $(".selector-wrapper-3 .swatch-element", this.$container).each(function() {
                var oldCondition = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option2 == r && data.option3 == oldCondition && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), smallSet = this._getCurrentOptions(), null != (res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })) && 0 != res.available || ($(".selector-wrapper-3 .swatch-element", this.$container).each(function() {
                var year = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), year = $(".selector-wrapper-3", this.$container).find("input:checked").val(), $(".selector-wrapper-2 .swatch-element", this.$container).each(function() {
                var oldCondition = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.option2 == oldCondition && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), smallSet = this._getCurrentOptions(), res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            }))) : (n = $(".selector-wrapper-1", this.$container).find("option:selected").val(), r = $(".selector-wrapper-2", this.$container).find("option:selected").val(), year = $(".selector-wrapper-3", this.$container).find("option:selected").val(), $(".selector-wrapper-3 .single-option-selector option", this.$container).each(function() {
                var bs_op = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option2 == r && data.option3 == bs_op && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), smallSet = this._getCurrentOptions(), null != (res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })) && 0 != res.available || ($(".selector-wrapper-3 .single-option-selector option", this.$container).each(function() {
                var year = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), year = $(".selector-wrapper-3", this.$container).find("option:selected").val(), $(".selector-wrapper-2 .single-option-selector option", this.$container).each(function() {
                var bs_op = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.option2 == bs_op && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), smallSet = this._getCurrentOptions(), res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })))), res;
        },
        _getVariantFromOptions2 : function(expectedTranslations) {
            if (!expectedTranslations) {
                expectedTranslations = $(".pr-selectors .pr-swatch.active").data("title");
            }
            var variants = this.product.variants;
            return _.find(variants, function(metaWindow) {
                return _.isEqual(metaWindow.title, expectedTranslations);
            });
        },
        _onSelectChange : function() {
            var variant = this._getVariantFromOptions();
            this.$container.trigger({
                type : "variantChange",
                variant : variant
            });
            if (variant) {
                this._updateMasterSelect(variant);
                this._updateImages(variant);
                this._updatePrice(variant);
                this._updateSKU(variant);
                this._updateVariant(variant);
                this.currentVariant = variant;
                if (this.enableHistoryState) {
                    this._updateHistoryState(variant);
                }
            }
        },
        _onSelectChange2 : function(event) {
            var variant = this._getVariantFromOptions2($(event.currentTarget).data("title"));
            this.$container.trigger({
                type : "variantChange",
                variant : variant
            });
            if (variant) {
                this._updateMasterSelect(variant);
                this._updateImages(variant);
                this._updatePrice(variant);
                this._updateSKU(variant);
                this._updateVariant2(variant);
                this.currentVariant = variant;
                if (this.enableHistoryState) {
                    this._updateHistoryState(variant);
                }
            }
        },
        _updateImages : function(variant) {
            var nextProps = variant.featured_image || {};
            var bufDef = this.currentVariant.featured_image || {};
            if (variant.featured_image && nextProps.src !== bufDef.src) {
                this.$container.trigger({
                    type : "variantImageChange",
                    variant : variant
                });
            }
        },
        _updatePrice : function(variant) {
            if (!(variant.price === this.currentVariant.price && variant.compare_at_price === this.currentVariant.compare_at_price)) {
                this.$container.trigger({
                    type : "variantPriceChange",
                    variant : variant
                });
            }
        },
        _updateSKU : function(variant) {
            if (variant.sku !== this.currentVariant.sku) {
                this.$container.trigger({
                    type : "variantSKUChange",
                    variant : variant
                });
            }
        },
        _updateHistoryState : function(line) {
            if (history.replaceState && line) {
                /** @type {string} */
                var url = window.location.protocol + "//" + window.location.host + window.location.pathname + "?variant=" + line.id;
                window.history.replaceState({
                    path : url
                }, "", url);
            }
        },
        _updateMasterSelect : function(variant) {
            $(this.originalSelectorId, this.$container).val(variant.id);
        },
        _firstupdateVariant : function(post) {
            var _this = this;
            /** @type {boolean} */
            var a = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch") && (a = true), post) {
                if (1 == a) {
                    var variants = this.product.variants;
                    var chart = $(".selector-wrapper", this.$container);
                    var non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("input:checked").val();
                    var Copay = $(".selector-wrapper-2", this.$container).find("input:checked").val();
                    $(".selector-wrapper-3", this.$container).find("input:checked").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var syncedAnimals = $(this).find(".swatch-element");
                        switch(t) {
                            case 1:
                                syncedAnimals.each(function() {
                                    var ok = $(this).data("value");
                                    if (null == variants.find(function(data) {
                                        return data.option1 == ok && data.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 2:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 3:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                        }
                    });
                } else {
                    variants = this.product.variants;
                    chart = $(".selector-wrapper", this.$container);
                    non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("option:selected").val();
                    Copay = $(".selector-wrapper-2", this.$container).find("option:selected").val();
                    $(".selector-wrapper-3", this.$container).find("option:selected").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var $selectOptions = $(this).find("option");
                        switch(t) {
                            case 1:
                                $selectOptions.each(function() {
                                    var requestStyleId = $(this).val();
                                    if (null == variants.find(function(data) {
                                        return data.option1 == requestStyleId && data.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 2:
                                $selectOptions.each(function() {
                                    var serious_adverse_events = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == serious_adverse_events && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 3:
                                $selectOptions.each(function() {
                                    var user_activated = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == user_activated && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                        }
                    });
                }
                if (null == post.featured_media) {
                    return;
                }
                var left = post.featured_media.id;
                $(document).ready(function() {
                    _this._switchImage(left);
                    _this._setActiveThumbnail(left);
                });
            }
        },
        _updateVariant : function(variant) {
            /** @type {boolean} */
            var e = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch") && (e = true), variant) {
                var styleName = variant.option1;
                var option2 = variant.option2;
                var numInternals = variant.option3;
                /** @type {string} */
                var n = "";
                if (this.currentVariant.option1 != styleName ? (n = styleName, "option1", $(".label-value-1", this.$container).html(n)) : this.currentVariant.option2 != option2 ? (n = option2, "option2", $(".label-value-2", this.$container).html(n)) : this.currentVariant.option3 != numInternals && (n = numInternals, "option3", $(".label-value-3", this.$container).html(n)), 1 == e) {
                    var variants = this.product.variants;
                    var chart = $(".selector-wrapper", this.$container);
                    var non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("input:checked").val();
                    var Copay = $(".selector-wrapper-2", this.$container).find("input:checked").val();
                    $(".selector-wrapper-3", this.$container).find("input:checked").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var syncedAnimals = $(this).find(".swatch-element");
                        switch(t) {
                            case 1:
                                syncedAnimals.each(function() {
                                    var ok = $(this).data("value");
                                    if (null == variants.find(function(data) {
                                        return data.option1 == ok && data.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 2:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 3:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                        }
                    });
                } else {
                    variants = this.product.variants;
                    chart = $(".selector-wrapper", this.$container);
                    non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("option:selected").val();
                    Copay = $(".selector-wrapper-2", this.$container).find("option:selected").val();
                    $(".selector-wrapper-3", this.$container).find("option:selected").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var $selectOptions = $(this).find("option");
                        switch(t) {
                            case 1:
                                $selectOptions.each(function() {
                                    var requestStyleId = $(this).val();
                                    if (null == variants.find(function(data) {
                                        return data.option1 == requestStyleId && data.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 2:
                                $selectOptions.each(function() {
                                    var serious_adverse_events = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == serious_adverse_events && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 3:
                                $selectOptions.each(function() {
                                    var user_activated = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == user_activated && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                        }
                    });
                }
            }
        },
        _updateVariant2 : function(value) {
            if (value) {
                _.map($(this.singleOptionSelector, this.$container), function(e) {
                    var a = $(e);
                    var property = a.data("index");
                    var expectedTranslations = a.val();
                    if (_.isEqual(value[property], expectedTranslations)) {
                        if (value.available) {
                            a.removeAttr("disabled");
                            a.parent().removeClass("soldout");
                            a.attr("checked", true);
                        } else {
                            a.attr("disabled", "disabled");
                            a.parent().addClass("soldout");
                            if (a[0].checked) {
                                $("[name=" + a.attr("name") + "]:first").attr("checked", true);
                            }
                        }
                    }
                });
            }
        },
        _switchImage : function(fromImg) {
            var $sharePopup = $(".product_template .product-single__photo[data-image-id='" + fromImg + "']");
            var $tipBox = $(".product_template .product-single__photo:not([data-image-id='" + fromImg + "'])");
            if ($(".product_template .product-slider").hasClass("custom")) {
                $sharePopup.removeClass("hide");
                $tipBox.addClass("hide");
            }
        },
        _setActiveThumbnail : function(sideToPad) {
            if (void 0 === sideToPad) {
                sideToPad = $(".product_template .product-single__photo:not(.hide)").data("image-id");
            }
            var $oElemDragged = $(".product_template .product-single__thumbnails-item:not(.slick-cloned)");
            var anchor = $oElemDragged.find(".product-single__thumbnail[data-thumbnail-id='" + sideToPad + "']");
            $(".product-single__thumbnail").removeClass("active-thumb").removeAttr("aria-current");
            anchor.addClass("active-thumb");
            anchor.attr("aria-current", true);
            setTimeout(function() {
                if ($oElemDragged.hasClass("slick-slide")) {
                    var artistTrack = anchor.parent().data("slick-index");
                    $(".product_template .product-single__thumbnails").slick("slickGoTo", artistTrack);
                }
            }, 300);
        }
    }), render;
}(), slate.Variants2 = function() {
    /**
     * @param {!Object} options
     * @return {undefined}
     */
    function update(options) {
        this.$container = options.$container;
        this.product = options.product;
        this.singleOptionSelector = options.singleOptionSelector;
        this.originalSelectorId = options.originalSelectorId;
        this.enableHistoryState = options.enableHistoryState;
        this.currentVariant = this._getVariantFromOptions();
        this._firstupdateVariant(this.currentVariant);
        $(this.singleOptionSelector, this.$container).on("change", this._onSelectChange.bind(this));
    }
    return update.prototype = _.assignIn({}, update.prototype, {
        _getCurrentOptions : function() {
            var entitiesToPull = _.map($(this.singleOptionSelector, this.$container), function(t) {
                var e = $(t);
                var type = e.attr("type");
                var a = {};
                return "radio" === type || "checkbox" === type ? !!e[0].checked && (a.value = e.val(), a.index = e.data("index"), a) : (a.value = e.val(), a.index = e.data("index"), a);
            });
            return entitiesToPull = _.compact(entitiesToPull);
        },
        _getVariantFromOptions : function() {
            var n;
            var r;
            var year;
            var smallSet = this._getCurrentOptions();
            var variants = this.product.variants;
            /** @type {boolean} */
            var ReloadPagePopup = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch")) {
                /** @type {boolean} */
                ReloadPagePopup = true;
            }
            var res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            });
            return null != res && 0 != res.available || (1 == ReloadPagePopup ? (n = $(".selector-wrapper-1", this.$container).find("input:checked").val(), r = $(".selector-wrapper-2", this.$container).find("input:checked").val(), year = $(".selector-wrapper-3", this.$container).find("input:checked").val(), $(".selector-wrapper-3 .swatch-element", this.$container).each(function() {
                var oldCondition = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option2 == r && data.option3 == oldCondition && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), smallSet = this._getCurrentOptions(), null != (res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })) && 0 != res.available || ($(".selector-wrapper-3 .swatch-element", this.$container).each(function() {
                var year = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), year = $(".selector-wrapper-3", this.$container).find("input:checked").val(), $(".selector-wrapper-2 .swatch-element", this.$container).each(function() {
                var oldCondition = $(this).data("value");
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.option2 == oldCondition && data.available;
                })) {
                    $(this).addClass("soldout");
                    $(this).find(":radio").prop("disabled", true);
                } else {
                    $(this).removeClass("soldout");
                    $(this).find(":radio").prop("disabled", false);
                    $(this).find(":radio").prop("checked", true);
                }
            }), smallSet = this._getCurrentOptions(), res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            }))) : (n = $(".selector-wrapper-1", this.$container).find("option:selected").val(), r = $(".selector-wrapper-2", this.$container).find("option:selected").val(), year = $(".selector-wrapper-3", this.$container).find("option:selected").val(), $(".selector-wrapper-3 .single-option-selector option", this.$container).each(function() {
                var bs_op = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option2 == r && data.option3 == bs_op && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), smallSet = this._getCurrentOptions(), null != (res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })) && 0 != res.available || ($(".selector-wrapper-3 .single-option-selector option", this.$container).each(function() {
                var year = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), year = $(".selector-wrapper-3", this.$container).find("option:selected").val(), $(".selector-wrapper-2 .single-option-selector option", this.$container).each(function() {
                var bs_op = $(this).val();
                if (null == variants.find(function(data) {
                    return data.option1 == n && data.option3 == year && data.option2 == bs_op && data.available;
                })) {
                    $(this).attr("disabled", "disabled").removeAttr("selected", "selected").prop("selected", false);
                } else {
                    $(this).removeAttr("disabled", "disabled").attr("selected", "selected").prop("selected", true);
                }
            }), smallSet = this._getCurrentOptions(), res = _.find(variants, function(value) {
                return smallSet.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            })))), res;
        },
        _onSelectChange : function() {
            var variant = this._getVariantFromOptions();
            this.$container.trigger({
                type : "variantChange",
                variant : variant
            });
            if (variant) {
                this._updateMasterSelect(variant);
                this._updateImages(variant);
                this._updatePrice(variant);
                this._updateSKU(variant);
                this._updateVariant(variant);
                this.currentVariant = variant;
                if (this.enableHistoryState) {
                    this._updateHistoryState(variant);
                }
            }
        },
        _updateImages : function(variant) {
            var nextProps = variant.featured_image || {};
            var bufDef = this.currentVariant.featured_image || {};
            if (variant.featured_image && nextProps.src !== bufDef.src) {
                this.$container.trigger({
                    type : "variantImageChange",
                    variant : variant
                });
            }
        },
        _updatePrice : function(variant) {
            if (!(variant.price === this.currentVariant.price && variant.compare_at_price === this.currentVariant.compare_at_price)) {
                this.$container.trigger({
                    type : "variantPriceChange",
                    variant : variant
                });
            }
        },
        _updateSKU : function(variant) {
            if (variant.sku !== this.currentVariant.sku) {
                this.$container.trigger({
                    type : "variantSKUChange",
                    variant : variant
                });
            }
        },
        _updateHistoryState : function(variantName) {
            if (history.replaceState && variantName) {
                /** @type {string} */
                var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({
                    path : url
                }, "", url);
            }
        },
        _updateMasterSelect : function(variant) {
            $(this.originalSelectorId, this.$container).val(variant.id);
        },
        _firstupdateVariant : function(canCreateDiscussions) {
            /** @type {boolean} */
            var e = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch") && (e = true), canCreateDiscussions) {
                if (1 == e) {
                    var variants = this.product.variants;
                    var chart = $(".selector-wrapper", this.$container);
                    var card = $(".selector-wrapper-1", this.$container).find("input:checked").val();
                    var JSON = $(".selector-wrapper-2", this.$container).find("input:checked").val();
                    $(".selector-wrapper-3", this.$container).find("input:checked").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var syncedAnimals = $(this).find(".swatch-element");
                        switch(t) {
                            case 1:
                                syncedAnimals.each(function() {
                                    var ok = $(this).data("value");
                                    if (null == variants.find(function(data) {
                                        return data.option1 == ok && data.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 2:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == card && $scope.option2 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 3:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == card && $scope.option2 == JSON && $scope.option3 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                        }
                    });
                } else {
                    variants = this.product.variants;
                    chart = $(".selector-wrapper", this.$container);
                    card = $(".selector-wrapper-1", this.$container).find("option:selected").val();
                    JSON = $(".selector-wrapper-2", this.$container).find("option:selected").val();
                    $(".selector-wrapper-3", this.$container).find("option:selected").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var $selectOptions = $(this).find("option");
                        switch(t) {
                            case 1:
                                $selectOptions.each(function() {
                                    var requestStyleId = $(this).val();
                                    if (null == variants.find(function(data) {
                                        return data.option1 == requestStyleId && data.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 2:
                                $selectOptions.each(function() {
                                    var serious_adverse_events = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == card && $scope.option2 == serious_adverse_events && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 3:
                                $selectOptions.each(function() {
                                    var horizontal_slide = $(this).val();
                                    if (null == variants.find(function(options) {
                                        return options.option1 == card && options.option2 == JSON && options.option3 == horizontal_slide && options.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                        }
                    });
                }
            }
        },
        _updateVariant : function(variant) {
            /** @type {boolean} */
            var e = false;
            if ($(".selector-wrapper-1", this.$container).hasClass("swatch") && (e = true), variant) {
                var styleName = variant.option1;
                var option2 = variant.option2;
                var numInternals = variant.option3;
                /** @type {string} */
                var n = "";
                if (this.currentVariant.option1 != styleName ? (n = styleName, "option1", $(".label-value-1", this.$container).html(n)) : this.currentVariant.option2 != option2 ? (n = option2, "option2", $(".label-value-2", this.$container).html(n)) : this.currentVariant.option3 != numInternals && (n = numInternals, "option3", $(".label-value-3", this.$container).html(n)), 1 == e) {
                    var variants = this.product.variants;
                    var chart = $(".selector-wrapper", this.$container);
                    var non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("input:checked").val();
                    var Copay = $(".selector-wrapper-2", this.$container).find("input:checked").val();
                    $(".selector-wrapper-3", this.$container).find("input:checked").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var syncedAnimals = $(this).find(".swatch-element");
                        switch(t) {
                            case 1:
                                syncedAnimals.each(function() {
                                    var ok = $(this).data("value");
                                    if (null == variants.find(function(data) {
                                        return data.option1 == ok && data.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 2:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                                break;
                            case 3:
                                syncedAnimals.each(function() {
                                    var alpha = $(this).data("value");
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == alpha && $scope.available;
                                    })) {
                                        $(this).addClass("soldout");
                                        $(this).find(":radio").prop("disabled", true);
                                    } else {
                                        $(this).removeClass("soldout");
                                        $(this).find(":radio").prop("disabled", false);
                                    }
                                });
                        }
                    });
                } else {
                    variants = this.product.variants;
                    chart = $(".selector-wrapper", this.$container);
                    non_serious_adverse_events = $(".selector-wrapper-1", this.$container).find("option:selected").val();
                    Copay = $(".selector-wrapper-2", this.$container).find("option:selected").val();
                    $(".selector-wrapper-3", this.$container).find("option:selected").val();
                    chart.each(function() {
                        var t = $(this).data("option-index");
                        var $selectOptions = $(this).find("option");
                        switch(t) {
                            case 1:
                                $selectOptions.each(function() {
                                    var requestStyleId = $(this).val();
                                    if (null == variants.find(function(data) {
                                        return data.option1 == requestStyleId && data.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 2:
                                $selectOptions.each(function() {
                                    var serious_adverse_events = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == serious_adverse_events && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                                break;
                            case 3:
                                $selectOptions.each(function() {
                                    var user_activated = $(this).val();
                                    if (null == variants.find(function($scope) {
                                        return $scope.option1 == non_serious_adverse_events && $scope.option2 == Copay && $scope.option3 == user_activated && $scope.available;
                                    })) {
                                        $(this).attr("disabled", "disabled");
                                    } else {
                                        $(this).removeAttr("disabled", "disabled");
                                    }
                                });
                        }
                    });
                }
            }
        }
    }), update;
}(), theme.Drawers = function() {
    /**
     * @param {string} id
     * @param {number} renderer
     * @param {?} data
     * @return {?}
     */
    function App(id, renderer, data) {
        var options = {
            close : ".js-drawer-close",
            open : ".js-drawer-open-" + renderer,
            openClass : "js-drawer-open",
            dirOpenClass : "js-drawer-open-" + renderer
        };
        if (this.nodes = {
            $parent : $("html").add("body"),
            $page : $("#PageContainer")
        }, this.config = $.extend(options, data), this.position = renderer, this.$drawer = $("#" + id), !this.$drawer.length) {
            return false;
        }
        /** @type {boolean} */
        this.drawerIsOpen = false;
        this.init();
    }
    return App.prototype.init = function() {
        $(this.config.open).on("click", $.proxy(this.open, this));
        this.$drawer.on("click", this.config.close, $.proxy(this.close, this));
    }, App.prototype.open = function(event) {
        /** @type {boolean} */
        var id = false;
        return event ? event.preventDefault() : id = true, event && event.stopPropagation && (event.stopPropagation(), this.$activeSource = $(event.currentTarget)), this.drawerIsOpen && !id ? this.close() : (this.$drawer.prepareTransition(), this.nodes.$parent.addClass(this.config.openClass + " " + this.config.dirOpenClass), this.drawerIsOpen = true, slate.a11y.trapFocus({
            $container : this.$drawer,
            namespace : "drawer_focus"
        }), this.config.onDrawerOpen && "function" == typeof this.config.onDrawerOpen && (id || this.config.onDrawerOpen()), this.$activeSource && this.$activeSource.attr("aria-expanded") && this.$activeSource.attr("aria-expanded", "true"), this.bindEvents(), this);
    }, App.prototype.close = function() {
        if (this.drawerIsOpen) {
            $(document.activeElement).trigger("blur");
            this.$drawer.prepareTransition();
            this.nodes.$parent.removeClass(this.config.dirOpenClass + " " + this.config.openClass);
            if (this.$activeSource && this.$activeSource.attr("aria-expanded")) {
                this.$activeSource.attr("aria-expanded", "false");
            }
            /** @type {boolean} */
            this.drawerIsOpen = false;
            slate.a11y.removeTrapFocus({
                $container : this.$drawer,
                namespace : "drawer_focus"
            });
            this.unbindEvents();
            if (this.config.onDrawerClose && "function" == typeof this.config.onDrawerClose) {
                this.config.onDrawerClose();
            }
        }
    }, App.prototype.bindEvents = function() {
        this.nodes.$parent.on("keyup.drawer", $.proxy(function(event) {
            return 27 !== event.keyCode || (this.close(), false);
        }, this));
        this.nodes.$page.on("touchmove.drawer", function() {
            return false;
        });
        this.nodes.$page.on("click.drawer", $.proxy(function() {
            return this.close(), false;
        }, this));
    }, App.prototype.unbindEvents = function() {
        this.nodes.$page.off(".drawer");
        this.nodes.$parent.off(".drawer");
    }, App;
}(), window.theme = window.theme || {}, theme.Search = function() {
    /**
     * @return {undefined}
     */
    function constructFrames() {
        callback($(i));
    }
    /**
     * @param {!Object} body
     * @return {undefined}
     */
    function callback(body) {
        body.focus();
        body[0].setSelectionRange(0, body[0].value.length);
    }
    /**
     * @return {undefined}
     */
    function callPrint() {
        $($span).focus();
    }
    /**
     * @return {undefined}
     */
    function clear() {
        this.$searchErrorMessage.addClass(hideClass);
        this.$searchResultInput.removeAttr("aria-describedby").removeAttr("aria-invalid");
    }
    /**
     * @return {undefined}
     */
    function setup() {
        this.$searchErrorMessage.removeClass(hideClass);
        this.$searchResultInput.attr("aria-describedby", "error-search-form").attr("aria-invalid", true);
    }
    /**
     * @param {?} event
     * @return {undefined}
     */
    function ready(event) {
        if (0 === this.$searchResultInput.val().trim().length) {
            if (void 0 !== event) {
                event.preventDefault();
            }
            callback(this.$searchResultInput);
            setup.call(this);
        } else {
            clear.call(this);
        }
    }
    /** @type {string} */
    var tbl = ".site-header";
    /** @type {string} */
    var $span = ".site-header__search-toggle";
    /** @type {string} */
    var arrowDiv = ".search-bar";
    /** @type {string} */
    var i = ".search-bar__input";
    /** @type {string} */
    var upCtrl = ".search-header";
    /** @type {string} */
    var cur_prev = ".search-header__input";
    /** @type {string} */
    var o = ".search-header__submit";
    /** @type {string} */
    var oe_website_sale = "#SearchResultSubmit";
    /** @type {string} */
    var c = "#SearchInput";
    /** @type {string} */
    var l = "[data-search-error-message]";
    /** @type {string} */
    var _openClass = "search--focus";
    /** @type {string} */
    var hideClass = "hide";
    /** @type {string} */
    var boardClass = "template-search";
    return {
        init : function() {
            if ($(tbl).length) {
                this.$searchResultInput = $(c);
                this.$searchErrorMessage = $(l);
                $("#PageContainer").addClass("drawer-page-content");
                $(".js-drawer-open-top").attr("aria-controls", "SearchDrawer").attr("aria-expanded", "false").attr("aria-haspopup", "dialog");
                theme.SearchDrawer = new theme.Drawers("SearchDrawer", "top", {
                    onDrawerOpen : constructFrames,
                    onDrawerClose : callPrint
                });
                if (null !== slate.utils.getParameterByName("q") && $("body").hasClass(boardClass)) {
                    ready.call(this);
                }
                $(oe_website_sale).on("click", ready.bind(this));
                $(cur_prev).add(o).on("focus blur", function() {
                    $(upCtrl).toggleClass(_openClass);
                });
                $($span).on("click", function() {
                    var windowInnerHeight = $(tbl).outerHeight();
                    /** @type {number} */
                    var orig_top = $(tbl).offset().top - windowInnerHeight;
                    $(arrowDiv).css({
                        height : windowInnerHeight + "px",
                        top : orig_top + "px"
                    });
                });
            }
        }
    };
}(), function() {
    /**
     * @param {string} e
     * @return {?}
     */
    function getLocation(e) {
        /** @type {!Element} */
        var o = document.createElement("a");
        return o.ref = e, o.hostname;
    }
    var input = $(".return-link");
    if (document.referrer && input.length && window.history.length) {
        input.one("click", function(event) {
            event.preventDefault();
            var loc = getLocation(document.referrer);
            return getLocation(window.location.href) === loc && history.back(), false;
        });
    }
}(), theme.Video = function() {
    /**
     * @param {string} i
     * @return {undefined}
     */
    function ready(i) {
        if ((declars || assigns) && i && "function" == typeof instances[i].playVideo) {
            success(i);
        }
    }
    /**
     * @param {string} i
     * @return {undefined}
     */
    function play(i) {
        if (instances[i] && "function" == typeof instances[i].pauseVideo) {
            instances[i].pauseVideo();
        }
    }
    /**
     * @param {string} id
     * @param {boolean} i
     * @return {?}
     */
    function success(id, i) {
        var item = data[id];
        var player = instances[id];
        var element = item.$videoWrapper;
        if (assigns) {
            handler(item);
        } else {
            if (i || xx) {
                return element.removeClass(state.loading), handler(item), void player.playVideo();
            }
            player.playVideo();
        }
    }
    /**
     * @param {string} force
     * @return {undefined}
     */
    function stop(force) {
        /** @type {string} */
        var nextPage = force ? state.supportsAutoplay : state.supportsNoAutoplay;
        $(document.documentElement).removeClass(state.supportsAutoplay).removeClass(state.supportsNoAutoplay).addClass(nextPage);
        if (!force) {
            /** @type {boolean} */
            assigns = true;
        }
        /** @type {boolean} */
        xx = true;
    }
    /**
     * @return {undefined}
     */
    function initObserver() {
        if (!declars) {
            if (callback()) {
                /** @type {boolean} */
                assigns = true;
            }
            if (assigns) {
                stop(false);
            }
            /** @type {boolean} */
            declars = true;
        }
    }
    /**
     * @param {!Object} data
     * @return {undefined}
     */
    function handler(data) {
        var element = data.$videoWrapper;
        var a = element.find(target);
        element.removeClass(state.loading);
        if (a.hasClass(state.userPaused)) {
            a.removeClass(state.userPaused);
        }
        if ("background" !== data.status) {
            $("#" + data.id).attr("tabindex", "0");
            if ("image_with_play" === data.type) {
                element.removeClass(state.paused).addClass(state.playing);
            }
            setTimeout(function() {
                element.find(p).focus();
            }, config.scrollAnimationDuration);
        }
    }
    /**
     * @param {!Object} self
     * @return {undefined}
     */
    function onEnd(self) {
        var _ = self.$videoWrapper;
        if ("image_with_play" === self.type) {
            if ("closed" === self.status) {
                _.removeClass(state.paused);
            } else {
                _.addClass(state.paused);
            }
        }
        _.removeClass(state.playing);
    }
    /**
     * @param {string} key
     * @return {undefined}
     */
    function update(key) {
        var self = data[key];
        var a = self.$videoWrapper;
        /** @type {string} */
        var i = [state.paused, state.playing].join(" ");
        switch(callback() && a.removeAttr("style"), $("#" + self.id).attr("tabindex", "-1"), self.status = "closed", self.type) {
            case "image_with_play":
                instances[key].stopVideo();
                onEnd(self);
                break;
            case "background":
                instances[key].mute();
                (function(index) {
                    $("#" + index).removeClass(state.videoWithImage).addClass(state.backgroundVideo);
                    data[index].$videoWrapper.addClass(state.backgroundVideoWrapper);
                    /** @type {string} */
                    data[index].status = "background";
                    fn($("#" + index));
                })(key);
        }
        a.removeClass(i);
    }
    /**
     * @param {!Object} event
     * @return {?}
     */
    function parse(event) {
        var roleModel = event.target.a ? event.target.a : event.target.f;
        return data[roleModel.id];
    }
    /**
     * @param {string} name
     * @param {boolean} e
     * @return {undefined}
     */
    function setup(name, e) {
        var elements = data[name];
        var posTop = elements.$videoWrapper.offset().top;
        var p = elements.$videoWrapper.find(permissions);
        /** @type {number} */
        var scrollHeight = 0;
        /** @type {number} */
        var dxdydust = 0;
        if (callback()) {
            elements.$videoWrapper.parent().toggleClass("page-width", !e);
        }
        if (e) {
            /** @type {number} */
            dxdydust = callback() ? $(window).width() / config.ratio : elements.$videoWrapper.width() / config.ratio;
            /** @type {number} */
            scrollHeight = ($(window).height() - dxdydust) / 2;
            elements.$videoWrapper.removeClass(state.wrapperMinHeight).animate({
                height : dxdydust
            }, 600);

        } else {
            dxdydust = callback() ? elements.$videoWrapper.data("mobile-height") : elements.$videoWrapper.data("desktop-height");
            elements.$videoWrapper.height(elements.$videoWrapper.width() / config.ratio).animate({
                height : dxdydust
            }, 600);
            setTimeout(function() {
                elements.$videoWrapper.addClass(state.wrapperMinHeight);
            }, 600);
            p.focus();
        }
    }
    /**
     * @param {undefined} i
     * @return {undefined}
     */
    function initialize(i) {
        var group = data[i];
        switch(group.$videoWrapper.addClass(state.loading), group.$videoWrapper.attr("style", "height: " + group.$videoWrapper.height() + "px"), group.status = "open", group.type) {
            case "image_with_play":
                success(i, true);
                break;
            case "background":
                !function(caseId) {
                    $("#" + caseId).removeClass(state.backgroundVideo).addClass(state.videoWithImage);
                    setTimeout(function() {
                        $("#" + caseId).removeAttr("style");
                    }, 600);
                    data[caseId].$videoWrapper.removeClass(state.backgroundVideoWrapper).addClass(state.playing);
                    /** @type {string} */
                    data[caseId].status = "open";
                }(i);
                instances[i].unMute();
                success(i, true);
        }
        setup(i, true);
        $(document).on("keydown.videoPlayer", function(event) {
            var canvas = $(document.activeElement).data("controls");
            if (event.keyCode === slate.utils.keyboardKeys.ESCAPE && canvas) {
                update(canvas);
                setup(canvas, false);
            }
        });
    }
    /**
     * @return {undefined}
     */
    function log() {
        $("." + state.backgroundVideo).each(function(canCreateDiscussions, a) {
            fn($(a));
        });
    }
    /**
     * @param {!Object} self
     * @return {undefined}
     */
    function fn(self) {
        if (i) {
            if (callback()) {
                self.removeAttr("style");
            } else {
                var t = self.closest(l);
                var width = t.width();
                var c = self.width();
                var height = t.data("desktop-height");
                if (width / config.ratio < height) {
                    /** @type {number} */
                    c = Math.ceil(height * config.ratio);
                    self.width(c).height(height).css({
                        left : (width - c) / 2,
                        top : 0
                    });
                } else {
                    /** @type {number} */
                    height = Math.ceil(width / config.ratio);
                    self.width(width).height(height).css({
                        left : 0,
                        top : (height - height) / 2
                    });
                }
                self.prepareTransition();
                t.addClass(state.loaded);
            }
        }
    }
    /**
     * @return {?}
     */
    function callback() {
        return $(window).width() < 750 || window.mobileCheck();
    }
    /**
     * @return {undefined}
     */
    function init() {
        $(document).on("click.videoPlayer", permissions, function(event) {
            initialize($(event.currentTarget).data("controls"));
        });
        $(document).on("click.videoPlayer", p, function(event) {
            var canvas = $(event.currentTarget).data("controls");
            $(event.currentTarget).blur();
            update(canvas);
            setup(canvas, false);
        });
        $(document).on("click.videoPlayer", target, function(event) {
            !function(id) {
                var $this = data[id].$videoWrapper.find(target);
                var enable = $this.hasClass(state.userPaused);
                if (enable) {
                    $this.removeClass(state.userPaused);
                    ready(id);
                } else {
                    $this.addClass(state.userPaused);
                    play(id);
                }
                $this.attr("aria-pressed", !enable);
            }($(event.currentTarget).data("controls"));
        });
        $(window).on("resize.videoPlayer", $.debounce(200, function() {
            if (i) {
                var i;
                /** @type {boolean} */
                var e = window.innerHeight === screen.height;
                if (log(), callback()) {
                    for (i in data) {
                        if (data.hasOwnProperty(i)) {
                            if (data[i].$videoWrapper.hasClass(state.playing)) {
                                if (!e) {
                                    play(i);
                                    onEnd(data[i]);
                                }
                            }
                            data[i].$videoWrapper.height($(document).width() / config.ratio);
                        }
                    }
                    stop(false);
                } else {
                    for (i in stop(true), data) {
                        if (!data[i].$videoWrapper.find("." + state.videoWithImage).length) {
                            instances[i].playVideo();
                            handler(data[i]);
                        }
                    }
                }
            }
        }));
        $(window).on("scroll.videoPlayer", $.debounce(50, function() {
            if (i) {
                var type;
                for (type in data) {
                    if (data.hasOwnProperty(type)) {
                        var self = data[type].$videoWrapper;
                        if (self.hasClass(state.playing) && (self.offset().top + .75 * self.height() < $(window).scrollTop() || self.offset().top + .25 * self.height() > $(window).scrollTop() + $(window).height())) {
                            update(type);
                            setup(type, false);
                        }
                    }
                }
            }
        }));
    }
    /**
     * @param {string} id
     * @return {undefined}
     */
    function loadVideo(id) {
        var options = $.extend({}, config, data[id]);
        options.playerVars.controls = options.controls;
        instances[id] = new YT.Player(id, options);
    }
    /** @type {boolean} */
    var xx = false;
    /** @type {boolean} */
    var declars = false;
    /** @type {boolean} */
    var assigns = false;
    /** @type {boolean} */
    var i = false;
    var data = {};
    /** @type {!Array} */
    var instances = [];
    var config = {
        ratio : 16 / 9,
        scrollAnimationDuration : 400,
        playerVars : {
            iv_load_policy : 3,
            modestbranding : 1,
            autoplay : 0,
            controls : 0,
            wmode : "opaque",
            branding : 0,
            autohide : 0,
            rel : 0
        },
        events : {
            onReady : function(event) {
                event.target.setPlaybackQuality("hd1080");
                var data = parse(event);
                initObserver();
                $("#" + data.id).attr("tabindex", "-1");
                log();
                if ("background" === data.type) {
                    event.target.mute();
                    success(data.id);
                }
                data.$videoWrapper.addClass(state.loaded);
            },
            onStateChange : function(event) {
                var data = parse(event);
                if (!("background" !== data.status || callback() || xx || event.data !== YT.PlayerState.PLAYING && event.data !== YT.PlayerState.BUFFERING)) {
                    stop(true);
                    /** @type {boolean} */
                    xx = true;
                    data.$videoWrapper.removeClass(state.loading);
                }
                switch(event.data) {
                    case YT.PlayerState.ENDED:
                        !function(data) {
                            switch(data.type) {
                                case "background":
                                    instances[data.id].seekTo(0);
                                    break;
                                case "image_with_play":
                                    update(data.id);
                                    setup(data.id, false);
                            }
                        }(data);
                        break;
                    case YT.PlayerState.PAUSED:
                        setTimeout(function() {
                            if (event.target.getPlayerState() === YT.PlayerState.PAUSED) {
                                onEnd(data);
                            }
                        }, 200);
                }
            }
        }
    };
    var state = {
        playing : "video-is-playing",
        paused : "video-is-paused",
        loading : "video-is-loading",
        loaded : "video-is-loaded",
        backgroundVideoWrapper : "video-background-wrapper",
        videoWithImage : "video--image_with_play",
        backgroundVideo : "video--background",
        userPaused : "is-paused",
        supportsAutoplay : "autoplay",
        supportsNoAutoplay : "no-autoplay",
        wrapperMinHeight : "video-section-wrapper--min-height"
    };
    /** @type {string} */
    var SELECTOR = ".video-section";
    /** @type {string} */
    var l = ".video-section-wrapper";
    /** @type {string} */
    var permissions = ".video-control__play";
    /** @type {string} */
    var p = ".video-control__close-wrapper";
    /** @type {string} */
    var target = ".video__pause";
    return {
        init : function(target) {
            if (target.length) {
                if (data[target.attr("id")] = {
                    id : target.attr("id"),
                    videoId : target.data("id"),
                    type : target.data("type"),
                    status : "image_with_play" === target.data("type") ? "closed" : "background",
                    $video : target,
                    $videoWrapper : target.closest(l),
                    $section : target.closest(SELECTOR),
                    controls : "background" === target.data("type") ? 0 : 1
                }, !i) {
                    /** @type {!Element} */
                    var youtube_script = document.createElement("script");
                    /** @type {string} */
                    youtube_script.src = "https://www.youtube.com/iframe_api";
                    /** @type {!Element} */
                    var wafCss = document.getElementsByTagName("script")[0];
                    wafCss.parentNode.insertBefore(youtube_script, wafCss);
                }
                initObserver();
            }
        },
        editorLoadVideo : function(source) {
            if (i) {
                loadVideo(source);
                init();
            }
        },
        loadVideos : function() {
            var index;
            for (index in data) {
                if (data.hasOwnProperty(index)) {
                    loadVideo(index);
                }
            }
            init();
            /** @type {boolean} */
            i = true;
        },
        playVideo : ready,
        pauseVideo : play,
        removeEvents : function() {
            $(document).off(".videoPlayer");
            $(window).off(".videoPlayer");
        }
    };
}(), window.theme = window.theme || {}, theme.FormStatus = function() {
    /**
     * @return {undefined}
     */
    function indexPageCtrl() {
        this.$statusMessage.removeAttr("tabindex");
    }
    /** @type {string} */
    var t = "[data-form-status]";
    return {
        init : function() {
            this.$statusMessage = $(t);
            if (this.$statusMessage) {
                this.$statusMessage.attr("tabindex", -1).focus();
                this.$statusMessage.on("blur", indexPageCtrl.bind(this));
            }
        }
    };
}(), function() {
    var t = $("#BlogTagFilter");
    if (t.length) {
        t.on("change", function() {
            location.href = $(this).val();
        });
    }
}(), window.theme = theme || {}, theme.customerTemplates = function() {
    /**
     * @return {undefined}
     */
    function initialize() {
        //this.$RecoverHeading = $(t);
        //this.$RecoverEmail = $(e);
        //this.$LoginHeading = $(a);
        $("#RecoverPassword").on("click", function(event) {
            event.preventDefault();

            this.$RecoverHeading.attr("tabindex", "-1").focus();
        }.bind(this));
        $("#HideRecoverPasswordLink").on("click", function(event) {
            event.preventDefault();
            $("#RecoverPasswordForm").addClass("hide");
            $("#CustomerLoginForm").removeClass("hide");
            $("#CustomerRegisterForm").removeClass("hide");
            this.$LoginHeading.attr("tabindex", "-1").focus();
        }.bind(this));
        // this.$RecoverHeading.on("blur", function() {
        //     $(this).removeAttr("tabindex");
        // });
        // this.$LoginHeading.on("blur", function() {
        //     $(this).removeAttr("tabindex");
        // });
    }
    /**
     * @return {undefined}
     */

    /** @type {string} */
    var t = "#RecoverHeading";
    /** @type {string} */
    var e = "#RecoverEmail";
    /** @type {string} */
    var a = "#LoginHeading";

}(), window.theme = window.theme || {}, theme.Cart = function() {
    /**
     * @param {?} template
     * @return {undefined}
     */
    function initialize(template) {
        this.$container = $(template);
        this.$thumbnails = $(complete, this.$container);
        this.ajaxEnabled = this.$container.data("ajax-enabled");
        if (!this.cookiesEnabled()) {
            this.$container.addClass($errorContainer);
        }
        this.$thumbnails.css("cursor", "pointer");
        this.$container.on("click", complete, this._handleThumbnailClick);
        this.$container.on("change", html, $.debounce(500, this._handleInputQty.bind(this)));
        /** @type {(MediaQueryList|null)} */
        this.mql = window.matchMedia(query);
        this.mql.addListener(this.setQuantityFormControllers.bind(this));
        this.setQuantityFormControllers();
        if (this.ajaxEnabled) {
            this.$container.on("change", searchSelector, this._onNoteChange.bind(this));
            this.$container.on("click", m, this._onRemoveItem.bind(this));
            this._setupCartTemplates();
        }
        this._cartTermsConditions();
    }
    /** @type {string} */
    var delete_behavior_form = "[data-cart-count]";
    /** @type {string} */
    var add_development_dialog = "[data-cart-count-bubble]";
    /** @type {string} */
    var lastShowItem = "[data-cart-discount]";
    /** @type {string} */
    var i = "[data-cart-discount-title]";
    /** @type {string} */
    var l = "[data-cart-discount-amount]";
    /** @type {string} */
    var vcell2 = "[data-cart-discount-wrapper]";
    /** @type {string} */
    var congratsMessageID = "[data-cart-error-message]";
    /** @type {string} */
    var notificationButtonID = "[data-cart-error-message-wrapper]";
    /** @type {string} */
    var n = "[data-cart-item]";
    /** @type {string} */
    var p = "[data-cart-item-details]";
    /** @type {string} */
    var tableContainer = "[data-cart-item-discount]";
    /** @type {string} */
    var layer0 = "[data-cart-item-discounted-price-group]";
    /** @type {string} */
    var j = "[data-cart-item-discount-title]";
    /** @type {string} */
    var SEARCH_RESULTS = "[data-cart-item-discount-amount]";
    /** @type {string} */
    var y = "[data-cart-item-discount-list]";
    /** @type {string} */
    var questionWrapper = "[data-cart-item-final-price]";
    /** @type {string} */
    var ele = "[data-cart-item-image]";
    /** @type {string} */
    var contentSelector = "[data-cart-item-line-price]";
    /** @type {string} */
    var videoArea = "[data-cart-item-original-price]";
    /** @type {string} */
    var ol_el = "[data-cart-item-price]";
    /** @type {string} */
    var inp = "[data-cart-item-price-list]";
    /** @type {string} */
    var conversationBuffer = "[data-cart-item-property]";
    /** @type {string} */
    var k = "[data-cart-item-property-name]";
    /** @type {string} */
    var b = "[data-cart-item-property-value]";
    /** @type {string} */
    var rand = "[data-cart-item-regular-price-group]";
    /** @type {string} */
    var capDiv = "[data-cart-item-regular-price]";
    /** @type {string} */
    var classificationSelector = "[data-cart-item-title]";
    /** @type {string} */
    var renderBtnNode = "[data-cart-item-option]";
    /** @type {string} */
    var durationDOM = "[data-cart-line-items]";
    /** @type {string} */
    var searchSelector = "[data-cart-notes]";
    /** @type {string} */
    var populationSelector = "[data-cart-quantity-error-message]";
    /** @type {string} */
    var updateThisPrompt = "[data-cart-quantity-error-message-wrapper]";
    /** @type {string} */
    var m = "[data-cart-remove]";
    /** @type {string} */
    var tspan = "[data-cart-status]";
    /** @type {string} */
    var vcell = "[data-cart-subtotal]";
    /** @type {string} */
    var sampleTest1 = "[data-cart-table-cell]";
    /** @type {string} */
    var QOR_TABLE = "[data-cart-wrapper]";
    /** @type {string} */
    var navPrmySel = "[data-empty-page-content]";
    /** @type {string} */
    var remoteHostsUrl = "[data-quantity-input]";
    /** @type {string} */
    var arrowDiv = "[data-quantity-input-mobile]";
    /** @type {string} */
    var type_id = "[data-quantity-input-desktop]";
    /** @type {string} */
    var psSlideContent = "[data-quantity-label-mobile]";
    /** @type {string} */
    var customPlayerControls = "[data-quantity-label-desktop]";
    /** @type {string} */
    var html = "[data-quantity-input]";
    /** @type {string} */
    var complete = ".cart__image";
    /** @type {string} */
    var beforeCell = "[data-unit-price]";
    /** @type {string} */
    var fundsSelector = "[data-unit-price-base-unit]";
    /** @type {string} */
    var dot = "[data-unit-price-group]";
    /** @type {string} */
    var $errorContainer = "cart--no-cookies";
    /** @type {string} */
    var fixedClass = "cart__removed-product";
    /** @type {string} */
    var element = "hide";
    /** @type {string} */
    var hideClass = "input--error";
    /** @type {string} */
    var comp = "data-cart-item-index";
    /** @type {string} */
    var values = "data-cart-item-key";
    /** @type {string} */
    var dataBindingsRegExp = "data-cart-item-quantity";
    /** @type {string} */
    var style = "data-cart-item-title";
    /** @type {string} */
    var tile = "data-cart-item-url";
    /** @type {string} */
    var unitRange = "data-quantity-item";
    theme.breakpoints = theme.breakpoints || {};
    if (isNaN(theme.breakpoints.medium) || void 0 === theme.breakpoints.medium) {
        /** @type {number} */
        theme.breakpoints.medium = 768;
    }
    /** @type {string} */
    var query = "(min-width: " + theme.breakpoints.medium + "px)";
    return initialize.prototype = _.assignIn({}, initialize.prototype, {
        checkNeedToConvertCurrency : function() {
            return window.show_multiple_currencies && Currency.currentCurrency != shopCurrency || window.show_auto_currency;
        },
        _showErrorMessage : function(data, type) {
            $("[data-error-message]", type).html(data);
            $("[data-error-message-wrapper]", type).removeClass("product-form__error-message-wrapper--hidden").attr("aria-hidden", true).removeAttr("aria-hidden");
        },
        _hideErrorMessage : function(cElm) {
            $("[data-error-message-wrapper]", cElm).addClass("product-form__error-message-wrapper--hidden");
        },
        _setupCartTemplates : function() {
            this.$itemTemplate = $(n, this.$container).first().clone();
            this.$itemDiscountTemplate = $(tableContainer, this.$itemTemplate).clone();
            this.$itemOptionTemplate = $(renderBtnNode, this.$itemTemplate).clone();
            this.$itemPropertyTemplate = $(conversationBuffer, this.$itemTemplate).clone();
            this.$itemPriceListTemplate = $(inp, this.$itemTemplate).clone();
            this.$itemLinePriceTemplate = $(contentSelector, this.$itemTemplate).clone();
            this.$cartDiscountTemplate = $(lastShowItem, this.$container).clone();
            if (this.checkNeedToConvertCurrency()) {
                Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
            }
        },
        _handleInputQty : function(jEvent) {
            var e = $(jEvent.target);
            var toClone = e.data("quantity-item");
            var i = e.closest(n);
            var that = $("[data-quantity-item=" + toClone + "]");
            /** @type {number} */
            var value = parseInt(e.val());
            /** @type {boolean} */
            var o = !(value < 0 || isNaN(value));
            that.val(value);
            this._hideCartError();
            this._hideQuantityErrorMessage();
            if (o) {
                if (o && this.ajaxEnabled) {
                    this._updateItemQuantity(toClone, i, that, value);
                }
            } else {
                this._showQuantityErrorMessages(i);
            }
        },
        _updateItemQuantity : function(toClone, clone, func, value) {
            var uri = clone.attr(values);
            var str = clone.attr(comp);
            var options = {
                url : "/cart/change.js",
                data : {
                    quantity : value,
                    line : str
                },
                dataType : "json",
                async : false
            };
            $.post(options).done(function(self) {
                if (0 === self.item_count) {
                    this._emptyCart();
                } else {
                    if (this._createCart(self), 0 === value) {
                        this._showRemoveMessage(clone.clone());
                    } else {
                        var parent = $('[data-cart-item-key="' + uri + '"]');
                        var item = this.getItem(uri, self);
                        if (value != item.quantity) {
                            this._showQuantityErrorMessages2(item.quantity, parent);
                        }
                        $(remoteHostsUrl, parent).focus();
                        this._updateLiveRegion(item);
                    }
                }
                this._setCartCountBubble(self.item_count);
            }.bind(this)).fail(function() {
                this._showCartError(func);
            }.bind(this));
        },
        getItem : function(value, api) {
            return api.items.find(function(n) {
                return n.key === value;
            });
        },
        _liveRegionText : function(data) {
            /** @type {string} */
            var parseCode = theme.strings.update + ": [QuantityLabel]: [Quantity], [Regular] [$$] [DiscountedPrice] [$]. [PriceInformation]";
            /** @type {string} */
            parseCode = parseCode.replace("[QuantityLabel]", theme.strings.quantity).replace("[Quantity]", data.quantity);
            /** @type {string} */
            var q = "";
            var sizeDiv1 = theme.Currency.formatMoney(data.original_line_price, theme.moneyFormat);
            /** @type {string} */
            var b = "";
            /** @type {string} */
            var customAPIReturnString = "";
            /** @type {string} */
            var delim = "";
            return data.original_line_price > data.final_line_price && (q = theme.strings.regularTotal, b = theme.strings.discountedTotal, customAPIReturnString = theme.Currency.formatMoney(data.final_line_price, theme.moneyFormat), delim = theme.strings.priceColumn), parseCode = parseCode.replace("[Regular]", q).replace("[$$]", sizeDiv1).replace("[DiscountedPrice]", b).replace("[$]", customAPIReturnString).replace("[PriceInformation]", delim).trim();
        },
        _updateLiveRegion : function(value) {
            var span = $(tspan);
            span.html(this._liveRegionText(value)).attr("aria-hidden", false);
            setTimeout(function() {
                span.attr("aria-hidden", true);
            }, 1E3);
        },
        _createCart : function(data) {
            var e = this._createCartDiscountList(data);
            $(navPrmySel).addClass(element);
            $(QOR_TABLE).removeClass(element);
            $(durationDOM, this.$container).html(this._createLineItemList(data));
            this.setQuantityFormControllers();
            $(searchSelector, this.$container).val(data.note);
            if (0 === e.length) {
                $(vcell2, this.$container).html("").addClass(element);
            } else {
                $(vcell2, this.$container).html(e).removeClass(element);
            }
            $(vcell, this.$container).html(theme.Currency.formatMoney(data.total_price, theme.moneyFormatWithCurrency));
            if (this.checkNeedToConvertCurrency()) {
                Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
            }
        },
        _createCartDiscountList : function(t) {
            return $.map(t.cart_level_discount_applications, function(data) {
                var e = this.$cartDiscountTemplate.clone();
                return e.find(i).text(data.title), e.find(l).html(theme.Currency.formatMoney(data.total_allocated_amount, theme.moneyFormat)), e[0];
            }.bind(this));
        },
        _createLineItemList : function(t) {
            return $.map(t.items, function(item, cX1) {
                var a = this.$itemTemplate.clone();
                var keyCell = this.$itemPriceListTemplate.clone();
                this._setLineItemAttributes(a, item, cX1);
                this._setLineItemImage(a, item.featured_image);
                $(classificationSelector, a).text(item.product_title).attr("href", item.url);
                var uboard = this._createProductDetailsList(item.product_has_only_default_variant, item.options_with_values, item.properties);
                this._setProductDetailsList(a, uboard);
                this._setItemRemove(a, item.title);
                keyCell.html(this._createItemPrice(item.original_price, item.final_price, this.$itemPriceListTemplate));
                if (item.unit_price_measurement) {
                    keyCell.append(this._createUnitPrice(item.unit_price, item.unit_price_measurement, this.$itemPriceListTemplate));
                }
                this._setItemPrice(a, keyCell);
                var n = this._createItemDiscountList(item);
                this._setItemDiscountList(a, n);
                this._setQuantityInputs(a, item, cX1);
                var nberr = this._createItemPrice(item.original_line_price, item.final_line_price, this.$itemLinePriceTemplate);
                return this._setItemLinePrice(a, nberr), a[0];
            }.bind(this));
        },
        _setLineItemAttributes : function(t, i, a) {
            t.attr(values, i.key).attr(tile, i.url).attr(style, i.title).attr(comp, a + 1).attr(dataBindingsRegExp, i.quantity);
        },
        _setLineItemImage : function(pos, opts) {
            var $ele = $(ele, pos);
            var cleanElemFragments = null !== opts.url ? theme.Images.getSizedImageUrl(opts.url, "x190") : null;
            if (cleanElemFragments) {
                $ele.attr("alt", opts.alt).attr("src", cleanElemFragments).removeClass(element);
            } else {
                $ele.remove();
            }
        },
        _setProductDetailsList : function(t, e) {
            var d = $(p, t);
            if (0 === e.length) {
                d.addClass(element).text("");
            } else {
                d.removeClass(element).html(e);
            }
        },
        _setItemPrice : function(t, i) {
            $(ol_el, t).html(i);
        },
        _setItemDiscountList : function(t, c) {
            var a = $(y, t);
            if (0 === c.length) {
                a.html("").addClass(element);
            } else {
                a.html(c).removeClass(element);
            }
        },
        _setItemRemove : function(category, name) {
            $(m, category).attr("aria-label", theme.strings.removeLabel.replace("[product]", name));
        },
        _setQuantityInputs : function(d, l, a) {
            $(arrowDiv, d).attr("id", "updates_" + l.key).attr(unitRange, a + 1).val(l.quantity);
            $(type_id, d).attr("id", "updates_large_" + l.key).attr(unitRange, a + 1).val(l.quantity);
            $(psSlideContent, d).attr("for", "updates_" + l.key);
            $(customPlayerControls, d).attr("for", "updates_large_" + l.key);
        },
        setQuantityFormControllers : function() {
            if (this.mql.matches) {
                $(type_id).attr("name", "updates[]");
                $(arrowDiv).removeAttr("name");
            } else {
                $(arrowDiv).attr("name", "updates[]");
                $(type_id).removeAttr("name");
            }
        },
        _setItemLinePrice : function(t, e) {
            $(contentSelector, t).html(e);
        },
        _createProductDetailsList : function(clickRepeater, e, id) {
            /** @type {!Array} */
            var result = [];
            return clickRepeater || (result = result.concat(this._getOptionList(e))), null !== id && 0 !== Object.keys(id).length && (result = result.concat(this._getPropertyList(id))), result;
        },
        _getOptionList : function(fn) {
            return $.map(fn, function(n) {
                var title = this.$itemOptionTemplate.clone();
                return title.text(n.value).removeClass(element), title[0];
            }.bind(this));
        },
        _getPropertyList : function(s) {
            /** @type {!Array} */
            var e = null !== s ? Object.entries(s) : [];
            return $.map(e, function(cap) {
                var e = this.$itemPropertyTemplate.clone();
                if ("_" !== cap[0].charAt(0) && 0 !== cap[1].length) {
                    return e.find(k).text(cap[0]), -1 === cap[0].indexOf("/uploads/") ? e.find(b).text(": " + cap[1]) : e.find(b).html(': <a href="' + cap[1] + '"> ' + cap[1].split("/").pop() + "</a>"), e.removeClass(element), e[0];
                }
            }.bind(this));
        },
        _createItemPrice : function(value, current, context) {
            if (value !== current) {
                var a = $(layer0, context).clone();
                return $(videoArea, a).html(theme.Currency.formatMoney(value, theme.moneyFormat)), $(questionWrapper, a).html(theme.Currency.formatMoney(current, theme.moneyFormat)), a.removeClass(element), a[0];
            }
            var emptyContainer = $(rand, context).clone();
            return $(capDiv, emptyContainer).html(theme.Currency.formatMoney(value, theme.moneyFormat)), emptyContainer.removeClass(element), emptyContainer[0];
        },
        _createUnitPrice : function(text, branch, context) {
            var t = $(dot, context).clone();
            var geoJSON_str = (1 !== branch.reference_value ? branch.reference_value : "") + branch.reference_unit;
            return $(fundsSelector, t).text(geoJSON_str), $(beforeCell, t).html(theme.Currency.formatMoney(text, theme.moneyFormat)), t.removeClass(element), t[0];
        },
        _createItemDiscountList : function(t) {
            return $.map(t.line_level_discount_allocations, function(data) {
                var canvasLayersManager = this.$itemDiscountTemplate.clone();
                return canvasLayersManager.find(j).text(data.discount_application.title), canvasLayersManager.find(SEARCH_RESULTS).html(theme.Currency.formatMoney(data.amount, theme.moneyFormat)), canvasLayersManager[0];
            }.bind(this));
        },
        _showQuantityErrorMessages : function(t) {
            $(populationSelector, t).text(theme.strings.quantityMinimumMessage);
            $(updateThisPrompt, t).removeClass(element);
            $(html, t).addClass(hideClass).focus();
        },
        _showQuantityErrorMessages2 : function(data, elem) {
            $(populationSelector, elem).text(theme.strings.cartErrorMaximum.replace("[quantity]", data));
            $(updateThisPrompt, elem).removeClass(element);
            $(html, elem).addClass(hideClass).focus();
        },
        _hideQuantityErrorMessage : function() {
            var $moderator = $(updateThisPrompt).addClass(element);
            $(populationSelector, $moderator).text("");
            $(html, this.$container).removeClass(hideClass);
        },
        _handleThumbnailClick : function(jEvent) {
            var downloadHref = $(jEvent.target).closest(n).data("cart-item-url");
            window.location.href = downloadHref;
        },
        _onNoteChange : function(event) {
            var noteText = event.currentTarget.value;
            this._hideCartError();
            this._hideQuantityErrorMessage();
            var options = {
                url : "/cart/update.js",
                data : {
                    note : noteText
                },
                dataType : "json"
            };
            $.post(options).fail(function() {
                this._showCartError(event.currentTarget);
            }.bind(this));
        },
        _showCartError : function(val) {
            $(congratsMessageID).text(theme.strings.cartError);
            $(notificationButtonID).removeClass(element);
            val.focus();
        },
        _hideCartError : function() {
            $(notificationButtonID).addClass(element);
            $(congratsMessageID).text("");
        },
        _onRemoveItem : function(event) {
            event.preventDefault();
            var state = $(event.target).closest(n);
            var str = state.attr(comp);
            this._hideCartError();
            var options = {
                url : "/cart/change.js",
                data : {
                    quantity : 0,
                    line : str
                },
                dataType : "json"
            };
            $.post(options).done(function(result) {
                if (0 === result.item_count) {
                    this._emptyCart();
                } else {
                    this._createCart(result);
                    this._showRemoveMessage(state.clone());
                }
                this._setCartCountBubble(result.item_count);
            }.bind(this)).fail(function() {
                this._showCartError(null);
            }.bind(this));
        },
        _showRemoveMessage : function(todo) {
            var item;
            var a = todo.data("cart-item-index");
            var target = this._getRemoveMessage(todo);
            if (a - 1 == 0) {
                item = $('[data-cart-item-index="1"]', this.$container);
                $(target).insertBefore(item);
            } else {
                item = $('[data-cart-item-index="' + (a - 1) + '"]', this.$container);
                target.insertAfter(item);
            }
            target.focus();
        },
        _getRemoveMessage : function(self) {
            var cleanVal = this._formatRemoveMessage(self);
            var a = $(sampleTest1, self).clone();
            return a.removeClass().addClass(fixedClass).attr("colspan", "4").html(cleanVal), self.attr("role", "alert").html(a).attr("tabindex", "-1"), self;
        },
        _formatRemoveMessage : function(el) {
            var rxFn = el.data("cart-item-quantity");
            var objectsThere = el.attr(tile);
            var $sections = el.attr(style);
            return theme.strings.removedItemMessage.replace("[quantity]", rxFn).replace("[link]", '<a href="' + objectsThere + '" class="text-link text-link--accent">' + $sections + "</a>");
        },
        _setCartCountBubble : function(usersLayoutTemplate) {
            this.$cartCountBubble = this.$cartCountBubble || $(add_development_dialog);
            this.$cartCount = this.$cartCount || $(delete_behavior_form);
            if (usersLayoutTemplate > 0) {
                this.$cartCountBubble.removeClass(element);
                this.$cartCount.html(usersLayoutTemplate);
            } else {
                this.$cartCountBubble.addClass(element);
                this.$cartCount.html("0");
            }
        },
        _emptyCart : function() {
            this.$emptyPageContent = this.$emptyPageContent || $(navPrmySel, this.$container);
            this.$cartWrapper = this.$cartWrapper || $(QOR_TABLE, this.$container);
            $(navPrmySel).removeClass(element);
            $(QOR_TABLE).addClass(element);
        },
        _cartTermsConditions : function() {
        },
        cookiesEnabled : function() {
            /** @type {boolean} */
            var realMobileConnections = navigator.cookieEnabled;
            return realMobileConnections || (document.cookie = "testcookie", realMobileConnections = -1 !== document.cookie.indexOf("testcookie")), realMobileConnections;
        }
    }), initialize;




}(), theme.product_sticky_atc = {
    init : function() {
        var $Divs;
        var popoverElement;
        !function() {
            var $sharepreview = $("[data-sticky-add-to-cart]");
            var $target = $sharepreview.find(".pr-active");
            var nav_target = $sharepreview.find(".pr-swatch");
            var i = $(".product-form__variants option:selected").val();
            var $drop_zone_ = $sharepreview.find('.pr-swatch[data-value="' + i + '"]');
            var formattedChosenQuestion = $drop_zone_.html();
            $target.html(formattedChosenQuestion);
            $drop_zone_.addClass("active");
            var testFilenames = $drop_zone_.data("img");
            $(".sticky-add-to-cart .product-on-cart .product-image img").attr({
                src : testFilenames
            });
            $(".selector-wrapper").change(function() {
                var t;
                var val = $(".product-form__variants").val();
                if ($(".sticky_form .pr-selectors li").each(function(canCreateDiscussions) {
                    if ($(this).find("a").data("value") == val) {
                        $(this).find("a").addClass("active");
                        t = $(this).find("a").data("img");
                    } else {
                        $(this).find("a").removeClass("active");
                    }
                }), null != t && $(".sticky-add-to-cart .product-on-cart .product-image img").attr({
                    src : t
                }), nav_target.hasClass("active")) {
                    var formattedChosenQuestion = $(".sticky_form .pr-swatch.active").html();
                    $(".sticky_form .action input[type=hidden]").val(val);
                    $target.html(formattedChosenQuestion);
                    $target.attr("data-value", val);
                }
            });
        }();
        $Divs = $("[data-sticky-add-to-cart]");
        popoverElement = $Divs.find(".pr-active");
        $Divs.find(".pr-swatch");
        popoverElement.off("click.showListVariant").on("click.showListVariant", function(event) {
            event.preventDefault();
            $Divs.toggleClass("open-sticky");
        });
        $(document).off("click.hideVariantsOption").on("click.hideVariantsOption", function(e) {
            if (!(popoverElement.is(e.target) || 0 !== popoverElement.has(e.target).length)) {
                $Divs.removeClass("open-sticky");
            }
        });
        (function() {
            var verticalNavigation = $("[data-sticky-add-to-cart]");
            var aspan = verticalNavigation.find(".pr-active");
            var a = verticalNavigation.find(".pr-swatch");
            a.on("click", function(i) {
                var r = $(this);
                var geoJSON_str = r.text();
                var attr = r.data("value");
                var testFilenames = (r.data("title"), r.data("img"));
                return a.removeClass("active"), r.addClass("active"), verticalNavigation.toggleClass("open-sticky"), verticalNavigation.find(".action-wrapper input[type=hidden]").val(attr), aspan.attr("data-value", attr).text(geoJSON_str), r.hasClass("sold-out") ? verticalNavigation.find(".btn-sticky-add-to-cart").val(theme.strings.soldOut).addClass("disabled").attr("disabled", "disabled") : verticalNavigation.find(".btn-sticky-add-to-cart").removeClass("disabled").removeAttr("disabled").val(theme.strings.addToCart),
                    $(".sticky-add-to-cart .product-on-cart .product-image img").attr({
                        src : testFilenames
                    }), false;
            });
        })();
        $(document).on("click", "[data-sticky-btn-addToCart]", function(event) {
            event.preventDefault();
            if ($("[data-product-form] [data-add-to-cart]").length) {
                $("[data-product-form] [data-add-to-cart]").click();
            }
        });
        (function() {
            if ($("[data-sticky-add-to-cart]").length) {
                var t = $("[data-add-to-cart]").offset().top + $("[data-add-to-cart]").height();
                $(window).scroll(function() {
                    if ($(this).scrollTop() > t) {
                        $("body").addClass("show_sticky");
                    } else {
                        $("body").removeClass("show_sticky");
                    }
                });
            }
        })();
    }
}, theme.products_frequently_by_together = function() {
    /**
     * @return {undefined}
     */
    function show() {
        var clonePaths = container.find(".fbt-item.isChecked");
        var item = $(".products-grouped-action .bundle-price");
        var slicesText = $(".products-grouped-action .old-price");
        var saturation = ($(".products-grouped-action .price-item--regular.price"), item.data("discount-rate"));
        /** @type {number} */
        var value = 0;
        $(".fbt-item.isChecked").length;
        $(".fbt-item").length;
        clonePaths.each(function() {
            /** @type {number} */
            var originalValue = parseFloat($(this).find("[data-fbt-price-change]").attr("data-price"));
            if (originalValue) {
                value = value + originalValue;
            }
        });
        if ($(a).length == clonePaths.length) {
            /** @type {boolean} */
            window.bundleMatch = true;
            slicesText.html(theme.Currency.formatMoney(value, theme.moneyFormat)).show();
            item.html(theme.Currency.formatMoney(value * (1 - saturation), theme.moneyFormat));
        } else {
            /** @type {boolean} */
            window.bundleMatch = false;
            slicesText.html("").hide();
            item.html(theme.Currency.formatMoney(value, theme.moneyFormat));
        }
        if (window.show_multiple_currencies && Currency.currentCurrency != shopCurrency || window.show_auto_currency) {
            Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
        }
    }
    /**
     * @param {string} location
     * @return {?}
     */
    function get(location) {
        var html = function(endLineA) {
            var paramsArrayMaxLength = container.find('[data-bundle-product-id="' + endLineA + '"]');
            var todays = _.map($(".single-option-selector-frequently", paramsArrayMaxLength), function(t) {
                var e = $(t);
                var type = e.attr("type");
                var a = {};
                return "radio" === type || "checkbox" === type ? !!e[0].checked && (a.value = e.val(), a.index = e.data("index"), a) : (a.value = e.val(), a.index = e.data("index"), a);
            });
            return _.compact(todays);
        }(location);
        var result = window.productVariants[location];
        if (result) {
            return _.find(result, function(value) {
                return html.every(function($scope) {
                    return _.isEqual(value[$scope.index], $scope.value);
                });
            });
        }
    }
    /**
     * @return {undefined}
     */
    function addListeners() {
        // $(".single-option-selector-frequently", this.$bundleContainer).on("change", function(event) {
        //     const value = $(event.currentTarget).closest("[data-bundle-product-id]").data("bundle-product-id");
        //     const removeCritText = $(event.currentTarget).data("index");
        //     const expectedTranslationJson = $(event.currentTarget).val();
        //     if (value) {
        //         var data = window.productVariants[value];
        //         /** @type {number} */
        //         var i = 0;
        //         for (; i < data.length; i++) {
        //             if (data[i].id == container.find("#ProductSelect-" + value).val()) {
        //                 post = data[i];
        //             }
        //         }
        //         var res = get(value);
        //         if (on(), !res) {
        //             return;
        //         }
        //         !function(selected, result) {
        //             container.find("#ProductSelect-" + selected).val(result.id).trigger("change");
        //         }(value, res);
        //         (function(lineStringProperty, item) {
        //             var node = item.featured_image || {};
        //             var media = post.featured_image || {};
        //             if (item.featured_image && node.src !== media.src) {
        //                 container.find('[data-bundle-product-id="' + lineStringProperty + '"] img').attr("src", node.src);
        //             }
        //         })(value, res);
        //         (function(lineStringProperty, item) {
        //             if (item.price !== post.price || item.compare_at_price !== post.compare_at_price) {
        //                 var options = $(".product-price", container.find('[data-bundle-product-id="' + lineStringProperty + '"]'));
        //                 var r = $(".price-item--regular", options);
        //                 var n = $(".price-item--sale", options);
        //                 var o = $("[data-unit-price]", options);
        //                 var $cell = $("[data-unit-price-base-unit]", options);
        //                 options.removeClass(b).removeClass(v).removeClass(cell).removeAttr("aria-hidden");
        //                 if (item) {
        //                     if (item.compare_at_price > item.price) {
        //                         r.html(theme.Currency.formatMoney(item.compare_at_price, theme.moneyFormat));
        //                         n.html(theme.Currency.formatMoney(item.price, theme.moneyFormat));
        //                         options.addClass(v);
        //                     } else {
        //                         r.html(theme.Currency.formatMoney(item.price, theme.moneyFormat));
        //                         n.html("");
        //                     }
        //                     $("[data-fbt-price-change]", options).attr("data-price", item.price);
        //                     if (item.unit_price) {
        //                         o.html(theme.Currency.formatMoney(item.unit_price, theme.moneyFormat));
        //                         $cell.html(function(demoItem) {
        //                             return 1 === demoItem.unit_price_measurement.reference_value ? demoItem.unit_price_measurement.reference_unit : demoItem.unit_price_measurement.reference_value + demoItem.unit_price_measurement.reference_unit;
        //                         }(item));
        //                         options.addClass(cell);
        //                     }
        //                 } else {
        //                     options.addClass(b).attr("aria-hidden", true);
        //                 }
        //             }
        //         })(value, res);
        //         (function(name, value, key, expectedTranslations) {
        //             if (value && name) {
        //                 var n = window.productVariants[name];
        //                 var paramsArrayMaxLength = container.find('[data-bundle-product-id="' + name + '"]');
        //                 _.map($(".single-option-selector-frequently", paramsArrayMaxLength), function(t) {
        //                     var e = $(t);
        //                     var i = e.data("index");
        //                     if (i != key) {
        //                         _.find(n, function(a) {
        //                             if (_.isEqual(a[key], expectedTranslations) && _.isEqual(a[i], e.val())) {
        //                                 return a.available ? (e.removeAttr("disabled"), e.parent().removeClass("soldout")) : (e.attr("disabled", "disabled"), e.parent().addClass("soldout"), e[0].checked && $("[name=" + e.attr("name") + "]:first").attr("checked", true)), false;
        //                             }
        //                         });
        //                     }
        //                 });
        //             }
        //         })(value, res, removeCritText, expectedTranslationJson);
        //         show();
        //     }
        // });
    }
    /**
     * @param {boolean} isAuth
     * @return {undefined}
     */
    function open(isAuth) {
        if (isAuth) {
            wrapper.attr("aria-disabled", true);
            $el.addClass(hideClass);
            button.removeClass(hideClass);
            label.attr("aria-hidden", false);
        } else {
            wrapper.removeAttr("aria-disabled");
            $el.removeClass(hideClass);
            button.addClass(hideClass);
            label.attr("aria-hidden", true);
        }
    }
    /**
     * @return {undefined}
     */
    function on() {
        $item.addClass(HAS_CHILDREN_CLASS);
    }


    /**
     * @param {!Object} evt
     * @return {undefined}
     */
    function callback(evt) {
        /** @type {boolean} */
        var t = 0 === evt.detail;
        this.$cartPopupWrapper.prepareTransition().addClass(this.classes.cartPopupWrapperHidden);
        setTimeout(function() {
            $("[data-cart-popup-frequently-wrapper] .cart-popup-content").html("");
        }, 500);
        slate.a11y.removeTrapFocus({
            $container : this.$cartPopupWrapper,
            namespace : "cartPopupFocus"
        });
        if (t) {
            this.$previouslyFocusedElement[0].focus();
        }
        this.$cartPopupWrapper.off("keyup");
        this.$cartPopupClose.off("click");
        this.$cartPopupDismiss.off("click");
        $("body").off("click");
    }
    /**
     * @param {!Object} field
     * @return {undefined}
     */
    function getUnsavedValue(field) {
        var $target = $(field.target);
        if (!($target[0] === this.$cartPopupWrapper[0] || $target.parents(this.selectors.cartPopup).length)) {
            callback(field);
        }
    }
    $("[data-bundle-images-slider]");
    var item = $(".products-grouped-action .bundle-price");
    var element = $(".products-grouped-info[data-slick]");
    /** @type {string} */
    var a = ".fbt-checkbox label";
    var container = $(".frequently-bought-together-block");
    var $existing_results = $("[data-cart-count]");
    var absoluteUrlImg = $("[data-cart-count-bubble]");
    var relativeUrlImg = $("[data-cart-popup-frequently]");
    var s = ($("[data-cart-popup-cart-quantity]"), $("[data-cart-popup-close-frequently]"));
    var relativeUrlWithSlashImg = $("[data-cart-popup-dismiss-frequently]");
    var l = ($("[data-cart-popup-image]"), $("[data-cart-popup-image-wrapper]"), $("[data-cart-popup-image-placeholder]"), $("[data-cart-popup-product-details]"), $("[data-cart-popup-quantity]"));
    var $gBCRBottom = $("[data-cart-popup-quantity-label]");
    var $music = $("[data-cart-popup-title]");
    var $realtime = $("[data-cart-popup-frequently-wrapper]");
    var wrapper = $("[data-bundle-addtocart]", container);
    var $el = $("[data-add-to-cart-text]", container);
    /** @type {string} */
    var hideClass = "hide";
    /** @type {string} */
    var v = "price--on-sale";
    /** @type {string} */
    var cell = "price--unit-available";
    /** @type {string} */
    var b = "price--unavailable";
    /** @type {string} */
    var HAS_CHILDREN_CLASS = "product-form__error-message-wrapper--hidden";
    var $item = $("[data-error-message-wrapper]", container);
    var button = $("[data-loader]", wrapper);
    var label = $("[data-loader-status]", container);
    var post = {};
    return {
        init : function() {
            if (element.length) {
                if (!element.hasClass("slick-slider")) {
                    element.slick();
                }
            }
            $(document).on("click", ".fbt-toogle-options", function(event) {
                event.preventDefault();
                $(this).parents(".product-content").next().slideToggle();
            });
            $(document).on("click", ".close-options", function(event) {
                event.preventDefault();
                $(this).parent().slideToggle();
            });
            (function() {
                if (item.length > 0) {
                    /** @type {number} */
                    var your_items = 100 * item.data("discount-rate");
                    var a = $(".products-grouped-action .discount-text");
                    if (a.length > 0) {
                        a.each(function() {
                            $(this).text($(this).text().replace("[discount]", your_items));
                        });
                    }
                }
            })();
            $(document).on("click", a, function(event) {
                event.preventDefault();
                var socialButton = $(this);
                var $realtime = socialButton.prev();
                var i = socialButton.closest(".fbt-item").data("bundle-product-id");
                if ($realtime.prop("checked")) {
                    $realtime.prop("checked", false);
                    $('[data-bundle-product-id="' + i + '"]').removeClass("isChecked");
                } else {
                    $realtime.prop("checked", true);
                    $('[data-bundle-product-id="' + i + '"]').addClass("isChecked");
                }
                show();
            });
            addListeners();
            show();

        }
    };



}(), theme.VideoSection = function(containerSelector) {
    var $container = this.$container = $(containerSelector);
    $(".video", $container).each(function() {
        var t = $(this);
        theme.Video.init(t);
        theme.Video.editorLoadVideo(t.attr("id"));
    });
}, theme.VideoSection.prototype = _.assignIn({}, theme.VideoSection.prototype, {
    onUnload : function() {
        theme.Video.removeEvents();
    }
}), theme.SlideshowSection = function() {
    /**
     * @param {!Object} t
     * @return {undefined}
     */
    function test(t) {
        t.each(function() {
            var that = $(this);
            if (that.find(".youtube").length > 0) {
                /**
                 * @param {!Object} o
                 * @param {string} options
                 * @return {undefined}
                 */
                var close = function(o, options) {
                    var video;
                    var data;
                    var previewPlayer;
                    if (data = (video = o.find(".slick-current .slide-video")).find("iframe").get(0), video.hasClass("youtube")) {
                        switch(options) {
                            case "play":
                                callback(data, {
                                    event : "command",
                                    func : "mute"
                                });
                                callback(data, {
                                    event : "command",
                                    func : "playVideo"
                                });
                                break;
                            case "pause":
                                callback(data, {
                                    event : "command",
                                    func : "pauseVideo"
                                });
                        }
                    } else {
                        if (video.hasClass("mp4") && null != (previewPlayer = video.children("video").get(0))) {
                            if ("play" === options) {
                                previewPlayer.play();
                            } else {
                                previewPlayer.pause();
                            }
                        }
                    }
                };
                /**
                 * @param {!Object} t
                 * @param {?} r
                 * @return {undefined}
                 */
                var callback = function(t, r) {
                    if (null != t && null != r) {
                        t.contentWindow.postMessage(JSON.stringify(r), "*");
                    }
                };
                that.addClass("slick-slider--video");
                that.on("init", function(obj) {
                    obj = $(obj.currentTarget);
                    setTimeout(function() {
                        close(obj, "play");
                    }, 1E3);
                });
                that.on("beforeChange", function(canCreateDiscussions, component) {
                    close(component = $(component.$slider), "pause");
                });
                that.on("afterChange", function(canCreateDiscussions, component) {
                    close(component = $(component.$slider), "play");
                });
            }
        });
    }
    return function() {
        var thread_rows = $(".slideshow[data-slick]");
        thread_rows.each(function() {
            if ($(this).length) {
                if (!$(this).hasClass("slick-slider")) {
                    $(this).slick();
                }
                (function(data) {
                    if (data.find(".youtube").length > 0) {
                        if ("undefined" == typeof YT || void 0 === YT.Player) {
                            /** @type {!Element} */
                            var youtube_script = document.createElement("script");
                            /** @type {string} */
                            youtube_script.src = "https://www.youtube.com/iframe_api";
                            /** @type {!Element} */
                            var wafCss = document.getElementsByTagName("script")[0];
                            wafCss.parentNode.insertBefore(youtube_script, wafCss);
                            window.onYouTubeIframeAPIReady = test.bind(window, data);
                        } else {
                            test(data);
                        }
                    }
                })($(this));
                $(this);
            }
        });
        thread_rows.each(function() {
            var t = $(this);
            if (t.find(".slide-video").length) {
                t.find(".slide-video").css("height", t.height());
            }
        });
    };
}(), theme.collection = function() {
    /**
     * @param {number} t
     * @return {?}
     */
    function t(t) {
        return t < 10 ? "<span class='num'>0</span><span class='num'>" + t + "</span>" : "<span class='num'>" + parseInt(t / 10) + "</span><span class='num'>" + t % 10 + "</span>";
    }
    /**
     * @param {number} i
     * @return {?}
     */
    function e(i) {
        return i < 10 ? "<span class='num'>0</span><span class='num'>" + i + "</span>" : i > 100 ? e(parseInt(i / 10)) + "<span class='num'>" + i % 10 + "</span>" : "<span class='num'>" + parseInt(i / 10) + "</span><span class='num'>" + i % 10 + "</span>";
    }


    return {
        init : function() {
            var t;
            !function() {
                if ($(".custom-html").length) {
                    var that = $(".custom-html");
                    /** @type {number} */
                    var curPos = 600;
                    var $selectorListPanel = theme.strings.showMore;
                    var formattedChosenQuestion = theme.strings.showLess;
                    /** @type {string} */
                    var valueOverride = '<div class="button-group text-center"><a id="button-showmore-html" class="btn btn--secondary btn--big" href="#"><span class="text">' + theme.strings.showMore + '</span><svg class="icon"><use xlink:href="#icon-chevron-down" /></svg></a>';
                    that.each(function() {
                        var oldValueNorm = $(this).html();
                        if (oldValueNorm.length > curPos) {
                            /** @type {string} */
                            var str = oldValueNorm.substr(0, curPos) + '<span class="moreellipses">...&nbsp;</span><span class="morecontent"><span>' + oldValueNorm.substr(curPos, oldValueNorm.length - curPos) + "</span></span>";
                            /** @type {string} */
                            str = str + valueOverride;
                            $(this).html(str);
                        }
                    });
                    $("#button-showmore-html").on("click", function(event) {
                        event.preventDefault();
                        if ($(this).hasClass("less")) {
                            $(this).removeClass("less");
                            $(this).find(".text").html($selectorListPanel);
                            that.removeClass("showmore");
                        } else {
                            $(this).addClass("less");
                            $(this).find(".text").html(formattedChosenQuestion);
                            that.addClass("showmore");
                        }
                    });
                }
            }();
            t = $("#collection-page");
            $(document).on("click", ".view-as-btn a", function() {
                var e = $(this).attr("data-layout");
                /** @type {string} */
                document.getElementById("collection-page").className = "";
                t.addClass(e);
            });

            $("[data-section-type='collection-tabs'] .collection-tab-banner").each(function() {
                var element = $(this).find("[data-slick]");
                if (element.length) {
                    if (!element.hasClass("slick-slider")) {
                        element.slick();
                    }
                }
            });

        }
    };
}(), theme.homepage_section = function() {
    /**
     * @param {!Object} input
     * @param {(number|string)} options
     * @return {undefined}
     */
    function fn(input, options) {
        input.hide(700);
        if (!(!input.find('input[name="dismiss"]').prop("checked") && input.find('input[name="dismiss"]').length)) {
            $.cookie("emailSubcribeModal", "closed", {
                expires : options,
                path : "/"
            });
        }
    }
    /**
     * @param {string} secret
     * @param {!Object} params
     * @param {!Object} data
     * @param {string} moduleId
     * @return {undefined}
     */
    function load(secret, params, data, moduleId) {
        $.ajax({
            type : "get",
            url : secret,
            cache : false,
            data : {
                view : "json",
                limit : "&" + moduleId
            },
            beforeSend : function() {
            },
            success : function(i) {
                var options;
                params.remove();
                if (secret == window.router + "/collections/?view=json") {
                    params.html("<p>Please link to collections</p>").show();
                } else {
                    data.html($(i).html());
                    /** @type {!Object} */
                    options = data;
                    if ($(window).width() < 1025) {
                        if (options.length && options.hasClass("slick-slider")) {
                            options.slick("unslick");
                        }
                    } else {
                        if (options.length) {
                            if (!options.hasClass("slick-slider")) {
                                options.slick();
                            }
                        }
                    }
                }
            },
            complete : function() {
            },
            error : function(deleted_model, err) {
                params.text("Sorry, there are no products in this collection").show();
            }
        });
    }
    return {
        init : function() {
            if ($("[data-section-type='logolist']").length) {
                $("[data-section-type='logolist']").each(function(canCreateDiscussions) {
                    var element = $(this).find("[data-slick]");
                    if ($(window).width() < 1025) {
                        if (element.length && element.hasClass("slick-slider")) {
                            element.slick("unslick");
                        }
                    } else {
                        if (element.length) {
                            if (!element.hasClass("slick-slider")) {
                                element.slick();
                            }
                        }
                    }
                });
            }
            $("[data-section-type='featured-blog-section']").each(function(canCreateDiscussions) {
                var element = $(this).find("[data-slick]");
                if ($(window).width() < 1025) {
                    if (element.length && element.hasClass("slick-slider")) {
                        element.slick("unslick");
                    }
                } else {
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                }
            });
            $("[data-gallery-images]").each(function(canCreateDiscussions) {
                var element = $(this).find("[data-slick]");
                if ($(window).width() < 1025) {
                    if (element.length && element.hasClass("slick-slider")) {
                        element.slick("unslick");
                    }
                } else {
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                }
            });
            $("[data-gallery-images2]").each(function(canCreateDiscussions) {
                var element = $(this).find("[data-slick]");
                if (element.length) {
                    if (!element.hasClass("slick-slider")) {
                        element.slick();
                    }
                }
            });
            $("[data-section-type='featured-blog-section'] .halo-blog-layout-video").each(function(canCreateDiscussions) {
                var element = $(this).find("[data-slick]");
                if ($(window).width() < 1025) {
                    if (element.length && element.hasClass("slick-slider")) {
                        element.slick("unslick");
                    }
                } else {
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                }
            });
            if ($("[data-section-type='customBlockCarousel']").length) {
                $("[data-section-type='customBlockCarousel']").each(function(canCreateDiscussions) {
                    var element = $(this).find("[data-slick]");
                    if ($(window).width() < 1025) {
                        if (element.length && element.hasClass("slick-slider")) {
                            element.slick("unslick");
                        }
                    } else {
                        if (element.length) {
                            if (!element.hasClass("slick-slider")) {
                                element.slick();
                            }
                        }
                    }
                });
            }
            if ($("[data-section-type='collection-list']").length) {
                $("[data-section-type='collection-list']").each(function(canCreateDiscussions) {
                    var element = $(this).find("[data-slick]");
                    if ($(window).width() < 1025) {
                        if (element.length && element.hasClass("slick-slider")) {
                            element.slick("unslick");
                        }
                    } else {
                        if (element.length) {
                            if (!element.hasClass("slick-slider")) {
                                element.slick();
                            }
                        }
                    }
                });
            }
            if ($("[data-section-type='quotes']").length) {
                $("[data-section-type='quotes']").each(function(canCreateDiscussions) {
                    var element = $(this).find("[data-slick]");
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                });
            }
            $(".btn-arrow-prev").on("click", function(event) {
                event.preventDefault();
                $(this).parents("[data-carousel]").find("[data-slick]").slick("slickPrev");
            });
            $(".btn-arrow-next").on("click", function(event) {
                event.preventDefault();
                $(this).parents("[data-carousel]").find("[data-slick]").slick("slickNext");
            });
            $("[data-section-type='collection'] [data-slick]").each(function(canCreateDiscussions) {
                var element = $(this);
                if ($(window).width() < 1025) {
                    if (element.length && element.hasClass("slick-slider")) {
                        element.slick("unslick");
                    }
                } else {
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                }
            });
            (function() {
                if (!$("#back-to-top").length) {
                    return;
                }
                /** @type {number} */
                var t = $(window).height() / 2;
                const backToTopBtn = $("#back-to-top");
                $(window).scroll(function() {
                    if ($(this).scrollTop() > t) {
                        backToTopBtn.addClass("is-visible");
                    } else {
                        backToTopBtn.removeClass("is-visible");
                    }
                });
                backToTopBtn.on("click", function(event) {
                    event.preventDefault();
                    $("body,html").animate({
                        scrollTop : 0
                    }, 1E3);
                });
            })();
            (function() {
                if ($("#halo_newsletter").length) {
                    var input = $("#halo_newsletter");
                    var resizeOptionsTable = input.find(".popup-overlay");
                    var $contentContextMenu = input.find(".close");
                    var delay = $(".newsletterPopup").data("delay");
                    var index = $(".newsletterPopup").data("expire");
                    input.find(".newsletterPopup");
                    if ("closed" != $.cookie("emailSubcribeModal")) {
                        setTimeout(function() {
                            input.show(700);
                        }, delay);
                    }
                    $contentContextMenu.on("click", function(event) {
                        event.preventDefault();
                        fn(input, index);
                    });
                    resizeOptionsTable.on("click", function(event) {
                        event.preventDefault();
                        fn(input, index);
                    });
                    $("#mc_embed_signup form").submit(function() {
                        if ("" != $("#mc_embed_signup .email").val()) {
                            fn(input, index);
                        }
                    });
                }
            })();
            if ($("[data-section-type='hero-section']").length) {
                $(".btn-popup-video").on("click", function() {
                    var _td_h = $(this).attr("data-id");
                    const formattedChosenQuestion = '<div class="modal-content">                        <a data-dismiss="modal" class="close close-modal" href="javascript:void(0)">&#215;</a>                        <div class="popup-video" data-video-gallery>                            <div id="popup-video-content">                                <div class="popup-video-main">                                    <iframe                                        id="player"                                        type="text/html"                                        width="100%"                                        frameborder="0"                                        webkitAllowFullScreen                                        mozallowfullscreen                                        allowFullScreen                                        src="' +
                        $(this).attr("data-src") + '"                                        data-video-player>                                    </iframe>                                </div>                            </div>                        </div>                    </div>';
                    $("#popup_video_" + _td_h + " .modal-video").html(formattedChosenQuestion);
                });
                $(document).on("click", function(jEvent) {
                    if ($(".halo_modal_video .modal-video .modal-content").length && 0 === $(jEvent.target).closest(".btn-popup-video").length && 0 === $(jEvent.target).closest(".halo_modal_video .close-modal").length) {
                        $(".halo_modal_video .modal-video .modal-content").remove();
                    }
                });
                $(".hero-custom-block [data-slick]").each(function(canCreateDiscussions) {
                    var element = $(this);
                    if ($(window).width() < 1025) {
                        if (element.length && element.hasClass("slick-slider")) {
                            element.slick("unslick");
                        }
                    } else {
                        if (element.length) {
                            if (!element.hasClass("slick-slider")) {
                                element.slick();
                            }
                        }
                    }
                });
            }
            $(".points_popup .point").on("click", function() {
                var util = $(this).parent();
                var anchorBoundingBoxViewport = util.position();
                var $blockSelectorChildren = $(this).siblings();
                if ($(window).width() < 1024) {
                    $blockSelectorChildren.css({
                        top : 100 - anchorBoundingBoxViewport.top,
                        left : 30 - anchorBoundingBoxViewport.left
                    });
                }
                util.addClass("is-open");
            });
            $(".custom-product-card .close").on("click", function() {
                $(this).parents(".points_popup").removeClass("is-open");
            });
            $(document).on("click", function(jEvent) {
                if (0 === $(jEvent.target).closest(".custom-product-card").length && 0 === $(jEvent.target).closest(".points_popup").length) {
                    $(".points_popup").removeClass("is-open");
                }
            });
            $("[data-fancybox]").fancybox({
                mobile : {
                    clickContent : "close",
                    clickSlide : "close"
                },
                buttons : ["zoom", "slideShow", "close"]
            });
            if ($(".themevale_MultiCategory_wrapper_2").length && !$(".themevale_MultiCategory_wrapper").length) {
                if ($(window).width() < 1025) {
                    if ($(".slideshow-special .item-right .themevale_MultiCategory_wrapper_2").length) {
                        $(".slideshow-special .item-right .themevale_MultiCategory_wrapper_2").appendTo(".slideshow-special .item-left .home-category-filter-sections");
                    }
                } else {
                    if (!$(".slideshow-special .item-right .themevale_MultiCategory_wrapper_2").length) {
                        $(".slideshow-special .item-left .themevale_MultiCategory_wrapper_2").appendTo(".slideshow-special .item-right .home-category-filter-sections");
                    }
                }
            }
            setTimeout(function() {
                $("[data-section-type='collection-tabs']").each(function(canCreateDiscussions) {
                    var $sharepreview = $(this);
                    var i = $sharepreview.find(".nav-tabs").find(".nav-link");
                    var oldActiveEntry = $sharepreview.find(".tab-pane");
                    var imageelement = $sharepreview.find(".nav-tabs .nav-link.active");
                    var container = $sharepreview.find(".nav-tabs").data("row");
                    var filteredView = $sharepreview.find(".tab-content .tab-pane.active");
                    load(imageelement.data("href"), filteredView.find(".halo-loading"), filteredView.find(".halo-row"), container);
                    i.on("click", function(event) {
                        if (event.preventDefault(), !$(this).hasClass("active")) {
                            i.removeClass("active");
                            oldActiveEntry.removeClass("active").removeClass("show");
                            var a = $(this);
                            var n = $(a.attr("href"));
                            a.addClass("active");
                            n.addClass("active show");
                            if (n.find(".halo-loading").length) {
                                load(a.data("href"), n.find(".halo-loading"), n.find(".halo-row"), container);
                            } else {
                                if ($(window).width() > 1024) {
                                    n.find(".collection-carousel").slick("setPosition");
                                }
                            }
                        }
                    });
                });
            }, 4500);
            if ($("[data-section-type='product-special-layout']").length) {
                $("[data-section-type='product-special-layout'] [data-slick]").each(function(canCreateDiscussions) {
                    var element = $(this);
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                });
            }
            (function() {
                if ($('[data-section-type="slideshow-section"]').length && ($(".slideshow-special .collection-carousel[data-slick]").each(function(canCreateDiscussions) {
                    var element = $(this);
                    if ($(window).width() < 1025) {
                        if (element.length && element.hasClass("slick-slider")) {
                            element.slick("unslick");
                        }
                    } else {
                        if (element.length) {
                            if (!element.hasClass("slick-slider")) {
                                element.slick();
                            }
                        }
                    }
                }), $(".slideshow-special").length && $(".halo-header-02").length && $(".site-nav-vertical-wrapper").hasClass("is-open-2"))) {
                    if ($(window).width() > 1024) {
                        var meterPos = $(".site-nav-vertical-wrapper").outerWidth();
                        $(".slideshow-special .item-left .slideshow").addClass("slideshow--custom");
                        $(".slideshow-special .item-left .slideshow").css("padding-left", meterPos);
                    } else {
                        $(".slideshow-special .item-left .slideshow").css("padding-left", 0);
                    }
                }
            })();
        }
    };
}(), theme.header = function() {
    /**
     * @return {undefined}
     */
    function doModal() {
        var uboard;
        if ($(".header-sticky").length) {
            if ($(window).width() > 1024) {
                uboard = $(".header-sticky").height();
                (function(canCreateDiscussions, e) {
                    $(window).on("scroll load", function(canCreateDiscussions) {
                        if ($(window).scrollTop() > e) {
                            $(".header-sticky").addClass("is-sticky");
                            $(".announcement-bar").hide();
                            $("body").css("padding-top", e);
                            if ($(".halo-header-01").length && $(".halo-header-01 .halo-header-PC #site-nav").length) {
                                $(".halo-header-01 .halo-header-PC #site-nav").appendTo(".halo_mobileNavigation_wrapper .site-nav-mobile-wrapper");
                                /** @type {string} */
                                document.getElementById("site-nav").className = "site-nav-mobile";
                            }
                        } else {
                            $(".header-sticky").removeClass("is-sticky");
                            $(".announcement-bar").show();
                            $("body").css("padding-top", 0);
                            if ($(".halo-header-01").length && $(".halo_mobileNavigation_wrapper #site-nav").length) {
                                $(".halo_mobileNavigation_wrapper #site-nav").appendTo(".halo-header-01 .halo-header-PC .header-bottom .container");
                                /** @type {string} */
                                document.getElementById("site-nav").className = "site-nav";
                            }
                        }
                    });
                    /**
                     * @return {undefined}
                     */
                    window.onload = function() {
                        if ($(window).scrollTop() > canCreateDiscussions) {
                            $(".header-sticky").addClass("is-sticky");
                        }
                    };
                })($(".page-container").offset().top, uboard);
            } else {
                uboard = $(".halo-header-mobile").height();
                (function(canCreateDiscussions, e) {
                    if ($(".halo-header-01").length && $(".halo-header-01 .halo-header-PC #site-nav").length) {
                        $(".halo-header-01 .halo-header-PC #site-nav").appendTo(".halo_mobileNavigation_wrapper .site-nav-mobile-wrapper");
                        /** @type {string} */
                        document.getElementById("site-nav").className = "site-nav-mobile";
                    }
                    $(window).on("scroll load", function(canCreateDiscussions) {
                        if ($(window).scrollTop() > e) {
                            $(".header-sticky").addClass("is-sticky");
                            $(".announcement-bar").hide();
                            $("body").css("padding-top", e);
                        } else {
                            $(".header-sticky").removeClass("is-sticky");
                            $(".announcement-bar").show();
                            $("body").css("padding-top", 0);
                        }
                    });
                    /**
                     * @return {undefined}
                     */
                    window.onload = function() {
                        if ($(window).scrollTop() > canCreateDiscussions) {
                            $(".header-sticky").addClass("is-sticky");
                        }
                    };
                })($(".page-container").offset().top, uboard);
            }
        }
    }
    /**
     * @return {undefined}
     */
    function initialize() {
        var t = $(".lang_currency-dropdown").find(".dropdown-label");
        if (t.length && t.is(":visible")) {
            t.on("click", function(event) {
                event.preventDefault();
                event.stopPropagation();
                var $menuElementWrapper = $(this).next();
                if ($menuElementWrapper.is(":visible")) {
                    $menuElementWrapper.slideUp(300);
                } else {
                    t.next(".dropdown-menu").hide();
                    $menuElementWrapper.slideDown(300);
                }
            });
            $(document).on("click", function(e) {
                var aboutMenu = $(".lang_currency-dropdown .dropdown-menu");
                if (!(aboutMenu.is(e.target) || aboutMenu.has(e.target).length)) {
                    aboutMenu.slideUp(300);
                }
            });
        } else {
            t.next(".dropdown-menu").css({
                display : ""
            });
        }
    }
    return {
        init : function() {
            if ($(".logo-wrapper").length) {
                if ($(window).width() > 1024) {
                    if ($(".halo-header-mobile .logo-wrapper").length) {
                        $(".halo-header-mobile .logo-wrapper").appendTo(".halo-header-PC .header-middle-logo");
                    }
                } else {
                    if ($(".halo-header-PC .logo-wrapper").length) {
                        $(".halo-header-PC .logo-wrapper").appendTo(".halo-header-mobile .header-Mobile-item.text-center .items");
                    }
                }
            }
            if ($(".announcement-bar-wrapper").length) {
                $(".announcement-bar-wrapper .announcement-bar-wrapper--close").on("click", function(canCreateDiscussions) {
                    $(".announcement-bar-wrapper").hide();
                });
            }
            if ($("#cart-dropdown").length) {
                if ($(window).width() > 1024) {
                    if ($("#cart-mobile #cart-dropdown").length) {
                        $("#cart-mobile #cart-dropdown").appendTo(".item--cart");
                    }
                } else {
                    if ($(".item--cart #cart-dropdown").length) {
                        $(".item--cart #cart-dropdown").appendTo("#cart-mobile .halo_mobileNavigation_wrapper");
                    }
                }
            }
            if ($("#login-dropdown").length) {
                if ($(window).width() > 1024) {
                    if ($("#login-mobile #login-dropdown").length) {
                        $("#login-mobile #login-dropdown").appendTo(".item--account .navUser-action");
                    }
                } else {
                    if ($(".item--account #login-dropdown").length) {
                        $(".item--account #login-dropdown").appendTo("#login-mobile .halo_mobileNavigation_wrapper");
                    }
                }
            }
            $(".halo-header-PC .site-nav .menu-lv-1.dropdown").mouseenter(function() {
                $("body").addClass("open_menu_pc");
            }).mouseleave(function() {
                $("body").removeClass("open_menu_pc");
            });
            if ($(".site-nav-vertical-wrapper").length) {
                $(".site-nav-vertical-wrapper").on("click", function() {
                    $(this).toggleClass("is-open");
                });
            }
            doModal();
            if ($(".search-form").length) {
                if ($(window).width() > 1024) {
                    if ($(".item--searchMobile .search-form").length) {
                        $(".item--searchMobile .search-form").appendTo(".item--quickSearch");
                    }
                } else {
                    if ($(".item--quickSearch .search-form").length) {
                        $(".item--quickSearch .search-form").appendTo(".item--searchMobile");
                    }
                }
            }
            if ($("#lang-switcher").length) {
                if ($(window).width() > 1024) {
                    if (!$(".navUser-currency .lang_currency-dropdown").length) {
                        $(".currency-groups .lang_currency-dropdown").appendTo(".navUser-currency");
                    }
                } else {
                    if ($(".navUser-currency .lang_currency-dropdown").length) {
                        $(".navUser-currency .lang_currency-dropdown").appendTo(".currency-groups");
                    }
                }
            }
            if ($("#lang-switcher").length) {
                if ($(window).width() > 1024) {
                    if (!$(".navUser-language .lang_currency-dropdown").length) {
                        $(".lang-groups .lang_currency-dropdown").appendTo(".navUser-language");
                    }
                } else {
                    if ($(".navUser-language .lang_currency-dropdown").length) {
                        $(".navUser-language .lang_currency-dropdown").appendTo(".lang-groups");
                    }
                }
            }
            if ($(".featuredProductCarousel").length) {
                if (!$(".featuredProductCarousel").hasClass("slick-slider")) {
                    $(".featuredProductCarousel").slick({
                        infinite : true,
                        slidesToShow : 1,
                        slidesToScroll : 1,
                        dots : true,
                        autoplay : false,
                        arrows : false
                    });
                }
                $(".site-nav > li").mouseover(function() {
                    $(".featuredProductCarousel").get(0).slick.setPosition();
                });
                $("ul.site-nav-mobile > li > .nav-action").on("click", function() {
                    $(".featuredProductCarousel").get(0).slick.setPosition();
                });
            }
            initialize();
            (function($tab, categorydiv, cur) {
                if (cur.length && cur.is(":visible")) {
                    var formattedChosenQuestion = categorydiv.html();
                    $tab.prev(cur).html(formattedChosenQuestion);
                }
            })($("#currencies"), $("#currencies [data-currency].active"), $("[data-currency-label]"));
        }
    };
}(), theme.footer = {
    init : function() {
        !function() {
            if ($(".geotrust_ssl--image[data-slick]").length) {
                var element = $(".geotrust_ssl--image[data-slick]");
                if (element.length) {
                    if (!element.hasClass("slick-slider")) {
                        element.slick();
                    }
                }
            }
        }();
        (function() {
            if ($(".footer-block-icon-row").length) {
                var element = $(".footer-block-icon-row");
                if ($(window).width() < 1025) {
                    if (element.length) {
                        if (!element.hasClass("slick-slider")) {
                            element.slick();
                        }
                    }
                } else {
                    if (element.length && element.hasClass("slick-slider")) {
                        element.slick("unslick");
                    }
                }
            }
        })();
        if ($(window).width() <= 767) {
            if (!$(".footer-info").hasClass("footerMobile")) {
                $(".footer-info").addClass("footerMobile");
                $(".footer-col--mobile .footer-info-list").css("display", "none");
            }
        } else {
            $(".footer-info").removeClass("footerMobile");
            $(".footer-col--mobile").removeClass("open-dropdown");
            $(".footer-col--mobile .footer-info-list").css("display", "block");
        }
        (function() {
            if ($(window).width() <= 767) {
                /** @type {string} */
                var nodeContentSelector = ".footerMobile .footer-col--mobile .footer-info-heading";
                $(document).off("click", nodeContentSelector).on("click", nodeContentSelector, function() {
                    $(this).parent().toggleClass("open-dropdown");
                    $(this).parent().find(".footer-info-list").slideToggle();
                });
            }
        })();
    }
}, theme.sidebar = function() {
    /**
     * @return {undefined}
     */
    function hookUserHistory() {
        if ($("[data-product-carousel-sidebar]").length) {
            $("[data-product-carousel-sidebar]").each(function(canCreateDiscussions) {
                var element = $(this).find("[data-slick]");
                if (element.length) {
                    if (!element.hasClass("slick-slider")) {
                        element.slick();
                    }
                }
            });
        }
    }



    /**
     * @param {?} response
     * @return {undefined}
     */
    function callback(response) {
        var $sharepreview = $(".template-collection .page-container");
        var knode = $sharepreview.find(".sidebar_content");
        var transcludeEl = $sharepreview.find("#main-collection-product-grid");
        var filteredView = ($sharepreview.find(".breadcrumb-wrapper"), $(response).find(".page-collections"));
        var val = filteredView.find(".sidebar_content");
        var template = (filteredView.find(".breadcrumb-wrapper"), filteredView.find("#main-collection-product-grid"));
        knode.replaceWith(val);
        transcludeEl.replaceWith(template);
        if (window.show_multiple_currencies && Currency.currentCurrency != shopCurrency || window.show_auto_currency) {
            Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
        }
    }

    return {
        init : function() {
            hookUserHistory();
            $(".sidebar_mobile").on("click", function(event) {
                event.preventDefault();
                const selectedPoint = $(event.target);
                if (selectedPoint.hasClass("is-open")) {
                    selectedPoint.removeClass("is-open");
                    $(".page-sidebar").removeClass("is-open");
                    $("body").removeClass("open_Sidebar");
                } else {
                    selectedPoint.addClass("is-open");
                    $(".page-sidebar").addClass("is-open");
                    $("body").addClass("open_Sidebar");
                }
            });
            $(".sidebar_close .close").on("click", function(event) {
                event.preventDefault();
                $(".page-sidebar").removeClass("is-open");
                $("body").removeClass("open_Sidebar");
            });
            $(".overlay_background").on("click", function(event) {
                event.preventDefault();
                if ($(".page-sidebar").hasClass("is-open")) {
                    $(".page-sidebar").removeClass("is-open");
                    $("body").removeClass("open_Sidebar");
                }
            });
            (function() {
                if ($(".all-categories-list").length) {
                    $(document).on("click", ".all-categories-list .navPages-action-wrapper", function(jEvent) {
                        const parent_li = $(jEvent.target).parent();
                        parent_li.siblings().removeClass("is-clicked");
                        parent_li.toggleClass("is-clicked");
                        parent_li.siblings().find("> .dropdown-category-list").slideUp("slow");
                        parent_li.find("> .dropdown-category-list").slideToggle("slow");
                    });
                }
                var href = $(".breadcrumb-wrapper ul.breadcrumb .item.is-active").children("a").attr("href");
                $(".all-categories-list .navPages-level-1").each(function() {
                    var $plone_toolbar_main = $(this);
                    if ($plone_toolbar_main.find(".navPages-action-wrapper a").attr("href") === href) {
                        $plone_toolbar_main.children(".navPages-action-wrapper").trigger("click");
                    }
                });
            })();


            $(document).off("click", ".sidebarBlock .sidebarBlock-heading").on("click", ".sidebarBlock .sidebarBlock-heading", function(canCreateDiscussions) {
                $(this).toggleClass("open");
                $(this).next().slideToggle();
            });
            $(document).off("click", ".infinite-scrolling-filter-list a").on("click", ".infinite-scrolling-filter-list a", function(event) {
                var undoEl = $(this).parents(".list-tags").find("li:hidden");
                event.preventDefault();
                undoEl.removeClass("d-none").addClass("d-inline-block");
                $(this).parent().addClass("d-none");
            });
        }
    };
}(), theme.cart_dropdown = function() {

    /**
     * @param {?} expiryInMilliseconds
     * @return {undefined}
     */
    function setup(expiryInMilliseconds) {
        $sharepreview.find(".btn-remove").on("click", function(event) {
            event.preventDefault();
            var vid = $(this).parents(".items").attr("id");
            vid = vid.match(/\d+/g);
            Shopify.removeItem(vid, function(f) {
                error(f);
            });
        });
    }

    /**
     * @param {!Object} data
     * @return {undefined}
     */
    function error(data) {
        if ($("[data-cart-count]").text(data.item_count), $sharepreview.find(".summary .price").html(theme.Currency.formatMoney(data.total_price, theme.moneyFormat)), e.html(""), data.item_count > 0) {
            /** @type {number} */
            var i = 0;
            for (; i < data.items.length; i++) {
                /** @type {string} */
                var s = '<div class="items" id="cart-item-{ID}"><div class="product-on-cart"><a href="{URL}" title="{TITLE}" class="product-image"><img src="{IMAGE}" alt="{TITLE}"></a><div class="product-details"><a href="javascript:void(0)" class="btn-remove"><span>&#215;</span></a><a class="product-name" href="{URL}">{TITLE}</a><div class="option"><span>{VARIANT}</span></div><div class="cart-collateral"><span class="qtt">{QUANTITY} X </span><span class="price money">{PRICE}</span></div></div></div></div>';
                /** @type {string} */
                s = (s = (s = (s = (s = (s = (s = s.replace(/\{ID\}/g, data.items[i].id)).replace(/\{URL\}/g, data.items[i].url)).replace(/\{TITLE\}/g, data.items[i].product_title)).replace(/\{VARIANT\}/g, data.items[i].variant_title || "")).replace(/\{QUANTITY\}/g, data.items[i].quantity)).replace(/\{IMAGE\}/g, Shopify.resizeImage(data.items[i].image, "160x"))).replace(/\{PRICE\}/g, theme.Currency.formatMoney(data.items[i].price, theme.moneyFormat));
                e.append(s);
            }
            setup();
            if (window.show_multiple_currencies && Currency.currentCurrency != shopCurrency || window.show_auto_currency) {
                Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
            }
        }

    }
    var $sharepreview = $("#cart-dropdown");
    var e = $sharepreview.find(".products-list");
    return {
        init : function() {
            $("[data-cart-preview-pc]").on("click", function(event) {
                event.preventDefault();

                const selectedPoint = $(event.currentTarget);
                if (selectedPoint.hasClass("is-open")) {
                    selectedPoint.removeClass("is-open");
                    $("#cart-dropdown").slideUp();
                } else {
                    selectedPoint.addClass("is-open");
                    $("#cart-dropdown").slideDown();
                }
            });
            $("[data-cart-preview]").on("click", function(event) {
                event.preventDefault();

            });
            $(document).on("click", function(jEvent) {
                if ($("[data-cart-preview-pc]").hasClass("is-open") && 0 === $(jEvent.target).closest("[data-cart-preview-pc]").length && 0 === $(jEvent.target).closest("#cart-dropdown").length) {
                    $("[data-cart-preview-pc]").removeClass("is-open");
                    $("#cart-dropdown").slideUp();
                }
            });

            setup();
        }
    };

}(), theme.compare = function() {
    /**
     * @param {string} data
     * @return {undefined}
     */
    function render(data) {
        var e = $("[data-compare-modal]").find(".product-grid");
        var sliderParent = $("[data-compare-modal]").find(".rating");
        var typeOptions = $("[data-compare-modal]").find(".description");
        var $menuDiv = $("[data-compare-modal]").find(".collection");
        var PM_TEXT = $("[data-compare-modal]").find(".availability");
        var formClone = $("[data-compare-modal]").find(".product-type");
        var currentListNode = $("[data-compare-modal]").find(".product-sku");
        jQuery.getJSON(window.router + "/products/" + data + ".js", function(data) {
            /** @type {string} */
            var name = "";
            /** @type {string} */
            var total = "";
            /** @type {string} */
            var pix_color = "";
            /** @type {string} */
            var first = "";
            /** @type {string} */
            var length = "";
            /** @type {string} */
            var header = "";
            /** @type {string} */
            var html = "";
            /** @type {string} */
            var style = "";
            /** @type {string} */
            var start = "";
            var sGroupID = (theme.Currency.formatMoney(data.price_min, theme.moneyFormat), data.id);
            /** @type {string} */
            var th_field = "";
            $(".halo-column-product .halo-item").each(function() {
                if ($(this).find(".product-card").data("id") == sGroupID) {
                    price = $(this).find(".product-price").html();
                    productLabel = $(this).find(".product_badges").html();
                    rating = $(this).find(".spr-badge").html();
                    coll = $(this).find(".collection-product").html();
                    desc = $(this).find(".product-description").html();
                    sku = $(this).find(".sku-product").html();
                    if ($(this).find(".product-price").hasClass("price--on-sale")) {
                        /** @type {string} */
                        th_field = "price--on-sale";
                    }
                    total = total + price;
                    if (null != productLabel && "" != productLabel) {
                        pix_color = pix_color + productLabel;
                    }
                    if ("" == rating || null == rating) {
                        first = first + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '"></div>');
                    } else {
                        first = first + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">' + rating + "</div>");
                    }
                    sliderParent.append(first);
                    if ("" == coll || null == desc) {
                        header = header + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">-</div>');
                    } else {
                        header = header + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">' + coll + "</div>");
                    }
                    $menuDiv.append(header);
                    if ("" == desc || null == desc) {
                        length = length + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">-</div>');
                    } else {
                        length = length + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">' + desc + "</div>");
                    }
                    typeOptions.append(length);
                    if ("" == sku || null == desc) {
                        start = start + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">-</div>');
                    } else {
                        start = start + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">' + sku + "</div>");
                    }
                    currentListNode.append(start);
                }
            });
            /** @type {string} */
            name = name + ('<div class="grid-item col-xl-4" data-compare-added="compare-' + data.id + '">');
            /** @type {string} */
            name = name + ('<div class="product-card" data-product-id="product-' + data.handle + '">');
            /** @type {string} */
            name = name + '<div class="product-image">';
            /** @type {string} */
            name = name + ('<a href="' + data.url + '" class="product-link">');
            /** @type {string} */
            name = name + ('<img src="' + data.featured_image + '" alt="' + data.featured_image.alt + '">');
            /** @type {string} */
            name = name + "</a>";
            /** @type {string} */
            name = name + ('<div class="product_badges">' + pix_color + "</div></div>");
            /** @type {string} */
            name = name + '<div class="product-content">';
            /** @type {string} */
            name = name + '<div class="product-vendor">';
            /** @type {string} */
            name = name + ('<a href="' + window.router + "/collections/vendors?q=" + data.vendor + '" title="' + data.vendor + '">' + data.vendor + "</a></div>");
            /** @type {string} */
            name = name + ('<h4 class="product-title"><a href="' + data.url + '" title="' + data.title + '">' + data.title + "</a></h4>");
            /** @type {string} */
            name = name + ('<div class="product-price ' + th_field + '">' + total + "</div>");
            /** @type {string} */
            name = name + '<div class="product-action">';
            /** @type {string} */
            name = name + ('<form action="/cart/add" method="post" class="variants" data-id="product-actions-' + data.id + '" enctype="multipart/form-data">');
            if (data.available) {
                if (1 == data.variants.length) {
                    /** @type {string} */
                    name = name + ('<button data-btn-addToCart class="btn product-btn" type="submit">' + theme.strings.addToCart + '</button><input type="hidden" name="id" value="' + data.variants[0].id + '" />');
                }
                if (data.variants.length > 1) {
                    /** @type {string} */
                    name = name + ('<a class="btn product-btn" title="' + data.title + '" href="' + data.url + '">' + theme.strings.select_options + "</a>");
                }
                /** @type {string} */
                html = html + ('<div class="col-xl-4 in-stock" data-compare-added="compare-' + data.id + '">' + theme.strings.in_stock + "</div>");
            } else {
                /** @type {string} */
                name = name + ('<button data-btn-addToCart class="btn product-btn product-btn-soldOut" type="submit" disabled="disabled">' + theme.strings.unavailable + "</button>");
                /** @type {string} */
                html = html + ('<div class="col-xl-4 unavailable" data-compare-added="compare-' + data.id + '">' + theme.strings.out_of_stock + "</div>");
            }
            /** @type {string} */
            name = name + ('</form><div class="text-center mt-3"><a class="product-remove" href="javascript:void(0);" data-icon-compare-added data-compare-product-handle="' + data.handle + '" data-id="' + data.id + '">' + theme.strings.remove + "</a></div>");
            /** @type {string} */
            name = name + "</div></div></div></div>";
            e.append(name);
            /** @type {string} */
            style = style + ('<div class="col-xl-4" data-compare-added="compare-' + data.id + '">' + data.type + "</div>");
            PM_TEXT.append(html);
            formClone.append(style);
            if (window.show_multiple_currencies && Currency.currentCurrency != shopCurrency || window.show_auto_currency) {
                Currency.convertAll(window.shop_currency, $("#currencies .active").attr("data-currency"), "span.money", "money_format");
            }
        });
    }
    /**
     * @return {undefined}
     */
    function del_food() {
        compareCountNum = $("[data-compare-count]");
        compareElm = $("[data-icon-compare]");
        $("[data-compare-added]").remove();
        obj.splice(0, obj.length);
        localStorage.setItem("compareArr", JSON.stringify(obj));
        if (compareElm.hasClass("compare-added")) {
            compareElm.removeClass("compare-added");
        }
        /** @type {number} */
        totalProduct = Math.ceil(obj.length);
        compareCountNum.text("0");
        compareCountNum.parent().removeClass("show");
    }
    /**
     * @param {string} e
     * @return {undefined}
     */
    function i(e) {
        var a = $('[data-compare-product-handle="' + e + '"]');
        var this_area = obj.indexOf(e);
        var $apiUri = $("[data-compare-count]");
        /** @type {*} */
        compareItems = localStorage.getItem("compareArr") ? JSON.parse(localStorage.getItem("compareArr")) : [];
        /** @type {number} */
        totalProduct = Math.ceil(compareItems.length);
        if (this_area >= 0) {
            a.addClass("compare-added");
            a.find(".compare-text").text("remove compare");
        } else {
            a.removeClass("compare-added");
            a.find(".compare-text").text("add compare");
        }
        $apiUri.text(totalProduct);
        if (totalProduct > 1) {
            $apiUri.parent().addClass("show");
        } else {
            $apiUri.parent().removeClass("show");
        }
    }

    /** @type {*} */
    var obj = localStorage.getItem("compareArr") ? JSON.parse(localStorage.getItem("compareArr")) : [];
    return localStorage.setItem("compareArr", JSON.stringify(obj)), obj.length && (obj = JSON.parse(localStorage.getItem("compareArr"))), {
        init : function() {
            if ($(".page-collections").length) {
                $(document).on("click", "[data-icon-compare]", function(event) {
                    event.preventDefault();
                    var r = $(this);
                    var salesTeam = r.data("id");
                    var css = r.data("compare-product-handle");
                    var match = obj.indexOf(css);
                    if (r.hasClass("compare-added")) {
                        r.removeClass("compare-added");
                        if ($('[data-compare-added="compare-' + salesTeam + '"]').length) {
                            $('[data-compare-added="compare-' + salesTeam + '"]').remove();
                        }
                        obj.splice(match, 1);
                        localStorage.setItem("compareArr", JSON.stringify(obj));
                    } else {
                        r.addClass("compare-added");
                        obj.push(css);
                        localStorage.setItem("compareArr", JSON.stringify(obj));
                        render(css);
                    }
                    i(css);
                });
            }
            (function() {
                if ($(".page-collections").length) {
                    var $copyFrom = $("[data-compare-count]");
                    if (totalProduct = Math.ceil(obj.length), $copyFrom.text(totalProduct), obj.length) {
                        /** @type {number} */
                        var i = 0;
                        for (; i < obj.length; i++) {
                            $('[data-compare-product-handle="' + obj[i] + '"]').addClass("compare-added");
                        }
                        if ("undefined" != typeof Storage) {
                            if (obj.length <= 0) {
                                return;
                            }
                            del_food();
                            setTimeout(function() {
                                obj.forEach(function(css) {
                                    render(css);
                                    i(css);
                                });
                            }, 700);
                        } else {
                            alert("Sorry! No web storage support..");
                        }
                    }
                }
            })();

        }
    };

}(), theme.someone_purchased = function() {
    /**
     * @return {undefined}
     */
    function cellDblClicked() {
        $(".product-notification .time-text span:visible").text();
        if ($(".product-notification").hasClass("active")) {
            $(".product-notification").removeClass("active");
        } else {
            var circuitLength = $(".data-product").length;
            /** @type {number} */
            var e = Math.floor(Math.random() * circuitLength);
            var cover = $(".product-notification .data-product:eq(" + e + ")").data("image");
            var changeLinkText = $(".product-notification .data-product:eq(" + e + ")").data("title");
            var r = $(".product-notification .data-product:eq(" + e + ")").data("url");
            var local = $(".product-notification .data-product:eq(" + e + ")").data("local");
            $(".product-notification").addClass("active");
            $(".product-notification .product-image").find("img").attr("src", cover);
            $(".product-notification .product-name").attr("href", r);
            $(".product-notification .product-name").text(changeLinkText);
            $(".product-notification .product-image").attr("href", r);
            $(".product-notification .time-text").text(local);
        }
    }
    return {
        init : function() {
            !function() {
                var t = $("#accept-cookies");
                if ("closed" == $.cookie("cookieMessage")) {
                    t.remove();
                } else {
                    t.removeClass("hide");
                }
                t.find("[data-accept-cookie]").on("click", function(event) {
                    event.preventDefault();
                    t.remove();
                    $.cookie("cookieMessage", "closed", {
                        expires : 1,
                        path : "/"
                    });
                });
            }();
            (function() {
                if ($(".product-notification").length) {
                    if ("closed" == $.cookie("pr_notification")) {
                        $(".product-notification").remove();
                    }
                    $(".close-notifi").bind("click", function() {
                        $(".product-notification").remove();
                        $.cookie("pr_notification", "closed", {
                            expires : 1,
                            path : "/"
                        });
                    });
                    var t = $(".product-notification").data("time");
                    setInterval(cellDblClicked, t);
                }
            })();
        }
    };
}(), theme.MultiCategory = function() {
    /**
     * @param {number} min
     * @param {!Array} value
     * @return {undefined}
     */
    function addNode(min, value) {
        /** @type {number} */
        var i = min;
        for (; i <= 4; i++) {
            $("#themevale_select-level-" + i + " ul li[data-value]").remove();
        }
        /** @type {number} */
        i = 0;
        for (; i < value.length; i++) {
            $("#themevale_select-level-" + min + " ul").append("<li data-value='" + i + "' onclick='theme.MultiCategory.changeOption(" + min + "," + i + ")'>" + value[i].title.trim() + "</li>");
        }
    }
    /**
     * @param {number} a
     * @param {?} id
     * @return {undefined}
     */
    function update(a, id) {
        if (null != id) {
            /** @type {boolean} */
            var o = false;
            if (0 == $("#themevale_select-level-" + a + ' ul li[data-value="' + id + '"]').hasClass("active")) {
                /** @type {boolean} */
                o = true;
            }
            $("#themevale_select-level-" + a + " ul").children().removeClass("active");
            $("#themevale_select-level-" + a + " ul").children().each(function() {
                if ($(this).attr("data-value") == id) {
                    $(this).addClass("active");
                    $("#themevale_select-level-" + a).prev(".da-force-up.form-select").text($(this).text());
                }
            });
            i(a);
            var source = node;
            /** @type {number} */
            var b = 1;
            for (; b <= a; b++) {
                /** @type {number} */
                var sourceIndex = parseInt($("#themevale_select-level-" + b + " ul").find(".active").attr("data-value"));
                if (null != source[sourceIndex]) {
                    source = source[sourceIndex].children;
                }
            }
            if (3 == a && 1 == o) {
                $("#themevale_select-browse").trigger("click");
            }
            addNode(a = parseInt(a) + 1, source);
            if (source.length && 0 == item) {
                $(".themevale_multilevel-category-filter").find(".loading").show();
                setTimeout(function() {
                    $("#themevale_select-level-" + a).addClass("open");
                    $(".themevale_multilevel-category-filter").find(".loading").hide();
                }, 300);
            }
        }
    }
    /**
     * @param {number} a
     * @return {undefined}
     */
    function i(a) {
        var val = a + 1;
        for (; val <= node.length; val++) {
            var formattedChosenQuestion = $("#themevale_select-level-" + val + " ul li:eq(0)").html();
            $("#themevale_select-level-" + val).prev(".da-force-up.form-select").html(formattedChosenQuestion);
            $("#themevale_select-level-" + val + " ul").html('<li onclick="theme.MultiCategory.changeOptionAll(' + val + ')">' + formattedChosenQuestion + "</li>");
        }
    }
    /** @type {boolean} */
    var item = true;
    var node = function build(survey) {
        /** @type {!Array} */
        var model = new Array;
        return survey.find(" > li").each(function() {
            var relevance_tab = jQuery(this).find(">a");
            var item = {
                link : relevance_tab.attr("href"),
                title : relevance_tab.html(),
                children : build(jQuery(this).find("> ul "))
            };
            model.push(item);
        }), model;
    }(jQuery("nav#navPages >  ul"));
    jQuery("#themevale_select-level-1 ul");
    jQuery("#themevale_select-level-2 ul");
    jQuery("#themevale_select-level-3 ul");
    return {
        init : function() {
            !function() {
                if ($(".themevale_MultiCategory").length) {
                    addNode(1, node);
                    /** @type {number} */
                    var remote = 1;
                    if ($("body").hasClass("template-index") && jQuery.cookie("multiLevelCategory", ""), "" != jQuery.cookie("multiLevelCategory")) {
                        var array = jQuery.cookie("multiLevelCategory").split(",");
                        /** @type {number} */
                        var i = 0;
                        for (; i < array.length; i++) {
                            update(remote, array[i]);
                            remote++;
                        }
                        $(".themevale_multilevel-category-filter").find(".loading").hide();
                        $(".dropdown-up").removeClass("open");
                    }
                    $(document).off("click", ".da-force-up").on("click", ".da-force-up", function(canCreateDiscussions) {
                        if ($(this).parent().find(".dropdown-up").hasClass("open")) {
                            $(this).parent().find(".dropdown-up").removeClass("open");
                        } else {
                            $(".dropdown-up").removeClass("open");
                            $(this).parent().find(".dropdown-up").addClass("open");
                        }
                    });
                    $(window).click(function(b) {
                        var e = $(".da-force-up");
                        if (!(e.is(b.target) || 0 !== e.has(b.target).length)) {
                            e.next(".dropdown-up").removeClass("open");
                        }
                    });
                    $("#themevale_select-browse").click(function() {
                        var result = node;
                        /** @type {number} */
                        var i = 0;
                        /** @type {string} */
                        var link = "";
                        /** @type {!Array} */
                        var overEls = [];
                        /** @type {number} */
                        var n = 1;
                        for (; n <= 3; n++) {
                            /** @type {number} */
                            i = parseInt($("#themevale_select-level-" + n + " ul").find(".active").attr("data-value"));
                            if (!isNaN(i)) {
                                overEls.push(i);
                                link = result[i].link;
                                if (result[i].children.length) {
                                    result = result[i].children;
                                }
                            }
                        }
                        jQuery.cookie("multiLevelCategory", overEls, {
                            expires : 1,
                            path : "/"
                        });
                        location.href = link;
                    });
                    $("#themevale_clear-select").click(function() {
                        i(1);
                        var formattedChosenQuestion = $("#themevale_select-level-1 ul li:eq(0)").html();
                        $("#themevale_select-level-1").prev(".da-force-up.form-select").html(formattedChosenQuestion);
                        $("#themevale_select-level-1 ul").children().removeClass("active");
                    });
                    /** @type {boolean} */
                    item = false;
                }
            }();
        },
        changeOption : function(e, option) {
            update(e, option);
        },
        changeOptionAll : function(tResult) {
            !function(t) {
                $("#themevale_select-level-" + t + " ul").children().removeClass("active");
                var formattedChosenQuestion = $("#themevale_select-level-" + t + " ul li:eq(0)").html();
                $("#themevale_select-level-" + t).prev(".da-force-up.form-select").html(formattedChosenQuestion);
            }(tResult);
        }
    };
}(), theme.halo_mobile = function() {
    /**
     * @return {undefined}
     */
    function open() {
        $("#site-nav-mobile").find(".is-open").removeClass("is-open");
        $("#site-nav-mobile").find(".is-hidden").removeClass("is-hidden");
    }
    return {
        init : function() {
            $('[data-mobile-menu-toggle="menu"]').on("click", function() {
                if ($(this).toggleClass("is-open"), $("body").toggleClass("open_menu"), $(window).width() > 1024) {
                    var canvasOffset = $(".site-header").position();
                    var miny = $(".site-header").outerHeight();
                    $("#site-nav-mobile").css({
                        top : canvasOffset.top + miny,
                        height : $(window).height() - canvasOffset.top - miny
                    });
                } else {
                    $("#site-nav-mobile").css("top", 0);
                }
            });
            $("#site-nav-mobile .close_menu").on("click", function(canCreateDiscussions) {
                $('[data-mobile-menu-toggle="menu"]').removeClass("is-open");
                $("body").removeClass("open_menu");
                open();
            });
            $(".overlay_background").on("click", function(canCreateDiscussions) {
                if ($("body").hasClass("open_menu")) {
                    $('[data-mobile-menu-toggle="menu"]').removeClass("is-open");
                    $("body").removeClass("open_menu");
                    open();
                }
            });
            $("[data-cart-preview]").on("click", function(canCreateDiscussions) {
                $("body").addClass("open_Cart");
            });
            $("[data-close-cart-preview]").on("click", function(canCreateDiscussions) {
                $("body").removeClass("open_Cart");
            });
            $(document).on("click", function(jEvent) {
                if ($("body").hasClass("open_Cart") && 0 === $(jEvent.target).closest("[data-cart-preview]").length && 0 === $(jEvent.target).closest("#cart-mobile").length) {
                    $("body").removeClass("open_Cart");
                }
            });
            $("[data-login-dropdown-pc]").on("click", function(event) {
                event.preventDefault();
                const selectedPoint = $(event.currentTarget);
                if (selectedPoint.hasClass("is-open")) {
                    selectedPoint.removeClass("is-open");
                    $("#login-dropdown").slideUp();
                } else {
                    selectedPoint.addClass("is-open");
                    $("#login-dropdown").slideDown();
                }
            });
            $("[data-close-login-dropdown-pc]").on("click", function(event) {
                event.preventDefault();
                $("[data-login-dropdown-pc]").removeClass("is-open");
                $("#login-dropdown").slideUp();
            });
            $(document).on("click", function(jEvent) {
                if ($("[data-login-dropdown-pc]").hasClass("is-open") && 0 === $(jEvent.target).closest("[data-login-dropdown-pc]").length && 0 === $(jEvent.target).closest("#login-dropdown").length) {
                    $("[data-login-dropdown-pc]").removeClass("is-open");
                    $("#login-dropdown").slideUp();
                }
            });
            $("[data-login-toggle]").on("click", function(canCreateDiscussions) {
                $("body").addClass("open_Account");
            });
            $("[data-close-login-dropdown]").on("click", function(canCreateDiscussions) {
                $("body").removeClass("open_Account");
            });
            $(".overlay_background").on("click", function(event) {
                event.preventDefault();
                if ($("body").hasClass("open_Account")) {
                    $("body").removeClass("open_Account");
                }
            });
            $(".item--searchMobile .navUser-action").on("click", function(canCreateDiscussions) {
                $(".item--searchMobile .navUser-action").toggleClass("is-open");
            });
            if ($(".terms_conditions_wrapper").length) {
                $('.terms_conditions_wrapper input[type="checkbox"]').each(function() {
                    if ($(this).is(":checked")) {
                        $(this).parent().next().removeClass("disabled");
                    } else {
                        $(this).parent().next().addClass("disabled");
                    }
                });
                $(document).on("click", ".terms_conditions_wrapper .title", function(event) {
                    event.preventDefault();
                    var $viewcontent = $(this);
                    var $realtime = $viewcontent.prev();
                    if ($realtime.prop("checked")) {
                        $realtime.prop("checked", false);
                        $viewcontent.parent().next().addClass("disabled");
                    } else {
                        $realtime.prop("checked", true);
                        $viewcontent.parent().next().removeClass("disabled");
                    }
                });
            }
            $(document).on("click", ".site-nav-mobile .nav-action", function(jEvent) {
                const $target = $(jEvent.target).parent();
                const label = $target.siblings();
                const $icheck = $target.closest(".site-nav-dropdown");
                const uploadWrapper = $icheck.siblings();
                if (!($(jEvent.target).hasClass("menu__moblie_end") || $(jEvent.target).hasClass("link"))) {
                    $target.addClass("is-open");
                    if ($target.hasClass("is-open")) {
                        label.addClass("is-hidden");
                    }
                    if ($icheck.length) {
                        uploadWrapper.addClass("is-hidden");
                    }
                }
            });
            $(document).on("click", ".site-nav-mobile .menu-mb-title", function(jEvent) {
                const $panel = $(jEvent.target).closest(".dropdown");
                const $newWord = $panel.siblings();
                const label = $(jEvent.target).closest(".site-nav-dropdown").siblings();
                $panel.removeClass("is-open");
                $newWord.removeClass("is-hidden");
                label.removeClass("is-hidden");
            });
        }
    };
}(), $(document).ready(function() {
    var state = new theme.Sections;
    state.register("cart-template", theme.Cart);
    state.register("product", theme.Product);
    state.register("collection-template", theme.Filters);
    state.register("product-template", theme.Product);
    state.register("map", theme.Maps);
    state.register("video-section", theme.VideoSection);
    state.register("product-recommendations", theme.ProductRecommendations);
    state.register("slideshow-section", theme.SlideshowSection);
    state.register("cart-page", theme.cart_page);
    theme.header.init();
    theme.footer.init();
    theme.sidebar.init();
    // theme.wishlist.init();
    theme.compare.init();
    theme.collection.init();
    theme.cart_dropdown.init();
    theme.halo_mobile.init();
    // theme.product_card.init();
    // theme.product_sticky_atc.init();
    // theme.someone_purchased.init();
    // theme.products_frequently_by_together.init();

    theme.MultiCategory.init();
    theme.homepage_section.init();
    $(window).resize(function() {
        theme.header.init();
        theme.footer.init();
        // theme.product_card.init();
        theme.homepage_section.init();
    });
}), theme.init = function() {

    slate.rte.wrapTable({
        $tables : $(".rte table,.custom__item-inner--html table"),
        tableWrapperClass : "scrollable-wrapper"
    });
    slate.rte.wrapIframe({
        $iframes : $('.rte iframe[src*="youtube.com/embed"],.rte iframe[src*="player.vimeo"],.custom__item-inner--html iframe[src*="youtube.com/embed"],.custom__item-inner--html iframe[src*="player.vimeo"]'),
        iframeWrapperClass : "video-wrapper"
    });
    slate.a11y.pageLinkFocus($(window.location.hash));
    $(".in-page-link").on("click", function(event) {
        slate.a11y.pageLinkFocus($(event.currentTarget.hash));
    });
    $('a[href="#"]').on("click", function(event) {
        event.preventDefault();
    });
    slate.a11y.accessibleLinks({
        messages : {
            newWindow : theme.strings.newWindow,
            external : theme.strings.external,
            newWindowExternal : theme.strings.newWindowExternal
        },
        $links : $("a[href]:not([aria-describedby], .product-single__thumbnail)")
    });
    theme.FormStatus.init();
    // $(selectors.image + ".lazyloaded").closest(selectors.imageWithPlaceholderWrapper).find(selectors.imagePlaceholder).addClass(classes.hidden);
    /** @type {!NodeList<Element>} */
    var artistTrack = document.querySelectorAll("svg[data-src]");
    SVGInjector(artistTrack);
}, $(theme.init);
