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

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->libdir . "/externallib.php");

/**
 * External Web Service class for SU Agent Moodle block
 *
 * @package    block_su_agent_moodle
 * @category   external
 * @copyright  2018 Sorbonne Université
 * @copyright  2024 Victor Da Silva Caseiro <victor.da_silva_caseiro@sorbonne-universite.fr>
 * @copyright  2024 Thomas Naudin <thomas.naudin@sorbonne-universite.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_su_agent_moodle_external extends external_api {

    /**
     * Returns description of send_mail parameters.
     * @return external_function_parameters
     */
    public static function send_mail_parameters() {
        return new external_function_parameters([
            'message' => new external_value(PARAM_TEXT, 'Message content'),
            'server' => new external_value(PARAM_TEXT, 'Server information'),
            'identification' => new external_value(PARAM_TEXT, 'User identification'),
            'ipaddress' => new external_value(PARAM_TEXT, 'IP address'),
            'configuration' => new external_value(PARAM_TEXT, 'Browser configuration'),
            'date' => new external_value(PARAM_TEXT, 'Date information'),
        ]);
    }

    /**
     * Send mail function
     * @param string $message The message content
     * @param string $server Server information
     * @param string $identification User identification
     * @param string $ipaddress IP address
     * @param string $configuration Browser configuration
     * @param string $date Date information
     * @return array status and message
     */
    public static function send_mail($message, $server, $identification, $ipaddress, $configuration, $date) {
        global $USER, $DB;

        $params = self::validate_parameters(self::send_mail_parameters(),
            [
                'message' => $message,
                'server' => $server,
                'identification' => $identification,
                'ipaddress' => $ipaddress,
                'configuration' => $configuration,
                'date' => $date,
            ]
        );

        $context = context_system::instance();
        self::validate_context($context);
        require_capability('block/su_agent_moodle:sendmail', $context);

        $langserver = get_string('server', 'block_su_agent_moodle');
        $langidentification = get_string('identification', 'block_su_agent_moodle');
        $langipaddress = get_string('ipaddress', 'block_su_agent_moodle');
        $langconfiguration = get_string('configuration', 'block_su_agent_moodle');
        $langdate = get_string('date', 'block_su_agent_moodle');
        $langmessage = get_string('message', 'block_su_agent_moodle');

        $subject = get_config('block_su_agent_moodle', 'subject');
        $emailbody = "<p>{$langmessage} : {$params['message']}</p>";
        $emailbody .= "<p>{$langserver} : {$params['server']}</p>";
        $emailbody .= "<p>{$langidentification} : {$params['identification']}</p>";
        $emailbody .= "<p>{$langipaddress} : {$params['ipaddress']}</p>";
        $emailbody .= "<p>{$langconfiguration} : {$params['configuration']}</p>";
        $emailbody .= "<p>{$langdate} : {$params['date']}</p>";

        $mailsenttocc = false;
        $mailtocc = get_config('block_su_agent_moodle', 'mailto');

        if ($mailtocc) {
            $externalemail = new stdClass();
            $externalemail->email = $mailtocc;
            $externalemail->firstname = "";
            $externalemail->lastname = "";
            $externalemail->maildisplay = true;
            $externalemail->mailformat = 1;
            $externalemail->id = -99;

            $mailsenttocc = email_to_user($externalemail, $USER, $subject, $emailbody);
        }

        $sendtoadmin = get_config('block_su_agent_moodle', 'supportemail');
        if ($sendtoadmin) {
            $admin = get_admin();
            email_to_user($admin, $USER, $subject, $emailbody);
        }

        if ($mailsenttocc || $sendtoadmin) {
            return [
                'status' => 'success',
                'message' => get_string('msgemailsuccess', 'block_su_agent_moodle'),
            ];
        } else {
            throw new moodle_exception('emailsenderror', 'block_su_agent_moodle');
        }
    }

    /**
     * Returns description of send_mail return values.
     * This method is required by Moodle's web service architecture
     * and is called automatically when processing web service responses.
     *
     * @return external_single_structure
     */
    public static function send_mail_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'message' => new external_value(PARAM_TEXT, 'Message describing the result'),
        ]);
    }
}
