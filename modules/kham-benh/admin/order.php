<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 11 May 2017 17:56:58 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order  WHERE id = ' . $db->quote( $id ) );
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
	$row['date_medical'] = $nv_Request->get_int( 'date_medical', 'post', 0 );
	$row['id_specialist'] = $nv_Request->get_int( 'id_specialist', 'post', 0 );

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
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_order WHERE id=' . $row['id'] )->fetch();
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
	$row['date_medical'] = 0;
	$row['id_specialist'] = 0;
}
$array_id_doctor_kham_benh = array();
$_sql = 'SELECT id,name FROM nv4_vi_kham_benh_doctor';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_id_doctor_kham_benh[$_row['id']] = $_row;
}

$array_date_medical_kham_benh = array();
$_sql = 'SELECT id,name FROM nv4_vi_kham_benh_drug';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_date_medical_kham_benh[$_row['id']] = $_row;
}

$array_id_specialist_kham_benh = array();
$_sql = 'SELECT id,name_specialist FROM nv4_vi_kham_benh_specialist';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_id_specialist_kham_benh[$_row['id']] = $_row;
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
		->from( '' . NV_PREFIXLANG . '_' . $module_data . '_order' );

	if( ! empty( $q ) )
	{
		$db->where( 'id_patient LIKE :q_id_patient OR id_doctor LIKE :q_id_doctor OR date_medical LIKE :q_date_medical OR id_specialist LIKE :q_id_specialist' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_id_patient', '%' . $q . '%' );
		$sth->bindValue( ':q_id_doctor', '%' . $q . '%' );
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_id_specialist', '%' . $q . '%' );
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
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_id_specialist', '%' . $q . '%' );
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

foreach( $array_id_doctor_kham_benh as $value )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $value['id'],
		'title' => $value['name'],
		'selected' => ($value['id'] == $row['id_doctor']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_id_doctor' );
}
foreach( $array_date_medical_kham_benh as $value )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $value['id'],
		'title' => $value['name'],
		'selected' => ($value['id'] == $row['date_medical']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_date_medical' );
}
foreach( $array_id_specialist_kham_benh as $value )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $value['id'],
		'title' => $value['name_specialist'],
		'selected' => ($value['id'] == $row['id_specialist']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_id_specialist' );
}
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
		$view['id_doctor'] = $array_id_doctor_kham_benh[$view['id_doctor']]['name'];
		$view['date_medical'] = $array_date_medical_kham_benh[$view['date_medical']]['name'];
		$view['id_specialist'] = $array_id_specialist_kham_benh[$view['id_specialist']]['name_specialist'];
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

$page_title = $lang_module['order'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';