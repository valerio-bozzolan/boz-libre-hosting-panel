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

/**
 * Amazing piece of shitcode to create a plot
 * in pure ASCII-art style.
 *
 * See https://gitpull.it/T292
 */
class ASCIILineChart {

	/**
	 * Associative array of [ x, y ]
 	 */
	private $data = [];

	/**
	 * Minimum value to be plotted
	 */
	private $min = null;

	/**
	 * Maximum value to be plotted
	 */
	private $max = null;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		// well yes but actually no asd
	}

	/**
	 * Add an element to the chart
	 *
	 * @param $date  date  Date for the x bar
	 * @param $value mixed Value for the y bar
	 */
	public function add( $date, $value ) {
		$this->data[] = [ $date, $value ];

		// check if this is the new max
		// note that every number is greater than NULL)
		if( $value > $this->max ) {
			$this->max = $value;
		}

		// check if this is the new min
		if( $this->min === null || $value < $this->min ) {
			$this->min = $value;
		}
	}

	/**
	 * Sort the internal data
	 *
	 * @param $callback callable Your custom sorting method
	 */
	public function sort( $callback = null ) {

		// as default sort by date
		if( !$callback ) {
			$callback = function( $a, $b ) {
				return ( $a > $b ) ? 1 : -1;
			};
		}

		usort( $this->data, $callback );
	}

	/**
	 * Get the ASCII-art chart.
	 *
	 * @param $args array Arguments
	 *                        data: associative array
	 * @return string ASCII chart
	 */
	public function render( $args = [] ) {

		// how many number of characters
		$height = $args['height'] ?? 12;

		// how many labels for the y axis
		$y_nlabels = $args['ynlabels'] ?? 5;

		// callback that will generate each y-label
		$ylabel_format = $args['ylabel-format'] ?? null;

		// character to be used to plot a piece of chart
		$dot = $args['dot'] ?? '·';

		// charater used to separate the y-labels from the data
		$column_separator = $args['column-separator'] ?? '|';

		// charater used to separate each y-labels
		$row_separator = $args['row-separator'] ?? '-';

		// character used to separate the footer
		$footer_separator = $args['footer-separator'] ?? $row_separator;

		// as default, the y label is just an integer value, with some left padding
		if( !$ylabel_format ) {
			$ylabel_format = function( $v ) {
				return $v;
			};
		}

		// shortcuts
		$min = $this->min;
		$max = $this->max;

		// how much amount between min and max
		$range = $max - $min;

		// array of columns, from left to right
		// every column has a char from bottom to top
		$columns = [];

		// how much is the weight of a single y character
		$ystep = $range / $y_nlabels;

		/**
		 * Y-HEADING labels
	 	 */
		$heading = [];
		for( $i = 0; $i < $height; $i = $i + $ystep ) {
			$heading[ (int) $i ] = $ylabel_format( $min + $i );
		}

		// uniform all the rows of the Y-heading
		self::padColumn( $heading, $height, $row_separator );

		// register the y-heading
		$columns[] = $heading;

		/**
		 * VERTICAL DIVISION
		 */
		$vertical_row = [];
		for( $i = 0; $i < $height; $i++ ) {
			$vertical_row[$i] = $column_separator;
		}
		$columns[] = $vertical_row;

		// plot the data columns
		foreach( $this->data as $element ) {
			list( $date, $value ) = $element;

			$data_column = [];
			$value_relative = $value - $min;

			$value_position = (int) ( ( $value - $min ) / ( $range ) * ( $height - 1 ) );
			$data_column[ $value_position ] = $dot;

			// uniform all the rows
			self::padColumn( $data_column, $height );

			// register this data column
			$columns[] = $data_column;
		}

		$chart = '';

		// print the whole chart
		$n_columns = count( $columns );
		for( $row = $height - 1; $row >= 0; $row-- ) {
			for( $column = 0; $column < $n_columns; $column++ ) {
				$chart .= $columns[ $column ][ $row ];
			}
			$chart .= "\n";
		}

		return $chart;
	}

	/**
	 * Put some empty spaces as padding for this array of rows
	 *
	 * @param $rows array  Array of characters
	 * @param $n    int    Number of the elements that should be present
	 * @param $pad  string Character used for the padding
	 */
	public static function padColumn( & $rows, $n, $pad = ' ' ) {
		// check the maximum length of this column
		$size = 0;
		foreach( $rows as $row ) {
			if( $row ) {
				$size = max( $size, strlen( $row ) );
			}
		}

		// fill from left
		for( $i = 0; $i < $n; $i++ ) {

			// must exist
			if( !isset( $rows[$i] ) ) {
				$rows[$i] = '';
			}

			$actual_size = mb_strlen( $rows[$i] );
			$diff = $size - $actual_size;

			if( $size ) {
				$padding = str_repeat( $pad, $diff );
				$rows[ $i ] = $padding . $rows[ $i ];
			}
		}
	}
}
