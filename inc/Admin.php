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

class Admin extends TagManagerBase
{
    public function _setup()
    {
        add_action('admin_menu', array($this, 'addPage'));
        add_action('admin_init', array($this, 'register'));
    }

    public function addPage()
    {
        $p = add_options_page(
            __('Google Tag Manager Settings', PMG_GTM_TD),
            __('Tag Manager', PMG_GTM_TD),
            'manage_options',
            'pmg-google-tag-manager',
            array($this, 'pageCallback')
        );
    }

    public function pageCallback()
    {
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e('Google Tag Manager Settings', PMG_GTM_TD); ?></h2>
    <form method="post" action="<?php echo admin_url('options.php'); ?>">
        <?php
        settings_fields(static::OPTION);
        do_settings_sections(static::OPTION);
        ?>

        <p>
            <?php submit_button(__('Save Settings', PMG_GTM_TD)); ?>
        </p>
    </form>
</div>
<?php
    }

    public function register()
    {
        register_setting(static::OPTION, static::OPTION, array($this, 'cleaner'));

        add_settings_section(
            'main',
            __('Tag Manager', PMG_GTM_TD),
            '__return_false',
            static::OPTION
        );

        $fields = array(
            'id'        => __('Container ID', PMG_GTM_TD),
            'in_footer' => __('Add Code in Footer', PMG_GTM_TD),
        );

        foreach ($fields as $f => $label) {
            $lf = sprintf('%s[%s]', static::OPTION, $f);

            add_settings_field(
                $lf,
                $label,
                array($this, "{$f}FieldCallback"),
                static::OPTION,
                'main',
                array('label_for' => $lf, 'key' => $f)
            );
        }
    }

    public function cleaner($dirty)
    {
        $out = array();

        $out['id'] = isset($dirty['id']) ? esc_attr($dirty['id']) : '';
        $out['in_footer'] = !empty($dirty['in_footer']) ? 'on' : 'off';

        return $out;
    }

    public function idFieldCallback($args)
    {
        printf(
            '<input type="text" class="regular-text" id="%1%s" name="%1$s" value="%2$s" />',
            esc_attr($args['label_for']),
            esc_attr(static::opt($args['key']))
        );
    }

    public function in_footerFieldCallback($args)
    {
        printf(
            '<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s />',
            esc_attr($args['label_for']),
            checked('on', static::opt($args['key'], 'on'), false)
        );

        echo '<p class="description">';
        printf(
            esc_html__('If this is unchecked plese use the %s template tag in your theme.', PMG_GTM_TD),
            '<code>google_tag_manager</code>'
        );
        echo '</p>';
    }
}
