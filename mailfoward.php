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

/*
 * This is the single e-mail fowarding edit page
 */

// load framework
require 'load.php';

// wanted domain and mail fowarding source
list( $domain_name, $mailfoward_source ) = url_parts( 2 );

// retrieve domain
$mailfoward = ( new MailfowardFullAPI )
	->select( [
		'domain.domain_ID',
		'domain.domain_name',
		'domain.domain_active',
		'mailfoward_source',
		'mailfoward_destination',
	] )
	->whereDomainName( $domain_name )
	->whereMailfowardSource( $mailfoward_source )
	->whereDomainIsEditable()
	->queryRow();

// 404?
$mailfoward or PageNotFound::spawn();

// handle save destination action
if( is_action( 'mailfoward-save-destination' ) ) {
	$destination = $_POST[ 'mailfoward_destination' ];
	if( filter_var( $destination, FILTER_VALIDATE_EMAIL ) ) {
		$mailfoward->update( [
			new DBCol( 'mailfoward_destination', $destination, 's' ),
		] );

		// POST/redirect/GET
		http_redirect( URL . $_SERVER[ 'REQUEST_URI' ] );
	}
}

// spawn header
Header::spawn( [
	'title' => sprintf(
		_( "Mailfoward: %s" ),
		"<em>" . esc_html( $mailfoward->getMailfowardAddress() ) . "</em>"
	),
] );

// spawn the page content
template( 'mailfoward', [
	'mailfoward' => $mailfoward,
] );

// spawn the footer
Footer::spawn();
