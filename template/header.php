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
 * This is the template for the website header
 *
 * Called from:
 * 	include/class-Header.php
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// load Bootstrap stuff
enqueue_js(  'jquery'    );
enqueue_js(  'bootstrap' );
enqueue_css( 'bootstrap' );
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo strip_tags( $title ) ?> - <?php echo strip_tags( SITE_NAME ) ?></title>
	<link rel="icon" href="<?php echo ROOT ?>/content/logo/logo-64.png" type="image/png" /><?php load_module( 'header' ) ?>

</head>
<body>
	<div class="container">
		<h1><?php echo SITE_NAME ?></h1>
		<h2><?php echo $title ?></h2>
	</div>

	<?php if( $container ): ?>
		<!-- page container -->
		<div class="container">
	<?php endif ?>

	<!-- page row -->
	<div class="row">

		<!-- sidebar content -->
		<?php if( $sidebar ): ?>
			<div class="col-sm-8">
		<?php else: ?>
			<div class="col-sm-12">
		<?php endif ?>
