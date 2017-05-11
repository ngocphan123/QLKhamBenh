<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 11 May 2017 16:55:15 GMT
 */

if( ! defined( 'NV_IS_MOD_KHAM-BENH' ) ) die( 'Stop!!!' );

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'date_medical', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['date_medical'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['date_medical'] = 0;
	}
	$row['id_specialist'] = $nv_Request->get_int( 'id_specialist', 'post', 0 );

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{

				$row['id_patient'] = 0;
				$row['id_doctor'] = 0;

				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_order (id_patient, id_doctor, date_medical, id_specialist) VALUES (:id_patient, :id_doctor, :date_medical, :id_specialist)' );

				$stmt->bindParam( ':id_patient', $row['id_patient'], PDO::PARAM_INT );
				$stmt->bindParam( ':id_doctor', $row['id_doctor'], PDO::PARAM_INT );

			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_order SET date_medical = :date_medical, id_specialist = :id_specialist WHERE id=' . $row['id'] );
			}
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
	$row['date_medical'] = 0;
	$row['id_specialist'] = 0;
}

if( empty( $row['date_medical'] ) )
{
	$row['date_medical'] = '';
}
else
{
	$row['date_medical'] = date( 'd/m/Y', $row['date_medical'] );
}
$array_id_specialist_kham_benh = array();
$_sql = 'SELECT id,name_specialist FROM nv4_vi_kham_benh_specialist';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_id_specialist_kham_benh[$_row['id']] = $_row;
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

foreach( $array_id_specialist_kham_benh as $value )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $value['id'],
		'title' => $value['name_specialist'],
		'selected' => ($value['id'] == $row['id_specialist']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_id_specialist' );
}

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