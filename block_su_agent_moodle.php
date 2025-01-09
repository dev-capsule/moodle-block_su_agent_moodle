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
 * Block for SU Agent Moodle
 *
 * @package     block_su_agent_moodle
 * @copyright   2018 Sorbonne Université
 * @copyright   2024 Victor Da Silva Caseiro <victor.da_silva_caseiro@sorbonne-universite.fr>
 * @copyright   2024 Thomas Naudin <thomas.naudin@sorbonne-universite.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Block class for SU Agent Moodle
 */
class block_su_agent_moodle extends block_base {
    /**
     * Initialize the block
     *
     * @throws coding_exception
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_su_agent_moodle');
    }

    /**
     * Indicates that this block has configuration.
     *
     * @return bool True if the block has configuration.
     */
    public function has_config(): bool {
        return true;
    }

    /**
     * Gets the content of the block.
     *
     * @throws coding_exception
     * @throws dml_exception
     * @return stdClass|null The block content
     */
    public function get_content() {
        global $USER;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->page->requires->js_call_amd('block_su_agent_moodle/copy', 'init');

        $data = [
            'ipserveur' => $_SERVER['SERVER_ADDR'] ?? null,
            'username' => $USER->username ?? null,
            'ipclient' => getremoteaddr(),
            'systemnavigator' => $_SERVER['HTTP_USER_AGENT'],
            'daytime' => date('d/m/Y H:i:s', time()),
            'mailactive' => get_config('block_su_agent_moodle', 'mailenabled') === '1',
        ];

        $this->content = new stdClass;
        $this->content->text = $this->render_template('block_su_agent_moodle/content', $data);

        return $this->content;
    }

    /**
     * Render a template
     *
     * @param string $template The template path
     * @param array|stdClass $data The data for the template
     * @return string The rendered content
     */
    protected function render_template($template, $data) {
        global $OUTPUT;
        return $OUTPUT->render_from_template($template, $data);
    }

    /**
     * Indicates that this block does not allow multiple instances
     *
     * @return bool False as multiple instances are not allowed
     */
    public function instance_allow_multiple(): bool {
        return false;
    }
}
