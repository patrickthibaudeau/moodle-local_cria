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



namespace local_cria;

class datatables
{
    /**
     * Column to start record limit
     * @var int
     */
    public static $start = 0;

    /**
     * Column to end record limit
     * @var int
     */
    public static $end = 25;

    /**
     * Search term
     * @var string
     */
    public static $term = '';

    /**
     * Query params
     * Array of columns that must always be searched
     * @var array
     */
    public static $query_params = [];

    /**
     * Column to order by
     * @var string
     */
    public static $orderColumn = 'name';

    /**
     * Order direction
     * @var string
     */
    public static $orderDirection = 'ASC';

    /**
     * Select option
     * @var bool
     */
    public static $select_option = false;

    /**
     * Select option column
     * @var string
     */
    public static $select_option_column = 'id';

    /**
     * Select option class name
     * @var string
     */
    public static $select_option_class_name = '';

    /**
     * columns array/stdClass
     * These are the columns returned by the query
     * @var array
     */
    public static $columns = [];

    /**
     * Table name
     * @var string
     */
    public static $table = '';

    /**
     * Require actions
     * @var bool
     */
    public static $require_actions = false;

    /**
     * Require actions
     * @var bool
     */
    public static $action_column = 'id';

    /**
     * Class name to use for action nuttons
     * @var string
     */
    public static $action_class_name = '';

    /**
     * @var array of buttons to display in action column
     */
    public static $action_item_buttons = [];


    // Set start value
    public static function set_start($start): void
    {
        self::$start = $start;
    }

    // Set end value default 50
    public static function set_end($end): void
    {
        self::$end = $end;
    }

    // Set term value default empty string
    public static function set_term($term): void
    {
        self::$term = $term;
    }

    // Set query params default empty array
    public static function set_query_params($query_params): void
    {
        self::$query_params = $query_params;
    }

    // Set order column default name
    public static function set_order_column($orderColumn): void
    {
        self::$orderColumn = $orderColumn;
    }

    // Set order direction default ASC
    public static function set_order_direction($orderDirection): void
    {
        self::$orderDirection = $orderDirection;
    }

    // Set columns array. Required for datatables
    public static function set_columns($columns): void
    {
        self::$columns = $columns;
    }

    // Set select option
    public static function set_select_option($select_option): void
    {
        self::$select_option = $select_option;
    }

    // Set select option column
    public static function set_select_option_column($select_option_column): void
    {
        self::$select_option_column = $select_option_column;
    }

    // Set select option class name
    public static function set_select_option_class_name($select_option_class_name): void
    {
        self::$select_option_class_name = $select_option_class_name;
    }

    // Set table name
    public static function set_table($table): void
    {
        self::$table = $table;
    }

    // Set require actions
    public static function set_require_actions($require_actions): void
    {
        self::$require_actions = $require_actions;
    }

    // Set action column
    public static function set_action_column($action_column): void
    {
        self::$action_column = $action_column;
    }

    // Set action class name
    public static function set_action_class_name($action_class_name): void
    {
        self::$action_class_name = $action_class_name;
    }

    /**
     * Array of buttons to display in action column
     * [
     *      [
     *          href,
     *          title,
     *          display-title,
     *          class,
     *          query_strings,
     *          file => [
     *              'context_id',
     *              'component',
     *              'filearea',
     *              'itemid',
     *              'filepath',
     *              'filename',
     *              'forcedownload =? true'
     *         ]
     * ],
     *      ],
     * ]
     * @param $action_item_buttons
     * @return void
     */
    public static function set_action_item_buttons($action_item_buttons): void
    {
        self::$action_item_buttons = $action_item_buttons;
    }

    // Get records
    public static function get_records()
    {
        global $DB;

        // Get columns
        if (count(self::$columns) == 0) {
            $db_columns = $DB->get_columns(self::$table);
            foreach ($db_columns as $column) {
                self::$columns[] = $column->name;
            }
        }

        // Comvert to string
        $columns = implode(',', self::$columns);

        // Set a count sql
        $count_sql = "SELECT COUNT(*) as total FROM {" . self::$table . "}";
        // Set a sql to return data records
        $sql = "SELECT $columns FROM {" . self::$table . "}";

        // If there is either a search term or query params, add WHERE to $sql
        if (self::$term || self::$query_params) {
            $sql .= " WHERE ";
            $count_sql .= " WHERE ";
        }

        // If there are query params, loop through them and add them to $sql as an AND statement
        if (self::$query_params) {
            foreach (self::$query_params as $key => $value) {
                $sql .= "$key = $value AND ";
                $count_sql .= "$key = $value AND ";
            }
            // Remove last AND
            $sql = substr($sql, 0, -4);
            $count_sql = substr($count_sql, 0, -4);
        }

        // count number of records found based on $sql query
        $total_records = $DB->get_record_sql($count_sql);

        // if there is a search term, loop through columns and add a LIKE statement for all columns
        if (self::$term) {
            $sql .= " AND (";
            foreach (self::$columns as $column) {
                $sql .= "$column LIKE '%" . self::$term . "%' OR ";
            }
            // Remove last OR
            $sql = substr($sql, 0, -3);
            $sql .= ")";
        }

        // Set column order
        $sql .= " ORDER BY " . self::$orderColumn . " " . self::$orderDirection;

        $records = $DB->get_recordset_sql($sql, null, self::$start, self::$end);

        $results = [];
        $i = 0;
        // Loop through records and add them to $results based on columns
        foreach ($records as $record) {
            if (self::$select_option) {
                $results[$i]['select'] = '<input type="checkbox" 
                name="select_option" class="' . self::$select_option_class_name . 'dt-select-box"
                 data-' . self::$select_option_column.'="' . $record->{self::$select_option_column} . '">';
            }
            foreach (self::$columns as $column) {
                $results[$i][$column] = $record->$column;
            }
            if (self::$require_actions) {
                $results[$i]['actions'] = self::draw_actions($record);
            }
            $i++;
        }

        $data = new \stdClass();
        $data->total_found = $total_records->total;
        $data->total_filtered = count($results);
        $data->results = $results;

        return $data;
    }

    /**
     * Draw actions
     * @param $data stdClass
     * @return string
     */
    public static function draw_actions($data)
    {
        global $OUTPUT;
        // Build action item buttons array
        $action_item_buttons = [];
        $i = 0;
        foreach (self::$action_item_buttons as $action_item_button) {
            $query_strings = [];
            foreach ($action_item_button['query_strings'] as $query_string => $column_name) {
                $query_strings[$query_string] = $data->$column_name;
            }
            // If href is empty, set the value to javascript:void(0);
            $is_button = false;
            if (empty($action_item_button['href'])) {
                if (isset($action_item_button['file'])) {
                    $action_item_button['href'] = \moodle_url::make_pluginfile_url(
                        $action_item_button['file']['context_id'],
                        'local_cria',
                        'content',
                        $data->intent_id,
                        '/',
                        $data->name,
                        true
                    );

                } else {
                    $is_button = true;
                }
            } else {
                // Build query strings.
                $x = 0;
                foreach ($action_item_button['query_strings'] as $query_string => $column_name) {
                    $search_query_strings[$column_name] = $query_strings[$x];
                    $x++;
                }
                $url = new \moodle_url($action_item_button['href'], $search_query_strings);
                // Make url ready for href by removing %amp;
                $new_url = str_replace('&amp;', '&', $url->out());
                $action_item_button['href'] = $new_url;
            }

            $action_item_buttons[$i] = [
                'is_button' => $is_button,
                'title' => $action_item_button['title'],
                'href' => str_replace('&amp;', '&', $action_item_button['href']),
                'class' => $action_item_button['class'],
                'display-title' => $action_item_button['display-title']
            ];
            $i++;
        }

        // Unique identifier
        $identifier = self::$action_column;
        $params = [
            'id' => $data->$identifier,
            'action_class_name' => self::$action_class_name,
            'action_buttons' => $action_item_buttons

        ];
        return $OUTPUT->render_from_template(
            'local_cria/datatables_actions',
            $params
        );
    }


}