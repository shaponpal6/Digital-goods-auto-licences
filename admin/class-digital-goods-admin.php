<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpartificial.com/
 * @since      1.0.0
 *
 * @package    Digital_Goods
 * @subpackage Digital_Goods/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Digital_Goods
 * @subpackage Digital_Goods/admin
 * @author     Shapon pal <wpartificial@gmail.com>
 */
class Digital_Goods_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();

	}

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function load_dependencies() {

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-digital-goods-settings.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-digital-goods-menus.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/metabox_executor.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'metabox/init.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/email_template_executor.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-lc-manager.php';
    }
 
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Digital_Goods_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Digital_Goods_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/digital-goods-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Digital_Goods_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Digital_Goods_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/digital-goods-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'dl_licence_obj',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

			 // Enqueue Scripts
        wp_enqueue_script(
            'dpls_magager_scripts',
            get_site_url() . '/wp-content/plugins/wc-auto-license/assets/build/js/license.js',
            ['wp-element'],
            time(),
            true
        );

	}

}