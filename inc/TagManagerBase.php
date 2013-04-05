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

namespace PMG\GoogleTagManager;

abstract class TagManagerBase
{
    const OPTION = 'pmg_gtm_main';

    private $project;
    
    private static $reg = array();
    
    public static function instance()
    {
        $cls = get_called_class();

        if (!isset(self::$reg[$cls])) {
            self::$reg[$cls] = new $cls;
        }

        return self::$reg[$cls];
    }

    public static function init()
    {
        add_action('plugins_loaded', array(static::instance(), '_setup'));
    }

    public static function opt($key, $default=null)
    {
        $opts = get_option(static::OPTION, array());
        return array_key_exists($key, $opts) ? $opts[$key] : $default;
    }

    abstract public function _setup();
}
