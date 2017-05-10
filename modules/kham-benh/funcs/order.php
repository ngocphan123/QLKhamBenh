<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 10 May 2017 16:40:29 GMT
 */

if( ! defined( 'NV_IS_MOD_KHAM-BENH' ) ) die( 'Stop!!!' );

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['id_patient'] = $nv_Request->get_int( 'id_patient', 'post', 0 );
	$row['id_doctor'] = $nv_Request->get_int( 'id_doctor', 'post', 0 );
	$row['date_medical'] = $nv_Request->get_int( 'date_medical', 'post', 0 );
	$row['id_specialist'] = $nv_Request->get_int( 'id_specialist', 'post', 0 );

	if( empty( $row['id_patient'] ) )
	{
		$error[] = $lang_module['error_required_id_patient'];
	}
	elseif( empty( $row['id_doctor'] ) )
	{
		$error[] = $lang_module['error_required_id_doctor'];
	}
	elseif( empty( $row['date_medical'] ) )
	{
		$error[] = $lang_module['error_required_date_medical'];
	}
	elseif( empty( $row['id_specialist'] ) )
	{
		$error[] = $lang_module['error_required_id_specialist'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_order (id_patient, id_doctor, date_medical, id_specialist) VALUES (:id_patient, :id_doctor, :date_medical, :id_specialist)' );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_order SET id_patient = :id_patient, id_doctor = :id_doctor, date_medical = :date_medical, id_specialist = :id_specialist WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_patient', $row['id_patient'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_doctor', $row['id_doctor'], PDO::PARAM_INT );
			$stmt->bindParam( ':date_medical', $row['date_medical'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_specialist', $row['id_specialist'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['id_patient'] = 0;
	$row['id_doctor'] = 0;
	$row['date_medical'] = 0;
	$row['id_specialist'] = 0;
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );


if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['order'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';