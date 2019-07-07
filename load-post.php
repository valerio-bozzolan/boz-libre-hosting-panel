<?php
# Copyright (C) 2018 Valerio Bozzolan
# Boz Libre Hosting Panel
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

// include path
define_default( 'INCLUDE_PATH',  ABSPATH . __ . 'include' );

// template path
define_default( 'TEMPLATE_PATH', ABSPATH . __ . 'template' );

// autoload classes from the /include directory
spl_autoload_register( function( $name ) {
	$path = INCLUDE_PATH . __ . "class-$name.php";
	if( is_file( $path ) ) {
		require $path;
	}
} );

// load common functions
require INCLUDE_PATH . __ . 'functions.php';

// jquery URL
// provided by the libjs-jquery package as default
define_default( 'JQUERY_URL', '/javascript/jquery/jquery.min.js' );

// Bootstrap CSS/JavaScript files without trailing slash
// provided by the libjs-bootstrap package as default
define_default( 'BOOTSTRAP_DIR_URL', '/javascript/bootstrap' );

// path to the Net SMTP class
// provided by the php-net-smtp package as default
define_default( 'NET_SMTP', '/usr/share/php/Net/SMTP.php' );

// register JavaScript/CSS files
register_js(  'jquery',     JQUERY_URL );
register_js(  'bootstrap',  BOOTSTRAP_DIR_URL .  '/js/bootstrap.min.js'  );
register_css( 'bootstrap',  BOOTSTRAP_DIR_URL . '/css/bootstrap.min.css' );
register_css( 'custom-css', ROOT . '/content/style.css' );

// GNU Gettext i18n
define( 'GETTEXT_DOMAIN', 'reyboz-hosting-panel' );
define( 'GETTEXT_DIRECTORY', 'l10n' );
define( 'GETTEXT_DEFAULT_ENCODE', CHARSET ); // UTF-8

// common strings
define_default( 'SITE_NAME', "Boz Libre Hosting Panel" );
define_default( 'CONTACT_EMAIL', 'support@' . DOMAIN );
define_default( 'REPO_URL', 'https://github.com/valerio-bozzolan/boz-libre-hosting-panel' );

// limit session duration to 5 minutes (60s * 100m)
define_default( 'SESSION_DURATION', 6000 );

// register web pages
add_menu_entries( [
	new MenuEntry( 'index',          '/',                   __( "Dashboard" ) ),
	new MenuEntry( 'login',          'login.php',           __( "Login" ) ),
	new MenuEntry( 'profile',        'profile.php',         __( "Profile" ) ),
	new MenuEntry( 'logout',         'logout.php',          __( "Logout" ) ),
	new MenuEntry( 'password-reset', 'password-reset.php',  __( "Password reset" ) ),
] );

// permissions
register_permissions( 'user', [
	'read',
	'backend',
] );

inherit_permissions( 'admin', 'user', [
	'edit-user-all',
	'edit-email-all',
	'edit-domain-all',
	'edit-ftp-all',
] );
