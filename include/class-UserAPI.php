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

/**
 * Methods for an UserAPI class
 */
trait UserAPITrait {

	/**
	 * Filter to a certain User UID
	 *
	 * @param string $uid User UID
	 * @return self
	 */
	public function whereUserUID( $uid ) {
		return $this->whereStr( 'user_uid', $uid );
	}

	/**
	 * Filter to a certain User E-mail
	 *
	 * @param string $email User E-mail
	 * @return self
	 */
	public function whereUserEmail( $email ) {
		return $this->whereStr( 'user_email', $email );
	}

	/**
	 * Filter to a certain User ID
	 *
	 * @param string $uid User ID
	 * @return self
	 */
	public function whereUserID( $id ) {
		return $this->whereInt( static::USER_ID, $id );
	}

	/**
	 * Filter to myself
	 *
	 * @return self
	 */
	public function whereUserIsMe() {
		$id = get_user()->getUserID();
		return $this->whereUserID( $id );
	}

	/**
	 * WHere the User(s) is editable
	 *
	 * @return Query
	 */
	public function whereUserIsEditable() {

		// if I can't see everyone, just see myself
		if( !has_permission( 'edit-user-all' ) ) {
			$this->whereUserIsMe();
		}

		return $this;
	}

	/**
	 * Limit to a specific User
	 *
	 * @param object $user User
	 * @return self
	 */
	public function whereUser( $user ) {
		$id = $user->getSessionuserID();
		return $this->whereUserID( $id );
	}

}

/**
 * Query the 'user' database table
 */
class UserAPI extends Query {

	use UserAPITrait;

	/**
	 * Univoque column name of the User ID
	 */
	const USER_ID = 'user.user_ID';

	/**
	 * Constructor
	 *
	 * @param object $db Database (or NULL for the current one)
	 */
	public function __construct( $db = null ) {

		// set database and class name
		parent::__construct( $db, User::class );

		// set database table
		$this->from( User::T );
	}

}
