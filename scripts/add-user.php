#!/usr/bin/php
<?php
# Linux Day - command line interface to create an user
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

// allowed only from command line interface
if( ! isset( $argv[ 0 ] ) ) {
	exit( 1 );
}

// autoload the framework
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'load.php';

// command line arguments
$opts = getopt( 'h', [
	'uid:',
	'role:',
	'email:',
	'name:',
	'surname:',
	'pwd:',
	'force::',
	'help',
] );

// show help
if( ! isset( $opts[ 'uid' ], $opts[ 'pwd' ], $opts[ 'role' ] ) || isset( $opts[ 'help' ] ) || isset( $opts[ 'h' ] ) ) {

	$roles = _roles();
	$roles_list = implode( '|', $roles );

	printf( "Usage: %s [OPTIONS]\n", $argv[ 0 ] );
	echo "OPTIONS:\n";
	echo "    --uid=UID          username\n";
	echo "    --email=EMAIL      email\n";
	echo "    --name=NAME        first name\n";
	echo "    --surname=SURNAME  family name\n";
	echo "    --role=ROLE        role ($roles_list)\n";
	echo "    --pwd=PASSWORD     password\n";
	echo "    --force            update the user password if exists\n";
	echo " -h --help             show this help and exit\n";
	exit( 0 );
}

// validate role
if( !Permissions::instance()->roleExists( $opts['role'] ) ) {
	printf( "The role '%s' does not exist\n", $opts['role'] );
	exit( 1 );
}

// look for existing user
$user = User::factoryFromUID( $opts[ 'uid' ] )
	->select( User::ID )
	->queryRow();

// check if it exists
if( $user && ! isset( $opts[ 'force' ] ) ) {
	printf( "User %s already exist\n", $opts[ 'uid' ] );
	exit( 1 );
}

// encrypt the password
$pwd = User::encryptPassword( $opts[ 'pwd' ] );

if( $user ) {

	// update the User
	( new UserAPI() )
		->whereUser( $user )
		->update( [
			'user_password' => $pwd,
		] );

	echo "Updated.\n";

} else {

	// insert a new user
	( new UserAPI() )
		->insertRow( [
			'user_uid'      => $opts[ 'uid' ],
			'user_role'     => $opts[ 'role' ],
			'user_name'     => $opts[ 'name' ],
			'user_surname'  => $opts[ 'surname' ],
			'user_email'    => $opts[ 'email' ],
			'user_password' => $pwd,
			'user_active'   => 1,
		] );

	echo "Created\n";
}


/**
 * Get a list of available roles
 *
 * Well, it just remove the DEFAULT_USER_ROLE from the roles.
 *
 * @return array
 */
function _roles() {

	$good_roles = [];

	// get the existing roles
	foreach( Permissions::instance()->getRoles() as $role ) {
		if( $role !== DEFAULT_USER_ROLE ) {
			$good_roles[] = $role;
		}
	}

	return $good_roles;
}
