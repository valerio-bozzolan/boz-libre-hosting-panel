<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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

/*
 * This is the single e-mail forwarding edit page
 */

// load framework
require '../load.php';

// wanted informations from the templates
$domain = null;
$plan   = null;

// URL paramenters (just domain)
list( $domain_name ) = url_parts( 1 );

// eventually retrieve domain from database
if( !$domain ) {
	$domain = ( new DomainAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',

			'plan.plan_ID',
			'plan_name',
			'plan_mailboxes',
			'plan_mailforwards',
			'plan_databases',
			'plan_ftpusers',
			'plan_yearlyprice',
		] )
		->whereDomainName( $domain_name )
		->joinPlan( 'LEFT' )
		->queryRow();

	// no domain no party
	if( !$domain ) {
		PageNotFound::spawn();
	}
}

// save destination action
if( is_action( 'domain-plan-save' ) ) {

	// check privileges
	require_permission( 'edit-domain-all' );

	// check if the submitted request has sense
	$new_plan_uid = $_POST['plan_uid'] ?? null;
	if( !$new_plan_uid || !is_string( $new_plan_uid ) ) {
		BadRequest::spawn();
	}

	// check if the new Plan exists
	$new_plan = ( new PlanAPI() )
		->wherePlanUID( $new_plan_uid )
		->queryRow();

	// no Plan no party
	if( !$new_plan ) {
		BadRequest::spawn( __( "Missing Plan" ) );
	}

	// finally update the Plan
	( new DomainAPI() )
		->whereDomain( $domain )
		->update( [
			'plan_ID' => $new_plan->getPlanID(),
		] );

	// POST -> REDIRECT -> GET
	http_redirect( $domain->getDomainPlanPermalink() );
}

// spawn header
Header::spawn( [
	'uid'          => false,
	'title'        => __( "Domain Plan" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'domain-plan-page', [
	'domain' => $domain,
	'plan'   => $domain,
] );

// spawn the footer
Footer::spawn();
