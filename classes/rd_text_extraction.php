<?php

namespace local_cria;
/**
 * Class RD_Text_Extraction
 *
 * Example usage:
 *
 * $response = RD_Text_Extraction::convert_to_text($path_to_valid_file);
 *
 * For PDF text extraction, this class requires the Smalot\PdfParser\Parser class.
 * @see https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php
 *
 */
class rd_text_extraction
{
    /**
     * @param $path_to_file
     * @return string
     * @throws \Exception
     */
    protected static function pdf_to_text( $path_to_file ) {

        if ( class_exists( '\\Smalot\\PdfParser\\Parser') ) {

            $parser   = new \Smalot\PdfParser\Parser();
            $pdf      = $parser->parseFile( $path_to_file );
            $response = $pdf->getText();

        } else {

            throw new \Exception('The library used to parse PDFs was not found.' );
        }

        return $response;

    }

    /**
     * @param $path_to_file
     * @return string
     */
    protected static function doc_to_text( $path_to_file ) : string
    {
        $fileHandle = fopen($path_to_file, 'r');
        $line       = @fread($fileHandle, filesize($path_to_file));
        $lines      = explode(chr(0x0D), $line);
        $response   = '';

        foreach ($lines as $current_line) {

            $pos = strpos($current_line, chr(0x00));

            if ( ($pos !== FALSE) || (strlen($current_line) == 0) ) {
                continue;
            } else {
                $response .= $current_line . ' ';
            }
        }

        $response = preg_replace('/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/', '', $response);

        return $response;
    }

    /**
     * @param $path_to_file
     * @return string
     */
    protected static function docx_to_text( $path_to_file ) : string
    {
        $zip = new \ZipArchive();
        if ($zip->open($path_to_file) !== true) {
            return false;
        }

        $response = '';

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            if ($filename !== 'word/document.xml') {
                continue;
            }

            $content = $zip->getFromIndex($i);
            $response .= $content;
        }

        $zip->close();

        $response = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $response);
        $response = str_replace('</w:r></w:p>', "\r\n", $response);
        $response = strip_tags($response);

        return $response;
    }

    /**
     * @param $path_to_file
     * @return string
     */
    protected static function xlsx_to_text( $path_to_file ) : string
    {
        $xml_filename = 'xl/sharedStrings.xml'; //content file name
        $zip_handle   = new \ZipArchive();
        $response     = '';

        if (true === $zip_handle->open($path_to_file)) {

            if (($xml_index = $zip_handle->locateName($xml_filename)) !== false) {

                $doc = new \DOMDocument();

                $xml_data   = $zip_handle->getFromIndex($xml_index);
                $doc->loadXML($xml_data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response   = strip_tags($doc->saveXML());

            }

            $zip_handle->close();

        }

        return $response;
    }

    /**
     * @return string
     */
    protected static function pptx_to_text( $path_to_file ) : string
    {
        $zip_handle = new \ZipArchive();
        $response   = '';

        if (true === $zip_handle->open($path_to_file)) {

            $slide_number = 1; //loop through slide files
            $doc = new \DOMDocument();

            while (($xml_index = $zip_handle->locateName('ppt/slides/slide' . $slide_number . '.xml')) !== false) {

                $xml_data   = $zip_handle->getFromIndex($xml_index);

                $doc->loadXML($xml_data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response  .= strip_tags($doc->saveXML());

                $slide_number++;

            }

            $zip_handle->close();

        }

        return $response;
    }

    /**
     * @return string[]
     */
    public static function get_valid_file_types() : array
    {
        return [
            'doc',
            'docx',
            'pptx',
            'xlsx',
            'pdf'
        ];
    }

    /**
     * @param $path_to_file
     * @return string
     * @throws \Exception
     */
    public static function convert_to_text( $path_to_file ) : string
    {
        if (isset($path_to_file) && file_exists($path_to_file)) {

            $valid_extensions = self::get_valid_file_types();

            $file_info = pathinfo($path_to_file);
            $file_ext  = strtolower($file_info['extension']);

            if (in_array( $file_ext, $valid_extensions )) {

                $method   = $file_ext . '_to_text';
                $response = self::$method( $path_to_file );

            } else {

                throw new \Exception('Invalid file type provided. Valid file types are doc, docx, xlsx or pptx.');

            }

        } else {

            throw new \Exception('Invalid file provided. The file does not exist.');

        }

        return $response;
    }

}