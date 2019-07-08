<?php
/**
 *
 * A filter for prefixing URLs with UCSF's EZProxy URL.
 *
 * @package filter
 * @subpackage ucsfezproxy
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Class filter_ucsfezproxy
 */
class filter_ucsfezproxy extends moodle_text_filter {

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
    const DOI_URL_REGEX = "%https?://([^\.]+\.)?doi\.(info|org)/((10\.\d{4,9}/[-._;()/:A-Z0-9]+)|(10\.1002/[^\s]+)|(10\.\d{4}/\d+-\d+X?(\d+)\d+<[\d\w]+:[\d\w]*>\d+\.\d+\.\w+;\d)|(10\.1021/\w\w\d++)|(10\.1207/[\w\d]+\&\d+_\d+))%iu";

    /**
     * @inheritdoc
     * @throws dml_exception
     */
    function filter($text, array $options = array()) {

        // short circuit this if no/empty text is given.
        if (!is_string($text) or empty($text)) {
            return $text;
        }

        $prefix_url = trim(get_config('filter_ucsfezproxy', 'prefixurl')) ?: self::DEFAULT_PROXY_PREFIX;

        // find and prefix all URLs in the given text.
        $text = preg_replace(self::DOI_URL_REGEX, $prefix_url . '${0}', $text);

        // eliminate double-prefixed URLs.
        $text = str_ireplace($prefix_url . $prefix_url, $prefix_url, $text);

        return $text;
    }
}

