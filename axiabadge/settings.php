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
/**
 * Plugin administration pages are defined here.
 *
 * @package
 * @category    admin
 * @copyright
 * @license
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/axiabadge/lib.php');
if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_axiabadge_settings', 'Holita'));
    $settingspage = new admin_settingpage('managelocalaxiabadge', 'axiabadge Settings');
    if ($ADMIN->fulltree) {
        $settingspage->add( new admin_setting_configtext(
            'local_axiabadge/pluginkey',
            'Plugin KEY',
            'The plugin key for validation',
            "validation code",
            PARAM_TEXT
           ));

    }
    $ADMIN->add('localplugins', $settingspage);
}