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

/*
 * This is the template for the website header
 *
 * Please look at the /include/Header.php class to understand the arguments
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo strip_tags( $args[ 'title' ] ) ?> - <?php echo SITE_NAME ?></title><?php load_module( 'header' ) ?>

</head>
<body>
	<div class="container">
		<h1><?php echo $args[ 'h1' ] ?></h1>
	</div>

	<?php if( $args[ 'container' ] ): ?>
		<div class="container">
	<?php endif ?>
