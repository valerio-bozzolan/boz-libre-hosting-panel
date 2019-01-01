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
 * This is the template for a link
 *
 * Called from
 * 	include/functions.php - the_link() function
 *
 * Available variables:
 * 	$title string e.g. "Home"
 * 	$href string e.g. "/"
 * 	$args mixed
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

?><a class="btn btn-link" href="<?php _esc_attr( $href ) ?>"><?php _esc_html( $title ) ?></a>
