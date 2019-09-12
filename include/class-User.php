<?php
# Copyright (C) 2019 Valerio Bozzolan
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

trait UserTrait {

	/**
	 * Normalize a User object
	 */
	protected function normalizeUser() {
		$this->integers( 'user_ID' );
	}

	/**
	 * Get the user ID
	 *
	 * @return int
	 */
	public function getUserID() {
		return $this->get( 'user_ID' );
	}

	/**
	 * Get the user first name
	 *
	 * @return string
	 */
	public function getUserName() {
		return $this->get( 'user_name' );
	}

	/**
	 * Get the user surname
	 *
	 * @return string
	 */
	public function getUserSurname() {
		return $this->get( 'user_surname' );
	}

	/**
	 * Get the user UID
	 *
	 * @return string
	 */
	public function getUserUID() {
		return $this->get( 'user_uid' );
	}

	/**
	 * Get the user E-mail
	 *
	 * @return string
	 */
	public function getUserEmail() {
		return $this->get( 'user_email' );
	}

	/**
	 * Get the role of this user
	 *
	 * @return string
	 */
	public function getUserRole() {
		return $this->get( 'user_role' );
	}

	/**
	 * Get the human name of the user role
	 *
	 * @return string
	 */
	public function getUserRoleLabel() {
		$roles = User::roles();
		$role = $this->getUserRole();
		return $roles[ $role ];
	}

	/**
	 * Check if this user is me
	 *
	 * @return boolean
	 */
	public function isUserMyself() {
		$id = $this->getUserID();
		return is_logged() && get_user()->getUserID() === $id;
	}

	/**
	 * Check if I can edit this user
	 *
	 * @return boolean
	 */
	public function isUserEditable() {
		return $this->isUserMyself() || has_permission( 'edit-user-all' );
	}

	/**
	 * Get the domain edit URl
	 *
	 * @param boolean $absolute True for an absolute URL
	 * @return string
	 */
	public function getUserPermalink( $absolute = false ) {
		return User::permalink( $this->getUserUID(), $absolute );
	}
}

/**
 * A mailbox
 */
class User extends Sessionuser {

	use UserTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeUser();
	}

	/**
	 * Get the known user roles
	 *
	 * @return array
	 */
	public static function roles() {
		return [
			'user'  => __( "User" ),
			'admin' => __( "Admin" ),
		];
	}

	/**
	 * Get the User permalink
	 *
	 * @param  string  $user     User UID
	 * @param  boolean $absolute
	 * @return string
	 */
	public static function permalink( $user = null, $absolute = false ) {
		$part = site_page( 'user.php', $absolute );
		if( $user ) {
			$part .= _ . $user;
		}
		return $part;
	}
}
