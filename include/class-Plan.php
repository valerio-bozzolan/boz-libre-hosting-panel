<?php
# Copyright (C) 2018, 2019, 2020, 2021, 2022 Valerio Bozzolan
# KISS Libre Hosting Panel
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

// load Plan trait
class_exists( 'Plan' );

/**
 * Methods for a Plan class
 */
trait PlanTrait {

	/**
	 * Get plan ID
	 *
	 * @return int
	 */
	public function getPlanID() {
		return $this->get( 'plan_ID' );
	}

	/*
	 * Get plan name
	 *
	 * @return string
	 */
	public function getPlanName() {
		return $this->get( 'plan_name' );
	}

	/*
	 * Get the plan UID
	 *
	 * @return string
	 */
	public function getPlanUID() {
		return $this->get( 'plan_uid' );
	}

	/**
	 * Get the number of FTP users of this Plan
	 *
	 * @return int|null
	 */
	public function getPlanFTPUsers() {
		return $this->get( 'plan_ftpusers' );
	}

	/**
	 * Get the number of Databases of this Plan
	 *
	 * @return int|null
	 */
	public function getPlanDatabases() {
		return $this->get( 'plan_databases' );
	}

	/**
	 * Get the number of Mailboxes of this Plan
	 *
	 * @return int|null
	 */
	public function getPlanMailboxes() {
		return $this->get( 'plan_mailboxes' );
	}

	/**
	 * Get the number of Mailforwardings of this Plan
	 *
	 * @return int|null
	 */
	public function getPlanMailforwardings() {
		return $this->get( 'plan_mailforwards' );
	}

	/**
	 * Get the Plan yearly price
	 *
	 * @return float
	 */
	public function getPlanYearlyPrice() {
		return $this->get( 'plan_yearlyprice' );
	}

	/**
	 * Get the Plan mailbox quota in bytes
	 *
	 * @return int
	 */
	public function getPlanMailboxQuota() {
		return $this->get( 'plan_mailboxquota' );
	}

	/**
	 * Get the plan edit URl
	 *
	 * @param boolean $absolute True for an absolute URL
	 * @return string
	 */
	public function getPlanPermalink( $absolute = false ) {
		return Plan::permalink( $this->get( 'plan_name' ), $absolute );
	}

	/**
	 * Normalize a Plan object after being retrieved from database
	 */
	protected function normalizePlan() {
		$this->integers(
			'plan_ID',
			'plan_ftpusers',
			'plan_databases',
			'plan_mailboxes',
			'plan_mailforwards',
			'plan_mailboxquota',
		);
		$this->floats(
			'plan_yearlyprice'
		);
	}

}

/**
 * Describe the 'plan' table
 */
class Plan extends Queried {

	use PlanTrait;

	/**
	 * Table name
	 */
	const T = 'plan';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizePlan();
	}

	/**
	 * Get the plan permalink
	 *
	 * @param string  $plan_name Plan name
	 * @param boolean $absolute  True for an absolute URL
	 */
	public static function permalink( $plan_name = null, $absolute = false ) {
		$url = 'plan.php';
		if( $plan_name ) {
			$url .= "/$plan_name";
		}
		return site_page( $url, $absolute );
	}

	/**
	 * Get the Domain's Plan permalink
	 *
	 * @param string  $plan_name Plan name
	 * @param boolean $absolute  True for an absolute URL
	 */
	public static function domainPermalink( $domain_name = null, $absolute = false ) {
		$url = 'domain-plan.php';
		if( $domain_name ) {
			$url .= "/$domain_name";
		}
		return site_page( $url, $absolute );
	}

	/**
	 * Get a percentage from two values
	 *
	 * It's always between 0 and 100, or NULL if cannot be calculated.
	 *
	 * @param $current  int   Current amount
	 * @param $max      int   Maximum amount
	 * @param $overflow bool  Set to TRUE to allow to go over 100%
	 * @return         mixed Percentage or NULL
	 */
	public static function percentage( $current, $max, $overflow = false ) {

		// no args no party
		if( !$current || !$max ) {
			return null;
		}

		// cannot be negative
		if( $current < 0 ) {
			return 0;
		}

		// calculate the percentage
		$percentage = $current * 100 / $max;

		// you may don't want to go over 100%
		if( $percentage > 100 && !$overflow ) {
			return 100;
		}

		return (int) $percentage;
	}

	/**
	 * Force to get a Plan ID, whatever is passed
	 *
	 * @param  mixed $plan Plan object or Plan ID
	 * @return int
	 */
	public static function getID( $plan ) {
		return is_object( $plan ) ? $plan->getPlanID() : (int)$plan;
	}
}
