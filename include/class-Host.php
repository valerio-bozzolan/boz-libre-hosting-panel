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

/**
 * Methods for a Host class
 */
trait HostTrait {

	use HostTrait;

	/**
	 * Get the Host's ID
	 *
	 * @return int
	 */
	public function getHostID() {
		return $this->get( 'host_ID' );
	}

	/**
	 * Get the Host's IPv4 in a numeric expression
	 *
	 * @return int
	 */
	public function getHostIPv4Long() {
		return $this->get( 'host_ipv4' );
	}

	/**
	 * Get the Host's IPv4 in the expected dotted format
	 *
	 * @return string
	 */
	public function getHostIPv4() {
		return long2ip( $this->getHostIPv4Long() );
	}

	/**
	 * Get the host's hostname
	 *
	 * @return string
	 */
	public function getHostName() {
		return $this->get( 'host_hostname' );
	}

	/**
	 * Get the Host's description
	 *
	 * @return string
	 */
	public function getHostDescription() {
		return $this->get( 'host_description' );
	}

	/**
	 * Normalize an Host object
	 */
	protected function normalizeHost() {
		$this->integers(
			'host_ID',
			'host_ipv4'
		);
	}

}

/**
 * Describe the 'host' database table
 */
class Host extends Queried {

	use HostTrait;

	/**
	 * Table name
	 */
	const T = 'host';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeHost();
	}

	/**
	 * Force to get a Host ID, whatever is passed
	 *
	 * @param  mixed $host Host object or Host ID
	 * @return int
	 */
	public static function getID( $host ) {
		return is_object( $host ) ? $host->getHostID() : (int)$host;
	}
}
