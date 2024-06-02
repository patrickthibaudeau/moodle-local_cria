<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

use local_cria\base;

class conversation_styles {

	/**
	  * Get tone buttons
	 */
	public function get_tone_buttons() {
        global $DB;
        $results = $DB->get_records(
            'local_cria_convo_styles',
            ['context' => Base::CONTEXT_TONE],
            'name ASC'
        );
        $buttons = [];
        foreach($results as $r) {
            $gpt_data = json_decode($r->value);
            $gpt_data_string = '';
            // create data params
            foreach ($gpt_data as $key => $value) {
                $gpt_data_string .= 'data-'. $key . '=' . $value . ' ';
            }
            $buttons[] = [
                'id' => $r->id,
                'element_id' => 'tone-' . strtolower($r->name),
                'name' => $r->name,
                'gpt_data' => $gpt_data_string,
                'class' => $r->class
            ];
        }
        $data = [
            'tone' => $buttons,
        ];
	    return $data;
	}
}