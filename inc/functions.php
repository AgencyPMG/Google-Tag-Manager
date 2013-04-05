<?php
/**
 * PMG Google Tag Manager
 *
 * @category    WordPress
 * @package     GoogleTagManager
 * @since       1.0
 * @author      Christopher Davis <chris@pmg.co>
 * @copyright   2013 Performance Media Group
 * @license     http://opensource.org/licenses/GPL-2.0 GPL-2.0+
 */

function pmg_gtm_load()
{
    require_once __DIR__ . '/TagManagerBase.php';

    if (is_admin()) {
        require_once __DIR__ . '/Admin.php';
        \PMG\GoogleTagManager\Admin::init();
    } else {
        require_once __DIR__ . '/Front.php';
        \PMG\GoogleTagManager\Front::init();
    }

    do_action('pmg_gtm_loaded');
}

function pmg_gtm_activate()
{
    add_option('pmg_gtm_main', array(
        'id'            => '',
        'in_footer'     => 'on',
    ));
}

/**
 * Template tag if users want to manually add the code.
 *
 * @since   1.0
 * @return  void
 */
function google_tag_manager()
{
    \PMG\GoogleTagManager\Front::instance()->act();
}
