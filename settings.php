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
defined('MOODLE_INTERNAL') || die;
global $CFG;
if ($hassiteconfig) {
    $settings = new admin_settingpage('block_su_agent_moodle', get_string('pluginname', 'block_su_agent_moodle'));
    $settings->add(new admin_setting_heading('block_su_agent_moodle/moodleinfo', 'Configurations', ''));
    $settings->add(
        new admin_setting_configcheckbox(
            'block_su_agent_moodle/mailenabled',
            get_string('enabled', 'block_su_agent_moodle'),
            get_string('enabled_desc', 'block_su_agent_moodle'),
            1
        )
    );
    $settings->add(
        new admin_setting_configcheckbox(
            'block_su_agent_moodle/supportemail',
            $CFG->supportemail,
            get_string('supportemail', 'block_su_agent_moodle'),
            1
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'block_su_agent_moodle/mailto',
            get_string('receiver', 'block_su_agent_moodle'),
            null,
            null
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'block_su_agent_moodle/subject',
            get_string('subject_custom', 'block_su_agent_moodle'),
            null,
            null
        )
    );
}

