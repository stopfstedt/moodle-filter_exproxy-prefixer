<?php
/**
 * A filter for prefixing URLs with UCSF's EZProxy URL.
 *
 * @package    filter
 * @subpackage ucsfezproxy
 */

defined('MOODLE_INTERNAL') || die();

class filter_ucsfezproxy extends moodle_text_filter {
    function filter($text, array $options = array()) {
        // @todo implement [ST 2019/06/25]
        return $text;
    }
}

