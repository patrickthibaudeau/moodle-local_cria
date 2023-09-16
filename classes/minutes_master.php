<?php

namespace local_cria;

class minutes_master
{
    public static function create_document(
        $minutes,
        $project_name = '',
        $date = '',
        $time = '',
        $location = '',
        $file_path = false
    ) {
        global $CFG;
        // Load PhpWord
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/PhpWord.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/TemplateProcessor.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Settings.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Style.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Media.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/IOFactory.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Shared/ZipArchive.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Shared/Text.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Exception/Exception.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Exception/CopyFileException.php');
        require_once($CFG->dirroot . '/local/cria/classes/PhpWord/Exception/CreateTemporaryFileException.php');

        $settings = new \PhpOffice\PhpWord\Settings();
        if (!file_exists($CFG->dataroot . '/temp/minutes_master')) {
            mkdir($CFG->dataroot . '/temp/minutes_master', 0777, true);
        }
        $settings->setTempDir($CFG->dataroot . '/temp/minutes_master');
        // load the template
        if (!$file_path) {
            $template_path = $CFG->dirroot . '/local/cria/doc_templates/template.docx';
        } else {
            $template_path = $file_path;
        }

        $template_processor = new \PhpOffice\PhpWord\TemplateProcessor(
            $template_path
        );

        // Convert each line as a new line specific for Word Document
        $minutes =  preg_replace('~\R~u', '</w:t><w:br/><w:t>', $minutes);

        // Replace all values by those received in parameters
        $template_processor->setValues(
            array(
                'project_name' => $project_name,
                'date' => $date,
                'time' => $time,
                'location' =>  $location,
                'minutes' => $minutes
            )
        );

        // Make sure temp folder exists
        if (!file_exists($CFG->dataroot . '/temp/minutes_master')) {
            mkdir($CFG->dataroot . '/temp/minutes_master', 0777, true);
        }
        // Set file name based on project name adn date
        $file_name = preg_replace('/\s+/', '_', $project_name) . '_' . time() . '.docx';
        // Save file
        $path = $CFG->dataroot .  '/temp/minutes_master/' . $file_name;
        $template_processor->saveAs($path);
        // Return file path
        return json_encode(['path' => $path, 'file_name' => $file_name]);

    }

    private static function serve_file($filepath, $new_filename=null) {
        $filename = basename($filepath);
        if (!$new_filename) {
            $new_filename = $filename;
        }
        $mime_type = mime_content_type($filepath);
        header('Content-type: '.$mime_type);
        header('Content-Disposition: attachment; filename="'.$new_filename.'"');
        readfile($filepath);
    }

}