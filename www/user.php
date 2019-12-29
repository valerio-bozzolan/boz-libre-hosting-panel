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

// require the permission to see the backend
require_permission( 'backend' );

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
if( is_action( 'save-user' ) ) {

	$email   = $_POST['email']   ?? null;
	$uid     = $_POST['uid']     ?? null;
	$name    = $_POST['name']    ?? null;
	$surname = $_POST['surname'] ?? null;

	if( $email && $uid && $name && $surname ) {
		$email = (string) $email;

		// data to be saved
		$data = [];
		$data['user_email']   = $email;
		$data['user_name']    = $name;
		$data['user_surname'] = $surname;

		if( $user ) {
			// update existing User
			( new UserAPI() )
				->whereUser( $user )
				->update( $data );
		} else {
			// insert new User
			$data['user_uid']      = $uid;
			$data['user_active']   = 1;
			$data['user_password'] = '!';
			$data['user_role']     = 'user';
			$data[] = new DBCol( 'user_registration_date', 'NOW()', '-' );

			( new UserAPI() )
				->insertRow( $data );
		}
	}
}

// add a Domain to the user
if( is_action( 'add-domain' ) ){

	// check for permissions
	if( !has_permission( 'edit-user-all' ) ) {
		error_die( "Not authorized to add a Domain" );
	}

	// get the Domain by name
	$domain_name = $_POST['domain_name'] ?? null;
	if( !$domain_name ) {
		die( "Please fill that damn Domain name" );
	}

	// search the Domain name
	$domain =
		( new DomainAPI() )
			->whereDomainName( $domain_name )
			->queryRow();

	query( 'START TRANSACTION' );

	// domain ID to be assigned to the User
	$domain_ID = null;

	// does the Domain already exist?
	if( $domain ) {
		$domain_ID = $domain->getDomainID();
	} else {
		// can I add this Domain?
		if( has_permission( 'edit-domain-all' ) ) {

			// add this Domain
			( new DomainAPI() )
				->insertRow( [
					'domain_name'   => $domain_name,
					'domain_active' => 1,
					new DBCol( 'domain_born', 'NOW()', '-' ),
				] );

			$domain_ID = last_inserted_ID();
		}
	}

	if( $domain_ID ) {

		$is_domain_mine =
			( new DomainUserAPI() )
				->whereUserIsMe()
				->whereDomainID( $domain_ID )
				->queryRow();

		// is it already mine?
		if( !$is_domain_mine ) {

			// associate this domain to myself
			( new DomainUserAPI() )
				->insertRow( [
					'domain_ID' => $domain_ID,
					'user_ID'   => $user->getUserID(),
					new DBCol( 'domain_user_creation_date', 'NOW()', '-' ),
				] );
		}

	} else {
		die( "this Domain is not registered and can't be added" );
	}

	query( 'COMMIT' );

	// end add Domain to User
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
			User::IS_ACTIVE => 1,
			User::PASSWORD  => $encrypted,
		] );
}

// expose the User domains
$user_domains = [];
if( $user ) {

	// get User domains
	$user_domains =
		( new DomainUserAPI() )
			->joinDomain()
			->whereUser( $user )
			->orderByDomainName()
			->queryGenerator();
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
	'user_domains' => $user_domains,
] );

// spawn the footer
Footer::spawn();
