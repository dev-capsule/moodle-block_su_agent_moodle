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
 *
 * @package     block_su_agent_moodle
 * @copyright   2018 Sorbonne Université
 * @copyright   2024 Victor Da Silva Caseiro <victor.da_silva_caseiro@sorbonne-universite.fr>
 * @copyright   2024 Thomas Naudin <thomas.naudin@sorbonne-universite.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
global $PAGE, $USER, $DB;
// Commencer la mise en mémoire tampon pour éviter les sorties accidentelles.
ob_start();
require_login();
require_sesskey();
$context = context_system::instance();
$PAGE->set_context($context);
require_capability('block/su_agent_moodle:sendmail', $context);
// Définir le type de contenu en JSON pour la réponse.
header('Content-Type: application/json');
// Récupérer les chaînes de caractères du fichier de langue.
$langmessage = get_string('message', 'block_su_agent_moodle');
$subject = get_config('block_su_agent_moodle', 'subject');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Récupérer et nettoyer les variables.
    $message = clean_param($_POST['message'], PARAM_TEXT);
    $server = clean_param($_POST['server'], PARAM_TEXT);
    $identification = clean_param($_POST['identification'], PARAM_TEXT);
    $ipaddress = clean_param($_POST['ipaddress'], PARAM_RAW);
    $configuration = clean_param($_POST['configuration'], PARAM_TEXT);
    $date = clean_param($_POST['date'], PARAM_TEXT);
    // Construire le corps de l'e-mail.
    $emailbody = "<p>{$langmessage} : $message</p>";
    $emailbody .= "<p> $server</p>";
    $emailbody .= "<p> $identification</p>";
    $emailbody .= "<p> $ipaddress</p>";
    $emailbody .= "<p> $configuration</p>";
    $emailbody .= "<p> $date</p>";
    // Vérifier si la case à cocher mailto est activée.
    $mailtocc = get_config('block_su_agent_moodle', 'mailto');
    $mailsenttocc = false;
    if ($mailtocc) { // Si la case est cochée.
        $externalemail = new stdClass();
        $externalemail->email = $mailtocc;
        $externalemail->firstname = "";
        $externalemail->lastname = "";
        $externalemail->maildisplay = true;
        $externalemail->mailformat = 1;
        $externalemail->id = -99;
        // Envoi au destinataire principal en copie.
        $mailsenttocc = email_to_user($externalemail, $USER, $subject, $emailbody);
    }
    // Vérifier si l'e-mail doit aussi être envoyé à l'administrateur.
    $sendtoadmin = get_config('block_su_agent_moodle', 'supportemail');
    if ($sendtoadmin) {
        $admin = get_admin();
        email_to_user($admin, $USER, $subject, $emailbody);
    }
    // Nettoyer la sortie avant de retourner le JSON.
    ob_clean();
    // Retourner le statut en JSON.
    if ($mailsenttocc || $sendtoadmin) {
        echo json_encode(['status' => 'success']);
    } else {
        debugging('Email not sent to primary recipient', DEBUG_DEVELOPER);
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email to primary recipient.']);
    }
} else {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
// Fin de la mise en mémoire tampon et envoi de la réponse.
ob_end_flush();
