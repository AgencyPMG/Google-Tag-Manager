<?php
/**
 * Plugin Name: Google Tag Manager
 * Plugin URI: https://github.com/AgencyPMG/Google-Tag-Manager
 * Description: Add google tag manager to your WordPress site.
 * Version: 1.0
 * Text Domain: pmg-google-tag-manager
 * Author: Christopher Davis
 * Author URI: http://pmg.co/people/chris
 * License: GPL-2.0+
 *
 * Copyright 2013 Performance Media Group <http://pmg.co>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category    WordPress
 * @package     GoogleTagManager
 * @since       1.0
 * @author      Christopher Davis <chris@pmg.co>
 * @copyright   2013 Performance Media Group
 * @license     http://opensource.org/licenses/GPL-2.0 GPL-2.0+
 */

namespace PMG\GoogleTagManager;

!defined('ABSPATH') && exit;

define('PMG_GTM_TD', 'pmg-google-tag-manager');

require_once __DIR__ . '/inc/functions.php';

register_activation_hook(__FILE__, 'pmg_gtm_activate');

add_action('plugins_loaded', 'pmg_gtm_load', 5);
