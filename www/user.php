<?php
# Copyright (C) 2019 Valerio Bozzolan
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
 * This is the single User creation/edit page
 */

// load framework
require '../load.php';

// wanted informations
$user = null;

// URL paramenters (user_uid)
list( $user_uid ) = url_parts( 1, 0 );

// eventually retrieve mailforward from database
if( $user_uid ) {
	$user = ( new UserAPI() )
		->whereUserUID( $user_uid )
		->whereUserIsEditable()
		->queryRow();

	// 404
	if( !$user || !$user->isUserEditable() ) {
		PageNotFound::spawn();
	}
} else {
	// to create an FTP user, must edit all FTP users
	require_permission( 'edit-user-all' );
}

// save destination action
if( is_action( 'user-save' ) ) {

}

// register action to generate a new password
$new_password = null;
if( is_action( 'change-password' ) && $user ) {

	// generate a new password and save
	$new_password = generate_password();
	$encrypted = User::encryptPassword( $new_password );
	( new UserAPI() )
		->whereUser( $user )
		->update( [
			new DBCol( User::PASSWORD, $encrypted, 's' ),
		] );
}

// spawn header
Header::spawn( [
	'uid' => false,
	'title-prefix' => __( "User" ),
	'title' => $user
		? $user->getUserUID()
		: __( "create" ),
] );

// spawn the page content
template( 'user', [
	'user'         => $user,
	'new_password' => $new_password,
] );

// spawn the footer
Footer::spawn();
