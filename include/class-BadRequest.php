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

class BadRequest {

	/**
	 * Spawn a bad request message
	 *
	 * @param string $message
	 * @param int    $code    HTTP status code
	 */
	public static function spawn( $message = null, $code = 400 ) {

		http_response_code( $code );

		Header::spawn( [
			'uid'   => false,
			'title' => __( "Bad request" )
		] );

		template( 'bad-request', [
			'message' => $message,
		] );

		Footer::spawn();

		exit;
	}

}
