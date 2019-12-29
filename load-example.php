<?php
# Copyright (C) 2018, 2019 Valerio Bozzolan
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

// database credentials
$username = 'insert here database username';
$password = 'insert here database password';
$database = 'insert here database name';
$location = 'localhost';

// database prefix (if any)
$prefix = '';

// your contact e-mail
define( 'CONTACT_EMAIL', 'services@example.org' );

// your SMTP credentials
define( 'MAIL_FROM',     'noreply@example.org' );
define( 'SMTP_USERNAME', 'noreply@example.org' );
define( 'SMTP_PASSWORD', 'insert here smtp password' );
define( 'SMTP_AUTH',     'PLAIN' );
define( 'SMTP_TLS',      true );
define( 'SMTP_SERVER',   'mail.example.org' );
define( 'SMTP_PORT',     465 );

// absolute path to the project directory without trailing slash
define( 'ABSPATH', __DIR__ );

// absolute web directory without trailing slash
define( 'ROOT', '' );

// other specific configuration about your hosting environments
$HOSTING_CONFIG = new stdClass();

// Mailbox password encryption custom mechanism (you can leave this commented for the default)
# $HOSTING_CONFIG->MAILBOX_ENCRYPT_PWD = function ( $password ) {
#	$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
#	return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
# };

// FTP password encryption custom mechanism (you can leave this commented for the default)
# $HOSTING_CONFIG->FTP_ENCRYPT_PWD = function ( $password ) {
#	$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
#	return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
# };

// path to the boz-php framework
require '/usr/share/php/suckless-php/load.php';
