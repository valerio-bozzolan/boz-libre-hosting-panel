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

/**
 * Handle a menu
 */
class MenuTree {

	/**
	 * Spawn the menu
	 *
	 * @param $args array Menu arguments where:
	 * 	uid:
	 */
	public static function spawn( $args = [] ) {

		// default arguments
		$args = array_replace( [
			'uid'       => null,
			'level'     => 0,
			'max-level' => 99,
		], $args );

		// end if level reached
		if( $args[ 'level' ] > $args['max-level'] ) {
			return;
		}

		$args[ 'entries' ] = get_children_menu_entries( $args[ 'uid' ] );
		if( ! $args[ 'entries' ] ) {
			return;
		}

		// spawn the related template
		template( 'menu', $args );
	}

}
