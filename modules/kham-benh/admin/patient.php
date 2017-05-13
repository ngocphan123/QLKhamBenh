<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 09 May 2017 09:28:25 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_patient  WHERE id = ' . $db->quote($id));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['code_patient'] = $nv_Request->get_title('code_patient', 'post', '');
    $row['name'] = $nv_Request->get_title('name', 'post', '');
    $row['year'] = $nv_Request->get_int('year', 'post', 0);
    $row['email'] = $nv_Request->get_title('email', 'post', '');
    $row['phone'] = $nv_Request->get_title('phone', 'post', '');
    $row['sex'] = $nv_Request->get_int('sex', 'post', 0);
    $row['address'] = $nv_Request->get_title('address', 'post', '');
    $row['status'] = $nv_Request->get_int('status', 'post', 0);

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_patient (code_patient, name, year, email, phone, sex, address, status) VALUES (:code_patient, :name, :year, :email, :phone, :sex, :address, :status)');
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_patient SET code_patient = :code_patient, name = :name, year = :year, email = :email, phone = :phone, sex = :sex, address = :address, status = :status WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':code_patient', $row['code_patient'], PDO::PARAM_STR);
            $stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
            $stmt->bindParam(':year', $row['year'], PDO::PARAM_INT);
            $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $row['phone'], PDO::PARAM_INT);
            $stmt->bindParam(':sex', $row['sex'], PDO::PARAM_INT);
            $stmt->bindParam(':address', $row['address'], PDO::PARAM_STR);
            $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);

            $exc = $stmt->execute();
            if ($exc) {
                $last_id = '';
                $code_patient = '';
                if (empty($row['id'])) {
                    $last_id = $db->lastInsertId();

                } else {
                    $last_id = $row['id'];
                }
                $code_patient = 'BN' . $last_id;
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_patient SET code_patient = ' . $db->quote($code_patient) . ' WHERE id = ' . $last_id);
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch( PDOException $e ) {
            trigger_error($e->getMessage());
            die($e->getMessage());
            //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_patient WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['code_patient'] = '';
    $row['name'] = '';
    $row['year'] = 0;
    $row['email'] = '';
    $row['phone'] = 0;
    $row['sex'] = 0;
    $row['address'] = '';
    $row['status'] = 0;
}

$q = $nv_Request->get_title('q', 'post,get');
$status_key = $nv_Request->get_title('status_key', 'post,get');

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_patient');
    $where = '';
    if (!empty($q)) {
        $where = 'name LIKE :q_name OR year LIKE :q_year OR email LIKE :q_email OR phone LIKE :q_phone OR sex LIKE :q_sex OR address LIKE :q_address OR status LIKE :q_status';
    }
    if (!empty($status_key)) {
        if (!empty($where)) {
            $where = '(' . $where . ') AND status =' . $status_key;
        } else {
            $where .= 'status =' . $status_key;
        }
    }
    if (!empty($where)) {
        $db->where($where);
    }
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_name', '%' . $q . '%');
        $sth->bindValue(':q_year', '%' . $q . '%');
        $sth->bindValue(':q_email', '%' . $q . '%');
        $sth->bindValue(':q_phone', '%' . $q . '%');
        $sth->bindValue(':q_sex', '%' . $q . '%');
        $sth->bindValue(':q_address', '%' . $q . '%');
        $sth->bindValue(':q_status', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')->order('id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_name', '%' . $q . '%');
        $sth->bindValue(':q_year', '%' . $q . '%');
        $sth->bindValue(':q_email', '%' . $q . '%');
        $sth->bindValue(':q_phone', '%' . $q . '%');
        $sth->bindValue(':q_sex', '%' . $q . '%');
        $sth->bindValue(':q_address', '%' . $q . '%');
        $sth->bindValue(':q_status', '%' . $q . '%');
    }
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

$xtpl->assign('Q', $q);

if ($show_view) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    if (!empty($q)) {
        $base_url .= '&q=' . $q;
    }
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }
    $number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
    while ($view = $sth->fetch()) {
        if ($view['sex'] == 1) {
            $view['sex'] = $lang_module['sex_male'];
        } else {
            $view['sex'] = $lang_module['sex_female'];
        }
        if ($view['status'] == 1) {
            $view['status'] = $lang_module['status_patient_1'];
        } else {
            $view['status'] = $lang_module['status_patient_2'];
        }
        $view['number'] = $number++;
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    foreach ($arr_status as $status) {
        //$status['selected'] = ($status['id'] == $row['status']) ? 'selected' : '';
        $xtpl->assign('OPTION_STATUS', $status);
        $xtpl->parse('main.view.select_status');

    }
    $xtpl->parse('main.view');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}
foreach ($arr_sex as $sex) {
    $sex['checked'] = ($sex['id'] == $row['sex']) ? ' checked="checked"' : '';
    $xtpl->assign('OPTION_SEX', $sex);
    $xtpl->parse('main.radio_sex');

}
foreach ($arr_status as $status) {
    $status['selected'] = ($status['id'] == $row['status']) ? 'selected' : '';
    $xtpl->assign('OPTION_STATUS', $status);
    $xtpl->parse('main.select_status');

}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['patient'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
