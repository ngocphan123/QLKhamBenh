<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 11 May 2017 16:47:01 GMT
 */

if (!defined('NV_IS_MOD_KHAM-BENH'))
    die('Stop!!!');

$page_title = $module_info['site_title'];
$row = array();
$error = array();
$notification = '';
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $type = $nv_Request->get_int('type', 'post', 0);
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('date_medical', 'post'), $m)) {
        $_hour = 0;
        $_min = 0;
        $row['date_medical'] = mktime($_hour, $_min, 0, $m[2], $m[1], $m[3]);
    } else {
        $row['date_medical'] = 0;
    }
    $row['id_specialist'] = $nv_Request->get_int('id_specialist', 'post', 0);
    if ($type == 1) {
        $row['id'] = 0;
        $row['name'] = '';
        $row['year'] = 0;
        $row['email'] = '';
        $row['phone'] = 0;
        $row['sex'] = 0;
        $row['address'] = '';
        $row['id_specialist'] = 0;
        $id_patient = $nv_Request->get_title('id_patient', 'post', '');
        if (empty($id_patient)) {
            $error[] = $lang_module['error_required_patient'];
        }
        if (empty($error)) {
            $str = explode('BN', $id_patient);
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_order(id_patient, date_medical, id_specialist, type)VALUES(' . $str[1] . ',' . $row['date_medical'] . ',' . $row['id_specialist'] . ',' . $type . ')';
            $db->query($sql);
            $nv_Cache->delMod($module_name);
            $notification = sprintf($lang_module['notification'], $id_patient);
            //Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            //die();
        }
    } else {
        $row['name'] = $nv_Request->get_title('name', 'post', '');
        $row['year'] = $nv_Request->get_int('year', 'post', 0);
        $row['email'] = $nv_Request->get_title('email', 'post', '');
        $row['phone'] = $nv_Request->get_title('phone', 'post', '');
        $row['sex'] = $nv_Request->get_int('sex', 'post', 0);
        $row['address'] = $nv_Request->get_title('address', 'post', '');
        if (empty($row['name'])) {
            $error[] = $lang_module['error_required_name'];
        } elseif (empty($row['email'])) {
            $error[] = $lang_module['error_required_email'];
        } elseif (empty($row['phone'])) {
            $error[] = $lang_module['error_required_phone'];
        } elseif (empty($row['sex'])) {
            $error[] = $lang_module['error_required_sex'];
        }
        if (empty($error)) {
            $row['code_patient'] = '';
            $row['status'] = 1;
            try {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_patient (code_patient, name, year, email, phone, sex, address, status) VALUES (:code_patient, :name, :year, :email, :phone, :sex, :address, :status)');

                $stmt->bindParam(':code_patient', $row['code_patient'], PDO::PARAM_STR);
                $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
                $stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
                $stmt->bindParam(':year', $row['year'], PDO::PARAM_INT);
                $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
                $stmt->bindParam(':phone', $row['phone'], PDO::PARAM_INT);
                $stmt->bindParam(':sex', $row['sex'], PDO::PARAM_INT);
                $stmt->bindParam(':address', $row['address'], PDO::PARAM_STR);
                $exc = $stmt->execute();
                if ($exc) {
                    $id_patien = $db->lastInsertId();
                    $code_patient = 'BN' . $id_patien;
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_patient SET code_patient = ' . $db->quote($code_patient) . ' WHERE id = ' . $id_patien);
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_order(id_patient, date_medical, id_specialist, type)VALUES(' . $id_patien . ',' . $row['date_medical'] . ',' . $row['id_specialist'] . ',' . $type . ')';
                    $db->query($sql);
                    $nv_Cache->delMod($module_name);
                    $notification = sprintf($lang_module['notification'], $code_patient);
                    //Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                    //die();
                }
            } catch( PDOException $e ) {
                trigger_error($e->getMessage());
                die($e->getMessage());
                //Remove this line after checks finished
            }

        }
    }
}

$array_sex = array();
$array_sex[1] = 'Nam';
$array_sex[2] = 'Nữ';

$array_id_specialist_kham_benh = array();
$_sql = 'SELECT id,name_specialist FROM nv4_vi_kham_benh_specialist';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_id_specialist_kham_benh[$_row['id']] = $_row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);

foreach ($array_sex as $key => $title) {
    $xtpl->assign('OPTION', array(
        'key' => $key,
        'title' => $title,
    ));
    $xtpl->parse('main.radio_sex');
}
foreach ($array_id_specialist_kham_benh as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['name_specialist'],
    ));
    $xtpl->parse('main.select_id_specialist');
}
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

if (!empty($notification)) {
    $xtpl->assign('NOTIFICATION', $notification);
    $xtpl->parse('main.notification');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['patient'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
