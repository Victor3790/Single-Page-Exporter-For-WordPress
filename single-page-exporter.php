<?php
/**
 * Plugin Name: Single Page Exporter
 * Description: This plugin allows you to export a single page via the WordPress API.
 * Author: Victor Crespo
 * Author URI: https://victorcrespo.net
 * Version: 1.0.0
 *
 * @package SinglePageExporter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once __DIR__ . '/class-main.php';

$single_page_exporter = SinglePageExporter\Main::get_instance();
