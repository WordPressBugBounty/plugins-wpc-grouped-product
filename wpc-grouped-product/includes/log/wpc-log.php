<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WOOSG_LITE' ) ? WOOSG_LITE : WOOSG_FILE, 'woosg_activate' );
register_deactivation_hook( defined( 'WOOSG_LITE' ) ? WOOSG_LITE : WOOSG_FILE, 'woosg_deactivate' );
add_action( 'admin_init', 'woosg_check_version' );

function woosg_check_version() {
	if ( ! empty( get_option( 'woosg_version' ) ) && ( get_option( 'woosg_version' ) < WOOSG_VERSION ) ) {
		wpc_log( 'woosg', 'upgraded' );
		update_option( 'woosg_version', WOOSG_VERSION, false );
	}
}

function woosg_activate() {
	wpc_log( 'woosg', 'installed' );
	update_option( 'woosg_version', WOOSG_VERSION, false );
}

function woosg_deactivate() {
	wpc_log( 'woosg', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}