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
 * Database upgrades.
 *
 * @package     local
 * @subpackage  axiabadge
 * @copyright   2021 axiateam.com
 * @license
 */
// Create site course
// ---   if ($DB->record_exists('course', array())) {
// ---       throw new moodle_exception('generalexceptionmessage', 'error', '', 'Can not create frontpage course, courses already exist.');
// ── ─ - ·    }
defined('MOODLE_INTERNAL') || die;
// use custom_menu;
function xmldb_local_axiabadge_install() {
    global $CFG, $DB, $PAGE;
    print_r("<br>axiabadge - Install Started");
    print_r("<br>------------------------------------");
    require_once($CFG->libdir.'/navigationlib.php');
    // Check if users is logged in to extend navigation.
    if (!isloggedin()) {
        return;
    }
    #region =====  Add Menu Node in left menu  id="·nav-drawer"  ===============
    $branchlabel = get_string('axiabadge', 'local_axiabadge');
    $branchurl   = new moodle_url('local/axiabadge/index.php');
    $branchtitle = $branchlabel;
    $branchsort  = 10000;
    $previewnode = $PAGE->navigation->add( $branchlabel, $branchurl, navigation_node::TYPE_CONTAINER);
    $thingnode = $previewnode->add($branchlabel, $branchurl);
    $thingnode->make_active();
    #endregion =====  Add Menu Node in left menu  id="·nav-drawer"  ============

    return true;
}
