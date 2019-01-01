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

/*
 * This is the user profile page
 */

// load framework
require 'load.php';

// must be logged
require_permission( 'read' );

// myself
$user = get_user();

// edit whatever user
if( isset( $_SERVER[ 'PATH_INFO' ] ) ) {
	require_permission( 'edit-all-user' );

	list( $user_uid ) = url_parts( 1 );
	$user = Sessionuser::factoryFromUID( $user_uid )
		->queryRow();

	if( ! $user ) {
		PageNotFound::spaqn();
	}
}

// spawn header
Header::spawn( [
	'title' => __( "Profile" ),
] );

// user e-mail
$email = $user->get( 'user_email' );

// handle send user password action
if( is_action( 'send-user-password' ) ) {

	// generate
	$password = generate_password();

	// e-mail body
	$mail_content = template_content( 'mail-user-password', [
		'email'    => $email,
		'name'     => $user->get( 'user_name' ),
		'uid'      => $user->get( 'user_uid' ),
		'surname'  => $user->get( 'user_surname' ),
		'password' => $password,
	] );

	// update
	query_update( 'user', [
		new DBCol( 'user_password', Sessionuser::encryptPassword( $password ), 's' ),
	], sprintf(
		'user_ID = %d',
		$user->get( 'user_ID' )
	) );

	// send
	send_email( __( "Password reset" ), $mail_content, $email );
}

// spawn the profile page
template( 'profile', [ 'email' => $email ] );

// spawn footer
Footer::spawn();
