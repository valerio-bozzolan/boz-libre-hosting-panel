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

// load the dependent traits
class_exists( Host::class, true );

/**
 * Methods for a MTA class
 */
trait MTATrait {

	use HostTrait;

	/**
	 * Get the MTA's ID
	 *
	 * @return int
	 */
	public function getMTAID() {
		return $this->get( 'mta_ID' );
	}

	/**
	 * Get the name of this MTA
	 *
	 * @return string
	 */
	public function getMTAName() {
		return $this->getHostName();
	}

	/**
	 * Normalize an MTA object
	 */
	protected function normalizeMTA() {
		$this->integers( 'mta_ID' );
	}

}

/**
 * Describe the 'mta' database table
 *
 * This rappresents a single Mail Transfer Agent
 *
 * https://en.wikipedia.org/wiki/Message_transfer_agent
 *
 * Actually the KISS Libre Hosting Panel supports
 * multiple MTA for a single host. That's why an MTA
 * is a different entity than an Host.
 *
 * Anyway, in the current implementation it's just
 * a pointer to the Host so, trust me, it's not
 * a brainfuck but just a no-cost feature to describe
 * whatever weird infrastructure.
 */
class MTA extends Queried {

	use MTATrait;
	use HostTrait;

	/**
	 * Table name
	 */
	const T = 'mta';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeMTA();
		$this->normalizeHost();
	}

	/**
	 * Get the Domain-MTA permalink
	 *
	 * @param string  $domain_name Domain name
	 * @param boolean $absolute    True for an absolute URL
	 */
	public static function domainPermalink( $domain_name = null, $absolute = false ) {
		$url = 'domain-mta.php';
		if( $domain_name ) {
			$url .= _ . $domain_name;
		}
		return site_page( $url, $absolute );
	}

	/**
	 * Force to get a MTA ID, whatever is passed
	 *
	 * @param  mixed $mta MTA object or MTA ID
	 * @return int
	 */
	public static function getID( $mta ) {
		return is_object( $mta ) ? $mta->getMTAID() : (int)$mta;
	}
}
