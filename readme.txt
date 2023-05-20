=== Syllable Hyphenator ===
Contributors: joppuyo
Tags: hyphen, hyphenation, timber
Requires at least: 5.0
Tested up to: 5.5
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Server-side hyphenation for WordPress with Syllable library

== Changelog ==

= 1.1.0 (2023-05-20) =
* Feature: Updated dependencies
* Feature: Renamed the filter `hyphenate` to `syllable_hyphenate` to prevent namespace collisions. The old filter name
will be kept around for backwards compatibility reasons, but it will be removed in the next major version of the plugin
* Feature: Added new `syllable_hyphenate_html` filter for hyphenating text inside HTML tags
* Fix: Fixed typo in `syllable_hyphenator_current_locale` filter name (Fixes #2 )

= 1.0.3 (2020-09-20) =
* Fix: Fix readme

= 1.0.2 (2020-09-20) =
* Fix: Fix directory structure

= 1.0.1 (2020-09-17) =
* Fix: Fix notice in admin when "show all languages" is selected

= 1.0.0 (2020-09-15) =
* Initial release