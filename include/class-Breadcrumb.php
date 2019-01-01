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
 * Handle a breadcrumb with the related microdata
 *
 * @url https://schema.org/BreadcrumbList
 * @url https://developers.google.com/search/docs/data-types/breadcrumb
 */
class Breadcrumb {

	/**
	 * Spawn the breadcrumb
	 *
	 * @param $uid string|null Starting menu uid
	 * @param $title string|null Starting title
	 * @param $parent_uid string|null Parent menu uid
	 */
	public static function spawn( $breadcrumbs ) {
		?>
			<ol itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumb">
				<?php $i = 0 ?>
				<?php foreach( $breadcrumbs as $crumb ): ?>
					<?php self::menuEntryLink( ++$i, $crumb ) ?>
				<?php endforeach ?>
			</ol>
		<?php
	}

	/**
	 * Spawn a link from a menu entry
	 *
	 * @param $i int
	 * @param $entry MenuEntry
	 */
	public static function menuEntryLink( $i, $entry ) {
		$url = $entry->url ? $entry->getSitePage() : null;
		return self::link( $i, $url, $entry->name );
	}

	/**
	 * Spawn a link with microdata
	 */
	public static function link( $i, $url, $title ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		if( $url ) {
			echo '<a itemprop="item" href="' . esc_attr( $url ) . '">';
			echo '<span itemprop="name">' . esc_html( $title ) . '</span>';
			echo '</a>';
		} else {
			echo '<span itemprop="name">' . _esc_html( $title ) . '</span>';
		}
		echo '<meta itemprop="position" content="' . $i . '" />';
		echo "</li>\n";
	}

}
