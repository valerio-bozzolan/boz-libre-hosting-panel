#!/usr/bin/php
<?php
# Linux Day - command line interface to create an user
# Copyright (C) 2018-2026 Valerio Bozzolan
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
require __DIR__ . '/../load.php';

// command line arguments
$opts = getopt( 'h', [
	'family:',
	'action:',
	'actor:',
	'marionette:',
	'timestamp:',
	'domain:',
	'help',
] );

// No arg, no party.
if (empty($opts['family'])) {
	echo "ERROR: missing --family=FAMILY\n";
	_help();
	exit(1);
}

// No arg, no party.
if (empty($opts['action'])) {
	echo "ERROR: missing --action=ACTION\n";
	_help();
	exit(1);
}

// No arg, no party.
if (empty($opts['marionette'])) {
	echo "ERROR: missing --marionette=USERNAME\n";
	_help();
	exit(1);
}

// No arg, no party.
if (empty($opts['actor'])) {
	echo "ERROR: missing --actor=USERNAME\n";
	_help();
	exit(1);
}

// Show help.
if( isset($opts['help']) || isset($opts['h'] ) ) {
	_help();
	exit(0);
}

// Look for existing user.
$actor = User::factoryFromUID($opts['actor'])
	->select(User::ID)
	->queryRowOrFail();

// Look for existing user.
$marionette = User::factoryFromUID($opts['marionette'])
	->select(User::ID)
	->queryRowOrFail();

$domain = null;
if (isset($opts['domain'])) {
	$domain = Domain::factoryFromUID($opts['domain'])
		->select(Domain::ID)
		->queryRowOrFail();
}

// insert a new user
APILog::insert( [
	'actor' => $actor,
	'marionette' => $marionette,
	'domain' => $domain,
	'family' => $opts['family'],
	'action' => $opts['action'],
	'timestamp' => $opts['timestamp'] ?? null,
] );

function _help() {
	global $argv;

	printf("Usage: %s [OPTIONS]\n", $argv[0]);

	echo "OPTIONS:\n";
	echo "    --family=FAMILY                   audit family (e.g. 'domain')\n";
	echo "    --action=ACTION                   audit family action (e.g. 'admin.add')\n";
	echo "    --actor=USERNAME                  user UID actively causing the action\n";
	echo "    --marionette=USERNAME             user UID passively receiving the action\n";
	echo "    --timestamp='YYYY-MM-DD HH:ii:ss' timestamp (default: now)\n";
	echo " -h --help                            show this help and exit successfully\n";
}
