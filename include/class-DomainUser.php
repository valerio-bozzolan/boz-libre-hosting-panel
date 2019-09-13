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
class_exists( 'Domain' );
class_exists( 'User' );

/**
 * Trait for a DomainUser class
 */
trait DomainUserTrait {

	use DomainTrait;
	use UserTrait;

	/**
	 * Normalize a DomainUser object after being retrieved from database
	 */
	protected function normalizeDomainUser() {
		$this->normalizeDomain();
		$this->normalizeUser();
	}

}

/**
 * Describe the 'domain_user' table
 */
class DomainUser extends Queried {

	use DomainUserTrait;

	/**
	 * Database table
	 */
	const T = 'domain_user';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeDomainUser();
	}


}
