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
 * A domain handled by the user
 */
class Domain extends Queried {

	const T = 'domain';
	const UID = 'domain_name';

	public function __construct() {
		$this->integers( 'domain_ID' );
		$this->booleans( 'domain_active' );
		$this->dates( 'domain_born', 'domain_expiration' );
	}

	/**
	 * Get domain ID
	 *
	 * @return int
	 */
	public function getDomainID() {
		return $this->get( 'domain_ID' );
	}

	/**
	 * Get domain name
	 *
	 * @return string
	 */
	public function getDomainName() {
		return $this->get( 'domain_name' );
	}

	/**
	 * Get the domain edit URl
	 *
	 * @param boolean $absolute True for an absolute URL
	 * @return string
	 */
	public function getDomainPermalink( $absolute = false ) {
		return Domain::permalink( $this->get( 'domain_name' ), $absolute );
	}

	/**
	 * Factory mailbox from this domain
	 *
	 * @return MailboxFullAPI
	 */
	public function factoryMailbox() {
		return ( new MailboxFullAPI() )->whereDomain( $this );
	}

	/**
	 * Factory e-mail forward from this domain
	 *
	 * @return MailforwardFullAPI
	 */
	public function factoryMailforwardfrom() {
		return ( new MailforwardfromAPI() )->whereDomain( $this );
	}

	/**
	 * Factory FTP users from this domain
	 *
	 * @return FTPAPI
	 */
	public function factoryFTP() {
		return ( new FTPAPI() )->whereDomain( $this );
	}

	/**
	 * Get the domain permalink
	 *
	 * @param string  $domain_name Domain name
	 * @param boolean $absolute    True for an absolute URL
	 */
	public static function permalink( $domain_name = null, $absolute = false ) {
		$url = 'domain.php';
		if( $domain_name ) {
			$url .= _ . $domain_name;
		}
		return site_page( $url, $absolute );
	}
}
