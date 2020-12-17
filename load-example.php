<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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
 * This is an example configuration file
 *
 * Please fill this file and save as 'load.php'!
 */

// change these MySQL/MariaDB database credentials
$username = 'libre_hosting_panel';
$database = 'libre_hosting_panel';
$password = 'insert here a password';

$location = 'localhost';

// database prefix (if any)
$prefix = 'librehost_';

// your SMTP credentials
define( 'MAIL_FROM',     'noreply@example.org' );
define( 'SMTP_USERNAME', 'noreply@example.org' );
define( 'SMTP_PASSWORD', 'insert here smtp password' );
define( 'SMTP_AUTH',     'PLAIN' );
define( 'SMTP_TLS',      true );
define( 'SMTP_SERVER',   'mail.example.org' );
define( 'SMTP_PORT',     465 );

// your contact e-mail
define( 'CONTACT_EMAIL', 'services@example.org' );

// absolute web directory without trailing slash
// if your URL is http://asd.org/hosting/ then set '/hosting'
// if your URL is http://asd.org/ then set ''
define( 'ROOT', '' );

// absolute path to the project directory without trailing slash
// this is rarely changed
define( 'ABSPATH', __DIR__ );

// other specific configuration about your hosting environments
$HOSTING_CONFIG = new stdClass();

// Mailbox password encryption custom mechanism
// you can leave this commented for the default- this is just an example.
# $HOSTING_CONFIG->MAILBOX_ENCRYPT_PWD = function ( $password ) {
#	$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
#	return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
# };

// FTP password encryption custom mechanism
// you can leave this commented for the default. this is just an example.
# $HOSTING_CONFIG->FTP_ENCRYPT_PWD = function ( $password ) {
#	$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
#	return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
# };

// customize your path to the suckess-php framework
//   https://gitpull.it/source/suckless-php/
require __DIR__ . '/../suckless-php/load.php';
