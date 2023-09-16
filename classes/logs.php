<?php

namespace local_cria;

class logs
{
    /**
     * Insert a log record
     *
     * @param int $bot_id
     * @param string $prompt
     * @param string $message
     * @param string $prompt_tokens
     * @param string $response_tokens
     * @param int $cost
     * @throws \dml_exception
     */
    public static function insert($bot_id, $prompt, $message, $prompt_tokens, $completion_tokens, $total_tokens, $cost, $context = '') {
        global $DB, $USER;
        // Get client IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $data = [
            'bot_id' => $bot_id,
            'userid' => $USER->id,
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

        $DB->insert_record('local_cria_logs', $data);
    }

    /**
     * Get logs for a given bot_id
     *
     * @param int $bot_id
     * @return array
     */
    public static function get_logs($bot_id) {
        global $DB, $USER;

        $sql = "Select
                    fl.id,
                    fl.bot_id,
                    f.name As bot_name,
                    fl.userid,
                    u.firstname,
                    u.lastname,
                    u.idnumber,
                    u.email,
                    fl.prompt,
                    fl.message,
                    fl.index_context,
                    fl.prompt_tokens,
                    fl.completion_tokens,
                    fl.total_tokens,
                    fl.cost,
                    fl.ip,
                    DATE_FORMAT(FROM_UNIXTIME(fl.timecreated), '%m/%d/%Y %h:%i') as timecreated
                From
                    {local_cria_logs} fl Inner Join
                    {user} u On u.id = fl.userid Inner Join
                    {local_cria} f On f.id = fl.bot_id
                WHERE 
                    bot_id = ? ";

        $params = [
            'bot_id' => $bot_id
        ];

        // Site admins can see all logs, users can only see their own
        if (!is_siteadmin()) {
            $sql .= " AND f.userid = ?";
            $params['userid'] = $USER->id;
        }

        $sql .= " ORDER BY f.timecreated DESC";

        $logs = $DB->get_records_sql($sql, $params);
        return array_values($logs);
    }

    /**
     * Get total usage cost for a given bot_id
     *
     * @param int $bot_id
     * @return array
     */
    public static function get_total_usage_cost($bot_id, $currency = 'CAD') {
        global $DB;
        $sql = "SELECT 
                    SUM(cost) as total_cost
                FROM 
                    {local_cria_logs}
                WHERE
                    bot_id = ?";
        $logs = $DB->get_records_sql($sql, [$bot_id]);
        $fmt = new \NumberFormatter( 'en_CA', \NumberFormatter::CURRENCY );
        $value = $fmt->formatCurrency(array_values($logs)[0]->total_cost, $currency);
        return $value;
    }

}