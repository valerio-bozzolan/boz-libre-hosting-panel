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
 * This is the template used to say that some mailboxes were overquota.
 *
 * Note that the output is considered in plaintext. No escape is done.
 *
 * Called from:
 * 	cli/update-mailbox-quotas.php
 *
 * Available variables:
 * 	$problematic_list array List of problematic mailboxes
 */

echo "Some mailboxes were overquota:\n";
foreach( $problematic_list as $problematic_entry ) {
	echo " $problematic_entry\n";
}
