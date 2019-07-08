<?php
/**
 * @package    filter
 * @subpackage ucsfezproxy
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot.'/filter/ucsfezproxy/filter.php';

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configtext(
            'filter_ucsfezproxy/prefixurl',
            new lang_string('prefixurl', 'filter_ucsfezproxy'),
            new lang_string('prefixurl_help', 'filter_ucsfezproxy'),
            filter_ucsfezproxy::DEFAULT_PROXY_PREFIX,
            PARAM_URL
        )
    );
}
