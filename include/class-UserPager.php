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

/**
 * User users API
 */
class UserPager extends QueryPager {

	/**
	 * Constructor
	 *
	 * @param $data array
	 */
	public function __construct( $data = [] ) {
		parent::__construct();

		if( isset( $data['uid'] ) ) {
			$data['uid'] = luser_input( $data['uid'], 32 );
			$this->setArg( 'uid', $data['uid'] );
		}
	}

	/**
	 * Create a Query for User(s)
	 *
	 * @return Query
	 */
	public function createQuery() {
		$query = new UserAPI();

		$query->whereUserIsEditable();

		return $query;
	}


	/**
	 * Eventually apply an order
	 *
	 * @override
	 */
	public function applyOrder( & $query, $order_by, $direction ) {


	}

}
