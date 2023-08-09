<?php
// This file is part of Moodle - https://moodle.org/
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
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_axiabadge
 * @copyright   2021 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

$plugin             = new stdClass();
$plugin->component  = 'local_axiabadge' ;
$plugin->release    = '1.03'            ;
$plugin->version    = 2023032403        ;
$plugin->cron       = 0                 ;
$plugin->requires   = 2019111800        ;
$plugin->maturity   = MATURITY_STABLE   ;

/*
********************************************************************************
················································································
Date: 2023/01/25
Ver : 1.01
Clt : VIEWNEXT
Rpt :
Subj: First version

OBJECTIVE:
To send an email to the student's manager (PEM) when the student obtains a badge.
TASKS:
Development of a PLUGIN that will capture the event of the award of a badge, 
and send the PEM an email informing him/her of the event.
The PEM will be obtained from the user's custom fields in MOODLE, which previously 
would have been populated via data synchronization from ATENEA.
The student's PEM field contains the name and surname of the person in charge, 
the plugin will look for it in the MOODLE user table and will obtain the PEM's email.

********************************************************************************
*                          CHANGES AND VERSIONS LOG                            *
********************************************************************************
················································································
Date: 2023/03/24
Ver : 2023032403  1.03
Clt : VIEWNEXT
Rpt :
Subj: Change message
BUG:    

················································································
················································································
Date: 2023/01/31
Ver : 1.02
Clt : VIEWNEXT
Rpt :
Subj: If not exists PEM, sent to 'main admin user'
BUG:    

················································································

********************************************************************************
*/