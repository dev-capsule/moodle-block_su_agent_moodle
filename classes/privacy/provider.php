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

namespace block_su_agent_moodle\privacy;

use core_privacy\local\metadata\null_provider;
use core_privacy\local\legacy_polyfill;

/**
 * Privacy provider implementation for block_su_agent_moodle
 *
 * @package     block_su_agent_moodle
 * @copyright   2018 Sorbonne Université
 * @copyright   2024 Victor Da Silva Caseiro <victor.da_silva_caseiro@sorbonne-universite.fr>
 * @copyright   2024 Thomas Naudin <thomas.naudin@sorbonne-universite.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Privacy provider class for block_su_agent_moodle.
 *
 * This provider indicates that the plugin stores no personal data.
 *
 * @package     block_su_agent_moodle\privacy
 */
class provider implements null_provider {
    use legacy_polyfill;

    /**
     * Get the language string identifier with the component's language.
     * file to explain why this plugin stores no data.
     *
     * @return string
     */
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}
