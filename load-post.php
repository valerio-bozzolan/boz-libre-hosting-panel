<?php
# Copyright (C) 2018, 2019, 2020, 2021 Valerio Bozzolan
# KISS Libre Hosting Panel
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

/**
 * This is your versioned configuration file
 *
 * It does not contains secrets.
 *
 * This file is required after loading your
 * unversioned configuration file:
 *
 *   load.php
 */

// Database vesion
//
// Do not touch if not sure.
//
// the maintainer increases this database version
// each version is related to a patch here:
//   documentation/database/patches
define( 'DATABASE_VERSION', 7 );

/**
 * VirtualHost(s) base path
 *
 * e.g. you may have /var/www/example.com/index.html
 * do NOT end with a slash
 */
define_default( 'VIRTUALHOST_BASE_PATH', '/var/www' );

/**
 * Mailbox base path
 *
 * Used by CLI scripts to calculate the current quotas.
 *
 * The mailboxes should have paths like:
 *     MAILBOX_BASE_PATH/domain_name/user_name/
 */
define_default( 'MAILBOX_BASE_PATH', '/home/vmail' );

// include path
define_default( 'INCLUDE_PATH',  ABSPATH . __ . 'include' );

// template path
define_default( 'TEMPLATE_PATH', ABSPATH . __ . 'template' );

// override default user class
define_default( 'SESSIONUSER_CLASS', 'User' );

// autoload classes from the /include directory
spl_autoload_register( function( $name ) {
	// TODO: autoload classes and create DomainTrait and use in Mailbox
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

// default currency simbol
define_default( 'DEFAULT_CURRENCY_SYMBOL', '€' );

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
define_default( 'SITE_NAME', "Keep It Simple And Stupid Libre Hosting Panel" );
define_default( 'CONTACT_EMAIL', 'support@' . DOMAIN );
define_default( 'REPO_URL', 'https://gitpull.it/source/kiss-libre-hosting-panel/' );

// limit session duration to 5 minutes (60s * 100m)
define_default( 'SESSION_DURATION', 6000 );

// register web pages
add_menu_entries( [
	new MenuEntry( 'index',          '',                    __( "Dashboard"      ), null, 'backend'       ),
	new MenuEntry( 'login',          'login.php',           __( "Login"          )                        ),
	new MenuEntry( 'profile',        'profile.php',         __( "Profile"        ), null, 'read'          ),
	new MenuEntry( 'logout',         'logout.php',          __( "Logout"         ), null, 'read'          ),
	new MenuEntry( 'user-list',      'user-list.php',       __( "Users"          ), null, 'edit-user-all' ),
	new MenuEntry( 'activity',       'activity.php',        __( "Last Activity"  ), null, 'monitor'       ),
	new MenuEntry( 'password-reset', 'password-reset.php',  __( "Password reset" )                        ),
] );

// permissions of a normal user
register_permissions( 'user', [
	'read',
	'backend',
] );

// permissions of an admin
inherit_permissions( 'admin', 'user', [
	'edit-user-all',
	'edit-email-all',
	'edit-domain-all',
	'edit-plan-all',
	'edit-ftp-all',
	'edit-mta-all',
	'monitor',
] );
