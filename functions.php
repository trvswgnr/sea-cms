<?php
/**
 * Global Functions
 *
 * @package quark
 */

/**
 * Get Site URL as Variable
 *
 * @return string The static site URL.
 */
function get_site_url() {
	$string   = ltrim( $_SERVER['REQUEST_URI'], '/' );
	$host     = $_SERVER['HTTP_HOST'];
	$protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';

	list( $before_slash ) = explode( '/', $string );

	$url = rtrim( $protocol . $host . '/' . $before_slash, '/' ) . '/';
	return $url;
}

/**
 * Echo Site URL
 */
function site_url() {
	echo get_site_url();
}

function title_to_slug( $str ) {
	$str  = strtolower( $str );
	$slug = preg_replace( '/[^A-Za-z0-9-]+/', '-', $str );
	$slug = rtrim( $slug, '-' );
	return $slug;
}

function get_remote_file_contents( $url ) {
	$ch      = curl_init();
	$timeout = 5;
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	$data = curl_exec( $ch );
	curl_close( $ch );
	return $data;
}

function remote_file_contents( $url ) {
	echo get_remote_file_contents( $url );
}

function list_file_links( $ext = '*' ) {
	$files = glob( "*.$ext" );

	echo '<ul>' . implode( '', array_map( 'sprintf', array_fill( 0, count( $files ), '<li><a href="%s">%s</a></li>' ), $files, $files ) ) . '</ul>';
}

function quark_debug( $bool ) {
	$enabled   = ( $bool === true ) ? 1 : 0;
	$reporting = ( $bool === true ) ? E_ALL : 0;
	ini_set( 'display_errors', $enabled );
	ini_set( 'display_startup_errors', $enabled );
	error_reporting( $reporting );
}

function get_current_file () {
	preg_match( '/([^\/]+$)/', $_SERVER['PHP_SELF'], $match);
	return $match[0];
}


function is_current_file( $file ) {
	$current_file = get_current_file();
	return $file === $current_file ? true : false;
}
