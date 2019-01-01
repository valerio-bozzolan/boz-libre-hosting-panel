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
	 * @return string
	 */
	public function getDomainPermalink( $absolute = false ) {
		return ROOT . '/domain.php/' . $this->get( 'domain_name' );
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
	 * Factory e-mail foward from this domain
	 *
	 * @return MailfowardFullAPI
	 */
	public function factoryMailfoward() {
		return ( new MailfowardFullAPI() )->whereDomain( $this );
	}

}
