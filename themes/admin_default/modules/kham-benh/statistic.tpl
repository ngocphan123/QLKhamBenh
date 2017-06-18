<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
	<tr>
		<td class="w200">Lịch khám chờ xác nhận</td>
		<td >{NUMBER_ORDER}</td>
	</tr>
	<tr>
		<td class="w200">Lịch khám hoàn thành</td>
		<td >{NUMBER_FINISH}</td>
	</tr>
</table>
<h2>Top bệnh nhân thường xuyên</h2>
<table class="table table-striped table-bordered table-hover">
	<tr>
		<th>STT</th>
		<th>Mã bệnh nhân</th>
		<th>Họ tên</th>
		<th>Email</th>
		<th>Số lượt khám</th>
	</tr>
	<!-- BEGIN: patient -->
	<tr>
		<td>{STT}</td>
		<td>{PATIENT.code_patient}</td>
		<td>{PATIENT.name}</td>
		<td>{PATIENT.email}</td>
		<td>{PATIENT.number_order}</td>
	</tr>
	<!-- END: patient -->
</table>

<!-- END: main -->