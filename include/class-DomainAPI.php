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

// load dependent traits
class_exists( 'PlanAPI' );

/**
 * Methods related to a Domain class
 */
trait DomainAPITrait {

	use PlanAPITrait;

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
	 * Where the Domain is Active (or not)
	 *
	 * @param  boolean $active If you want the active, or the inactive
	 * @return self
	 */
	public function whereDomainIsActive( $active = true ) {
		return $this->whereInt( 'domain_active', $active );
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
		return $this->whereInt( static::DOMAIN_ID, $domain_ID );
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
	 * Order by the Domain name
	 *
	 * @param  string $direction DESC|ASC
	 * @return self
	 */
	public function orderByDomainName( $direction = null ) {
		return $this->orderBy( 'domain_name', $direction );
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

	/**
	 * Join whatever table with the domain table
	 *
	 * @return self
	 */
	public function joinDomain() {
		return $this->joinOn( 'INNER', 'domain', static::DOMAIN_ID, 'domain.domain_ID' );
	}

}

/**
 * Domain API
 */
class DomainAPI extends Query {

	use DomainAPITrait;

	/**
	 * Univoque Domain ID column name
	 */
	const DOMAIN_ID = 'domain.domain_ID';

	/**
	 * Univoque Plan ID column name
	 */
	const PLAN_ID = 'domain.plan_ID';

	/**
	 * Constructor
	 *
	 * @param object $db Database (or NULL for the current one)
	 */
	public function __construct( $db = null ) {
		// set database and class name
		parent::__construct( $db, 'Domain' );

		// set database table
		$this->from( Domain::T );
	}

}
