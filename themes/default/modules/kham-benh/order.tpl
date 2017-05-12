<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.date_medical}</strong></label>
		<div class="col-sm-19 col-md-20">
			<div class="input-group">
			<input class="form-control" type="text" name="date_medical" value="{ROW.date_medical}" id="date_medical" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="date_medical-btn">
						<em class="fa fa-calendar fa-fix"> </em>
					</button> </span>
				</div>
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
</form>
</div></div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
//<![CDATA[
	$("#date_medical").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

//]]>
</script>
<!-- END: main -->