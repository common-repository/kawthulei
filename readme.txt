=== Kawthulei ===
Contributors: ben011
Donate link: http://kanyawtech.com/
Tags: kawthulei, karen language, sgaw karen, pwo karen
karen tagger, remote fonts
Requires at least: 3.0
Tested up to: 3.6
Stable tag: tags/1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Kawthulei is a plugin designed to make using Karen text on a Wordpress
site more reliable accross various browsers.

== Description ==

This plugin is devoted to making it easier to use the Karen languages on
the Internet. Due to poor technical support, the Karen langauges
currently have limited online presence. However, with the help of this
Wordpress plugin, you can help change that.

The Karen languages are commonly written with a script derived from the
Myanmar script. The Sgaw and Pwo Karen scripts are implemented in recent
versions of Unicode as an extension of the Myanmar script. However, many
people may still be using psuedo-Unicode Myanmar fonts, which commonly
use minority language code points for their own use. This causes a
problem for browsers which don't know which font to use, and commonly
choose the wrong one, rendering Karen text unreadable.

The solution is to specifically assign the correct font to all Karen
text. The primary function of this plugin is to facilitate this
assignment. It does this by scanning content for Karen text, and
assigning a CSS class containing font family information to any text it
finds.

== Installation ==

To complete installation of the Kawthulei plugin, you simply:

1. Upload the `kawthulei` folder to the `/wp-content/plugins/`
directory. And activate the plugin through the 'Plugins' menu in
WordPress

The Wordpress framework does not make it easy to process post and page
titles. To enable Karen titles on your own theme, you're going to have
to modify your templates. First, put the following function in your
`functions.php` file.

	function yyp_the_displayed_title() { echo apply_filters(
	  'the_displayed_title', the_title( '', '', false ) );
	  }
	}

Second, you need to change all occurences of `the_title();` in your
template files to `the_displayed_title();`.

== Frequently Asked Questions ==

= Who are the Karen people? =

The Karen people are an ethnic group in Thailand and Myanmar, and are
the second largest ethnic group in Myanmar. Most estimates place their
population around 5 million.

= What on earth does "Kawthulei" mean? =

"Kawthulei" is the name the Karens have traditionally call their
homeland. The word has been variously translated, but one translation is
"Green land" or "Lush land" ("Thulei" is the name of an delicious green
edible plant that grows in aboundance there).

= What is meant by Sgaw Karen? Pwo Karen? =

There are a number of Karen tribes in Thailand and Myanmar speaking
several dialects of the Karenic language group. Two of those tribes are
Sgaw and Pwo.

= What problems must be overcome for Karen to be commonly used on the
Internet? =

The four main problems that a Karen speaker must overcome to use her
language on a computer are:

  1. Fonts for this language are not installed by default on most operating systems and many people don't know that real Unicode fonts exist.
  1. Psuedo-Unicode fonts often get in the way.
  1. Keyboards forthis language are not installed by default, and many people don't realize they are available at all. 1. Many applications don't properly draw complex Myanmar fonts.

= I'm a developer, how can I help the Karen language move forward in the
modern world? =

You can go to the (kanyawtech.org) wiki site, and learn how you can
contribute there.

== Changelog ==

= 1.4 = 
  * Changed to using my own font, Karen Text, rather than Tharlon or Padauk. This should work better on OS X and Windows 8.
  * Fixed some small bugs on OS X.
  
= 1.3 =
  * Added the necessary Karen fonts to support Chrome and Safari on OSX.
  * Added CSS to select the proper font for WPML's language switcher widgit.

= 1.2 =
  * Added support for Pwo Karen.
  * Removed the canvas drawing and font changing scripts, remote font loading should be enough in most cases.

= 1.1 =
  * Fixed a bug in the search filter hook
  * Added CSS to specify fonts, no longer any need to add this to the theme
  * Added remote font loading via the CSS `@font-face` rule
  * Added a script to try different fonts and apply the first one that displays correctly
  * Added script functionality to draw text on html canvas elements in cases where the native fonts are not able to display correctly (mostly webkit browsers, I think)

This version should work out of the box on any theme as long as you
don't use Karen in post or page titles. Also, in order for the script to
display a warning message if it fails to display correctly, your theme
will need to provide one. For more information, see the installation
page.

= 1.0 =
  * Initial release

== Upgrade Notice ==

