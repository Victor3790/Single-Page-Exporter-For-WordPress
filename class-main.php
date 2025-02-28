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

		add_action( 'admin_post_export_page', array( $this, 'export_page' ) );
		add_action( 'admin_notices', array( $this, 'add_notices' ) );
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
		$actions['export'] = '<a href="'
			. esc_url( admin_url( 'admin-post.php?action=export_page' ) )
			. '&page_id=' . esc_attr( get_the_ID() )
			. '&_wpnonce=' . wp_create_nonce( 'single_page_exporter_export_page' )
			. '">' . esc_html__( 'Export', 'single-page-exporter' )
			. '</a>';

		return $actions;
	}

	/**
	 * Export the page. Fires on an authenticated admin post request for the given action.
	 */
	public function export_page() {
		if ( ! isset( $_GET['page_id'] ) ) {
			return;
		}

		if ( ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		$nonce = wp_kses( wp_unslash( $_GET['_wpnonce'] ), 'strip' );

		if ( ! wp_verify_nonce( $nonce, 'single_page_exporter_export_page' ) ) {
			return;
		}

		$page_id = absint( wp_kses( wp_unslash( $_GET['page_id'] ), 'strip' ) );

		if (
			wp_safe_redirect(
				admin_url(
					'edit.php?post_type=page&page_exported=true&'
					. '_wpnonce=' . wp_create_nonce( 'single_page_exporter_show_message' )
				)
			)
		) {
			exit;
		}
	}

	/**
	 * Add the notices to the admin whether the page has been exported or not.
	 */
	public function add_notices() {
		if ( ! isset( $_GET['page_exported'] ) ) {
			return;
		}

		if ( ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		$nonce = wp_kses( wp_unslash( $_GET['_wpnonce'] ), 'strip' );

		if ( ! wp_verify_nonce( $nonce, 'single_page_exporter_show_message' ) ) {
			return;
		}

		$was_page_exported = rest_sanitize_boolean( wp_kses( wp_unslash( $_GET['page_exported'] ), 'strip' ) );

		if ( ! empty( $was_page_exported ) ) {
			wp_admin_notice( 'Page exported!' );
		}
	}
}
