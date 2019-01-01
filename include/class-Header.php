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
 * The header of the web page
 */
class Header {

	/**
	 * Header arguments
	 *
	 * It's public because the Footer reads it.
	 *
	 * @var array
	 */
	public static $args;

	/**
	 * Spawn the header to standard output
	 *
	 * @param $args mixed Can be the page UID, or arguments where:
	 * 	uid:   menu UID like 'home'
	 * 	title: document title
	 */
	public static function spawn( $args = [] ) {

		// store arguments for future reads
		self::$args = & $args;

		// shortcut
		if( is_string( $args ) ) {
			$args = [ 'uid' => $args ];
		}

		// eventually retrieve actual page UID
		if( ! isset( $args[ 'uid' ] ) ) {
			$args[ 'uid' ] = self::actualPageUID();
		}

		// retrieve page informations
		$page = isset(       $args[ 'uid' ] )
		   ? get_menu_entry( $args[ 'uid' ] )
		   : null;

		// populate the page informations
		if( $page ) {
			$args = array_replace( [
				'title' => $page->name,
			], $args );
		} else {
			$args[ 'uid' ] = null;
		}

		// populate default arguments
		$args = array_replace( [
			'container'    => true,
			'sidebar'      => true,
			'breadcrumb'   => [],
		], $args );

		// charset is usually UTF-8
		header( 'Content-Type: text/html; charset=' . CHARSET );

		// spawn header template
		template( 'header', [ 'args' => $args ] );
	}

	/**
	 * Get the default page UID
	 *
	 * @return string
	 */
	public static function actualPageUID() {
		$page = basename( $_SERVER[ 'SCRIPT_NAME' ] );
		return str_replace( '.php', '', $page );
	}
}
