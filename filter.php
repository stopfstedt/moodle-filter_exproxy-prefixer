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
     * Face-melting regular expression for matching any and all URLs.
     * @var string URL_REGEX
     * @link http://urlregex.com/
     */
    const URL_REGEX = "%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu";

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
        $text = preg_replace(self::URL_REGEX, $prefix_url . '${0}', $text);

        // eliminate double-prefixed URLs.
        $text = str_ireplace($prefix_url . $prefix_url, $prefix_url, $text);

        return $text;
    }
}

