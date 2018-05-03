<?php
/**
 * SCRM setup
 *
 * Created by Andrey Pavluk
 * Date: 22.03.2018
 *
 * @package  SCRM
 * @since    1.0.0
 */

defined('ABSPATH') || exit;

final class SCRM
{

    /**
     * SCRM version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var SCRM
     * @since 1.0
     */
    protected static $_instance = null;


    /**
     * Main SCRM Instance.
     *
     * Ensures only one instance of SCRM is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @see SCRM()
     * @return SCRM - Main instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * SCRM constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define SCRM Constants.
     */
    private function define_constants()
    {
        $this->define('SCRM_ABSPATH', dirname(SCRM_PLUGIN_FILE) . '/');
        $this->define('SCRM_PLUGIN_BASENAME', plugin_basename(SCRM_PLUGIN_FILE));
        $this->define('SCRM_VERSION', $this->version);

    }

    private function includes()
    {
        
        /**
         * Class autoloader.
         */
        include_once SCRM_ABSPATH . 'includes/class-scrm-autoloader.php';

        /**
         * Core classes.
         */
        include_once SCRM_ABSPATH . 'includes/class-scrm-post-types.php';
        include_once SCRM_ABSPATH . 'includes/class-scrm-install.php';
        include_once SCRM_ABSPATH . 'includes/class-scrm-ajax.php';

        /**
         * Load admin core
         */
        if ($this->is_request('admin')) {
            include_once SCRM_ABSPATH . 'includes/admin/class-scrm-admin.php';
        }

    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks()
    {
        register_activation_hook(SCRM_PLUGIN_FILE, array('SCRM_Install', 'install'));
//        SCRM_Install::install(); // @todo delete this
        register_deactivation_hook(SCRM_PLUGIN_FILE, array('SCRM_Install', 'deactivation'));

        add_action('init', array($this, 'init'), 0);
    }

    /**
     * Init SCRM when WordPress Initialises.
     */
    public function init()
    {
        // Before init action.
        do_action('before_scrm_init');
        $this->carret();


        // Init action.
        do_action('scrm_init');
    }

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     *
     * Locales found in:
     *      - WP_LANG_DIR/scrm/scrm-LOCALE.mo
     *      - WP_LANG_DIR/plugins/scrm-LOCALE.mo
     */
    public function load_plugin_textdomain()
    {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'scrm');

        unload_textdomain('scrm');
        load_textdomain('scrm', WP_LANG_DIR . '/scrm/scrm-' . $locale . '.mo');
        load_plugin_textdomain('scrm', false, plugin_basename(dirname(SCRM_PLUGIN_FILE)) . '/languages');
    }

    /**
     * Define constant if not already set.
     *
     * @param $name
     * @param $value
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    public function carret()
    {
// Set up localisation.
        $this->load_plugin_textdomain();

        $this->post_types = new SCRM_Post_Types();
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
    }

    /**
     * Get the plugin url
     */
    public function plugin_url() {
        
        return untrailingslashit( plugins_url( '/', SCRM_PLUGIN_FILE ) );
    }

    /**
     * Get the plugin path
     */
    public function plugin_path() {
        
        return untrailingslashit( plugin_dir_path( SCRM_PLUGIN_FILE ) );
    }
}