<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 09 May 2017 11:44:39 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );


$row = array();
$error = array();
//Số lịch khám chờ xác nhận
$sql = 'SELECT count(*) as number_order FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order WHERE status=0';
$number_order = $db->query($sql)-> fetch();//print_r($number_order);die('ok');

//Lịch khám đã hoàn thành
$sql = 'SELECT count(*) as number FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history';
$number_finish = $db->query($sql)-> fetch();

//Top 20 bệnh nhân thường xuyên
$sql = 'SELECT count(*) as number_order,code_patient, id_patient, name, email  FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order as tb1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_patient tb2 ON ( tb1.id_patient = tb2.id ) group by id_patient order by count(*) DESC limit 20';
$_query = $db->query( $sql );
while($row = $_query-> fetch()){
	$patient[] = $row;

}
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'NUMBER_ORDER', $number_order['number_order'] );
$xtpl->assign( 'NUMBER_FINISH', $number_finish['number'] );
$stt = 0;
foreach($patient as $row){
	$stt++;
	$xtpl->assign( 'STT', $stt );
	$xtpl->assign( 'PATIENT', $row );
	$xtpl->parse( 'main.patient' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['statistic'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';