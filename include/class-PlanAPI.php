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
 * Methods related to a Plan class
 */
trait PlanAPITrait {

	/**
	 * Limit to a specific Plan by its UID
	 *
	 * @param $plan_name string
	 * @return self
	 */
	public function wherePlanUID( $uid ) {
		return $this->whereStr( 'plan_uid', $plan_uid );
	}

	/**
	 * Constructor from a plan ID
	 *
	 * @param $plan_ID int
	 * @return self
	 */
	public function wherePlanID( $plan_ID ) {
		return $this->whereInt( static::PLAN_ID, $plan_ID );
	}

	/**
	 * Constructor from a Plan object
	 *
	 * @param $plan object
	 * @return self
	 */
	public function wherePlan( $plan ) {
		return $this->wherePlanID( $plan->getPlanID() );
	}

	/**
	 * Order by the Plan name
	 *
	 * @param  string $direction DESC|ASC
	 * @return self
	 */
	public function orderByPlanName( $direction = null ) {
		return $this->orderBy( 'plan_name', $direction );
	}

	/**
	 * Join whatever table with the plan table
	 *
	 * @return self
	 */
	public function joinPlan() {
		return $this->joinOn( 'INNER', 'plan', static::PLAN_ID, 'plan.plan_ID' );
	}

}

/**
 * Plan API
 */
class PlanAPI extends Query {

	use PlanAPITrait;

	/**
	 * Plan ID column name
	 */
	const PLAN_ID = 'plan.plan_ID';

	/**
	 * Constructor
	 *
	 * @param object $db Database (or NULL for the current one)
	 */
	public function __construct( $db = null ) {

		// set database connection and default class name for the results
		parent::__construct( $db, 'Plan' );

		// select from this database table
		$this->from( Plan::T );
	}

}
