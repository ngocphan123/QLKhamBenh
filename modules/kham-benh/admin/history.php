<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 15 Jun 2017 16:10:14 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history WHERE id!=' . $id . ' ORDER BY id_patient ASC';
		$result = $db->query( $sql );
		$id_patient = 0;
		while( $row = $result->fetch() )
		{
			++$id_patient;
			if( $id_patient == $new_vid ) ++$id_patient;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_history SET id_patient=' . $id_patient . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_history SET id_patient=' . $new_vid . ' WHERE id=' . $id;
		$db->query( $sql );
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
$id_specialist= $nv_Request->get_int( 'id_specialist', 'post', 0 );
$doctor = 0;
if(!empty($id_specialist) and !($nv_Request->isset_request( 'submit', 'post' ))) {
    $result_doctor = $db->query('SELECT `id`, `name` FROM ' . NV_PREFIXLANG . '_' . $module_data . '_doctor WHERE `specialist_id` = '.$id_specialist);
    $select_doctor='<select class="form-control" name="id_doctor">';
    while($row_doctor = $result_doctor->fetch()) {
        $select_doctor .='<option value="'.$row_doctor['id'].'">'.$row_doctor['name'].'</option>';
    }
    $select_doctor .='</select>';
    die($select_doctor);
    
}
if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$id_patient=0;
		$sql = 'SELECT id_patient FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $id_patient) = $result->fetch( 3 );
		
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history  WHERE id = ' . $db->quote( $id ) );
		if( $id_patient > 0)
		{
			$sql = 'SELECT id, id_patient FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history WHERE id_patient >' . $id_patient;
			$result = $db->query( $sql );
			while(list( $id, $id_patient) = $result->fetch( 3 ))
			{
				$id_patient--;
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_history SET id_patient=' . $id_patient . ' WHERE id=' . intval( $id ));
			}
		}
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
	$row['prescription'] = $nv_Request->get_editor( 'prescription', '', NV_ALLOWED_HTML_TAGS );
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
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'date_appointment', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['date_appointment'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['date_appointment'] = 0;
	}
	$row['money_medical'] = $nv_Request->get_int( 'money_medical', 'post', 0 );

	if( empty( $row['id_patient'] ) )
	{
		$error[] = $lang_module['error_required_id_patient'];
	}
	elseif( empty( $row['id_doctor'] ) )
	{
		$error[] = $lang_module['error_required_id_doctor'];
	}
	elseif( empty( $row['prescription'] ) )
	{
		$error[] = $lang_module['error_required_prescription'];
	}
	elseif( empty( $row['date_medical'] ) )
	{
		$error[] = $lang_module['error_required_date_medical'];
	}
	elseif( empty( $row['money_medical'] ) )
	{
		$error[] = $lang_module['error_required_money_medical'];
	}

	$insert = false;
	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_history (id_patient, id_doctor, prescription, date_medical, date_appointment, money_medical) VALUES (:id_patient, :id_doctor, :prescription, :date_medical, :date_appointment, :money_medical)' );
				$insert = true;
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_history SET id_patient = :id_patient, id_doctor = :id_doctor, prescription = :prescription, date_medical = :date_medical, date_appointment = :date_appointment, money_medical = :money_medical WHERE id=' . $row['id'] );
				$insert = false;
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
			    if($insert and !empty($row['date_appointment'])) {
			        $id_specialist= $db->query('SELECT `specialist_id` FROM `nv4_vi_kham_benh_doctor` WHERE `id` = '.$row['id_doctor'])->fetchColumn();
			        $db->query('INSERT INTO `nv4_vi_kham_benh_order`( `id_patient`, `id_doctor`, `date_medical`, `id_specialist`, `type`, `status`) VALUES ('.$row['id_patient'].','.$row['id_doctor'].','.$db->quote($row['date_appointment']).','.$id_specialist.',1,1)');
			    }
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

if( empty( $row['date_medical'] ) )
{
	$row['date_medical'] = '';
}
else
{
	$row['date_medical'] = date( 'd/m/Y', $row['date_medical'] );
}

if( empty( $row['date_appointment'] ) )
{
	$row['date_appointment'] = '';
}
else
{
	$row['date_appointment'] = date( 'd/m/Y', $row['date_appointment'] );
}

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$row['prescription'] = htmlspecialchars( nv_editor_br2nl( $row['prescription'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['prescription'] = nv_aleditor( 'prescription', '100%', '300px', $row['prescription'] );
}
else
{
	$row['prescription'] = '<textarea style="width:100%;height:300px" name="prescription">' . $row['prescription'] . '</textarea>';
}

$array_id_doctor_kham_benh = array();
$_sql = 'SELECT id,name FROM nv4_vi_kham_benh_doctor';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_id_doctor_kham_benh[$_row['id']] = $_row;
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
		$db->where( 'id_doctor LIKE :q_id_doctor OR prescription LIKE :q_prescription OR date_medical LIKE :q_date_medical OR date_appointment LIKE :q_date_appointment OR money_medical LIKE :q_money_medical' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_id_doctor', '%' . $q . '%' );
		$sth->bindValue( ':q_prescription', '%' . $q . '%' );
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_date_appointment', '%' . $q . '%' );
		$sth->bindValue( ':q_money_medical', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'id_patient ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_id_doctor', '%' . $q . '%' );
		$sth->bindValue( ':q_prescription', '%' . $q . '%' );
		$sth->bindValue( ':q_date_medical', '%' . $q . '%' );
		$sth->bindValue( ':q_date_appointment', '%' . $q . '%' );
		$sth->bindValue( ':q_money_medical', '%' . $q . '%' );
	}
	$sth->execute();
}

//chuyên khoa
$array_id_specialist_kham_benh = array();
$_sql = 'SELECT id,name_specialist FROM nv4_vi_kham_benh_specialist';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_id_specialist_kham_benh[$_row['id']] = $_row;
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
	    'selected' => ($value['id'] == $row['id_doctor'] || $value['id'] == $doctor) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_id_doctor' );
	
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
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['id_patient'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.id_patient_loop' );
		}
		$view['date_medical'] = ( empty( $view['date_medical'] )) ? '' : nv_date( 'd/m/Y', $view['date_medical'] );
		$view['date_appointment'] = ( empty( $view['date_appointment'] )) ? '' : nv_date( 'd/m/Y', $view['date_appointment'] );
		$view['id_doctor'] = $array_id_doctor_kham_benh[$view['id_doctor']]['name'];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$view['money_medical'] = number_format($view['money_medical']);
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

//chuyen khoa
foreach ($array_id_specialist_kham_benh as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['name_specialist'],
    ));
    $xtpl->parse('main.select_id_specialist');
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