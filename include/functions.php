<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
# Reyboz another self-hosting panel project
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
 * Require a certain page from the template directory
 *
 * It will eventually echo something.
 *
 * @param $name string page name (to be sanitized)
 * @param $args mixed arguments to be passed to the page scope
 */
function template( $template_name, $template_args = [] ) {
	extract( $template_args, EXTR_SKIP );
	return require TEMPLATE_PATH . __ . "$template_name.php";
}

/**
 * Get the template output
 *
 * It will echo nothing.
 *
 * @param $name string page name (to be sanitized)
 * @param $args mixed arguments to be passed to the page scope
 * @see template()
 * @return string The template output
 */
function template_content( $name, $args = [] ) {
	ob_start();
	template( $name, $args );
	$text = ob_get_contents();
	ob_end_clean();
	return $text;
}

/**
 * Print an e-mail (safe for bots)
 *
 * @param $email string
 */
function email_blur( $email ) {
	$dot = strip_tags( __( " dot <!-- . -->" ) );
	$at  = strip_tags( __( " at <!-- @ -->"  ) );
	$email = esc_html( $email );
	echo str_replace( [ '.', '@' ], [ $dot, $at ], $email );
}

/**
 * Send an e-mail to someone
 *
 * @param $subject string E-mail subject
 * @param $message string E-mail message
 * @param $to string E-mail recipient (from current logged-in user as default)
 */
function send_email( $subject, $message, $to = false ) {
	if( ! $to ) {
		if( ! is_logged() ) {
			die( "can't retrieve e-mail address from anon user" );
		}
		$to = get_user( 'user_email' );
	}
	return SMTPMail::instance()
		->to( $to )
		->message( $subject, $message )
		->disconnect();
}

/**
 * Require a certain permission
 *
 * @param $permission string An internal permission like 'edit-all-user'
 * @param $redirect boolean Enable or disable the redirect
 */
function require_permission( $permission, $redirect = true ) {
	if( ! has_permission( $permission ) ) {
		require_more_privileges( $redirect );
	}
}

/**
 * Require more privileges then actual ones
 */
function require_more_privileges( $redirect = true ) {
	if( is_logged() ) {
		Header::spawn( [
			'title' => __( "Permission denied" ),
		] );
		Footer::spawn();
		exit;
	} else {
		$login = menu_entry( 'login' );
		$url = $login->getAbsoluteURL();
		if( $redirect && isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
			$url = http_build_get_query( $url, [
				'redirect' => $_SERVER[ 'REQUEST_URI' ],
			] );
		}
		http_redirect( $url, 307 );
	}
}
/**
 * Get URL parts from the PATH_INFO
 *
 * It spawn a "bad request" page if something goes wrong.
 *
 * @param $max int If $min is specified, this is the maximum number of parameters. When unspecified, this is the exact number of parameters.
 * @param $min int Mininum number of parameters.
 * @return array
 * @see https://httpd.apache.org/docs/2.4/mod/core.html#acceptpathinfo
 */
function url_parts( $max, $min = false ) {
	if( $min === false ) {
		$min = $max;
	}

	// split the PATH_INFO parts
	$parts = explode( _, $_SERVER['PATH_INFO'] ?? '' );
	array_shift( $parts );

	// eventually spawn the "bad request"
	$n = count( $parts );
	if( $n > $max || $n < $min ) {
		BadRequest::spawn( __( "unexpected URL" ) );
	}

	// eventually fill expected fields
	for( $i = $n; $i < $max; $i++ ) {
		$parts[] = null;
	}
	return $parts;
}

/**
 * Link to an existing page from the menu
 *
 * @param $uid string E.g. 'index'
 * @param $args mixed Arguments
 */
function the_menu_link( $uid, $args = [] ) {
	$page = menu_entry( $uid );
	the_link( $page->getURL(), $page->name, $args );
}

/**
 * Link to a whatever page
 *
 * P.S. link() is a reserved function
 *
 * @param $url string
 * @param $title string
 * @param $args mixed Arguments
 */
function the_link( $url, $title, $args = [] ) {
	template( 'link', [
		'title' => $title,
		'url'   => $url,
		'args'  => $args,
	] );
}

/**
 * Generate a password
 *
 * @param $bytes int
 */
function generate_password( $bytes = 8 ) {
	return rtrim( base64_encode( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) ), '=' );
}

/**
 * Validate a mailbox username
 *
 * @param $mailbox string
 * @return bool
 */
function validate_mailbox_username( $mailbox ) {
	return 1 === preg_match( '/^[a-z][a-z0-9-_.]+$/', $mailbox );
}

/**
 * A certain value must be an e-mail
 *
 * @param $email string
 * @return string filtered e-mail
 */
function require_email( $email ) {
	$email = luser_input( $email, 128 );
	if( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
		BadRequest::spawn( __( "fail e-mail validation" ) );
	}
	return $email;
}

/**
 * Require a strictly safe file/directory name or throw an exception
 *
 * This method is designed to prevend known filesystem exploitations
 * that may also occurr if a database is compromised and with malicous
 * data inside domain names, etc. This also removes a wide rang of valid
 * characters in Unix but this way we reduce every possible risk in other
 * operating systems.
 *
 * Note that an empty directory is NOT a valid directory name so '///'
 * is not valid even if it's a safe pathname resolution in Unix-like systems.
 * This is because I don't like it. No other reasons.
 *
 * @param string $dirname
 */
function require_safe_dirname( $dirname ) {
	if( !$dirname || $dirname === '.' || $dirname === '..' || !preg_match( '/^[a-zA-Z0-9-.]+$/', $dirname ) ) {
		throw new Exception( sprintf(
			"the directory name '%s' is weird for me",
			$dirname
		) );
	}
}

/**
 * Check if a pathname can be considered a valid sub-directory of something else
 *
 * The pathname is valid if it starts with a slash.
 * The pathname will be normalized after the validation.
 *
 * @param string $pathname Pathname to be validated like '/my-site/www'
 */
function validate_subdirectory( & $pathname ) {

	// assure that the string starts with just a slash and does not end with slashes
	$pathname = trim( $pathname, '/' );

	// validate each directory (if any)
	$directories = [];
	if( $pathname ) {
		$directories = explode( '/', $pathname );
		foreach( $directories as $directory ) {
			try {
				require_safe_dirname( $directory );
			} catch( Exception $e ) {
				throw new Exception( sprintf(
					"apologies but I do not like the pathname '%s': %s",
					$pathname,
					$e->getMessage()
				) );
			}
		}
	}

	// merge all the directories
	$pathname = '/' . implode( '/', $directories );
}
