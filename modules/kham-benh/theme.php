<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Fri, 03 Feb 2017 08:08:32 GMT
 */

if ( ! defined( 'NV_IS_MOD_SIM_SO_DEP' ) ) die( 'Stop!!!' );

/**
 * nv_theme_sim_so_dep_main()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_kham_benh_main ( $array_result )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}
