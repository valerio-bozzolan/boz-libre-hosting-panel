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

// assure load of dependent traits
class_exists( 'DomainAPI' );
class_exists( 'UserAPI' );

/**
 * Trait for a DomainUserAPI class
 */
trait DomainUserAPITrait {

	use DomainAPITrait;
	use UserAPITrait;

}

/**
 * Query the 'domain_user' table
 */
class DomainUserAPI extends Query {

	use DomainUserAPITrait;

	/**
	 * Univoque Domain ID column name
	 *
	 * Used by DomainApi
	 */
	const DOMAIN_ID = 'domain_user.domain_ID';

	/**
	 * Univoque User ID column name
	 *
	 * Used by DomainApi
	 */
	const USER_ID = 'domain_user.user_ID';

	/**
	 * Constructor
	 *
	 * @param object $db Database (or NULL for the current one)
	 */
	public function __construct( $db = null ) {

		// set database and class name
		parent::__construct( $db, DomainUser::class );

		// set database table
		$this->from( DomainUser::T );
	}

}
