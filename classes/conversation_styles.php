<?php
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