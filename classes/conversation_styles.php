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
                'gpt_data' => $gpt_data_string
            ];
        }
        $data = [
            'tone' => $buttons,
        ];
	    return $data;
	}

    /**
     * Get length buttons
     */
    public function get_length_buttons($gpt_model)
    {
        global $DB;
        $results = $DB->get_records(
            'local_cria_convo_styles',
            ['context' => Base::CONTEXT_LENGTH],
            'name ASC'
        );

        $buttons = [];
        foreach ($results as $r) {
            $gpt_data = json_decode($r->value);
            $gpt_data_string = '';
            // create data params
            switch ($gpt_model) {
                case 'gpt-35-turbo':
                    $values = $gpt_data['gpt-35-turbo'];
                    foreach($values as $key => $value) {
                        $gpt_data_string .= 'data-' . $key . '=' . $value . ' ';
                    }
                    break;
            }
            $buttons[] = [
                'id' => $r->id,
                'name' => $r->name,
                'gpt_data' => $gpt_data
            ];
        }
        return $buttons;
    }
}