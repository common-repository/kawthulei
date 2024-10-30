<?php
/*
 * Plugin Name: Kawthulei
 * Description: This plugin allows you to more reliably use Karen Unicode text on your wordpress site. It inserts a character which helps the browser know where to break lines, it assigns a CSS class to all Karen text in your site (which a theme can then use to set the proper font), and it tries to make searching for Karen text across your site more reliable.
 * Version: 1.4
 * Author: Leroy Benjamin Sharon
 * Author URI: http://kanyawtech.org
 * License: GPLv2 or later
 *
 * Copyright (C) 2013 by Leroy Benjamin Sharon
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the:
 *   Free Software Foundation, Inc.,
 *   51 Franklin Street, Fifth Floor,
 *   Boston, MA 
 *   02110-1301,
 *   USA
 * 
 * 
 * Some Notes About This Program
 * 
 * This plugin is meant to do thee things:
 *  1. Suround all characters from the Myanmar code block (U+1000 to U+109F)
 *     with html span taggs which contain a class attribute set to kswText
 *  2. Insert U+200B (ZERO WIDTH SPACE) before all Karen consonants if
 *     they are preceded by a character from the Myanmar block.
 *  3. Change all ra-gha combinations to sha when inserting into the database
 *     (or searching it) and change all sha's to ra-gha when displaying on the
 *     front end
 *  4. Load scripts and CSS to make the fonts display properly across different
 *     browsers and platforms.
 * 
 * The wordpress framework doesn't provide a way good way to handle post and page
 * titles. For this reason, your theme files will need to be edited as well. Ideally,
 * you should do the following to your theme:
 *  1. Modify the code that displays titles so that this plugin can work on them too.
 *  2. Include a warning message that the script can show if it is not able to get
 *     Karen text displayed properly.
 * 
 * I will release a compatible theme. For now, I have a child theme of the wordpress
 * default TwentyTwelve theme hosted here: https://github.com/Ben01/yoyopo.git
 * 
 * Currently it imlements the two things I mentioned above. As I have time, I will
 * add a few more things.
 */
 

/**************************
* Set up some constants for use farther down
**************************/
define( "ksw_LINEBREAKCHAR", json_decode( '"\u200b"' ) );
// this character is inserted anywhere a browser is allowed to break a the line

define("ksw_KARENCSSCLASS", 'ksw');
// this is the class that Karen text will be set to

define("ksw_LANGUAGE", 'ksw');
// this is the language code that the tags will be set too (either mya, my, ksw, or pwo. I don't know which ones work on which browsers or if any work at all)

/**************************
* CSS
**************************/
function ksw_attachCSSFile() {
	wp_register_style( 'ksw-style', plugins_url('styles.css', __FILE__) );
	wp_enqueue_style( 'ksw-style' );
}
add_action( 'wp_enqueue_scripts', 'ksw_attachCSSFile' );


/**************************
* Post / page content hooks
**************************/
add_filter( 'the_content', 'ksw_prepForDisplay');
// applied to the post content retrieved from the database, prior to printing on the screen (also used in some other operations, such as trackbacks).
// applies to search results too

add_filter( 'the_content_feed', 'ksw_prepForDisplay');
// applied to the post content prior to including in an RSS feed.

add_filter( 'content_edit_pre', 'ksw_prepForEditing');
// applied to post excerpt prior to display for editing.

add_filter( 'content_save_pre', 'ksw_prepForDatabase');
// applied to post content prior to saving it in the database (also used for attachments).

/**************************
* Menu/widget content hooks
**************************/
add_filter( 'wp_nav_menu', 'ksw_prepForDisplay');
// applied to the menu name retrieved from the database, prior to printing on the screen.

add_filter( 'wp_list_pages', 'ksw_prepForDisplay');
// applied to the menu name retrieved from the database, prior to printing on the screen.

add_filter( 'widget_content', 'ksw_prepForDisplay');
// applied to the widget text retrieved from the database, prior to printing on the screen.

/**************************
* Content excerpt hooks
**************************/
add_filter( 'the_excerpt', 'ksw_prepForDisplay');
// applied to the post excerpt (or post content, if there is no excerpt) retrieved from the database, prior to printing on the screen (also used in some other operations, such as trackbacks).

add_filter( 'the_excerpt_rss', 'ksw_prepForDisplay');
// applied to the post excerpt prior to including in an RSS feed.

add_filter( 'excerpt_edit_pre', 'ksw_prepForEditing');
// applied to post excerpt prior to display for editing.

add_filter( 'excerpt_save_pre', 'ksw_prepForDatabase');
// applied to post excerpt prior to saving it in the database (also used for attachments).

/**************************
* Comment hooks
**************************/
add_filter( 'comment_text', 'ksw_prepForDisplay');
// applied to the comment text before displaying on the screen by the comment_text function, and in the admin menus.

add_filter( 'comment_text_rss', 'ksw_prepForDisplay');
// applied to the comment text prior to including in an RSS feed.

add_filter( 'pre_comment_content', 'ksw_prepForDatabase');
// applied to the content of a comment prior to saving the comment in the database.

/**************************
* Title hooks
**************************/
/*
 * Here is a workaround for the title issue.
 * Every where in the theme template files, the_title(); needs to be relaced with the_displayed_title(); and the following function needs to be added to the theme's functions.php file:
 * function the_displayed_title() {
 *    echo apply_filters( 'the_displayed_title', the_title( '', '', false ) );
 * }
*/
add_filter( 'the_displayed_title', 'ksw_prepForDisplay' );

/*
add_filter( 'the_title_rss', 'ksw_prepForDisplay');
// applied to the post title before including in an RSS feed (after first filtering with the_title.
// need to check this, I think it's not possible to format rss feed titles with html, it will display as text, I think.
*/

add_filter( 'title_edit_pre', 'ksw_prepForEditing');
// applied to post title prior to display for editing.

add_filter( 'title_save_pre', 'ksw_prepForDatabase');
// applied to post title prior to saving it in the database (also used for attachments).

//add_filter( 'wp_title', 'ksw_prepForDisplay');
// applied to the blog page title before sending to the browser in the wp_title function.
// This is the title tag in the head, html in there isn't rendered on my Firefox.

add_filter( 'widget_title', 'ksw_prepForDisplay');

/**************************
* Search term hook
**************************/
add_filter('request', 'ksw_modifySearchTerm');
// This filter is applied to the query variables that are passed to the default main SQL query that drives your page's content. It is applied after additional private query variables have been added in, and is one of the places you can hook into to modify the query that will generate your list of posts (or pages) before the main query is executed and the database is actually accessed.

function ksw_modifySearchTerm( $request_vars ) {
	global $search_replacements;
	if (!empty($request_vars['s'])) {
		$request_vars['s'] = ksw_prepForDatabase($request_vars['s']);
	}
	return $request_vars;
}

/***********************************************************************
 * Callback Functions
 **********************************************************************/
function ksw_prepForDisplay($inputText) {
/* prepares text potentially containing Karen (or Myanmar) Unicode text
 * for display.
 */
	$inputText = preg_replace('/(?<=[\p{Myanmar}])([ကခဂဃဎငစဆၡဇညတထဒနပဖဘမယရလဝသၥဟအဧၦ])/u', ksw_LINEBREAKCHAR.'$1', $inputText);
	// inserts U+200b before all Sgaw Karen consonants (which allows a browser to wrap the line there if it so desires)
	// now that we're putting spans around each syllable, we don't really need this (or do we?)

	$inputText = preg_replace('/([\p{Myanmar}'.ksw_LINEBREAKCHAR.']+)/u', '<span lang="'.ksw_LANGUAGE.'" class="'.ksw_KARENCSSCLASS.'">$1'.ksw_LINEBREAKCHAR.'</span>', $inputText);
	// inserts span tags around Karen (and Myanmar) text

	return $inputText;
}

function ksw_prepForDatabase($inputText) {
/* strips out the line breaking characters, and does some magic on
 * the sha character to make sure it is stored in proper canonical
 * order. this is necessary to for search to be reliable.
 */

    $inputText = str_replace('ရှ', 'ၡ', $inputText);
    // replace ra gha with the real sha so that search, etc. in the database is consistent

	$inputText = str_replace(ksw_LINEBREAKCHAR, '', $inputText);
	// strip out U+200B (zero width space)

	return $inputText;
}

function ksw_prepForEditing( $inputText ) { 
/* adds line breaking characters in preparation for wp user editing. */

	return preg_replace('/(?<=[\p{Myanmar}])([ကခဂဃဎငစဆၡဇညတထဒနပဖဘမယရလဝသၥဟအဧၦ])/u', ksw_LINEBREAKCHAR.'$1', $inputText);
}

?>
