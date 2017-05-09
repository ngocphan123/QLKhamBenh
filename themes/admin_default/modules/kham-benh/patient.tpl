<!-- BEGIN: main -->
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
			<div class="col-xs-24 col-md-10">
				<div class="col-sm-19 col-md-20">
					<select class="form-control" name="status_key">
						<!-- BEGIN: select_status -->
						<option value="{OPTION_STATUS.id}" {OPTION_STATUS.selected}>{OPTION_STATUS.name}</option>
						<!-- END: select_status -->
					</select>
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
					<th>{LANG.name_patient}</th>
					<th>{LANG.year}</th>
					<th>{LANG.email}</th>
					<th>{LANG.phone}</th>
					<th>{LANG.sex}</th>
					<th>{LANG.address}</th>
					<th>{LANG.status_patient}</th>
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
					<td> {VIEW.name} </td>
					<td> {VIEW.year} </td>
					<td> {VIEW.email} </td>
					<td> {VIEW.phone} </td>
					<td> {VIEW.sex} </td>
					<td> {VIEW.address} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">
	{ERROR}
</div>
<!-- END: error -->
<div class="panel panel-default">
	<div class="panel-body">
		<form class="form-horizontal" id ="insert_patient" name= "insert_patient" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.name_patient}</strong></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="name" value="{ROW.name}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.year}</strong></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="year" value="{ROW.year}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.email}</strong></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="email" value="{ROW.email}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.phone}</strong></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="phone" value="{ROW.phone}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.sex}</strong></label>
				<div class="col-sm-19 col-md-20">

					<!-- BEGIN: radio_sex -->
					<label><input class="form-control" type="radio" name="sex" value="{OPTION_SEX.id}" {OPTION_SEX.checked}>{OPTION_SEX.name} &nbsp;</label>
					<!-- END: radio_sex -->
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.address}</strong></label>
				<div class="col-sm-19 col-md-20">
					<input class="form-control" type="text" name="address" value="{ROW.address}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.status_patient}</strong></label>
				<div class="col-sm-19 col-md-20">
					<select class="form-control" name="status">
						<!-- BEGIN: select_status -->
						<option value="{OPTION_STATUS.id}" {OPTION_STATUS.selected}>{OPTION_STATUS.name}</option>
						<!-- END: select_status -->
					</select>
				</div>
			</div>
			<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/additional-methods.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() {
		alert('abc');
		$('#insert_patient').validate({// initialize the plugin
			rules : {
				name : {
					required : true,
					minlength : 5
				},
				email : {
					required : true,
					email : true
				},
				year : {
					required : true,
					number : true,
					min: 1
				},
				phone : {
					required : true,
					number : true
				},
				sex : {
					required : true,
				},
				address : {
					required : true,
					minlength : 5
				},
				status : {
					required : true,
				}
			},
			messages : {
				name : {
					required : '{LANG.error_patient_empty}'
				},
				email : {
					required : '{LANG.error_patient_empty}',
					email : '{LANG.error_patient_email}'
				},
				sex : {
					required : '{LANG.error_patient_empty}'
				},
				address : {
					required : '{LANG.error_patient_empty}'
				},
				status : {
					required : '{LANG.error_patient_empty}'
				},
				year : {
					min : '{LANG.error_patient_year}'
				},
			},
			submitHandler : function(form) {// for demo
				alert('valid form submitted');
				return false;
			}
		});
	});
</script>
<!-- END: main -->