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
	private $ymin = null;

	/**
	 * Maximum value to be plotted
	 */
	private $ymax = null;

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
		if( $value > $this->ymax ) {
			$this->ymax = $value;
		}

		// check if this is the new min
		if( $this->ymin === null || $value < $this->ymin ) {
			$this->ymin = $value;
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

		// no data no party
		if( !$this->data ) {
			return null;
		}

		// how many number of characters
		$height = $args['height'] ?? 12;

		// how many labels for the y axis
		$y_nlabels = $args['ynlabels'] ?? 5;

		// callback that will generate each x-label
		$xlabel_format = $args['xlabel-format'] ?? null;

		// callback that will generate each y-label
		$ylabel_format = $args['ylabel-format'] ?? null;

		// label for the x axis
		$xaxis_label = $args['xlabel'] ?? 'Time';

		// label for the x axis
		$yaxis_label = $args['ylabel'] ?? null;

		// character to be used to plot a piece of chart
		$dot = $args['dot'] ?? '+';

		// charater used to separate the y-labels from the data
		$column_separator = $args['column-separator'] ?? '|';

		// charater used to separate each y-labels
		$row_separator = $args['row-separator'] ?? '—';

		// character used to separate the footer
		$footer_separator = $args['footer-separator'] ?? $row_separator;

		// as default, the y label is just an integer value, with some left padding
		if( !$ylabel_format ) {
			$ylabel_format = function( $v ) {
				return $v;
			};
		}

		// as default, the y label is just an integer value, with some left padding
		if( !$xlabel_format ) {
			$xlabel_format = function( $v ) {
				return $v->format( 'Y-m-d (H:i)' );
			};
		}

		// shortcuts for the y axis min and max values
		$ymin = $this->ymin;
		$ymax = $this->ymax;

		// shortcuts for the x axis min and max values
		// note that data[0] is the first element, and data[0][0] is its x
		$data_n = count( $this->data );
		$xmin = $this->data[0][0];
		$xmax = $this->data[ $data_n - 1 ][0];

		// how much amount between miny and max
		$range = $ymax - $ymin;

		// array of columns, from left to right
		// every column has a char from bottom to top
		$columns = [];

		// how much is the weight of a single y character
		$ystep = $range / $y_nlabels;

		/**
		 * Y-HEADING labels
	 	 */
		$yheading = [];
		for( $i = 0; $i < $height; $i = $i + $ystep ) {
			$yheading[ (int) $i ] = $ylabel_format( $ymin + $i );
		}

		// uniform all the rows of the Y-heading and get the lenght in chars
		$yheading_len = self::padColumn( $yheading, $height, $row_separator );

		// register the y-heading
		$columns[] = $yheading;

		/**
		 * Y VERTICAL DIVISION
		 */
		$vertical_row = [];
		for( $i = 0; $i < $height; $i++ ) {
			$vertical_row[ $i ] = $column_separator;
		}
		$columns[] = $vertical_row;
		$yheading_len++;

		// plot the data columns
		foreach( $this->data as $element ) {
			list( $date, $value ) = $element;

			// correlate the data to the y axis
			$value_position = ( $value - $ymin ) / ( $range ) * ( $height - 1 );
			$value_position = (int) $value_position;

			// plot this element
			$data_column = [];
			$data_column[ $value_position ] = $dot;

			// uniform all the rows of this column
			self::padColumn( $data_column, $height );

			// register this data column
			$columns[] = $data_column;
		}

		// current chart dimensions in chars (plus the y-labels)
		$width  = $data_n + $yheading_len;
		$height = count( $columns[0] );


		// the whole chart
		$chart = '';

		/**
		 * Y axis label (if any) and arrow
		 */
		$yaxis_margin = str_repeat( ' ', $yheading_len - 1 );
		if( $yaxis_label ) {
			$chart .= $yaxis_margin . $yaxis_label . "\n";
		}
		$chart .= $yaxis_margin . '↑' . "\n";

		// build the data chart (from top to bottom)
		$n_columns = count( $columns );
		for( $row = $height - 1; $row >= 0; $row-- ) {
			for( $column = 0; $column < $n_columns; $column++ ) {
				$chart .= $columns[ $column ][ $row ];
			}
			$chart .= "\n";
		}

		/**
		 * Footer ( y => x )
		 */
		$footer = [];

		// footer line separator and x axis label
		$footer_line = $yaxis_margin . $column_separator . str_repeat( $row_separator, $width ) . '→ ' . $xaxis_label;
		self::intoMatrix( $footer, 0, 0, $footer_line );

		// x-min label
		self::intoMatrixMultiline( $footer, 1, $yheading_len - 1, [
			'|',
			'|',
			$xlabel_format( $xmin ),
		] );

		// x-max label
		self::intoMatrixMultiline( $footer, 1, $width - 1, [
			'|',
			$xlabel_format( $xmax ),
		] );

		// print the footer
		$chart .= self::matrix2text( $footer );

		return $chart;
	}

	/**
	 * Put some empty spaces as padding for this array of rows
	 *
	 * @param $rows array  Array of characters
	 * @param $n    int    Number of the elements that should be present
	 * @param $pad  string Character used for the padding
	 * @return      int    Length of the column in bytes
	 */
	public static function padColumn( & $rows, $n, $pad = ' ' ) {
		// check the maximum length of this column
		$size = 0;
		foreach( $rows as $row ) {
			if( $row ) {
				$size = max( $size, mb_strlen( $row ) );
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

			if( $diff ) {
				$padding = str_repeat( $pad, $diff );
				$rows[ $i ] = $padding . $rows[ $i ];
			}
		}

		return $size;
	}

	/**
	 * Put a string in a matrix of characters
	 *
	 * @param array  $matrix Matrix of chars (y => x)
	 * @param int    $y      Starting y coordinate (y starts from top)
	 * @param int    $x      Starting x coordinate (x starts from left)
	 * @param string $s      Multi-byte string to be put in the char matrix
	 */
	private static function intoMatrix( &$matrix, $y, $x, $s ) {

		// split the string in bytes (chars)
		$parts = self::split( $s );
		$len = count( $parts );

		// the horizontal line must exists
		if( !isset( $matrix[ $y ] ) ) {
			$matrix[ $y ] = [];
		}

		// append each char
		for( $i = 0; $i < $len; $i++ ) {
			$matrix[ $y ][ $x ] = $parts[ $i ];
			$x++;
		}
	}

	/**
	 * Put some strings in a matrix of characters
	 *
	 * @param array  $matrix Matrix of chars (y => x)
	 * @param int    $y      Starting y coordinate (y starts from top)
	 * @param int    $x      Starting x coordinate (x starts from left)
	 * @param array  $lines  Multi-byte strings to be put in the char matrix
	 */
	private static function intoMatrixMultiline( &$matrix, $y, $x, $lines ) {
		foreach( $lines as $line ) {
			self::intoMatrix( $matrix, $y, $x, $line );
			$y++;
		}
	}

	/**
	 * From a matrix of characters, return some text
	 *
	 * @param  array $matrix Matrix of chars (y => x)
	 * @return string
	 */
	private static function matrix2text( $matrix ) {

		$txt = '';

		// latest array key (y length)
		end( $matrix );
		$y_max = key( $matrix );

		// print each line
		for( $y = 0; $y <= $y_max; $y++ ) {

			$line = $matrix[ $y ];

			// latest array key of this line (x length)
			end( $line );
			$x_max = key( $line );

			// print each char of this line (if it exists)
			for( $x = 0; $x <= $x_max; $x++ ) {
				$txt .= $line[ $x ] ?? ' ';
			}

			$txt .= "\n";
		}

		return $txt;

	}

	/**
	 * Split a multibyte string
	 *
	 * See https://stackoverflow.com/a/2556348/3451846
	 *
	 * @param $s string
	 * @return array
	 */
	private static function split( $s ) {

		$parts = [];

		$n = mb_strlen( $s );
		for( $i = 0; $i < $n; $i++ ) {
			$parts[] = mb_substr( $s, $i, 1 );
		}

		return $parts;
	}
}
