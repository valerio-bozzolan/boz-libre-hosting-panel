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
 * Another Net_SMTP wrapper
 */
class SMTPMail {

	/**
	 * @var object
	 */
	private $smtp;

	/**
	 * Get the singleton instance
	 */
	public static function instance() {
		static $instance = false;
		if( ! $instance ) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		if( ! class_exists( 'Net_SMTP' ) ) {
			require NET_SMTP;
		}

		$this->smtp = new Net_SMTP( SMTP_SERVER );
		if( ! $this->smtp ) {
			die( "Unable to instantiate Net_SMTP object\n" );
		}

		/* Connect to the SMTP server. */
		if( PEAR::isError($e = $this->smtp->connect()) ) {
			die( $e->getMessage() . "\n" );
		}
		$this->smtp->auth( SMTP_USERNAME, SMTP_PASSWORD, SMTP_AUTH, SMTP_TLS );

		/* Send the 'MAIL FROM:' SMTP command. */
		if( PEAR::isError( $this->smtp->mailFrom( MAIL_FROM ) ) ) {
			die("Unable to set sender to <$from>\n");
		}
	}

	/**
	 * Add a recipient
	 *
	 * @param $to string E-mail
	 */
	public function to( $to ) {
		if( PEAR::isError( $res = $this->smtp->rcptTo( $to ) ) ) {
			die( "Unable to add recipient <$to>: " . $res->getMessage() . "\n" );
		}
		return $this;
	}

	/**
	 * Specify subject and message body
	 *
	 * @param $subject string
	 * @param $body string
	 */
	public function message( $subject, $body ) {
		$data = '';

		$headers = [ 'Subject' => $subject ];
		foreach( $headers as $header => $value ) {
			$value = str_replace( "\n", '', $value );
			$data .= "$header: $value\n";
		}

		$data .= "\r\n$body";

		if (PEAR::isError( $this->smtp->data( $data ) ) ) {
			die( "Unable to send data\n" );
		}

		return $this;
	}

	/**
	 * Disconnect to the SMTP server
	 */
	public function disconnect() {
		$this->smtp->disconnect();
		unset( $this->smtp );
	}
}
