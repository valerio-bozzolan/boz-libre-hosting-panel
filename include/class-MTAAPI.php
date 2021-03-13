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
 * Methods for an MTAAPI class.
 */
trait MTAAPITrait {

	/**
	 * Limit to a specific MTA ID
	 *
	 * @param int $id
	 * @return self
	 */
	public function whereMTAID( $id ) {
		return $this->whereInt( $this->MTA_ID, $id );
	}

}

/**
 * Class to retrieve MTA objects.
 */
class MTAAPI extends Query {

	use MTAAPITrait;
	use HostAPITrait;

	/**
	 * External MTA ID key
	 */
	protected $MTA_ID = 'mta.mta_ID';

	/**
	 * External Host ID key
	 */
	protected $HOST_ID = 'mta.host_ID';

	/**
	 * Constructor
	 *
	 * @param $db DB Database connection
	 */
	public function __construct( $db = null ) {

		// set database and class name
		parent::__construct( $db, MTA::class );

		// set database table
		$this->from( MTA::T );

	}

}
