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
class block_su_agent_moodle extends block_base {
    /**
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_su_agent_moodle');
    }
    public function has_config(): bool {
        return true;
    }
    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_mailactive() {
        if (get_config('block_su_agent_moodle', 'mailenabled') === '1') {
            $buttonlabel = get_string('msgbtn', 'block_su_agent_moodle');

            $varsbtn = '<textarea id="msg" name="msg" class="form-control"></textarea>'
                . '<div>'
                . '<button type="button" id="msgbtn" class="btn btn-primary mt-1">'
                . $buttonlabel
                . '</button></div>';
        } else {
            $infomessage = get_string('copydatamsg', 'block_su_agent_moodle');

            $varsbtn = '<a href="#" id="infos">'
                . $infomessage
                . '</a>';
        }
        return $varsbtn;
    }
    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content() {
        global $USER;
        // Charger le module JS AMD.
        $this->page->requires->js_call_amd('block_su_agent_moodle/copy', 'init');
        // Récupérer l'IP du client (utilisateur).
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipclient = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipclient = $_SERVER['REMOTE_ADDR'] ?? null;
        }
        // Récupérer d'autres informations du système et de l'utilisateur.
        $systemnavigator = $_SERVER['HTTP_USER_AGENT'];
        $time = time();
        $daytime = date('d/m/Y H:i:s', $time);
        $ipserveur = $_SERVER['SERVER_ADDR'] ?? null;
        $username = $USER->username ?? null;
        // Créer un objet pour stocker le contenu.
        $this->content = new stdClass;
        // Générer le contenu HTML en utilisant les informations collectées.
        $this->content->text = '
        <div id="compagnon">
            <div id="serverlabel">
                <span>' . get_string('server', 'block_su_agent_moodle') . ' : <strong>' . $ipserveur . '</strong></span>
            </div>
            <div id="identificationlabel">
                <span>' . get_string('identification', 'block_su_agent_moodle') . ' : <strong>' . $username . '</strong></span>
            </div>
            <div id="ipaddresslabel">
                <span>' . get_string('ipaddress', 'block_su_agent_moodle') . ' : <strong>' . $ipclient . '</strong></span>
            </div>
            <div id="configurationlabel">
                <span>' . get_string('configuration', 'block_su_agent_moodle') . ' : <i>' . $systemnavigator . '</i></span>
            </div>
            <div id="datelabel">
                <span>' . get_string('date', 'block_su_agent_moodle') . ' : <strong>' . $daytime . '</strong></span>
            </div>
            <div id="infoslabel">' . $this->get_mailactive() . '</div>
        </div>';
        // On ne fait PAS l'appel direct à send_mail() ici.
        return $this->content;
    }
    /**
     * Disallow multiple instance.
     *
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return false;
    }
}
