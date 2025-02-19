<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace filter_ucsfezproxy;

/**
 * A filter for prefixing URLs with UCSF's EZProxy URL.
 *
 * @package    filter_ucsfezproxy
 * @copyright  The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {

    /**
     * @var string PROXY_PREFIX
     */
    const DEFAULT_PROXY_PREFIX = "https://ucsf.idm.oclc.org/login?url=";

    /**
     * Face-melting regular expression that matches any valid Digital Object Identifier (DOI) URL.
     * What does that mean?
     * It means that the given URL must be a doi.org or doi.info (sub-)domain,
     * and the URL's path must be a valid DOI.
     *
     * Matching examples:
     *   http://doi.org/10.1109/5.771073
     *   https://doi.org/10.1109/5.771073
     *   http://doi.info/10.1109/5.771073
     *   https://doi.info/10.1109/5.771073
     *   https://www.doi.org/10.1109/5.771073
     *   https://dx.doi.org/10.1109/5.771073
     *
     * @var string DOI_URL_REGEX
     * @link https://www.crossref.org/blog/dois-and-matching-regular-expressions/
     */
     const DOI_URL_REGEX = "%https?://([^\.]+\.)?doi\.(info|org)/("
        . "(10\.\d{4,9}/[-._;()/:A-Z0-9]+)"
        . "|(10\.1002/[^\s]+)"
        . "|(10\.\d{4}/\d+-\d+X?(\d+)\d+<[\d\w]+:[\d\w]*>\d+\.\d+\.\w+;\d)"
        . "|(10\.1021/\w\w\d++)"
        . "|(10\.1207/[\w\d]+\&\d+_\d+)"
        . ")%iu";

    /**
     * Searches for DOI URLs in a given text and pre-fixes them with UCSF's EZProxy URL.
     *
     * @param string $text some HTML content to process.
     * @param array $options options passed to the filters
     * @return string the HTML content after the filtering has been applied.
     * @throws dml_exception
     */
    public function filter($text, array $options = []) {
        // Short circuit this if no/empty text is given.
        if (!is_string($text) || empty($text)) {
            return $text;
        }

        $prefixurl = trim(get_config('filter_ucsfezproxy', 'prefixurl')) ?: self::DEFAULT_PROXY_PREFIX;

        // Find and prefix all URLs in the given text.
        $text = preg_replace(self::DOI_URL_REGEX, $prefixurl . '${0}', $text);

        // Eliminate double-prefixed URLs.
        $text = str_ireplace($prefixurl . $prefixurl, $prefixurl, $text);

        return $text;
    }
}
