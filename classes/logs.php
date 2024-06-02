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

class logs
{
    /**
     * @param $bot_id
     * @param $prompt
     * @param $message
     * @param $prompt_tokens
     * @param $completion_tokens
     * @param $total_tokens
     * @param $cost
     * @param $context
     * @param $ip
     * @return void
     * @throws \dml_exception
     */
    public static function insert(
        $bot_id,
        $prompt,
        $message,
        $prompt_tokens,
        $completion_tokens,
        $total_tokens,
        $cost,
        $context = '',
        $ip = '',
        $user_id = 0
    )
    {
        global $DB, $USER;
        // Get client IP
        if (empty($ip)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        if (!$user_id) {
            $user_id = $USER->id;
        }

        $data = [
            'bot_id' => $bot_id,
            'userid' => $user_id,
            'prompt' => $prompt,
            'message' => $message,
            'prompt_tokens' => $prompt_tokens,
            'completion_tokens' => $completion_tokens,
            'total_tokens' => $total_tokens,
            'cost' => $cost,
            'index_context' => $context,
            'ip' => $ip,
            'timecreated' => time()
        ];

        $id = $DB->insert_record('local_cria_logs', $data);
        return $id;
    }

    /**
     * Get logs for a given bot_id
     *
     * @param int $bot_id
     * @return array
     */
    public static function get_logs($bot_id, $date_range = null)
    {
        global $DB, $USER;

        if ($date_range) {
            $date_range = explode(' - ', $date_range);
            $start_date = strtotime($date_range[0] . ' 00:00:00');
            $end_date = strtotime($date_range[1] . ' 23:59:59');
        } else {
            $start_date = strtotime('first day of this month');
            $end_date = strtotime('last day of this month');
        }

        $sql = "Select
                    cl.id,
                    cl.bot_id,
                    b.name As bot_name,
                    cl.userid,
                    u.firstname,
                    u.lastname,
                    u.idnumber,
                    u.email,
                    cl.prompt,
                    cl.message,
                    cl.index_context,
                    cl.prompt_tokens,
                    cl.completion_tokens,
                    cl.total_tokens,
                    cl.cost,
                    cl.ip,
                    DATE_FORMAT(FROM_UNIXTIME(cl.timecreated), '%m/%d/%Y %h:%i') as timecreated
                From
                    {local_cria_logs} cl Inner Join
                    {user} u On u.id = cl.userid Inner Join
                    {local_cria_bot} b On b.id = cl.bot_id
                WHERE 
                    cl.timecreated BETWEEN ? AND ? AND
                    bot_id = ?";

        $params = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bot_id' => $bot_id
        ];

        // Site admins can see all logs, users can only see their own
//        if (!is_siteadmin()) {
//            $sql .= " AND fl.userid = ?";
//            $params['userid'] = $USER->id;
//        }

        $sql .= " ORDER BY cl.timecreated DESC";

        $logs = $DB->get_records_sql($sql, $params);
        return array_values($logs);
    }

    /**
     * Get total usage cost for a given bot_id
     *
     * @param int $bot_id
     * @return array
     */
    public static function get_total_usage_cost($bot_id, $currency = 'CAD', $date_range = null)
    {
        global $DB;

        if ($date_range) {
            $date_range = explode(' - ', $date_range);
            $start_date = strtotime($date_range[0] . ' 00:00:00');
            $end_date = strtotime($date_range[1] . ' 23:59:59');
        } else {
            $start_date = strtotime('first day of this month');
            $end_date = strtotime('last day of this month');
        }

        $sql = "SELECT 
                    SUM(cost) as total_cost
                FROM 
                    {local_cria_logs}
                WHERE
                    timecreated BETWEEN ? AND ? AND
                    bot_id = ?";
        $logs = $DB->get_records_sql($sql, [$start_date, $end_date, $bot_id]);
        $fmt = new \NumberFormatter('en_CA', \NumberFormatter::CURRENCY);
        $value = $fmt->formatCurrency(array_values($logs)[0]->total_cost, $currency);

        return $value;
    }

}