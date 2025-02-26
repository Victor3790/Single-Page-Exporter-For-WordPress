<?php
/**
 * This is the main class for the plugin.
 *
 * @package SinglePageExporter
 */

namespace SinglePageExporter;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * The main class for the plugin.
 */
class Main {
	/**
	 * The instance of the class.
	 *
	 * @var Main
	 */
	private static $instance = null;

	/**
	 * The constructor for the class.
	 */
	private function __construct() {
		// Add hoooks here.
		add_filter( 'page_row_actions', array( $this, 'add_export_option' ) );
	}

	/**
	 * Get the instance of the class.
	 *
	 * @return Main
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add the export option to the page row actions.
	 *
	 * @param array $actions The current row actions.
	 * @return array
	 */
	public function add_export_option( $actions ) {
		$actions['export'] = '<a href="' . esc_url( admin_url( 'admin-post.php?action=export_page' ) ) . '">' . esc_html__( 'Export', 'single-page-exporter' ) . '</a>';
		return $actions;
	}
}
