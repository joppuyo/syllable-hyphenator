<?php

/*
 * Plugin name: Syllable Hyphenator
 * Description: Server-side hyphenation for WordPress with Syllable library
 * Plugin URI: https://github.com/joppuyo/syllable-hyphenator
 * Version: 1.0.3
 * Author: Johannes Siipola
 * Author URI: https://siipo.la
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

require __DIR__ . '/vendor/autoload.php';

use Vanderlee\Syllable\Syllable;

class SyllableHyphenator
{
    private $active = true;

    /**
     * @var null|Syllable
     */
    private $syllable = null;

    public function init()
    {
        $temp_dir = get_temp_dir() . 'syllable';

        if (!file_exists($temp_dir)) {
            @mkdir($temp_dir);
        }

        if (file_exists($temp_dir)) {
            Syllable::setCacheDir($temp_dir);
        } else {
            error_log(
                'Syllable Hyphenator: failed to create temporary directory'
            );
            Syllable::setCacheDir(null);
        }
        $wp_locale = get_locale();
        $wp_locale = apply_filters('syllable_hyphenator_wp_locale', $wp_locale);
        if (function_exists('pll_current_language')) {
            $locale_object = pll_current_language('OBJECT');
            if ($locale_object) {
                $wp_locale = $locale_object->locale;
            }
        }

        $locale = $this->map_locale($wp_locale);

        // Back compat
        $locale = apply_filters(
            '`syllable_hyphenator_current_locale`',
            $locale
        );

        $locale = apply_filters(
            'syllable_hyphenator_current_locale',
            $locale
        );

        if (empty($locale)) {
            $this->active = false;
            return;
        }

        $this->syllable = new Syllable($locale);

        $min_word_length = apply_filters(
            'syllable_hyphenator_min_word_length',
            12
        );

        $this->syllable->setMinWordLength($min_word_length);
    }

    public function init_updater()
    {
        $update_checker = Puc_v4_Factory::buildUpdateChecker(
            'https://github.com/joppuyo/syllable-hyphenator',
            __FILE__,
            'syllable-hyphenator'
        );

        $update_checker->getVcsApi()->enableReleaseAssets();
    }

    public function __construct()
    {
        add_filter('init', [$this, 'init']);
        add_filter('init', [$this, 'init_updater']);
        add_filter('hyphenate', [$this, 'hyphenate'], 10, 1);
        add_filter('syllable_hyphenate', [$this, 'hyphenate'], 10, 1);
        add_filter('timber/twig', [$this, 'add_twig_filter']);
    }

    function add_twig_filter($twig)
    {
        $twig->addFilter(
            new Timber\Twig_Filter('hyphenate', function ($text) {
                return $this->hyphenate($text);
            })
        );
        return $twig;
    }

    function hyphenate($string)
    {
        if ($this->active) {
            return $this->syllable->hyphenateText($string);
        }
        return $string;
    }

    public function map_locale($wp_locale)
    {
        $locale_array = [
            'af' => 'af',
            'ar' => 'ar',
            'as' => 'as',
            'bg_BG' => 'bg',
            'bn_BD' => 'bn',
            'ca' => 'ca',
            'cs_CZ' => 'cs',
            'cy' => 'cy',
            'da_DK' => 'da',
            'de_DE' => 'de',
            'de_CH' => 'de-ch-1901',
            'el' => 'el-monoton',
            'en_GB' => 'en-gb',
            'en_US' => 'en-us',
            'eo' => 'eo',
            'es_ES' => 'es',
            'et' => 'et',
            'eu' => 'eu',
            'fa_IR' => 'fa',
            'fi' => 'fi',
            'fr_FR' => 'fr',
            'fur' => 'fur',
            'ga' => 'ga',
            'gl_ES' => 'gl',
            'gu' => 'gu',
            'hi_IN' => 'hi',
            'hr' => 'hr',
            'hu_HU' => 'hu',
            'hy' => 'hy',
            'id_ID' => 'id',
            'is_IS' => 'is',
            'it_IT' => 'it',
            'ka_GE' => 'ka',
            'kn' => 'kn',
            'lt_LT' => 'lt',
            'lv' => 'lv',
            'ml_IN' => 'ml',
            'mn' => 'mn-cyrl',
            'mr' => 'mr',
            'nb_NO' => 'nb',
            'nl_NL' => 'nl',
            'nn_NO' => 'nn',
            'ory' => 'or',
            'pa_IN' => 'pa',
            'pl_PL' => 'pl',
            'pt_PT' => 'pt',
            'ro_RO' => 'ro',
            'ru_RU' => 'ru',
            'sk_SK' => 'sk',
            'sl_SI' => 'sl',
            'sv_SE' => 'sv',
            'ta_IN' => 'ta',
            'te' => 'te',
            'th' => 'th',
            'tr_TR' => 'tr',
            'uk' => 'uk',
        ];
        return !empty($locale_array[$wp_locale])
            ? $locale_array[$wp_locale]
            : null;
    }
}

$syllable_hyphenator = new SyllableHyphenator();
