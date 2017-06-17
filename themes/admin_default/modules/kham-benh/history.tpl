<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<!-- BEGIN: view -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" name="q"
						maxlength="255" placeholder="{LANG.search_title}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit"
						value="{LANG.search_submit}" />
				</div>
			</div>
		</div>
	</form>
</div>
<form
	action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}"
	method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100">{LANG.id_patient}</th>
					<th>{LANG.id_doctor}</th>
					<th>{LANG.prescription}</th>
					<th>{LANG.date_medical}</th>
					<th>{LANG.date_appointment}</th>
					<th>{LANG.money_medical}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>{VIEW.id_patient}</td>
					<td>{VIEW.id_doctor}</td>
					<td>{VIEW.prescription}</td>
					<td>{VIEW.date_medical}</td>
					<td>{VIEW.date_appointment}</td>
					<td>{VIEW.money_medical}</td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i>
						<a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em
						class="fa fa-trash-o fa-lg">&nbsp;</em> <a
						href="{VIEW.link_delete}"
						onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<link type="text/css"
	href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css"
	rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
	<div class="panel-body">
		<form class="form-horizontal"
			action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}"
			method="post">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_patient}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="id_patient"
						id="id_patient" value="{ROW.id_patient}"
						onblur="nv_change_doctor()" required="required"
						oninvalid="setCustomValidity( nv_required )"
						oninput="setCustomValidity('')" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_specialist}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<select class="form-control" name="id_specialist"
						id="id_specialist" onclick="nv_change_doctor()">
						<option value="">---</option>
						<!-- BEGIN: select_id_specialist -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_id_specialist -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_doctor}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<div id="id_doctor_1">
						<select class="form-control" name="id_doctor">
							<option value="">---</option>
							<!-- BEGIN: select_id_doctor -->
							<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
							<!-- END: select_id_doctor -->

						</select>
					</div>
					<div id="id_doctor_2"></div>
				</div>

			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.prescription}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">{ROW.prescription}</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.date_medical}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<div class="input-group">
						<input class="form-control" type="text" name="date_medical"
							value="{ROW.date_medical}" id="date_medical"
							pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$"
							required="required" oninvalid="setCustomValidity( nv_required )"
							oninput="setCustomValidity('')" /> <span class="input-group-btn">
							<button class="btn btn-default" type="button"
								id="date_medical-btn">
								<em class="fa fa-calendar fa-fix"> </em>
							</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.date_appointment}</strong></label>
				<div class="col-sm-19 col-md-20">
					<div class="input-group">
						<input class="form-control" type="text" name="date_appointment"
							value="{ROW.date_appointment}" id="date_appointment"
							pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" /> <span
							class="input-group-btn">
							<button class="btn btn-default" type="button"
								id="date_appointment-btn">
								<em class="fa fa-calendar fa-fix"> </em>
							</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.money_medical}</strong>
					<span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="money_medical"
						value="{ROW.money_medical}" pattern="^[0-9]*$"
						oninvalid="setCustomValidity( nv_digits )"
						oninput="setCustomValidity('')" required="required" />
				</div>
			</div>
			<div class="form-group" style="text-align: center">
				<input class="btn btn-primary" name="submit" type="submit"
					value="{LANG.save}" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript"
	src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$("#date_medical,#date_appointment").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name
				+ '&' + nv_fc_variable + '=history&nocache='
				+ new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid='
				+ new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '='
					+ nv_module_name + '&' + nv_fc_variable + '=history';
			return;
		});
		return;
	}
	$("#id_specialist, #id_doctor").select2();
	$("#id_specialist").change(
			function() {
				var id = $('#id_specialist').val();
				$.post(script_name + '?' + nv_name_variable + '='
						+ nv_module_name + '&' + nv_fc_variable
						+ '=history&nocache=' + new Date().getTime(),
						'id_specialist=' + id, function(res) {
							if (res != '') {
								$('#id_doctor_2').show();
								$("#id_doctor_2").html(res);
								$('#id_doctor_1').hide();
							} else {
								$('#id_doctor_2').hide();
							}
						});
			});

	//]]>
</script>
<!-- END: main -->