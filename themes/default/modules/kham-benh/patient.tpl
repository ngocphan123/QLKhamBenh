<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<!-- BEGIN: notification -->
<div class="alert alert-warning">
	{NOTIFICATION}
</div>
<!-- END: notification -->
<!-- BEGIN: error -->
<div class="alert alert-warning">
	{ERROR}
</div>
<!-- END: error -->
<div class="panel panel-default">
	<div class="panel-body">
		<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.type}</strong> <span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<select class="form-control order_type" name="type" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">
						<option value=""> --- </option>
						<option value="0" >{LANG.type_0}</option>
						<option value="1" >{LANG.type_1}</option>
					</select>
				</div>
			</div>
			<div id="type_0">
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.name}</strong> <span class="red">(*)</span></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="name" value="{ROW.name}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.year}</strong></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="year" value="{ROW.year}" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"  />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.email}</strong> <span class="red">(*)</span></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="email" value="{ROW.email}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.phone}</strong> <span class="red">(*)</span></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="phone" value="{ROW.phone}" pattern="^[0-9]*$" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.sex}</strong> <span class="red">(*)</span></label>
					<div class="col-sm-19 col-md-20">

						<!-- BEGIN: radio_sex -->
						<label><input class="form-control" type="radio" name="sex" value="{OPTION.key}" {OPTION.checked}>{OPTION.title} &nbsp;</label>
						<!-- END: radio_sex -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.address}</strong></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="address" value="{ROW.address}" />
					</div>
				</div>
			</div>
			<div id="type_1">
				<div class="form-group">
					<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_patient}</strong></label>
					<div class="col-sm-19 col-md-20">
						<input class="form-control" type="text" name="id_patient" value="{ROW.id_patient}"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.date_medical}</strong> <span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<div class="input-group">
						<input class="form-control" type="text" name="date_medical" value="{ROW.date_medical}" id="date_medical" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="date_medical-btn">
								<em class="fa fa-calendar fa-fix"> </em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_specialist}</strong> <span class="red">(*)</span></label>
				<div class="col-sm-19 col-md-20">
					<select class="form-control" name="id_specialist">
						<option value=""> --- </option>
						<!-- BEGIN: select_id_specialist -->
						<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_id_specialist -->
					</select>
				</div>
			</div>
			<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	$("#date_medical").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});
	$('#type_1').css('display', 'none');
	$('.order_type').change(function() {
		if ($(this).val() == 1) {
			$('#type_0').css('display', 'none');
			$('#type_1').css('display', '');
		} else {
			$('#type_1').css('display', 'none');
			$('#type_0').css('display', '');
		}
	});
</script>
<!-- END: main -->