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

class Front extends TagManagerBase
{
    const ACT = 'pmg_gtm_display';

    public function _setup()
    {
        if (static::opt('in_footer') === 'on') {
            add_action('wp_footer', array($this, 'act'), 1000);
        }

        // do nothing without an ID
        if (!static::opt('id')) {
            return;
        }

        add_action(static::ACT, array($this, 'outputDataLayer'), 9);
        add_action(static::ACT, array($this, 'outputTracking'));
    }

    public function act()
    {
        do_action(static::ACT);
    }

    public function outputDataLayer()
    {
        $layer = $this->getDataLayerVariables();

        if (!is_array($layer)) {
            $layer = array();
        }

        ?>
        <script>
            dataLayer = dataLayer || [];
            <?php if ($layer): ?>
                dataLayer.push(<?php echo json_encode($layer); ?>);
            <?php endif; ?>
        </script>
        <?php
    }

    public function outputTracking()
    {
        $id = static::opt('id'); // we checked for the ID above

        ob_start();

        ?>
        <!-- Google Tag Manager -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo urlencode($id); ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo esc_js($id); ?>');</script>
        <!-- End Google Tag Manager -->
        <?php

        echo apply_filters('pmg_gtm_tracking_code', ob_get_clean());
    }

    private function getDataLayerVariables()
    {
        $layer = array();

        if (apply_filters('pmg_gtm_user_in_datalayer', is_user_logged_in())) {
            $layer['current_user'] = wp_get_current_user()->user_login;
        }

        $obj = get_queried_object();

        if (is_front_page() && 'page' == get_option('show_on_front')) {
            $layer['page_type'] = 'homepage';
            $layer['post_type'] = isset($obj->post_type) ? $obj->post_type : 'page';
        } elseif (is_home()) {
            $layer['page_type'] = 'blog';
        } elseif(is_singular()) {
            $layer['page_type'] = 'singular';
            $layer['post_type'] = isset($obj->post_type) ? $obj->post_type : 'post';
        } elseif (is_post_type_archive()) {
            $layer['page_type'] = 'post_type_archive';
            $layer['post_type'] = isset($obj->name) ? $obj->name : null;
        } elseif (is_date()) {
            if (is_year()) {
                $layer['page_type'] = 'year_archive';
            } elseif (is_month()) {
                $layer['page_type'] = 'month_archive';
            } elseif (is_day()) {
                $layaer['page_type'] = 'day_archive';
            } else {
                $layer['page_type'] = 'date_archive';
            }
        } elseif (is_tax() || is_tag() || is_category()) {
            $layer['page_type'] = 'taxonomy';
            $layer['taxonomy'] = $obj->taxonomy;
        } elseif (is_author()) {
            $layer['page_type'] = 'author';
            $layer['author_name'] = isset($obj->user_login) ? $obj->user_login : null;
        } elseif (is_search()) {
            $layer['page_type'] = 'search';
            $layer['search_query'] = get_query_var('s');
        } elseif (is_archive()) {
            $layer['page_type'] = 'archive';
        } elseif (is_404()) {
            $layer['page_type'] = 'error404';
        }

        return apply_filters('pmg_gtm_datalayer', $layer);
    }
}
