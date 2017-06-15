<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<!-- BEGIN: view -->
<div class="well">
<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<div class="row">
		<div class="col-xs-24 col-md-6">
			<div class="form-group">
				<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_title}" />
			</div>
		</div>
		<div class="col-xs-12 col-md-3">
			<div class="form-group">
				<input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
			</div>
		</div>
	</div>
</form>
</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100">{LANG.number}</th>
					<th>{LANG.id_patient}</th>
					<th>{LANG.id_doctor}</th>
					<th>{LANG.date_medical}</th>
					<th>{LANG.hour_medical}</th>
					<th>{LANG.id_specialist}</th>
					<th>{LANG.type}</th>
					<th>{LANG.status}</th>
					<th class="w100 text-center">{LANG.active}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="9">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.number} </td>
					<td> {VIEW.id_patient} </td>
					<td> {VIEW.id_doctor} </td>
					<td> {VIEW.date_medical} </td>
					<td> {VIEW.hours_medical} </td>
					<td> {VIEW.id_specialist} </td>
					<td> {VIEW.type} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a><!--  - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a> --></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<input type="hidden" name="id_patient_edit" value="{ROW.id_patient}"" />
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_patient}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text"  name="id_patient" value="{ROW.id_patient}" pattern="^[0-9]*$" {DISABLED} oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_doctor}</strong></label>
		<div class="col-sm-19 col-md-20">
			<select class="form-control" name="id_doctor">
				<option value=""> --- </option>
				<!-- BEGIN: select_id_doctor -->
				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
				<!-- END: select_id_doctor -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.date_medical}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="date_medical" id="date_medical" value="{ROW.date_medical}"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_specialist}</strong></label>
		<div class="col-sm-19 col-md-20">
			<select class="form-control" name="id_specialist">
				<option value=""> --- </option>
				<!-- BEGIN: select_id_specialist -->
				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
				<!-- END: select_id_specialist -->
			</select>
		</div>
	</div>
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="confirm" type="submit" value="{LANG.confirm}" /></div>
</form>
</div></div>

<script type="text/javascript">
//<![CDATA[
	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=order&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert(nv_is_change_act_confirm[2]);
				}
			});
		}
		else{
			$('#change_status_' + id).prop('checked', new_status ? false : true );
		}
		return;
	}


//]]>
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	$("#date_medical").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

</script>
<!-- END: main -->