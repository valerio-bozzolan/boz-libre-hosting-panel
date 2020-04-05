#!/usr/bin/php
<?php
# Lavazza Sport Management System by ER Informatica
# Copyright (C) 2020 Valerio Bozzolan
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the script to upgrade the database
 *
 * It will execute the available database patches until the database
 * is to its latest version.
 *
 * To be honest, it also create the database schema if missing.
 */

require __DIR__ . '/../load.php';

echo <<<EOF
 _________________________________________
/ Welcome in the database upgrade script! \
\ Good luck!                              /
 -----------------------------------------
   \        \
    \        \
     \       _\^
      \    _- oo\
           \---- \______
                 \       )\
                ||-----||  \
                ||     ||

EOF;

// path to the documentation directory
$documentation_path = ABSPATH . '/documentation/database';

// directory to the database patches
$patch_directory = "$documentation_path/patches";

// execute a random query just to check if we have a database connection
query( 'SELECT 1' );

// get the database prefix
$database_prefix = DB::instance()->getPrefix();

// try to check if the database exists
$database_exists = true;
try {
	( new UserAPI() )
		->limit( 1 )
		->queryRow();

} catch( Exception $e ) {
	$database_exists = false;
}

if( !$database_exists ) {
	// database schema installation
	echo "important tables are missing! assuming no database.\n";
	echo "importing the schema for the first time\n";
	execute_queries_from_file( "$documentation_path/schema.sql" );

	// if we have not imported any database version, just set the latest one
	$version_exists = get_option( 'database_version', 0 );
	if( !$version_exists ) {
		set_option( 'database_version', DATABASE_VERSION );
	}
}

// get the current database version
$current_database_version = get_option( 'database_version', 1 );

// notify about the current status
printf( "current database version: %d\n", $current_database_version );
printf( "last database version:    %d\n", DATABASE_VERSION          );

// update to next database versions once at time
while( $current_database_version < DATABASE_VERSION ) {

	$current_database_version++;

	$patch_name = sprintf(
		'patch-%04d.sql',
		$current_database_version
	);

	// path to the expected patch
	$patch_path = "$patch_directory/$patch_name";

	echo "looking for patch $patch_path\n";

	// check if there is a database patch to be applied
	if( file_exists( $patch_path ) ) {
		execute_queries_from_file( $patch_path );
	} else {
		echo "\t skipped unexisting patch\n";
	}

	echo "\t increment database version to $current_database_version\n";

	// update the database version
	set_option( 'database_version', $current_database_version );
}

echo "database upgrade end. good for you!\n";






/**
 * Execute some queries from a file
 *
 * @param string $file
 */
function execute_queries_from_file( $file ) {
	echo "\t executing queries from $file\n";

	// get the patch content
	$queries = file_get_contents( $file );

	// replace the database prefix with the current one
	$database_prefix = DB::instance()->getPrefix();
	$queries = str_replace( '{$prefix}', $database_prefix,  $queries );

	// execute the patch queries (it will die in case of error)
	multiquery( $queries );
}