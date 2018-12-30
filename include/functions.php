<?php
# Copyright (C) 2018 Valerio Bozzolan
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
 * @param $name string page name (to be sanitized)
 * @param $args mixed arguments to be passed to the page scope
 */
function template( $name, $args = [] ) {
	require TEMPLATE_PATH . __ . "$name.php";
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
