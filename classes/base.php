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

use local_cria\bot;
use local_cria\bots;

//TO DO: Change this into a Singleton and get rid of static functions
class base
{

    // Set constant for buttons
    const CONTEXT_TONE = 'TONE';
    const CONTEXT_LENGTH = 'LENGTH';

    /**
     * Creates the Moodle page header
     * @param string $url Current page url
     * @param string $pagetitle Page title
     * @param string $pageheading Page heading (Note hard coded to site fullname)
     * @param array $context The page context (SYSTEM, COURSE, MODULE etc)
     * @param string $pagelayout The page context (SYSTEM, COURSE, MODULE etc)
     * @return HTML Contains page information and loads all Javascript and CSS
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @global \moodle_page $PAGE
     * @global \stdClass $SITE
     */
    public static function page($url, $pagetitle, $pageheading, $context = null, $pagelayout = 'base')
    {
        global $CFG, $PAGE, $SITE;


        $context = \context_system::instance();

        $PAGE->set_url($url);
        $PAGE->set_title($pagetitle);
        $PAGE->set_heading($pageheading);
        $PAGE->set_pagelayout($pagelayout);
        $PAGE->set_context($context);
        // We need datatables to work. So we load it from cdn
        // We also load one JS file that initialises all datatables.
        // This same file is used throughout, including in the blocks
        self::loadJQueryJS();
    }

    public static function loadJQueryJS()
    {
        global $CFG, $PAGE;
        $stringman = get_string_manager();
        $strings = $stringman->load_component_strings('local_yulearn', current_language());

        $PAGE->requires->jquery();
        $PAGE->requires->jquery_plugin('ui');
        $PAGE->requires->jquery_plugin('ui-css');
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js'), true);
        $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.css'));
        $PAGE->requires->strings_for_js(array_keys($strings), 'local_cria');
    }

    /**
     * Sets filemanager options
     * @param \stdClass $context
     * @param int $maxfiles
     * @return array
     * @global \stdClass $CFG
     */
    public static function getFileManagerOptions($context, $maxfiles = 1)
    {
        global $CFG;
        return array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => $maxfiles);
    }


    public static function getEditorOptions($context)
    {
        global $CFG;
        return array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1,
            'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => 0);
    }

    /**
     * Returns bootstrap modal
     * @param $id string No spaces
     * @param $message string
     * @return string
     * @throws \coding_exception
     */
    public static function getAlertModal($id = 'cts-alert-modal', $title = 'Alert', $message = '...')
    {
        $html = '<div class="modal fade" id="' . $id . '" tabindex="-1" aria-labelledby="' . $id . '-Label" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="' . $id . '-Label">' . $title . '</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        ' . $message . '
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">' . get_string('close', 'local_cts_co') . '</button>
                      </div>
                    </div>
                  </div>
                </div>
';
        echo $html;
    }

    /**
     * Locale-formatted strftime using \IntlDateFormatter (PHP 8.1 compatible)
     * This provides a cross-platform alternative to strftime() for when it will be removed from PHP.
     * Note that output can be slightly different between libc sprintf and this function as it is using ICU.
     *
     * Usage:
     * use function \PHP81_BC\strftime;
     * echo strftime('%A %e %B %Y %X', new \DateTime('2021-09-28 00:00:00'), 'fr_FR');
     *
     * Original use:
     * \setlocale('fr_FR.UTF-8', LC_TIME);
     * echo \strftime('%A %e %B %Y %X', strtotime('2021-09-28 00:00:00'));
     *
     * @param string $format Date format
     * @param integer|string|DateTime $timestamp Timestamp
     * @return string
     * @author BohwaZ <https://bohwaz.net/>
     */
    public static function strftime(string $format, $timestamp = null, ?string $locale = null): string
    {
        if (null === $timestamp) {
            $timestamp = new \DateTime;
        } elseif (is_numeric($timestamp)) {
            $timestamp = date_create('@' . $timestamp);

            if ($timestamp) {
                $timestamp->setTimezone(new \DateTimezone(date_default_timezone_get()));
            }
        } elseif (is_string($timestamp)) {
            $timestamp = date_create($timestamp);
        }

        if (!($timestamp instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException('$timestamp argument is neither a valid UNIX timestamp, a valid date-time string or a DateTime object.');
        }

        $locale = substr((string)$locale, 0, 5);

        $intl_formats = [
            '%a' => 'EEE',    // An abbreviated textual representation of the day	Sun through Sat
            '%A' => 'EEEE',    // A full textual representation of the day	Sunday through Saturday
            '%b' => 'MMM',    // Abbreviated month name, based on the locale	Jan through Dec
            '%B' => 'MMMM',    // Full month name, based on the locale	January through December
            '%h' => 'MMM',    // Abbreviated month name, based on the locale (an alias of %b)	Jan through Dec
        ];

        $intl_formatter = function (\DateTimeInterface $timestamp, string $format) use ($intl_formats, $locale) {
            $tz = $timestamp->getTimezone();
            $date_type = \IntlDateFormatter::FULL;
            $time_type = \IntlDateFormatter::FULL;
            $pattern = '';

            // %c = Preferred date and time stamp based on locale
            // Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
            if ($format == '%c') {
                $date_type = \IntlDateFormatter::LONG;
                $time_type = \IntlDateFormatter::SHORT;
            }
            // %x = Preferred date representation based on locale, without the time
            // Example: 02/05/09 for February 5, 2009
            elseif ($format == '%x') {
                $date_type = \IntlDateFormatter::SHORT;
                $time_type = \IntlDateFormatter::NONE;
            } // Localized time format
            elseif ($format == '%X') {
                $date_type = \IntlDateFormatter::NONE;
                $time_type = \IntlDateFormatter::MEDIUM;
            } else {
                $pattern = $intl_formats[$format];
            }

            return (new \IntlDateFormatter($locale, $date_type, $time_type, $tz, null, $pattern))->format($timestamp);
        };

        // Same order as https://www.php.net/manual/en/function.strftime.php
        $translation_table = [
            // Day
            '%a' => $intl_formatter,
            '%A' => $intl_formatter,
            '%d' => 'd',
            '%e' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('j'));
            },
            '%j' => function ($timestamp) {
                // Day number in year, 001 to 366
                return sprintf('%03d', $timestamp->format('z') + 1);
            },
            '%u' => 'N',
            '%w' => 'w',

            // Week
            '%U' => function ($timestamp) {
                // Number of weeks between date and first Sunday of year
                $day = new \DateTime(sprintf('%d-01 Sunday', $timestamp->format('Y')));
                return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
            },
            '%V' => 'W',
            '%W' => function ($timestamp) {
                // Number of weeks between date and first Monday of year
                $day = new \DateTime(sprintf('%d-01 Monday', $timestamp->format('Y')));
                return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
            },

            // Month
            '%b' => $intl_formatter,
            '%B' => $intl_formatter,
            '%h' => $intl_formatter,
            '%m' => 'm',

            // Year
            '%C' => function ($timestamp) {
                // Century (-1): 19 for 20th century
                return floor($timestamp->format('Y') / 100);
            },
            '%g' => function ($timestamp) {
                return substr($timestamp->format('o'), -2);
            },
            '%G' => 'o',
            '%y' => 'y',
            '%Y' => 'Y',

            // Time
            '%H' => 'H',
            '%k' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('G'));
            },
            '%I' => 'h',
            '%l' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('g'));
            },
            '%M' => 'i',
            '%p' => 'A', // AM PM (this is reversed on purpose!)
            '%P' => 'a', // am pm
            '%r' => 'h:i:s A', // %I:%M:%S %p
            '%R' => 'H:i', // %H:%M
            '%S' => 's',
            '%T' => 'H:i:s', // %H:%M:%S
            '%X' => $intl_formatter, // Preferred time representation based on locale, without the date

            // Timezone
            '%z' => 'O',
            '%Z' => 'T',

            // Time and Date Stamps
            '%c' => $intl_formatter,
            '%D' => 'm/d/Y',
            '%F' => 'Y-m-d',
            '%s' => 'U',
            '%x' => $intl_formatter,
        ];

        $out = preg_replace_callback('/(?<!%)(%[a-zA-Z])/', function ($match) use ($translation_table, $timestamp) {
            if ($match[1] == '%n') {
                return "\n";
            } elseif ($match[1] == '%t') {
                return "\t";
            }

            if (!isset($translation_table[$match[1]])) {
                throw new \InvalidArgumentException(sprintf('Format "%s" is unknown in time format', $match[1]));
            }

            $replace = $translation_table[$match[1]];

            if (is_string($replace)) {
                return $timestamp->format($replace);
            } else {
                return $replace($timestamp, $match[1]);
            }
        }, $format);

        $out = str_replace('%%', '%', $out);
        return $out;
    }

    /**
     * Returns the list of faculties
     * @return array
     */
    public static function get_faculties()
    {
        global $CFG;
        $config = get_config('local_cria');
        $faculty_settings = explode("\n", $config->faculties);
        $faculties = [];
        $faculties[''] = get_string('all', 'local_cria');
        foreach ($faculty_settings as $faculty) {
            $faculty = explode('|', $faculty);
            $faculties[$faculty[0]] = $faculty[1];
        }
        return $faculties;
    }

    /**
     * Returns the list of programs
     * @return array
     */
    public static function get_programs()
    {
        global $CFG;
        $config = get_config('local_cria');
        $program_settings = explode("\n", $config->programs);
        $programs = [];
        $programs[''] = get_string('all', 'local_cria');
        foreach ($program_settings as $program) {
            $program = explode('|', $program);
            $programs[$program[0]] = $program[1];
        }
        return $programs;
    }

    /**
     * Returns the list of languages
     * @return array
     */
    public static function get_languages()
    {
        global $CFG;
        $config = get_config('local_cria');
        $language_settings = explode("\n", $config->languages);
        $languages = [];
        $languages[''] = get_string('all', 'local_cria');
        foreach ($language_settings as $language) {
            $language = explode('|', $language);
            $languages[$language[0]] = $language[1];
        }
        return $languages;
    }

    /**
     * Check to see if port is open
     * @param $host
     * @param $port
     * @return bool
     */
    public static function is_port_open($host, $port) {
        $fp = fsockopen($host, $port, $errno, $errstr, 1);
        if ($fp) {
            fclose($fp);
            return true; // Port is open
        } else {
            return false; // Port is closed
        }
    }

    /**
     * Create indexes if missing
     * @param $bot_name string
     * @return void
     * @throws \dml_exception
     */
    public static function create_cria_indexes($bot_name) {
        // Check to see that the document index exists in Criadex
        $document_index = criadex::index_about($bot_name . '-document-index');
        $question_index = criadex::index_about($bot_name . '-question-index');
        $cache_index = criadex::index_about($bot_name . '-cache-index');
        // Split bot_name to get bot_id and intent_id
        $bot_intent_ids = explode('-', $bot_name);
        $BOT = new bot($bot_intent_ids[0]);
        // Get bot parameters
        $bot_parameters = json_decode($BOT->get_bot_parameters_json());
        // Create intent object
        $INTENT = new intent($bot_intent_ids[1]);
        $api_key = $INTENT->get_bot_api_key();
        // If document index does not exist, create it
        if ($document_index->status == 404) {
            // Create index
            $index = criadex::index_create(
                $bot_name . '-document-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'DOCUMENT'
            );

            // Check to see if Index authorization exists
            $api_key_exists = criadex::index_authorization_check($bot_name . '-document-index', $api_key );
            // If index authorization does not exist, create it
            if ($api_key_exists != 200) {
                criadex::index_authorization_create($bot_name . '-document-index', $api_key);
            }
        }

        // If question index does not exist, create it
        if ($question_index->status == 404) {
            // Create index
            $index = criadex::index_create(
                $bot_name . '-question-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'QUESTION'
            );

            // Check to see if Index authorization exists
            $api_key_exists = criadex::index_authorization_check($bot_name . '-question-index', $api_key);
            // If index authorization does not exist, create it
            if ($api_key_exists != 200) {
                criadex::index_authorization_create($bot_name . '-question-index', $api_key);
            }
        }

        // if cache index does not exist, create it
        if ($cache_index->status == 404) {
            // Create index
            $index = criadex::index_create(
                $bot_name . '-cache-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'CACHE'
            );

            // Check to see if Index authorization exists
            $api_key_exists = criadex::index_authorization_check($bot_name . '-cache-index', $api_key);
            // If index authorization does not exist, create it
            if ($api_key_exists != 200) {
                criadex::index_authorization_create($bot_name . '-cache-index', $api_key);
            }
        }

        return true;
    }

    /**
     * Returns the list of available child bots
     * @return array
     */
    public static function get_available_child_bots($bot_id = 0) {
        $BOTS = new bots();
        return $BOTS->get_available_child_bots($bot_id);
    }


    /**
     * Returns the list of available parsing strategies
     * @return array
     */
    public static function get_parsing_strategies() {
        $CRIAPARSE = new criaparse();
        $parse_strategies = $CRIAPARSE->get_strategies();

        $strategies = [];
        foreach ($parse_strategies['strategies'] as $key => $strategy) {
            $strategies[$strategy] = $strategy;
        }
        return $strategies;
    }

    /**
     * Deletes a folder and all file swithin it.
     * @return array
     */
    public static function delete_files($path) {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path must be a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteFolder($file);
            } else {
                unlink($file);
            }
        }
    }

    /**
     * Returns the list of available parsing strategies
     * @return array
     */
    public static function create_directory_if_not_exists($path) {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }
}
