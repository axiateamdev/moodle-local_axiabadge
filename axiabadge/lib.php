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
 * @package     local_axiabadge
 * @category    admin
 * @copyright   2019 wisdmlabs <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Check whether the plugin is available or not
 * this will return true is plugin is available
 * @param  [string] $plugintype Plugin type to check
 * @param  [string] $puginname Plugin Name
 * @return boolean Return boolean
 */

defined('MOODLE_INTERNAL') || die();
/**
 * Adding 'Axia Reports' link in sidebar
 * @param navigation_node $nav navigation node
 */

function local_axiabadge_extend_navigation(navigation_node $nav) {
    global $CFG, $PAGE, $COURSE, $USER;
    // Check if users is logged in to extend navigation.
    if (!isloggedin()) {
        return;
    }
    // require_login();
    // =====  ACCESS CONTROL  =================================================
    //TODO: In Production ─> COMMENT
    // if( $USER->username != '_developer' )    {
    //     return true;
    // }
    /*
        if( has_capability('local/axiabadge:admin', context_system::instance() ) ) {
        // Axia Report utilizamos icono generico, luego modificado por estilos
        $icon = new pix_icon ('e/table', 'Axia');
        // $icon = new pix_icon ('e/split_cells', 'Axia');
        $node = $nav->add(    get_string('axiabadge', 'local_axiabadge')    // NOTE: HERE dont use function axiabadge_get_translated_text()
                            , new moodle_url($CFG->wwwroot . '/local/axiabadge/index.php')
                            , navigation_node::TYPE_CUSTOM
                            , 'axiabadge'
                            , 'axiabadge'
                            , $icon
        );
        $node->showinflatnavigation = true;
        // If url is not set.
        if (!$PAGE->has_set_url()) {
            return true;
        }
    }
    */
}