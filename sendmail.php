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
 * Mail sending script for SU Agent Moodle block
 *
 * @package     block_su_agent_moodle
 * @copyright   2018 Sorbonne Université
 * @copyright   2024 Victor Da Silva Caseiro <victor.da_silva_caseiro@sorbonne-universite.fr>
 * @copyright   2024 Thomas Naudin <thomas.naudin@sorbonne-universite.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $PAGE, $USER, $DB;
ob_start();
require_login();
require_sesskey();
$context = context_system::instance();
$PAGE->set_context($context);
require_capability('block/su_agent_moodle:sendmail', $context);
header('Content-Type: application/json');
$langmessage = get_string('message', 'block_su_agent_moodle');
$subject = get_config('block_su_agent_moodle', 'subject');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $message = required_param('message', PARAM_TEXT);
    $server = required_param('server', PARAM_TEXT);
    $identification = required_param('identification', PARAM_TEXT);
    $ipaddress = required_param('ipaddress', PARAM_TEXT);
    $configuration = required_param('configuration', PARAM_TEXT);
    $date = required_param('date', PARAM_TEXT);
    $emailbody = "<p>{$langmessage} : $message</p>";
    $emailbody .= "<p> $server</p>";
    $emailbody .= "<p> $identification</p>";
    $emailbody .= "<p> $ipaddress</p>";
    $emailbody .= "<p> $configuration</p>";
    $emailbody .= "<p> $date</p>";
    $mailtocc = get_config('block_su_agent_moodle', 'mailto');
    $mailsenttocc = false;
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
    ob_clean();
    if ($mailsenttocc || $sendtoadmin) {
        echo json_encode(['status' => 'success']);
    } else {
        debugging('Email not sent to primary recipient', DEBUG_DEVELOPER);
        echo json_encode([
            'status' => 'error',
            'message' => get_string('emailsenderror', 'block_su_agent_moodle'),
        ]);
    }
} else {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => get_string('invalidrequest', 'block_su_agent_moodle')]);
}

ob_end_flush();
