<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 19 Jul 2011 09:07:26 GMT
 */

 if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}
global $arr_sex, $arr_status;

$arr_status = array(
    '0' => array(
        'id' => '0',
        'name' => $lang_module['status_patient']
    ),
    '1' => array(
        'id' => '1',
        'name' => $lang_module['status_patient_1']
    ),
    '2' => array(
        'id' => '2',
        'name' => $lang_module['status_patient_2']
    ),
);

$arr_sex = array(
    '1' => array(
        'id' => '1',
        'name' => $lang_module['sex_male']
    ),
    '2' => array(
        'id' => '2',
        'name' => $lang_module['sex_female']
    ),
);