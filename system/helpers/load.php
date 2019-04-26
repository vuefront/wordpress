<?php

$base_dir = realpath( '../../../' );

define('SHORTINIT',1);

require_once $base_dir . '/wp-load.php';
wp_initial_constants();
wp_plugin_directory_constants();

global $wpdb;
require( ABSPATH . WPINC . '/class-wp-query.php' );
require( ABSPATH . WPINC . '/class-wp-user.php' );
require( ABSPATH . WPINC . '/user.php' );

require( ABSPATH . WPINC . '/shortcodes.php' );
require( ABSPATH . WPINC . '/media.php' );

require( ABSPATH . WPINC . '/comment.php' );
require( ABSPATH . WPINC . '/class-wp-comment.php' );
require( ABSPATH . WPINC . '/class-wp-comment-query.php' );

require( ABSPATH . WPINC . '/widgets.php' );
require( ABSPATH . WPINC . '/class-wp-widget.php' );

require_once( ABSPATH . WPINC . '/l10n.php' );
require_once( ABSPATH . WPINC . '/class-wp-locale.php' );

require( ABSPATH . WPINC . '/theme.php' );
require( ABSPATH . WPINC . '/class-wp-theme.php' );

require( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' );

