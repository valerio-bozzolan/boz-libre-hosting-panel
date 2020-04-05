<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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

// load Plan trait
class_exists( 'Plan' );

/**
 * Methods for a Domain class
 */
trait DomainTrait {

	use PlanTrait;

	/**
	 * Count of the Domain's Mailboxes
	 *
	 * This is a kind of cache
	 *
	 * @var int
	 */
	private $domainMailboxCount = null;

	/**
	 * Count of the Domain's FTP accounts
	 *
	 * This is a kind of cache
	 *
	 * @var int
	 */
	private $domainFTPAccountCount = null;

	/**
	 * Get domain ID
	 *
	 * @return int
	 */
	public function getDomainID() {
		return $this->get( 'domain_ID' );
	}

	/*
	 * Get domain name
	 *
	 * @return string
	 */
	public function getDomainName() {
		return $this->get( 'domain_name' );
	}

	/**
	 * Get the sanitized relative directory name of this domain name
	 *
	 * Actually this should be valid for both the MTA and for the webserver.
	 *
	 * @return string
	 */
	public function getDomainDirname() {

		$dir = $this->getDomainName();

		// it was validated during creation time, but validate also now
		// to prevent malicious actions over hacked databases
		sanitize_subdirectory( $dir );

		return $dir;
	}

	/**
	 * Get the domain edit URL
	 *
	 * @param boolean $absolute True for an absolute URL
	 * @return string
	 */
	public function getDomainPermalink( $absolute = false ) {
		return Domain::permalink( $this->getDomainName(), $absolute );
	}

	/**
	 * Get the permalink to the edit plan page
	 *
	 * @param  boolean $absolute True for an absolute URL
	 * @return string
	 */
	public function getDomainPlanPermalink( $absolute = false ) {
		return Plan::domainPermalink( $this->getDomainName(), $absolute );
	}

	/**
	 * Check if you can create a new Mailbox for this Domain
	 *
	 * The Domain must have Plan informations.
	 *
	 * @return boolean
	 */
	public function canCreateMailboxInDomain() {
		return $this->getPlanMailboxes() > $this->getDomainMailboxCount()
		       ||
		       has_permission( 'edit-email-all' );
	}

	/**
	 * Check if you can create a new FTP account for this Domain
	 *
	 * The Domain must have Plan informations.
	 *
	 * The Domain must have Plan informations.
	 */
	public function canCreateFTPAccountForDomain() {
		return $this->getPlanFTPUsers() > $this->getDomainFTPAccountCount()
		       ||
		       has_permission( 'edit-ftp-all' );
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
	 * Factory e-mail forward from this domain
	 *
	 * @return MailforwardFullAPI
	 */
	public function factoryMailforwardfrom() {
		return ( new MailforwardfromQuery() )->whereDomain( $this );
	}

	/**
	 * Set a count of Domain's Mailboxes
	 *
	 * This method should not be used directly.
	 *
	 * @param $count int
	 * @return self
	 */
	public function setDomainMailboxCount( $count ) {
		$this->domainMailboxCount = $count;
	}

	/**
	 * Get the number of Mailboxes of this Domain
	 *
	 * This method has a layer of cache.
	 *
	 * @return int
	 */
	public function getDomainMailboxCount() {

		// check if we already know the count
		if( !isset( $this->domainMailboxCount ) ) {

			// count the number of mailboxes associated to this Domain
			$count = $this->factoryMailbox()
				->select( 'COUNT(*) count' )
				->queryValue( 'count' );

			// save in cache
			$this->domainMailboxCount = (int) $count;
		}

		return $this->domainMailboxCount;
	}

	/**
	 * Get the number of FTP accounts of this Domain
	 *
	 * This method has a layer of cache.
	 *
	 * @return int
	 */
	public function getDomainFTPAccountCount() {

		// check if we already know the count
		if( !isset( $this->domainFTPAccountCount ) ) {

			// count the number of mailboxes associated to this Domain
			$count = $this->factoryFTP()
				->select( 'COUNT(*) count' )
				->queryValue( 'count' );

			// save in cache
			$this->domainFTPAccountCount = (int) $count;
		}

		return $this->domainFTPAccountCount;
	}

	/**
	 * Get the expected MTA directory containing Domain's mailboxes
	 *
	 * This pathname should be considered true for the MTA host.
	 *
	 * TODO: actually all the mailbox are on the same host.
	 * Then, we should support multiple hosts.
	 *
	 * @return string
	 */
	public function getDomainMailboxesPath() {
		// mailboxes are stored under a $BASE/domain/username filesystem structure
		return MAILBOX_BASE_PATH . __ . $this->getDomainDirname();
	}

	/**
	 * Get the expected and sanitized base domain directory containing its directories
	 *
	 * This pathname should be considered true both for the webserver serving
	 * that domain and for the related FTP server.
	 *
	 * TODO: actually all the domains are on the same host.
	 * Then, we should support multiple hosts.
	 *
	 * @return string
	 */
	public function getDomainBasePath() {
		// mailboxes are stored under a $BASE/domain/username filesystem structure
		return VIRTUALHOSTS_DIR . __ . $this->getDomainDirname();
	}

	/**
	 * Factory FTP users from this domain
	 *
	 * @return FTPAPI
	 */
	public function factoryFTP() {
		return ( new FTPAPI() )->whereDomain( $this );
	}

	/**
	 * Normalize a Domain object after being retrieved from database
	 */
	protected function normalizeDomain() {
		$this->integers( 'domain_ID' );
		$this->booleans( 'domain_active' );
		$this->dates( 'domain_born', 'domain_expiration' );

		$this->normalizePlan();
	}

}

/**
 * Describe the 'domain' table
 */
class Domain extends Queried {

	use DomainTrait;

	/**
	 * Table name
	 */
	const T = 'domain';

	const UID = 'domain_name';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeDomain();
	}

	/**
	 * Get the domain permalink
	 *
	 * @param string  $domain_name Domain name
	 * @param boolean $absolute    True for an absolute URL
	 */
	public static function permalink( $domain_name = null, $absolute = false ) {
		$url = 'domain.php';
		if( $domain_name ) {
			$url .= _ . $domain_name;
		}
		return site_page( $url, $absolute );
	}

}
