<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sun, 07 May 2017 10:16:02 GMT
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
//test
$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_doctor";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_drug";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_history";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_patient";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_specialist";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_doctor(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ&tên bác sĩ',
  datetime int(11) unsigned NOT NULL COMMENT 'Ngày tháng năm sinh',
  specialist_id int(11) unsigned NOT NULL COMMENT 'Chuyên khoa',
  position varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Chức vụ',
  address varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa chỉ',
  phone varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Số điện thoại',
  business varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nơi công tác',
  story text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiểu sử',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_specialist(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name_specialist varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Chuyên khoa',
  description text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Giới thiệu',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_history(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  id_patient int(11) NOT NULL COMMENT 'Mã bệnh nhân',
  id_doctor int(11) unsigned NOT NULL COMMENT 'Bác sĩ trực',
  prescription text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Đơn thuốc',
  date_medical int(11) unsigned NOT NULL COMMENT 'Ngày khám bệnh',
  date_appointment int(11) unsigned NOT NULL COMMENT 'Ngày hẹn tiếp theo (Hẹn tái khám)',
  money_medical int(11) unsigned NOT NULL COMMENT 'Tiền khám',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_patient(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  code_patient varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã bệnh nhân',
  name varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ & tên',
  year smallint(4) unsigned NOT NULL COMMENT 'Tuổi',
  email varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email',
  phone int(11) unsigned NOT NULL COMMENT 'Số điện thoại',
  sex tinyint(1) unsigned NOT NULL COMMENT 'Giới tính',
  address varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa chỉ',
  status tinyint(1) unsigned NOT NULL COMMENT 'Trạng thái khám bệnh',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_drug(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  code_drug varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã Thuốc',
  name varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên thuốc',
  date_produce int(11) unsigned NOT NULL COMMENT 'Ngày sản xuất',
  date_import int(11) unsigned NOT NULL COMMENT 'Ngày nhập',
  time_expired int(11) unsigned NOT NULL COMMENT 'Ngày hết hạn',
  money int(11) unsigned NOT NULL COMMENT 'Giá thuốc',
  info_drug text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Thông tin thuốc',
  status tinyint(1) unsigned NOT NULL COMMENT 'Còn thuốc hay hết thuốc',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_order(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  id_patient int(11) NOT NULL COMMENT 'Mã bệnh nhân',
  id_doctor int(11)  NOT NULL COMMENT 'Bác sĩ',
  date_medical int(11)  NOT NULL COMMENT 'ngày khám',
  id_specialist int(11)  NOT NULL COMMENT 'Chuyên khoa',
  type TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Loại khám bệnh',
  status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Trạng thái duyệt',
  PRIMARY KEY (id)
) ENGINE=MyISAM";