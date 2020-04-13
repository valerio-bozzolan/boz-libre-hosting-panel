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
 * This is the template for a Mailbox size chart.
 *
 * Called from:
 * 	template/mailbox-stats.php
 *
 * Available variables:
 * 	$mailbox object
 */

// avoid to be load directly
defined( 'BOZ_PHP' ) or die;

// query one year of data
$stats = ( new MailboxSizeAPI() )
	->whereMailbox( $mailbox )
	->whereMailboxSizeInLatestYear()
	->queryGenerator();

// prepare the chart
$chart_args = [
	// label used for the y axis
	'ylabel' => __( "Size" ),

	// label used for each y axis data
	'ylabel-format' => function( $bytes ) {
		return human_filesize( $bytes );
	},
];

// prepare the chart
$chart = new ASCIILineChart();
foreach( $stats as $stat ) {
	$chart->add( $stat->getMailboxSizeDate(), $stat->getMailboxSizeBytes() );
}

// sort by date
$chart->sort();
?>

	<!-- start size chart -->
	<h3><?= esc_html( __( "Track Size" ) ) ?></h3>

	<pre class="ascii-chart"><?= $chart->render( $chart_args ) ?></pre>
	<!-- end size chart -->
