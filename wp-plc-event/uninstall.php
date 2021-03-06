<?php
/**
 * Plugin reset and uninstall cleanup.
 *
 * @package   OnePlace\Event
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 */

namespace OnePlace\Event;

# if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' )) {
    die;
}

# Set options that should be cleared
$aOptions = [
];

foreach($aOptions as $sOption) {
    // delete option
    delete_option($sOption);
    // for site options in Multisite
    delete_site_option($sOption);
}
