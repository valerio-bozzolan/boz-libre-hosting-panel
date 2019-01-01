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
 * Domain API
 */
class DomainAPI extends Query {

	const UID = 'name';

	public function __construct() {
		parent::__construct();
		$this->from( Domain::T );
		$this->defaultClass( 'Domain' );
	}

	/**
	 * Where the domains are editable by me
	 *
	 * @return self
	 */
	public function whereDomainIsEditable() {
		if( ! has_permission( 'edit-domain-all' ) ) {
			$this->whereDomainUser();
		}
		return $this;
	}

	/**
	 * Limit to a certain user (or yourself)
	 *
	 * @param $user_ID int
	 * @return self
	 */
	public function whereDomainUser( $user_ID = false ) {
		if( $user_ID === false ) {
			$user_ID = get_user( 'user_ID' );
		}
		$this->joinDomainUser();
		return $this->whereInt( 'domain_user.user_ID', $user_ID );
	}

	/**
	 * Limit to a certian domain name
	 *
	 * @param $domain_name string
	 * @return self
	 */
	public function whereDomainName( $domain_name ) {
		return $this->whereStr( 'domain_name', $domain_name );
	}

	/**
	 * Constructor from a domain ID
	 *
	 * @param $domain_ID int
	 * @return self
	 */
	public function whereDomainID( $domain_ID ) {
		return $this->whereInt( 'domain.domain_ID', $domain_ID );
	}

	/**
	 * Constructor from a Domain object
	 *
	 * @param $domain object
	 * @return self
	 */
	public function whereDomain( $domain ) {
		return $this->whereDomainID( $domain->getDomainID() );
	}

	/**
	 * Join domain and users (once)
	 *
	 * @return self
	 */
	public function joinDomainUser() {
		if( empty( $this->joinedDomainUser ) ) {
			$this->from( 'domain_user' );
			$this->equals( 'domain_user.domain_ID', 'domain.domain_ID' );

			$this->joinedDomainUser = true;
		}
		return $this;
	}
}
