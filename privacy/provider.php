<?php
/**
 * Privacy Subsystem implementation for filter_ucsfezproxy.
 *
 * @package filter_ucsfezproxy
 */
namespace filter_ucsfezproxy\privacy;

use core_privacy\local\metadata\null_provider;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for filter_ucsfezproxy implementing null_provider.
 */
class provider implements null_provider {

    /**
     * @inheritdoc
     */
    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}
