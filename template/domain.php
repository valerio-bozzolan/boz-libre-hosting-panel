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
 * This is the template for the website domain dashboard page
 *
 * Called from:
 * 	domain.php
 *
 * Available variables:
 * 	$domain object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// spawn the mailboxes list
template( 'mailboxes',    [ 'domain' => $domain ] );

// spawn the mail forwardings list
template( 'mailforwards', [ 'domain' => $domain ] );

// spawn the ftp list
template( 'ftp-users',    [ 'domain' => $domain ] );
