<?php
# Copyright (C) 2018, 2019, 2020, 2021 Valerio Bozzolan
# KISS Libre Hosting Panel
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

// load dependent traits
class_exists( HostAPI::class, true );

/**
 * Methods for an HostAPI class.
 */
trait HostAPITrait {

	/**
	 * Join whatever table with the Host table
	 *
	 * @param string $type Join type
	 * @return self
	 */
	public function joinHost( $type = 'INNER' ) {
		return $this->joinOn( $type, Host::T, 'host.host_ID', $this->HOST_ID );
	}

}

/**
 * Class to retrieve Host objects.
 */
class HostAPI extends Query {

	use HostAPITrait;

	/**
	 * External Host ID key
	 */
	protected $HOST_ID = 'host.host_ID';

	/**
	 * Constructor
	 *
	 * @param $db DB Database connection
	 */
	public function __construct( $db = null ) {

		// set database and class name
		parent::__construct( $db, Host::class );

		// set database table
		$this->from( Host::T );

	}

}
