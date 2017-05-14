<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 14 May 2017 15:02:38 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history  WHERE id = ' . $db->quote( $id ) );
		$nv_Cache->delMod( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['id_patient'] = $nv_Request->get_int( 'id_patient', 'post', 0 );
	$row['id_doctor'] = $nv_Request->get_int( 'id_doctor', 'post', 0 );
	$row['prescription'] = $nv_Request->get_string( 'prescription', 'post', '' );
	$row['date_medical'] = $nv_Request->get_int( 'date_medical', 'post', 0 );
	$row['date_appointment'] = $nv_Request->get_int( 'date_appointment', 'post', 0 );
	$row['money_medical'] = $nv_Request->get_int( 'money_medical', 'post', 0 );

	if( empty( $row['id_patient'] ) )
	{
		$error[] = $lang_module['error_required_id_patient'];
	}
	elseif( empty( $row['id_doctor'] ) )
	{
		$error[] = $lang_module['error_required_id_doctor'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_history (id_patient, id_doctor, prescription, date_medical, date_appointment, money_medical) VALUES (:id_patient, :id_doctor, :prescription, :date_medical, :date_appointment, :money_medical)' );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_history SET id_patient = :id_patient, id_doctor = :id_doctor, prescription = :prescription, date_medical = :date_medical, date_appointment = :date_appointment, money_medical = :money_medical WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':id_patient', $row['id_patient'], PDO::PARAM_INT );
			$stmt->bindParam( ':id_doctor', $row['id_doctor'], PDO::PARAM_INT );
			$stmt->bindParam( ':prescription', $row['prescription'], PDO::PARAM_STR, strlen($row['prescription']) );
			$stmt->bindParam( ':date_medical', $row['date_medical'], PDO::PARAM_INT );
			$stmt->bindParam( ':date_appointment', $row['date_appointment'], PDO::PARAM_INT );
			$stmt->bindParam( ':money_medical', $row['money_medical'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
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
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['id_patient'] = 0;
	$row['id_doctor'] = 0;
	$row['prescription'] = '';
	$row['date_medical'] = 0;
	$row['date_appointment'] = 0;
	$row['money_medical'] = 0;
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . NV_PREFIXLANG . '_' . $module_data . '_history' );

	if( ! empty( $q ) )
	{
		$db->where( 'id_patient LIKE :q_id_patient OR id_doctor LIKE :q_id_doctor OR prescription LIKE :q_prescription OR date_medical LIKE :q_date_medical OR date_appointment LIKE :q_date_appointment OR money_medical LIKE :q_money_medical' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_id_patient', '%' . $q . '%' );
		$sth->bindValue( ':q_id_doctor', '%' . $q . '%' );
		$sth->bindValue( ':q_prescription', '%' . $q . '%' );
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_date_appointment', '%' . $q . '%' );
		$sth->bindValue( ':q_money_medical', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'id DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_id_patient', '%' . $q . '%' );
		$sth->bindValue( ':q_id_doctor', '%' . $q . '%' );
		$sth->bindValue( ':q_prescription', '%' . $q . '%' );
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_date_appointment', '%' . $q . '%' );
		$sth->bindValue( ':q_money_medical', '%' . $q . '%' );
	}
	$sth->execute();
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
$xtpl->assign( 'ROW', $row );

$xtpl->assign( 'Q', $q );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if( ! empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	while( $view = $sth->fetch() )
	{
		$view['number'] = $number++;
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}


if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['history'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';