<?php echo $header; ?><?php echo $column_left; ?>
<?php if ($setting_check_err or $show_setting_checker) { ?>
	
	<div id="content">
		<div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button onclick="matchregions();" id="matchregions" data-toggle="tooltip" title="Сопоставить регионы" class="btn btn-success"><i class="fa fa-refresh"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                    <?php if ($button_save) { ?>
                    <button type="submit" form="form-dpd" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <?php } ?>
                </div>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

		<div class="container-fluid">
			<div style="margin-top: 15px; background: #fff; border: 1px solid #c3c4c7; border-left-width: 4px; border-left-color: #72aee6; padding: 10px 12px 1px; box-shadow: 0 1px 1px rgb(0 0 0 / 4%)">
				<p>
					<b>Внимание:</b>
					Ваша система должна соответствовать обязательным параметрам. Если какой-либо
					из этих параметров выделен красным цветом, то вам необходимо исправить его.
					В противном случае работоспособность модуля не гарантируется.
				</p>
			</div>
		</div>
	</div>

	<?= setting_check_html ?>

	<div class="" style="text-align: center">
		<? if ($setting_check_err) { ?>
			<input type="button" 
				class="btn btn-primary" 
				value="Проверить снова"
				onclick="document.location.href = document.location.href"
			>
		<? } else { ?>
			<input type="button" 
				class="btn btn-primary" 
				value="Продолжить"
				onclick="document.location.href = document.location.href"
			>
		<? } ?>
	</div>

	<br><br><br>

<? } else { ?>

    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button onclick="matchregions();" id="matchregions" data-toggle="tooltip" title="Сопоставить регионы" class="btn btn-success"><i class="fa fa-refresh"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
                    <?php if ($button_save) { ?>
                    <button type="submit" form="form-dpd" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <?php } ?>
                </div>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <?php if ($filled_city) { ?>
            <div class="alert alert-info"> <?php echo $filled_city; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>

            <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>

            <?php if ($error_warning) { ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($error_file_exists) { ?>
            <div class="text-danger"><?php echo $error_file_exists; ?></div>
            <?php } ?>

            <div class="alert alert-warning">
                <i class="fa fa-exclamation-circle"></i> 
                Документация к модулю доступна <a href="https://ipol.ru/spravka/dpd_opencart/nacalo_raboty/nastr/" target="_blank">здесь</a>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-dpd" class="form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-main" data-toggle="tab"><?php echo $tab_main; ?></a></li>
                            <li><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                            <li><a href="#tab-dimensions" data-toggle="tab"><?php echo $tab_dimensions; ?></a></li>
                            <li><a href="#tab-calculate-shipping" data-toggle="tab"><?php echo $tab_calculate_shipping; ?></a></li>
                            <li><a href="#tab-sender" id="test" data-toggle="tab"><?php echo $tab_sender; ?></a></li>
                            <li><a href="#tab-recipient" data-toggle="tab"><?php echo $tab_recipient; ?></a></li>
                            <li><a href="#tab-desc-sender" data-toggle="tab"><?php echo $tab_desc_sender; ?></a></li>
                            <li><a href="#tab-status" data-toggle="tab"><?php echo $tab_status; ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-main">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status-module"><?php echo $entry_status_module; ?></label>
                                    <div class="col-sm-3">
                                        <select name="dpd_status" id="input-status-module" class="form-control">
                                            <?php if ($dpd_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="col-sm-3 control-label"><?php echo $entry_sort_order; ?></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="dpd_sort_order" value="<?php echo $dpd_sort_order; ?>" class="form-control">
                                    </div>
                                </div>
                                </br>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab-to-door" data-toggle="tab"><?php echo $tab_to_door; ?></a></li>
                                    <li><a href="#tab-from-terminal" data-toggle="tab"><?php echo $tab_from_terminal; ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-to-door">
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_name_door" value="<?php echo $dpd_name_door; ?>" class="form-control">
                                                    <?php if ($error_name_door) { ?>
                                                    <div class="text-danger"><?php echo $error_name_door; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_door_status" id="input-status" class="form-control">
                                                    <?php if ($dpd_door_status) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                    <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label" for="description"><?php echo $entry_description; ?></label>
                                                <div class="col-sm-8">
                                                    <textarea id="description" data-toggle="summernote" name="dpd_description_door" cols="90" rows="12"><?php echo $dpd_description_door; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                            <div class="col-sm-10">
                                                <a href="" id="thumb-image-door" data-toggle="image" class="img-thumbnail"><img src="<?php echo $dpd_thumb_door; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                                <input type="hidden" name="dpd_image_door" value="<?php echo $dpd_image_door; ?>" id="input-image" /></td>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label"><?php echo $entry_markup; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="dpd_markup_door" value="<?php echo $dpd_markup_door; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-markup-type"><?php echo $entry_markup_type; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_markup_type_door" id="input-markup-type" class="form-control">
                                                    <?php if ($dpd_markup_type_door) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_percent; ?></option>
                                                    <option value="0"><?php echo $text_fix; ?></option>
                                                    <?php } else { ?>
                                                    <option value="1"><?php echo $text_percent; ?></option>
                                                    <option value="0" selected="selected"><?php echo $text_fix; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-from-terminal">
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_name_terminal" value="<?php echo $dpd_name_terminal; ?>" class="form-control">
                                                    <?php if ($error_name_terminal) { ?>
                                                    <div class="text-danger"><?php echo $error_name_terminal; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_terminal_status" id="input-status" class="form-control">
                                                    <?php if ($dpd_terminal_status) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                    <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label" for="description"><?php echo $entry_description; ?></label>
                                                <div class="col-sm-8">
                                                    <textarea id="description" data-toggle="summernote" name="dpd_description_terminal" cols="90" rows="12"><?php echo $dpd_description_terminal; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-image-terminal"><?php echo $entry_image; ?></label>
                                            <div class="col-sm-10">
                                                <a href="" id="thumb-image-terminal" data-toggle="image" class="img-thumbnail"><img src="<?php echo $dpd_thumb_terminal; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                                <input type="hidden" name="dpd_image_terminal" value="<?php echo $dpd_image_terminal; ?>" id="input-image-terminal" /></td>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label"><?php echo $entry_markup; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="dpd_markup_terminal" value="<?php echo $dpd_markup_terminal; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-markup-type"><?php echo $entry_markup_type; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_markup_type_terminal" id="input-markup-type" class="form-control">
                                                    <?php if ($dpd_markup_type_terminal) { ?>
                                                    <option value="1" selected="selected"><?php echo $text_percent; ?></option>
                                                    <option value="0"><?php echo $text_fix; ?></option>
                                                    <?php } else { ?>
                                                    <option value="1"><?php echo $text_percent; ?></option>
                                                    <option value="0" selected="selected"><?php echo $text_fix; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-general">
                                <div class="alert alert-warning" style="color:black;" role="alert">
                                    <?php echo $text_main_setting; ?><a href="http://www.dpd.ru/ols/order/personal/integrationkey.do2" target="_blank">http://www.dpd.ru/ols/order/personal/integrationkey.do2</a><?php echo $text_main_setting_end; ?>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab-russian" data-toggle="tab"><?php echo $tab_russian; ?></a></li>
                                    <li><a href="#tab-kazahstan" data-toggle="tab"><?php echo $tab_kazahstan; ?></a></li>
                                    <li><a href="#tab-belarus" data-toggle="tab"><?php echo $tab_belarus; ?></a></li>
                                    <li><a href="#tab-kirgizstan" data-toggle="tab"><?php echo 'Киргизстан'; ?></a></li>
                                    <li><a href="#tab-armenia" data-toggle="tab"><?php echo 'Армения'; ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-russian">
                                        <?php echo $text_auth_russian; ?>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_number; ?>"><?php echo $entry_dpd_number; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_russian_number" value="<?php echo $dpd_russian_number; ?>" class="form-control">
                                                    <?php if ($error_russian_number) { ?>
                                                    <div class="text-danger"><?php echo $error_russian_number; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_auth; ?>"><?php echo $entry_dpd_auth; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_russian_auth" value="<?php echo $dpd_russian_auth; ?>" class="form-control">
                                                    <?php if ($error_russian_auth) { ?>
                                                    <div class="text-danger"><?php echo $error_russian_auth; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_russian_currency" id="input-currency" class="form-control">
                                                    <?php foreach($currencies as $_key => $currency) { ?>
                                                    <?php if ($currency['code'] == $dpd_russian_currency) { ?>
                                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-kazahstan">
                                        <?php echo $text_auth_kazahstan; ?>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_number; ?>"><?php echo $entry_dpd_number; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_kazahstan_number" value="<?php echo $dpd_kazahstan_number; ?>" class="form-control">
                                                    <?php if ($error_kazahstan_number) { ?>
                                                    <div class="text-danger"><?php echo $error_kazahstan_number; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_auth; ?>"><?php echo $entry_dpd_auth; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_kazahstan_auth" value="<?php echo $dpd_kazahstan_auth; ?>" class="form-control">
                                                    <?php if ($error_kazahstan_auth) { ?>
                                                    <div class="text-danger"><?php echo $error_kazahstan_auth; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_kazahstan_currency" id="input-currency" class="form-control">
                                                    <?php foreach($currencies as $_key => $currency) { ?>
                                                    <?php if ($currency['code'] == $dpd_kazahstan_currency) { ?>
                                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-belarus">
                                        <?php echo $text_auth_belarus; ?>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_number; ?>"><?php echo $entry_dpd_number; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_belarus_number" value="<?php echo $dpd_belarus_number; ?>" class="form-control">
                                                    <?php if ($error_belarus_number) { ?>
                                                    <div class="text-danger"><?php echo $error_belarus_number; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_auth; ?>"><?php echo $entry_dpd_auth; ?></span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_belarus_auth" value="<?php echo $dpd_belarus_auth; ?>" class="form-control">
                                                    <?php if ($error_belarus_auth) { ?>
                                                    <div class="text-danger"><?php echo $error_belarus_auth; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_belarus_currency" id="input-currency" class="form-control">
                                                    <?php foreach($currencies as $_key => $currency) { ?>
                                                    <?php if ($currency['code'] == $dpd_belarus_currency) { ?>
                                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-kirgizstan">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_kirgizstan_currency" id="input-currency" class="form-control">
                                                    <?php foreach($currencies as $_key => $currency) { ?>
                                                    <?php if ($currency['code'] == $dpd_kirgizstan_currency) { ?>
                                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-armenia">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                                            <div class="col-sm-4">
                                                <select name="dpd_armenia_currency" id="input-currency" class="form-control">
                                                    <?php foreach($currencies as $_key => $currency) { ?>
                                                    <?php if ($currency['code'] == $dpd_armenia_currency) { ?>
                                                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </br>
                                <hr></hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-account-default"><span data-toggle="tooltip" title="<?php echo $help_account_default; ?>"><?php echo $entry_account_default; ?></span></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_account_default" id="input-account-default" class="form-control">
                                            <?php foreach($country_with_dpd as $_key => $country) { ?>
                                            <?php if ($country['value'] == $dpd_account_default) { ?>
                                            <option value="<?php echo $country['value']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $country['value']; ?>"><?php echo $country['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-test"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_test) { ?>
                                                <input type="checkbox" id="input-test" name="dpd_test" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-test" name="dpd_test" />
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-dpd-button"><?php echo $entry_dpd_button; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_button" id="input-dpd-button" class="form-control">
                                            <?php foreach($buttons_with_dpd as $_key => $button) { ?>
                                            <?php if ($button['value'] == $dpd_button) { ?>
                                            <option value="<?php echo $button['value']; ?>" selected="selected"><?php echo $button['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $button['value']; ?>"><?php echo $button['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-pvz"><?php echo $entry_pvz; ?></label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_pvz) { ?>
                                                <input type="checkbox" id="input-pvz" name="dpd_pvz" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-pvz" name="dpd_pvz" />
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-pvz"><?php echo $entry_wsdl; ?></label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_wsdl) { ?>
                                                <input type="checkbox" id="input-wsdl" name="dpd_wsdl" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-wsdl" name="dpd_wsdl" />
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-api-map"><span data-toggle="tooltip" title="<?php echo $help_api_map; ?>"><?php echo $entry_api_map; ?></span></label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_api_map) { ?>
                                                <input type="checkbox" id="input-api-map" name="dpd_api_map" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-api-map" name="dpd_api_map" />
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-dimensions">
                                <div class="alert alert-warning" style="color:black;"  role="alert">
                                    <?php echo $text_dimensions; ?>
                                    </br>
                                    </br>
                                    <b><?php echo $text_dimensions_heading1; ?></b>
                                    </br>
                                    <?php echo $text_dimensions_center; ?>
                                    </br>
                                    </br>
                                    <b><?php echo $text_dimensions_heading2; ?></b>
                                    </br>
                                    <?php echo $text_dimensions_end1; ?>
                                    </br>
                                    </br>
                                    <b class="text-danger"><?php echo $text_dimensions_warning; ?></b>
                                    <?php echo $text_dimensions_end2; ?>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-use-for"><?php echo $entry_use_for; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_use_for" id="input-use-for" class="form-control">
                                            <?php foreach($use_for as $_key => $use) { ?>
                                            <?php if ($use['value'] == $dpd_use_for) { ?>
                                            <option value="<?php echo $use['value']; ?>" selected="selected"><?php echo $use['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $use['value']; ?>"><?php echo $use['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_weight; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_weight" value="<?php echo $dpd_weight; ?>" class="form-control">
                                            <?php if ($error_weight) { ?>
                                            <div class="text-danger"><?php echo $error_weight; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_length; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_length" value="<?php echo $dpd_length; ?>" class="form-control">
                                            <?php if ($error_length) { ?>
                                            <div class="text-danger"><?php echo $error_length; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_width; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_width" value="<?php echo $dpd_width; ?>" class="form-control">
                                            <?php if ($error_width) { ?>
                                            <div class="text-danger"><?php echo $error_width; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_height; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_height" value="<?php echo $dpd_height; ?>" class="form-control">
                                            <?php if ($error_height) { ?>
                                            <div class="text-danger"><?php echo $error_height; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-calculate-shipping">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="not-calculate"><?php echo $entry_not_calculate; ?></label>
                                    <div class="col-sm-3">
                                        <select name="dpd_not_calculate[]" multiple class="form-control" id="calculate">
                                            <option></option>
                                            <?php foreach($not_calculate_dpd as $_key => $not_calculate) { ?>
                                            <?php if ($not_calculate['value'] == ${'dpd_not_calculate' . $not_calculate['value']}) { ?>
                                            <option value="<?php echo $not_calculate['value']; ?>" selected="selected"><?php echo $not_calculate['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $not_calculate['value']; ?>"><?php echo $not_calculate['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="not-calculate"><span data-toggle="tooltip" title="<?php echo $help_tariff; ?>"><?php echo $entry_tariff_default; ?></span></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_tariff_default" class="form-control" id="calculate">
                                            <option></option>
                                            <?php foreach($not_calculate_dpd as $_key => $not_calculate) { ?>
                                            <?php if ($not_calculate['value'] == $dpd_tariff_default) { ?>
                                            <option value="<?php echo $not_calculate['value']; ?>" selected="selected"><?php echo $not_calculate['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $not_calculate['value']; ?>"><?php echo $not_calculate['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_max_for_default; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_max_for_default" value="<?php echo $dpd_max_for_default; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-cart-q-product"><span data-toggle="tooltip" title="<?php echo $help_cart_equally_product; ?>"><?php echo $entry_cart_equally_product; ?></span></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_cart_equally_product) { ?>
                                                    <input type="checkbox" id="input-cart-q-product" name="dpd_cart_equally_product" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-cart-q-product" name="dpd_cart_equally_product" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-calculate-for-product"><span data-toggle="tooltip" title="<?php echo $help_calculate_for_product; ?>"><?php echo $entry_calculate_for_product; ?></span></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_calculate_for_product) { ?>
                                                    <input type="checkbox" id="input-calculate-for-product" name="dpd_calculate_for_product" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-calculate-for-product" name="dpd_calculate_for_product" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_ceil; ?>"><?php echo $entry_ceil; ?></span></label>
                                        <div class="col-sm-4">
                                            <input type="number" name="dpd_ceil" value="<?php echo $dpd_ceil; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_term_shipping; ?></label>
                                        <div class="col-sm-4">
                                            <input type="number" name="dpd_term_shipping" value="<?php echo $dpd_term_shipping; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label">Сумма по умолчанию:</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="dpd_default_price" value="<?php echo $dpd_default_price; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info" align="center" role="alert">
                                    <?php echo $entry_h_comission; ?>
                                </div>
                                <div class="alert alert-warning" style="color:black;" role="alert">
                                    <?php echo $entry_h_comission_info; ?>
                                </div>
                                <ul class="nav nav-tabs">
                                    <?php foreach($customer_groups as $_key => $group) { ?>
                                    <?php if ($minimum_customer_id == $group['customer_group_id']) { ?>
                                    <li class="active"><a href="#tab-<?php echo $group['customer_group_id']; ?>" data-toggle="tab"><?php echo $group['name']; ?></a></li>
                                    <?php } else { ?>
                                    <li><a href="#tab-<?php echo $group['customer_group_id']; ?>" data-toggle="tab"><?php echo $group['name']; ?></a></li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach($customer_groups as $_key => $group) { ?>
                                    <?php if ($minimum_customer_id == $group['customer_group_id']) { ?>
                                    <div class="tab-pane active" id="tab-<?php echo $group['customer_group_id']; ?>">
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label" for="input-comission-for-collection<?php echo $group['customer_group_id']; ?>"><?php echo $entry_comission_for_collection; ?></span></label>
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label> <?php if (${'dpd_comission_for_collection_' . $group['customer_group_id']}) { ?>
                                                            <input type="checkbox" id="input-comission-for-collection<?php echo $group['customer_group_id']; ?>" name="dpd_comission_for_collection_<?php echo $group['customer_group_id']; ?>" checked="checked" />
                                                            <?php } else { ?>
                                                            <input type="checkbox" id="input-comission-for-collection<?php echo $group['customer_group_id']; ?>" name="dpd_comission_for_collection_<?php echo $group['customer_group_id']; ?>" />
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><?php echo $entry_comission_for_product; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="dpd_comission_for_product_<?php echo $group['customer_group_id']; ?>" value="<?php echo ${'dpd_comission_for_product_' . $group['customer_group_id']}; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><?php echo $entry_min_sum_comission; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_min_sum_comission_<?php echo $group['customer_group_id']; ?>" value="<?php echo ${'dpd_min_sum_comission_' . $group['customer_group_id']}; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="binding-payment-<?php echo $group['customer_group_id']; ?>"><?php echo $entry_bind_payment; ?></label>
                                            <div class="col-sm-3">
                                                <select name="dpd_bind_payment_<?php echo $group['customer_group_id']; ?>[]" multiple class="form-control" id="binding-payment-<?php echo $group['customer_group_id']; ?>">
                                                    <option></option>
                                                    <?php foreach($payment_methods as $_key => $payment) { ?>
                                                    <?php if ($payment['code'] == ${'dpd_bind_payment_' . $group['customer_group_id'] . $payment['code']}) { ?>
                                                    <option value="<?php echo $payment['code']; ?>" selected="selected"><?php echo $payment['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $payment['code']; ?>"><?php echo $payment['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label" for="input-not-payment<?php echo $group['customer_group_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_not_payment; ?>"><?php echo $entry_not_payment; ?></span></label>
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label> <?php if (${'dpd_not_payment_' . $group['customer_group_id']}) { ?>
                                                            <input type="checkbox" id="input-not-payment<?php echo $group['customer_group_id']; ?>" name="dpd_not_payment_<?php echo $group['customer_group_id']; ?>" checked="checked" />
                                                            <?php } else { ?>
                                                            <input type="checkbox" id="input-not-payment<?php echo $group['customer_group_id']; ?>" name="dpd_not_payment_<?php echo $group['customer_group_id']; ?>" />
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <div class="tab-pane" id="tab-<?php echo $group['customer_group_id']; ?>">
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label" for="input-comission-for-collection<?php echo $group['customer_group_id']; ?>"><?php echo $entry_comission_for_collection; ?></span></label>
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label> <?php if (${'dpd_comission_for_collection_' . $group['customer_group_id']}) { ?>
                                                            <input type="checkbox" id="input-comission-for-collection<?php echo $group['customer_group_id']; ?>" name="dpd_comission_for_collection_<?php echo $group['customer_group_id']; ?>" checked="checked" />
                                                            <?php } else { ?>
                                                            <input type="checkbox" id="input-comission-for-collection<?php echo $group['customer_group_id']; ?>" name="dpd_comission_for_collection_<?php echo $group['customer_group_id']; ?>" />
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><?php echo $entry_comission_for_product; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="dpd_comission_for_product_<?php echo $group['customer_group_id']; ?>" value="<?php echo ${'dpd_comission_for_product_' . $group['customer_group_id']}; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label"><?php echo $entry_min_sum_comission; ?></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="dpd_min_sum_comission_<?php echo $group['customer_group_id']; ?>" value="<?php echo ${'dpd_min_sum_comission_' . $group['customer_group_id']}; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="binding-payment-<?php echo $group['customer_group_id']; ?>"><?php echo $entry_bind_payment; ?></label>
                                            <div class="col-sm-3">
                                                <select name="dpd_bind_payment_<?php echo $group['customer_group_id']; ?>" multiple class="form-control" id="binding-payment-<?php echo $group['customer_group_id']; ?>">
                                                    <option></option>
                                                    <?php foreach($payment_methods as $_key => $payment) { ?>
                                                    <?php if ($payment['code'] == ${'dpd_bind_payment_' . $group['customer_group_id']}) { ?>
                                                    <option value="<?php echo $payment['code']; ?>" selected="selected"><?php echo $payment['title']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $payment['code']; ?>"><?php echo $payment['title']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-4 control-label" for="input-not-payment<?php echo $group['customer_group_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_not_payment; ?>"><?php echo $entry_not_payment; ?></span></label>
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label> <?php if (${'dpd_not_payment_' . $group['customer_group_id']}) { ?>
                                                            <input type="checkbox" id="input-not-payment<?php echo $group['customer_group_id']; ?>" name="dpd_not_payment_<?php echo $group['customer_group_id']; ?>" checked="checked" />
                                                            <?php } else { ?>
                                                            <input type="checkbox" id="input-not-payment<?php echo $group['customer_group_id']; ?>" name="dpd_not_payment_<?php echo $group['customer_group_id']; ?>" />
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-sender">
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_contact_face; ?>"><?php echo $entry_contact_face; ?></span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_contact_face" value="<?php echo $dpd_contact_face; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_name_company; ?>"><?php echo $entry_name_company; ?></span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_name_company" value="<?php echo $dpd_name_company; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_phone_sender; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_phone_sender" value="<?php echo $dpd_phone_sender; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_email_sender; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_email_sender" value="<?php echo $dpd_email_sender; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_numb_r_sender; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_numb_r_sender" value="<?php echo $dpd_numb_r_sender; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-pass"><?php echo $entry_pass; ?></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_pass) { ?>
                                                    <input type="checkbox" id="input-pass" name="dpd_pass" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-pass" name="dpd_pass" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info" align="center" role="alert">
                                    <?php echo $entry_h_address; ?>
                                </div>
                                <ul class="nav nav-tabs ">
                                    <?php $row = 1; ?>
                                    <?php foreach($dpd_address_sender as $address) { ?>
                                    <li> <a href="#tab-address-<?php echo $row; ?>" class="tabnav" data-toggle="tab" id="address-<?php echo $row; ?>"><?php echo $address['name']; ?><img src="view/image/delete.png" alt="" onclick="$('.tab a:first').trigger('click'); $('#address-<?php echo $row; ?>').remove(); $('#tab-address-<?php echo $row; ?>').remove(); return false;" /></a></li>
                                    <?php $row = ($row + 1); ?>
                                    <?php } ?>
                                    <i id="address-add" class="pull-right"><a onclick="addAddress();" class="btn btn-success">Добавить адрес</a></i>
                                </ul>
                                <?php $row = 1; ?>
                                <div class="tab-content" id="adresses-t">
                                    <?php foreach($dpd_address_sender as $_key => $address) { ?>
                                    <div id="tab-address-<?php echo $row; ?>" class="tab-pane">
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label" for="name"><?php echo $entry_name; ?></label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="dpd_address_sender[<?php echo $row; ?>][name]" value="<?php echo $address['name']; ?>" class="form-control"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_city_sender; ?>"><?php echo $entry_city_sender; ?></span></label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="dpd_address_sender[<?php echo $row; ?>][city_sender]" value="<?php echo $address['city_sender']; ?>" id="city_sender-<?php echo $row; ?>" placeholder="<?php echo $entry_city_sender; ?>" class="form-control city-sender">
                                                    <input type="hidden" name="dpd_address_sender[<?php echo $row; ?>][city_id]" id="city_id-<?php echo $row; ?>" value="<?php echo $address['city_id']; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="sh-flex">
                                                <label class="col-sm-2 control-label" for="input-default<?php echo $row; ?>">По умолчанию</label>
                                                <div class="col-sm-6">
                                                    <div class="checkbox">
                                                        <label> <?php if ($address['default']) { ?>
                                                            <input type="checkbox" class="check-default" id="input-default<?php echo $row; ?>" name="dpd_address_sender[<?php echo $row; ?>][default]" checked="checked" />
                                                            <?php } else { ?>
                                                            <input type="checkbox" class="check-default" id="input-default<?php echo $row; ?>" name="dpd_address_sender[<?php echo $row; ?>][default]" />
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-door<?php echo $row; ?>" data-toggle="tab"><?php echo $tab_door; ?></a></li>
                                            <li><a href="#tab-terminal<?php echo $row; ?>" data-toggle="tab"><?php echo $tab_terminal; ?></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab-door<?php echo $row; ?>">
                                                <h4><?php echo $text_h1_door; ?></h4>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_street_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][street_sender]" value="<?php echo $address['street_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_ab_street_sender; ?>"><?php echo $entry_ab_street_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][ab_street_sender]" value="<?php echo $address['ab_street_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_house_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][house_sender]" value="<?php echo $address['house_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_corp_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][corp_sender]" value="<?php echo $address['corp_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_structure_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][structure_sender]" value="<?php echo $address['structure_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_poss_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][poss_sender]" value="<?php echo $address['poss_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_office_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][office_sender]" value="<?php echo $address['office_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="sh-flex">
                                                        <label class="col-sm-4 control-label"><?php echo $entry_apart_sender; ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="dpd_address_sender[<?php echo $row; ?>][apart_sender]" value="<?php echo $address['apart_sender']; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-terminal<?php echo $row; ?>">
                                                <h4><?php echo $text_h1_terminal; ?></h4>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="terminal-sender"><?php echo $entry_terminal_sender; ?></label>
                                                    <div class="col-sm-3">
                                                        <select name="dpd_address_sender[<?php echo $row; ?>][terminal_sender]" class="form-control" id="terminal-sender-<?php echo $row; ?>">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $row = ($row + 1); ?>

                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-recipient">
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-pass-rec"><?php echo $entry_pass; ?></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_pass_rec) { ?>
                                                    <input type="checkbox" id="input-pass-rec" name="dpd_pass_rec" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-pass-rec" name="dpd_pass_rec" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-desc-sender">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-departure-method"><span data-toggle="tooltip" title="<?php echo $help_departure_method; ?>"><?php echo $entry_departure_method; ?></span></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_departure_method" id="input-departure-method" class="form-control">
                                            <?php foreach($departure_method as $_key => $method) { ?>
                                            <?php if ($method['value'] == $dpd_departure_method) { ?>
                                            <option value="<?php echo $method['value']; ?>" selected="selected"><?php echo $method['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $method['value']; ?>"><?php echo $method['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-payment-method-delivery"><?php echo $entry_payment_method_delivery; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_payment_method_delivery" id="input-payment-method-delivery" class="form-control">
                                            <?php foreach($payment_method_delivery as $_key => $payment_method) { ?>
                                            <?php if ($payment_method['value'] == $dpd_payment_method_delivery) { ?>
                                            <option value="<?php echo $payment_method['value']; ?>" selected="selected"><?php echo $payment_method['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $payment_method['value']; ?>"><?php echo $payment_method['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-dpd-transit-interval"><?php echo $entry_dpd_transit_interval; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_transit_interval_dpd" id="input-dpd-transit-interval" class="form-control">
                                            <?php foreach($dpd_transit_interval as $_key => $dpd) { ?>
                                            <?php if ($dpd['value'] == $dpd_transit_interval_dpd) { ?>
                                            <option value="<?php echo $dpd['value']; ?>" selected="selected"><?php echo $dpd['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $dpd['value']; ?>"><?php echo $dpd['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-delivery-time-interval"><?php echo $entry_delivery_time_interval; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_delivery_time_interval" id="input-delivery-time-interval" class="form-control">
                                            <?php foreach($delivery_time_interval as $_key => $delivery) { ?>
                                            <?php if ($delivery['value'] == $dpd_delivery_time_interval) { ?>
                                            <option value="<?php echo $delivery['value']; ?>" selected="selected"><?php echo $delivery['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $delivery['value']; ?>"><?php echo $delivery['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_quantity_places; ?></label>
                                        <div class="col-sm-4">
                                            <input type="number" name="dpd_quantity_places" value="<?php echo $dpd_quantity_places; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><?php echo $entry_content_sender; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_content_sender" value="<?php echo $dpd_content_sender; ?>" class="form-control">
                                            <?php if ($error_content) { ?>
                                            <div class="text-danger"><?php echo $error_content; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info" align="center" role="alert">
                                    <?php echo $entry_h_options; ?>
                                </div>
                                <div class="alert alert-warning" style="color:black;" role="alert">
                                    <?php echo $entry_desc_sender_desc; ?>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-val-cargo"><span data-toggle="tooltip" title="<?php echo $help_val_cargo; ?>"><?php echo $entry_val_cargo; ?></span></label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_val_cargo) { ?>
                                                <input type="checkbox" id="input-val-cargo" name="dpd_val_cargo" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-val-cargo" name="dpd_val_cargo" />
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-weekend-delivery"><span data-toggle="tooltip" title="<?php echo $help_weekend_delivery; ?>"><?php echo $entry_weekend_delivery; ?></span></label>
                                    <div class="col-sm-5">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_weekend_delivery) { ?>
                                                <input type="checkbox" id="input-weekend-delivery" name="dpd_weekend_delivery" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-weekend-delivery" name="dpd_weekend_delivery" />
                                                <?php } ?>
                                            </label>
                                            <div class="text-danger"><?php echo $warning_weekend_delivery; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-condition"><?php echo $entry_condition; ?></label>
                                    <div class="col-sm-5">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_condition) { ?>
                                                <input type="checkbox" id="input-condition" name="dpd_condition" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-condition" name="dpd_condition" />
                                                <?php } ?>
                                            </label>
                                            <div class="text-danger"><?php echo $warning_weekend_delivery; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-loading-unloading"><span data-toggle="tooltip" title="<?php echo $help_loading_unloading; ?>"><?php echo $entry_loading_unloading; ?></span></label>
                                    <div class="col-sm-5">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_loading_unloading) { ?>
                                                <input type="checkbox" id="input-loading-unloading" name="dpd_loading_unloading" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-loading-unloading" name="dpd_loading_unloading" />
                                                <?php } ?>
                                            </label>
                                            <div class="text-danger"><?php echo $warning_weekend_delivery; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-return-doc"><span data-toggle="tooltip" title="<?php echo $help_return_doc; ?>"><?php echo $entry_return_doc; ?></span></label>
                                    <div class="col-sm-5">
                                        <div class="checkbox">
                                            <label> <?php if ($dpd_return_doc) { ?>
                                                <input type="checkbox" id="input-return-doc" name="dpd_return_doc" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" id="input-return-doc" name="dpd_return_doc" />
                                                <?php } ?>
                                            </label>
                                            <div class="text-danger"><?php echo $warning_weekend_delivery; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-wait-address"><?php echo $entry_wait_address; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_wait_address" id="input-wait-address" class="form-control">
                                            <?php foreach($waiting_address as $_key => $waiting) { ?>
                                            <?php if ($waiting['value'] == $dpd_wait_address) { ?>
                                            <option value="<?php echo $waiting['value']; ?>" selected="selected"><?php echo $waiting['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $waiting['value']; ?>"><?php echo $waiting['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-partial-purchase"><?php echo $entry_partial_purchase; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_partial_purchase" id="input-partial-purchase" class="form-control">
                                            <?php foreach($partial_purchase as $_key => $part) { ?>
                                            <?php if ($part['value'] == $dpd_partial_purchase) { ?>
                                            <option value="<?php echo $part['value']; ?>" selected="selected"><?php echo $part['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $part['value']; ?>"><?php echo $part['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="alert alert-info" align="center" role="alert">
                                    <?php echo $entry_h_alert; ?>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_order_mail; ?>"><?php echo $entry_order_mail; ?></span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="dpd_order_mail" value="<?php echo $dpd_order_mail; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-status">
                                <div class="alert alert-warning" style="color:black;" role="alert">
                                    <?php echo $text_status_info; ?>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-set-accepted"><?php echo $entry_set_accepted; ?></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_set_accepted) { ?>
                                                    <input type="checkbox" id="input-set-accepted" name="dpd_set_accepted" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-set-accepted" name="dpd_set_accepted" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-mark-paid"><span data-toggle="tooltip" title="<?php echo $help_mark_delivery_paid; ?>"><?php echo $entry_mark_delivery_paid; ?></span></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_mark_delivery_paid) { ?>
                                                    <input type="checkbox" id="input-mark-paid" name="dpd_mark_delivery_paid" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-mark-paid" name="dpd_mark_delivery_paid" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sh-flex">
                                        <label class="col-sm-4 control-label" for="input-track-status-dpd"><?php echo $entry_track_status_dpd; ?></label>
                                        <div class="col-sm-4">
                                            <div class="checkbox">
                                                <label> <?php if ($dpd_track_status_dpd) { ?>
                                                    <input type="checkbox" id="input-track-status-dpd" name="dpd_track_status_dpd" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="checkbox" id="input-track-status-dpd" name="dpd_track_status_dpd" />
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach($dpd_statuses as $_key => $dpd_status) { ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-status-<?php echo $dpd_status['key']; ?>"><?php echo $dpd_status['entry']; ?></label>
                                    <div class="col-sm-4">
                                        <select name="dpd_status_<?php echo $dpd_status['key']; ?>" id="input-status-<?php echo $dpd_status['key']; ?>" class="form-control">
                                            <option value="non"><?php echo $text_non; ?></option>
                                            <?php foreach($order_statuses as $_key => $status) { ?>
                                            <?php if ($status['order_status_id'] == ${'dpd_status_' . $dpd_status['key']}) { ?>
                                            <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                    </form>
                    </br>
                    <hr>
                    <?php if ($filled) { ?>
                    <h4><b><?php echo $text_service_procedures; ?></b></h4>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-service" value="1" id="test" data-toggle="tab"><?php echo $tab_service; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-service">
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <button style="height:41px;" class="btn btn-success" id="import" type="button" onclick="importdata();" title="<?php echo $entry_import; ?>"><?php echo $entry_import; ?></button>
                                </div>
                                <div class="col-sm-8">
                                    <b id="hh"></b>
                                    <div style="display:none;" id="loadimport" class="progress">
                                        <div id="progressbar1" class="progress-bar progress-bar-striped active" role="progressbar"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                            0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
    <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
    <script type="text/javascript" src="view/javascript/summernote/summernote-image-attributes.js"></script>
    <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
    <script type="text/javascript"><!--
    var row = <?php echo $row; ?>;

        function addAddress() {

            html = 	'	<div id="tab-address-' + row + '" class="tab-pane">';
            html += '	 <div class="form-group required">';
            html += '		<label class="col-sm-2 control-label" for="name"><?php echo $entry_name; ?></label>';
            html += '		<div class="col-sm-6">';
            html += '			<input class="form-control" type="text" name="dpd_address_sender[' +  row + '][name]" value="" class="form-control" />';
            html += '		</div>';
            html += '	 </div>';
            html += '    <div class="form-group">';
            html += '    	<div class="sh-flex">';
            html += '    		<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_city_sender; ?>"><?php echo $entry_city_sender; ?></span></label>';
            html += '    		<div class="col-sm-6">';
            html += '    			<input type="text" name="dpd_address_sender[' +  row + '][city_sender]" id="city_sender-' + row + '" value="" placeholder="<?php echo $entry_city_sender; ?>" class="form-control city-sender">';
            html += '    			<input type="hidden" name="dpd_address_sender[' +  row + '][city_id]" id="city_id-' + row + '" value=""/>';
            html += '    		</div>';
            html += '    	</div>';
            html += '    </div>';
            html += ' 	 <div class="form-group">';
            html += '	 	<div class="sh-flex">';
            html += '			<label class="col-sm-2 control-label" for="input-default' +  row + '">По умолчанию</label>';
            html += '			<div class="col-sm-6">';
            html += '				<div class="checkbox">';
            html += '					<label>';
            html += '						<input class="check-default" type="checkbox" id="input-default' +  row + '" name="dpd_address_sender[' +  row + '][default]" />';
            html += '					</label>';
            html += '				</div>';
            html += '			</div>';
            html += '	 	</div>';
            html += '	 </div>';
            html += '    <ul class="nav nav-tabs">';
            html += '      <li class="active"><a href="#tab-door' + row + '" data-toggle="tab"><?php echo $tab_door; ?></a></li>';
            html += '      <li><a href="#tab-terminal' + row + '" data-toggle="tab"><?php echo $tab_terminal; ?></a></li>';
            html += '    </ul>';
            html += '    <div class="tab-content">';
            html += '    	<div class="tab-pane active" id="tab-door' + row + '">';
            html += '    		<h4><?php echo $text_h1_door; ?></h4>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_street_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][street_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_ab_street_sender; ?>"><?php echo $entry_ab_street_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][ab_street_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_house_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][house_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_corp_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][corp_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_structure_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][structure_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_poss_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][poss_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_office_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][office_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    		<div class="form-group">';
            html += '    			<div class="sh-flex">';
            html += '    				<label class="col-sm-4 control-label"><?php echo $entry_apart_sender; ?></label>';
            html += '    				<div class="col-sm-4">';
            html += '    					<input type="text" name="dpd_address_sender[' +  row + '][apart_sender]" value="" class="form-control">';
            html += '    				</div>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    	</div>';
            html += '    	<div class="tab-pane" id="tab-terminal' + row + '">';
            html += '    		<h4><?php echo $text_h1_terminal; ?></h4>';
            html += '    		<div class="form-group">';
            html += '    			<label class="col-sm-4 control-label" for="terminal-sender"><?php echo $entry_terminal_sender; ?></label>';
            html += '    			<div class="col-sm-3">';
            html += '    				<select name="dpd_address_sender[' +  row + '][terminal_sender]" class="form-control" id="terminal-sender-' + row + '">';
            html += '    				</select>';
            html += '    			</div>';
            html += '    		</div>';
            html += '    	 </div>';
            html += '    	</div>';
            html += '    </div>	';

            $('#adresses-t').append(html);

            $('#address-add').before('<li><a data-toggle="tab" href="#tab-address-' + row + '" id="address-' + row + '">Адресс' + row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.tab a:first\').trigger(\'click\'); $(\'#address-' + row + '\').remove(); $(\'#tab-address-' + row + '\').remove(); return false;" /></a></li>');

            $('#address-' + row	).trigger('click');

            searchCity();
            checkValid();

            row++;
        }

        function checkValid(){
            $('.check-default').on('click', function () {
                var def = $(this)
                var default_id = def.attr('id');
                $('.check-default').prop('checked', false);
                $(this).prop('checked', true);
            });
        }

        function loadPVZSender(city_id, row_city){
            var options = '';

            $.post('index.php?route=extension/shipping/dpd/getTerminals&token=<?php echo $token; ?>', { city_id: city_id }).done(function(json) {

                $('#terminal-sender-' + row_city).find('option').remove();
                var shipping_dpd_address_sender = <?php echo json_encode($dpd_address_sender); ?>;

            for (var key in json.items) {
                    if(shipping_dpd_address_sender[row_city]){
                        if(shipping_dpd_address_sender[row_city].terminal_sender == json.items[key].CODE){
                            options += '<option selected value="' + json.items[key].CODE + '">' + json.items[key].NAME + '</option>';
                        }else{
                            options += '<option value="' + json.items[key].CODE + '">' + json.items[key].NAME + '</option>';
                        }
                    }else{
                        options += '<option value="' + json.items[key].CODE + '">' + json.items[key].NAME + '</option>';
                    }
                }
                $('#terminal-sender-' + row_city).append(options);
            });
        }

        function searchCity(){
            $('.city-sender').each(function () {
                var city = $(this)
                var row_city = city.attr('id').replace('city_sender-', '');

                loadPVZSender($('input[name=\'dpd_address_sender[' + row_city + '][city_id]\']').val(), row_city);

                var xhr;
                // City Sender
                $('input[name=\'dpd_address_sender[' + row_city + '][city_sender]\']').autocomplete({
                    'delay': 0,
                    'source': function(request, response) {
                        var regex = new RegExp(request.term, 'i');
                        if(xhr){
                            xhr.abort();
                        }

                        var fn = function(){
                            xhr = $.ajax({
                                url: 'index.php?route=extension/shipping/dpd/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                                dataType: 'json',
                                success: function(json) {
                                    json.unshift({
                                        city_id: 0,
                                        name: '<?php echo $text_none; ?>'
                                    });

                                    response($.map(json, function(item) {
                                        if(regex.test(item.label)){
                                            return {
                                                label: item['name'],
                                                value: item['city_id'],
                                                val:   item['value'],
                                            }
                                        }
                                    }));
                                },
                            });
                        };
                        var interval = setTimeout(fn, 400);
                    },
                    'minlength':2,
                    'select': function(item) {
                        $('input[name=\'dpd_address_sender[' + row_city + '][city_sender]\']').val(item['val']);
                        $('input[name=\'dpd_address_sender[' + row_city + '][city_id]\']').val(item['value']);
                        loadPVZSender(item['value'], row_city);
                    }
                });
            });
        }

        searchCity();

        function matchregions(){
            $('#matchregions').button('loading');
            $.post('index.php?route=extension/shipping/dpd/matchRegions&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                if(json.error){
                    alert(json.error);
                    $('#matchregions').button('reset');
                }

                if (json.success) {
                    alert(json.success);
                    $('#matchregions').button('reset');
                }
            });
        }

        function loadingimport(max,success) {
            success = parseInt(success);
            var	time = (success/max)*5;
            var loading = function() {
                var progressbar = $('#progressbar1');
                var value = progressbar.attr('aria-valuenow');
                value = parseInt(value);
                value += 1;


                addValue = progressbar1.setAttribute('aria-valuenow', value);
                document.getElementById('progressbar1').style.width = value + '%';

                $('#progressbar1').html(value + '%');
                if (value == max) {
                    clearInterval(animate);
                }

                if (value == 100) {
                    $.post('index.php?route=extension/shipping/dpd/unsetImport&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                        $('#import').button('reset');
                        loadimport.style.display = 'none';
                        $('#hh').html('<div class="alert alert-success alert-dismissible" align="center">Загрузка завершена!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        document.getElementById('progressbar1').style.width = '0%';
                        progressbar1.setAttribute('aria-valuenow', 0);
                        $('#progressbar1').html('0%');
                    });
                }
            };

            var animate = setInterval(function() {
                loading();
            }, time);
        }

        /* Import data */
        function importdata() {
            var progressbar = $('#progressbar1');

            $.post('index.php?route=extension/shipping/dpd/unsetImport&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {});
            loadimport.style.display = 'block';
            $('#import').button('loading');
            $('#hh').html('<?php echo $text_step_1; ?>');
            loadingimport(5, 25000);
            $.post('index.php?route=extension/shipping/dpd/loadAll&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                if(json.error){
                    alert(json.error);

                    $('#import').button('reset');
                    loadimport.style.display = 'none';
                    $('#hh').html('<div class="alert alert-danger alert-dismissible" align="center">' + json.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    document.getElementById('progressbar1').style.width = '0%';
                    progressbar1.setAttribute('aria-valuenow', 0);
                    $('#progressbar1').html('0%');
                }
                if (json.success) {
                    loadingimport(5, 2);
                    loadingimport(25, json.success);
                    $.post('index.php?route=extension/shipping/dpd/loadCashPay&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                        if(json.error){
                            alert(json.error);

                            $('#import').button('reset');
                            loadimport.style.display = 'none';
                            $('#hh').html('<div class="alert alert-danger alert-dismissible" align="center">' + json.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                            document.getElementById('progressbar1').style.width = '0%';
                            progressbar1.setAttribute('aria-valuenow', 0);
                            $('#progressbar1').html('0%');
                        }
                        if (json.success) {
                            $('#hh').html('<?php echo $text_step_2; ?>');
                            loadingimport(25, 2);
                            loadingimport(75, json.success);
                            $.post('index.php?route=extension/shipping/dpd/loadUnlimited&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                                if(json.error){
                                    alert(json.error);

                                    $('#import').button('reset');
                                    loadimport.style.display = 'none';
                                    $('#hh').html('<div class="alert alert-danger alert-dismissible" align="center">' + json.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                    document.getElementById('progressbar1').style.width = '0%';
                                    progressbar1.setAttribute('aria-valuenow', 0);
                                    $('#progressbar1').html('0%');
                                }
                                if (json.success) {
                                    $('#hh').html('<?php echo $text_step_3; ?>');
                                    loadingimport(50, 2);
                                    $.post('index.php?route=extension/shipping/dpd/loadLimited&token=<?php echo $token; ?>', { type: 'import' }).done(function(json) {
                                        if(json.error){
                                            alert(json.error);

                                            $('#import').button('reset');
                                            loadimport.style.display = 'none';
                                            $('#hh').html('<div class="alert alert-danger alert-dismissible" align="center">' + json.error + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                            document.getElementById('progressbar1').style.width = '0%';
                                            progressbar1.setAttribute('aria-valuenow', 0);
                                            $('#progressbar1').html('0%');
                                        }
                                        if (json.success) {
                                            $('#hh').html('<?php echo $text_step_4; ?>');
                                            loadingimport(100, json.success);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    </script>

<? } ?>
<?php echo $footer; ?>
