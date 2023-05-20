# Syllable Hyphenator

[![Build Status](https://img.shields.io/github/actions/workflow/status/joppuyo/syllable-hyphenator/php.yml?branch=master&logo=github)](https://github.com/joppuyo/syllable-hyphenator/actions)
[![Inactively Maintained](https://img.shields.io/badge/Maintenance%20Level-Inactively%20Maintained-yellowgreen.svg)](https://gist.github.com/cheerfulstoic/d107229326a01ff0f333a1d3476e068d)

![Screenshot of two WordPress pages. Normal on the left, hyphenated on the right](./syllable-hyphenator.png)

Server-side hyphenation for WordPress with [Syllable](https://github.com/vanderlee/phpSyllable) library. It works by inserting `&shy;` or "soft hyphen" characters to your strings so browsers will hyphenate the words correctly for you.

## Compatiblity

- PHP 7 +
- WordPress 5.0 +
- (Optional) Timber 1.10.0 +
- (Optional) Polylang

## Installation

1. Download latest version on the [Releases](https://github.com/joppuyo/syllable-hyphenator/releases) page.
2. Upload plugin on the WordPress plugin admin page.
3. Activate plugin.
4. See section below on how to use the plugin in your theme

## Usage

You can use the `syllable_hyphenate` WordPress filter to hyphenate text.

```php
echo '<h1>' . apply_filters('syllable_hyphenate', get_the_title($post)) . '</h1>';
```

If you are using [Timber](https://www.upstatement.com/timber/), you can also use the `hyphenate` Twig filter,

```twig
<h1>{{ post.title | hyphenate }}</h1>
```

You can use the `syllable_hyphenate_html` filter to hyphenate a HTML string. For example, let's say you want to hyphenate output of Heading Gutenberg blocks. You can do it with the following code:

```php
add_filter('render_block', function ($content, $block) {
    if ($block['blockName'] === 'core/heading') {
        $content = apply_filters('syllable_hyphenate_html', $content);
    }
    return $content;
}, 10, 2);
```

## Filters

### Minimum word length

`syllable_hyphenator_min_word_length`

By default, minimum word length for hyphenation is set to 12 characters. This prevents awkward hyphenation of very short words.

```php
add_filter('syllable_hyphenator_min_word_length', function () {
  return 6;
});
```

### WordPress locale

`syllable_hyphenator_wp_locale`

Language used for hyphenation. By default this is the language specified in the WordPress settings page. This can be overridden using this filter. The locale should be in WordPress locale format, for example `en_US`.

```php
add_filter('syllable_hyphenator_wp_locale', function () {
  return 'en_US';
});
```

### Current language

`syllable_hyphenator_current_locale`

This overrides the WordPress language. This is useful if you have different language content on your site. If Polylang is installed, the current Polylang post language will be used. Return value should be in Syllable library format, for example `en-us`.

```php
add_filter('syllable_hyphenator_current_locale', function () {
  return 'en-us';
});
```
