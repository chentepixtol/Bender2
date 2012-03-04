<?php

/*
 * This file is part of Inflector.
 *
 * Copyright (c) 2011 Javier Martinez
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

class Inflector {
    /**
     * Inflector rules to transform names from plural to singular.
     */
    private static $_singularInflections = array();

    /**
     * Inflector rules to transform names from singular to plural.
     */
    private static $_pluralInflections = array();

    /**
     * Inflector rules with names that can't be transformed from plural to
     * singular and viceversa.
     */
    private static $_uncountableInflections = array();

    /**
     * Constructor.
     */
    private function __construct() {
        // prevent class instantiation
    }

    /**
     * Clears the loaded inflections within a given scope (default is all).
     *
     * @param string scope The scope to be cleared (defaults to all).
     */
    public static function clear($scope = 'all') {
        if ($scope == 'all') {
            self::clear('singular');
            self::clear('plural');
            self::clear('uncountable');
        }
        else {
            $variable = '_' . strtolower($scope) . 'Inflections';
            self::$$variable = array();
        }
    }

    /**
     * Adds singular inflection rules for conversion.
     *
     * @param mixed  $match      A string with a regular expression that must
     *                           match on the string to be converted to
     *                           identify the string as a plural form.
     *                           It can be an array too with a key value pair
     *                           of regular expressions to match plural forms
     *                           and the regular expression to convert to the
     *                           plural form.
     * @param string $conversion The regular expression to convert a plural
     *                           word to a singular one.
     */
    public static function addSingularInflections($match, $conversion = NULL) {
        $rules = is_array($match) ? $match : array($match => $conversion);
        self::$_singularInflections = array_merge(
            self::$_singularInflections,
            $rules
        );
    }

    /**
     * Adds plural inflection rules for conversion.
     *
     * @param mixed  $match      A string with a regular expression that must
     *                           match on the string to be converted to
     *                           identify the string as a singular form.
     *                           It can be an array too with a key value pair
     *                           of regular expressions to match singular forms
     *                           and the regular expression to convert to the
     *                           singular form.
     * @param string $conversion The regular expression to convert a singular
     *                           word to a plural one.
     */
    public static function addPluralInflections($match, $conversion = NULL) {
        $rules = is_array($match) ? $match : array($match => $conversion);
        self::$_pluralInflections = array_merge(
            self::$_pluralInflections,
            $rules
        );
    }

    /**
     * Adds an irregular inflection rule about a word that can't be converted
     * from singular to plural and viceversa with the normal singular and
     * plural inflection rules.
     *
     * @param mixed  $singular A string with the singular form of the word.
     *                         It can be an array too with a key value pair of
     *                         singular and plural forms of words.
     * @param string $plural   The plural form of the given singular word.
     */
    public static function addIrregularInflections($singular, $plural = NULL) {
        $rules = is_array($singular) ? $singular : array($singular => $plural);

        foreach ($rules as $singular => $plural) {
            $singular = strtolower($singular);
            $plural = strtolower($plural);

            $ucSingular = ucfirst($singular);
            $ucPlural = ucfirst($plural);

            self::$_singularInflections = array_merge(array(
                "/^$plural$/" => $singular,
                "/^$ucPlural$/" => $ucSingular
            ), self::$_singularInflections);

            self::$_pluralInflections = array_merge(array(
                "/^$singular$/" => $plural,
                "/^$ucSingular$/" => $ucPlural
            ), self::$_pluralInflections);
        }
    }

    /**
     * Add uncountable words that shouldn‘t be attempted inflected.
     *
     * @param mixed $word A string with an uncountable word or an array filled
     *                    with uncountable words.
     */
    public static function addUncountableInflections($word) {
        self::$_uncountableInflections = array_merge(
            self::$_uncountableInflections,
            (array)$word
        );
    }

    /**
     * Get word in plural form.
     *
     * @param  string $word The word to convert to plural.
     * @return string       The plural form of the given word.
     */
    public static function pluralize($word) {
        $uncountable = implode('|', self::$_uncountableInflections);
        if (preg_match("/^($uncountable)$/i", $word)) {
            return $word;
        }

        foreach (self::$_pluralInflections as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return $word;
    }

    /**
     * Get word in singular form.
     *
     * @param  string $word The word to convert to singular.
     * @return string       The singular form of the given word.
     */
    public static function singularize($word) {
        $uncountable = implode('|', self::$_uncountableInflections);
        if (preg_match("/^($uncountable)$/i", $word)) {
            return $word;
        }

        foreach (self::$_singularInflections as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return $word;
    }

    /**
     * Get the corresponding table name for given class name.
     *
     * @param  string class The class name to convert.
     * @return string       The table name.
     */
    public static function tableize($class) {
        return self::pluralize(self::underscore($class));
    }

    /**
     * Get the corresponding classname for given table name.
     *
     * @param  string table The table name to convert.
     * @return string       The class name.
     */
    public static function classify($table) {
        return self::camelize(self::singularize($table));
    }

    /**
     * Converts an underscored or CamelCase word into a sentence.
     *
     * The titleize function converts text like "WelcomePage", "welcome_page"
     * or "welcome page" to this "Welcome Page".
     *
     * If second parameter is set to 'first' it will only capitalize the first
     * character of the title.
     *
     * @param  string $word      The word to format as title.
     * @param  string $uppercase If set to 'first' it will only uppercase the
     *                           first character. Otherwise it will uppercase
     *                           all the words in the title.
     * @return string            The text formatted as a title.
     */
    public static function titleize($word, $uppercase = NULL) {
        return call_user_func(
            $uppercase == 'first' ? 'ucfirst' : 'ucwords',
            self::humanize(self::underscore($word))
        );
    }

    /**
     * Replaces underscores with dashes in the string.
     *
     * @param  string $string The string to be dasherized.
     * @return string         The dasherized string.
     */
    public static function dasherize($string) {
        return str_replace('_', '-', $string);
    }

    /**
     * Get the given $lower_case_and_underscored_word as a CamelCased word.
     *
     * @param  string $string The string to be camelzied.
     * @return string         The camelized string.
     */
    public static function camelize($string) {
        return preg_replace_callback('/(?:^|_)(.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, $string);
    }

    /**
     * Get the underscore-syntaxed (like_this_dear_reader) version of the camel
     * cased word.
     *
     * @param  string $string The camel cased word to be underscored.
     * @return string         The underscored string.
     */
    public static function underscore($string) {
        $string = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1_$2', $string);
        $string = preg_replace('/([a-z\d])([A-Z])/', '$1_$2', $string);
        $string = str_replace('-', '_', $string);
        return strtolower($string);
    }

    /**
     * Get a human-readable string from $lower_case_and_underscored_word,
     * replacing underscores with a space, and by upper-casing the initial
     * characters.
     *
     * @param  string $string The string to be humanized.
     * @return string         The humanized string.
     */
    public static function humanize($string) {
        return ucfirst(str_replace('_', ' ', $string));
    }

    /**
     * Get the namespace for the given string.
     * 
     * @param  string $string The class to extract namespace.
     * @return string         The namespace for the given class.
     */
    public static function namespaceFor($string) {
        $i = strrpos($string, '\\');
        return $i === FALSE ? '\\' : substr($string, 0, $i);
    }

    /**
     * Remove the namespace from the given class string.
     * 
     * @param  string $string The class to remove namespace.
     * @return string         The class without the namespace.
     */
    public static function unnamespace($string) {
        $i = strrpos($string, '\\');
        return $i === FALSE ? $string : substr($string, $i + 1);
    }
}