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
	 * @param $args array arguments where:
	 * 	title: document title
	 * 	h1:    site title (as default, it's the document title)
	 */
	public static function spawn( $args = [] ) {

		// store arguments for future reads
		self::$args = & $args;

		// set default arguments
		$args = array_replace( [
			'h1'        => $args[ 'title' ],
			'container' => true,
		], $args );

		// load Bootstrap stuff
		enqueue_js(  'jquery'    );
		enqueue_js(  'bootstrap' );
		enqueue_css( 'bootstrap' );

		// charset is usually UTF-8
		header( 'Content-Type: text/html; charset=' . CHARSET );

		// spawn header template
		template( 'header', $args );
	}
}
