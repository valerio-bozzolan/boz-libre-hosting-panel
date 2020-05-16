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
 * This is the template for the activity panel
 *
 * Called from
 * 	include/class-ActivityPanel.php - the_link() function
 *
 * Available variables:
 * 	$message_args  array    Arguments
 * 	$query         QueryLog The query log
 */
?>

<ul>
<?php foreach( $query->queryGenerator() as $log ): ?>

	<li><?= $log->getLogMessageWithDateAndUser( $message_args ) ?></li>

<?php endforeach ?>
</ul>
