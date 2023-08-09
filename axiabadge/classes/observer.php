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
 * This file defines what events we wish to observe and the method responsible for handling the event.
 *
 * @package    local_axiabadge
 * @copyright  2023 AXIA INTELLIGENT LEARNING SL
 * @license
 */
namespace local_axiabadge;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/badges/classes/badge.php');
require_once($CFG->dirroot . '/lib/badgeslib.php');

class observer
{
    public static function send_mail_to_pem( $event)
    {
        global $CFG;

        if (!empty($CFG->enablebadges)) {
            // Obtain the awarded user of the event
            $userid = $event->relateduserid;
            // Get event badge from database 
            $badge = new \core_badges\badge($event->objectid);
            $now = time();
            $uniquehash = sha1(rand() . $userid . $badge->id . $now);
            // Bake a badge image.
            $pathhash = badges_bake($uniquehash, $badge->id, $userid, true);
            // Notify user's PEM.
            badges_notify_pem_badge_award($badge, $userid, $uniquehash, $pathhash);

        }
    }

}


/**
 * Sends notifications to user`s PEM about awarded badges.
 *
 * @param badge     $badge  Badge that was issued
 * @param int       $userid user who receives the badge
 * @param string    $issued Unique hash of an issued/published badge
 * @param string    $filepathhash File path hash of an badge for attachments
 */
function badges_notify_pem_badge_award(\core_badges\badge $badge, $userid, $issued, $filepathhash) {
    global $CFG, $DB;

    $userto = $DB->get_record('user', array('id' =>  $userid), '*', MUST_EXIST);

    // ----- Get noreply user record. It will return record of $CFG->noreplyuserid if set else return dummy
    //      user object with hard-coded $user->emailstop = 1 so noreply can be sent to user.
    // $userfrom = \core_user::get_noreply_user();
    // $userfrom->maildisplay = true;

    // ----- Get the main admin user
    $userfrom = get_admin();

    $issuedlink = \html_writer::link(new \moodle_url('/badges/badge.php', array('hash' => $issued)), $badge->name);
    
    require_once($CFG->dirroot.'/user/profile/lib.php');
    // -----  Add user custom fields to user
    profile_load_data($userto);

    #region -----  Create message and subject  ---------------------------------
    $a = new \stdClass();
    $a->user = fullname($userto);
    $a->link = $issuedlink;
    // axxo. 20230324. $user_pem_message = get_string('creatorbody', 'badges', $a);
    $user_pem_message = 
        "Nos es grato informarte que $a->user ha completado los requisitos necesarios para obtener la insignia:  $badge->name.
        <br><br>Este badge es un reconocimiento a su esfuerzo y dedicación para alcanzar nuevas metas. 
        ";

    $user_pem_subject = get_string('creatorsubject', 'badges', $badge->name);
    #endregion -----  Create message and subject  ---------------------------------

    #region -----  Get user's PEM  ---------------------------------------------
    if(!empty($userto->profile_field_PEM) ){
        $user_pem = $DB->get_record_sql(
            'SELECT *
                FROM  {user}
                WHERE 1
                    AND deleted = 0
                    AND CONCAT( firstname, " ", lastname) = :fullname ',
            array('fullname' => $userto->profile_field_PEM)
        );
    }

    #region //COMMENTS  ************************************************************
    /** 
     * For messages sent to 'main admin user', indicate the name 'notices'.
     * For the names 'badgerecipientnotice' or 'badgecreatornotice' the mail does not arrive,
     *  although in the LOG it appears as sent. 
     */
    #endregion *********************************************************************
    // $message_name='badgecreatornotice';
    $message_name= 'badgerecipientnotice';
    // ----- If there is no PEM, send the email to 'main admin user'
    if (empty($user_pem)) {
        $user_pem           = get_admin();
        $user_pem_message   = "<strong>WARNING:</strong> There is no PEM defined for this user.<br><br>$user_pem_message";
        $message_name       = 'notices';
    }
    #endregion --  Get user's PEM  ---------------------------------------------

    #region -----  Notify recipient  ------------------------------------------
    $eventdata                    = new \core\message\message();
    $eventdata->courseid          = is_null($badge->courseid) ? SITEID : $badge->courseid; // Profile/Site come with no courseid.
    $eventdata->component         = 'moodle'            ;
    $eventdata->name              = $message_name;
    $eventdata->userfrom          = $userfrom           ;
    $eventdata->userto            = $user_pem           ;
    $eventdata->notification      = 1                   ;
    $eventdata->subject           = "$user_pem_subject to user:  $a->user"  ;
    $eventdata->fullmessage       = html_to_text($user_pem_message)         ;
    $eventdata->fullmessageformat = FORMAT_HTML         ;
    $eventdata->fullmessagehtml   = $user_pem_message   ;
    // $eventdata->smallmessage      = ''                  ;
    $eventdata->smallmessage      = strip_tags($message->fullmessagehtml);

    $eventdata->customdata        = [
        'notificationiconurl' => \moodle_url::make_pluginfile_url(
            $badge->get_context()->id, 'badges', 'badgeimage', $badge->id, '/', 'f1')->out(),
        'hash' => $issued,
    ];

    // Attach badge image if possible.
    /* // axxo. 20230324. 
        if (!empty($CFG->allowattachments) && $badge->attachment && is_string($filepathhash)) {
            $fs                     = get_file_storage()                            ;
            $file                   = $fs->get_file_by_hash($filepathhash)          ;
            $eventdata->attachment  = $file                                         ;
            $eventdata->attachname  = str_replace(' ', '_', $badge->name) . ".png"  ;
            message_send($eventdata);
        } else {
            message_send($eventdata);
        }
    */
    // axxo. 20230324. 
    message_send($eventdata);
    
    
    #endregion --  Notify recipient  ------------------------------------------
    
}


