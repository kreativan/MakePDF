<?php

/**
 * MakePDF
 * @author Ivan Milincic <hello@kreativan.dev>
 * @link http://www.kraetivan.dev
 */

namespace ProcessWire;

class MakePDF extends WireData implements Module {

  public static function getModuleInfo() {
    return array(
      'title' => 'MakePDF',
      'version' => 101,
      'summary' => 'Wrapper around mPDF library for ProcessWire',
      'icon' => 'file-pdf-o',
      'author' => "Ivan Milincic",
      "href" => "https://kreativan.dev",
      'singular' => true,
      'autoload' => false
    );
  }

  public function __construct() {
    // ...
  }


  public function init() {
    /**
     * Temp folder. Create temp folder if not exists
     */
    $this->temp_folder = $this->config->paths->assets . 'temp/';
    if (!is_dir($this->temp_folder)) $this->files->mkdir($this->temp_folder);
  }

  /**
   * Get temp folder path
   * @return string
   */
  public function temp_path() {
    return $this->temp_folder;
  }

  /**
   * Default PDF options
   * @param string $html - html content to convert
   * @param string $options['mode'] - utf-8, iso-8859-1, etc
   * @param string $options['format'] - page  dimensions: [210, 297]
   * @param string $options['orientation'] - P or L - portrait or landscape
   * 
   * @param int $options['margin_top'] - default 20
   * @param int $options['margin_bottom'] - default 20
   * @param int $options['margin_left'] - default 20
   * @param int $options['margin_right'] - default 20
   * @param int $options['margin_header'] - default 20
   * @param int $options['margin_footer'] - default 20
   * 
   * @param string $options['output'] - INLINE, FILE, DOWNLOAD
   * @param string $options['dest'] - for 'FILE' output method
   * @param string $options['file_name'] - for 'FILE' output method
   * 
   * @param string $options['header'] - html header
   * @param string $options['footer'] - html footer
   * 
   * @param string $options['font'] - sans, condensed, serif, slab
   */
  public function options($array = []) {

    $options = [
      'mode' => !empty($array['mode']) ? $array['mode'] : "utf-8",
      'format' => !empty($array['format']) ? $array['format'] : [210, 297],
      'orientation' => !empty($array['orientation']) ? $array['orientation'] : "P",
      'margin_top' => !empty($array['margin_top']) ? $array['margin_top'] : 20,
      'margin_bottom' => !empty($array['margin_bottom']) ? $array['margin_bottom'] : 20,
      'margin_left' => !empty($array['margin_left']) ? $array['margin_left'] : 20,
      'margin_right' => !empty($array['margin_right']) ? $array['margin_right'] : 20,
      'margin_header' => !empty($array['margin_header']) ? $array['margin_header'] : 20,
      'margin_footer' => !empty($array['margin_footer']) ? $array['margin_footer'] : 20,
      'output' => !empty($array['output']) ? $array['output'] : "INLINE",
      'dest' => !empty($array['dest']) ? $array['dest'] : $this->temp_path(),
      'file_name' => !empty($array['file_name']) ? $array['file_name'] : time(),
      'header' => !empty($array['header']) ? $array['header'] : "",
      'footer' => !empty($array['footer']) ? $array['footer'] : "",
      'font' => !empty($array['font']) ? $array['font'] : "sans", // sans, cobdensed, serif, slab
      'debug' => !empty($array['debug']) && $array['debug'] == 1 ? true : false,
      'stylesheet' => !empty($array['stylesheet']) ? $array['stylesheet'] : '',
    ];

    //
    // Fonts
    //

    require_once(__DIR__ . "/mpdf/vendor/autoload.php");
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $options['fontDir'] = array_merge($fontDirs, [
      __DIR__ . '/fonts/',
    ]);

    $options['fontdata'] = $fontData + [ // lowercase letters only in font key
      'sans' => [
        'R' => 'roboto/Roboto-Regular.ttf',
        'I' => 'roboto/Roboto-Italic.ttf',
        'B' => 'roboto/Roboto-Bold.ttf',
        'L' => 'roboto/Roboto-Lite.ttf',
      ],
      'condensed' => [
        'R' => 'roboto-condensed/Roboto-Condensed-Regular.ttf',
        'I' => 'roboto-condensed/Roboto-Condensed-Italic.ttf',
        'B' => 'roboto-condensed/Roboto-Condensed-Bold.ttf',
        'L' => 'roboto-condensed/Roboto-Condensed-Lite.ttf',
      ],
      'serif' => [
        'R' => 'roboto-serif/Roboto-Serif-Regular.ttf',
        'I' => 'roboto-serif/Roboto-Serif-Italic.ttf',
        'B' => 'roboto-serif/Roboto-Serif-Bold.ttf',
        'L' => 'roboto-serif/Roboto-Serif-Lite.ttf',
      ],
      'slab' => [
        'R' => 'roboto-slab/Roboto-Slab-Regular.ttf',
        'B' => 'roboto-slab/Roboto-Slab-Bold.ttf',
        'L' => 'roboto-slab/Roboto-Slab-Lite.ttf',
      ],
      'mono' => [
        'R' => 'roboto-mono/Roboto-Mono-Regular.ttf',
        'I' => 'roboto-mono/Roboto-Mono-Italic.ttf',
        'B' => 'roboto-mono/Roboto-Mono-Bold.ttf',
        'L' => 'roboto-mono/Roboto-Mono-Lite.ttf',
      ],
    ];

    $options['default_font'] = $options['font'];

    // Add/override custom options
    if (count($array) > 0) {
      foreach ($array as $key => $val) $options[$key] = $val;
    }

    return $options;
  }

  /**
   * File to PDF
   * Use @method html2pdf() to convert contents of a file to pdf
   * @param string $file_path - path to file to convert
   * @param array $vars - array of variables to pass to the file
   * @param array $options - array of options to set/override
   * @see $this->options() for params
   * @example $this->file2pdf($file_path, $vars, $options);
   * @example $this->file2pdf($file_path, ['var1' => 'value1', 'var2' => 'value2'], ['output' => 'DOWNLOAD']);
   */
  public function file2pdf($file_path, $vars = [], $options = []) {
    // clean all the content before the pdf
    ob_get_clean();

    // Set passed variables
    foreach ($vars as $key => $value) $$key = $value;

    ob_start();
    include($file_path);
    $html = ob_get_clean();

    $this->html2pdf($html, $options);
  }


  /**
   * HTML to PDF
   * Simple converting html to pdf
   * @param string $html - html content to convert
   * @param array $options - array of options to set/override
   * @see $this->options() for params
   * @example $this->html2pdf($html, $options);
   */
  public function html2pdf($html, $params = []) {

    // pdf options
    $options = $this->options($params);

    // params
    $output     = $options['output'];
    $dest       = $options['dest'];
    $file_name  = $options['file_name'];
    $header     = $options['header'];
    $footer     = $options['footer'];

    $options['margin-header'] = 0;

    // mPDF
    require_once(__DIR__ . "/mpdf/vendor/autoload.php");

    /**
     * Init
     */
    $mpdf = new \Mpdf\Mpdf($options);

    // Show image errors in debug mode
    if ($this->config->debug) $mpdf->showImageErrors = true;

    // Add default stylesheet to the pdf
    $stylesheet = file_get_contents(__DIR__ . '/css/mpdf.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Add custom stylesheet to the pdf
    if ($options['stylesheet'] != '') {
      $custom_stylesheet = file_get_contents($options['stylesheet']);
      $mpdf->WriteHTML($custom_stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    }

    // Set header and footer
    if ($header != '') $mpdf->SetHTMLHeader($header);
    if ($footer != '') $mpdf->SetHTMLFooter($footer);

    // Write html
    $mpdf->WriteHTML($html);

    // Output
    if (!$output) {
      return $mpdf->Output();
    } elseif ($output == 'INLINE') { // display pdf in the browser - default
      return $mpdf->Output($file_name . ".pdf", \Mpdf\Output\Destination::INLINE);
    } elseif ($output == 'DOWNLOAD') { // trigger download pdf
      return $mpdf->Output($file_name . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);
    } elseif ($output == "FILE") { // download file in specified path
      return $mpdf->Output("{$dest}{$file_name}.pdf", \Mpdf\Output\Destination::FILE);
    }
  }

  /**
   * Generate multi-page PDF 
   * 
   * @param array $pdf_pages - array of pages to add
   * In array you have to specify the path of the file that contains the html to be included in the pdf.
   * You can pass any variable to the included file in same array.
   * 
   * @example
   * $pdf_pages = [
   *    "page_1" => [
   *      "tmpl" => "path/to/template-page-1.php",
   *      "some_var" => "some_value",
   *    ],
   *   "page_2" => [
   *      "tmpl" => "path/to/template-page-2.php",
   *      "some_var" => "some_value",
   *   ],
   * ];
   * 
   * @param array $pdf_files - array of static pdf files to add
   * @example $pdf_files = ["file_1.pdf", "file_2.pdf"];
   * 
   * Override default PDF options
   * @param array $options - array of options to set/override
   * @see $this->options() for params
   * 
   * How to use?
   * @example $this->generatePDF($pdf_pages, $pdf_files, $options);
   */
  public function generatePDF($pdf_pages = [], $pdf_files = [], $options_arr = []) {

    // pdf options
    $options = $this->options($options_arr);

    // params
    $output     = $options['output'];
    $dest       = $options['dest'];
    $file_name  = $options['file_name'];
    $header     = $options['header'];
    $footer     = $options['footer'];

    // mPDF
    require_once(__DIR__ . "/mpdf/vendor/autoload.php");

    // Init
    $mpdf = new \Mpdf\Mpdf($options);

    // debug
    if ($this->config->debug) {
      $mpdf->showImageErrors = true;
    }

    // Write html
    $stylesheet = file_get_contents(__DIR__ . '/css/mpdf.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    $i = 0;

    // Add pages - from $pdf_pages array, by specifued (tmpl) template files
    foreach ($pdf_pages as $item) {
      $tmpl = !empty($item['tmpl']) ? $item['tmpl'] : false;
      $data = [];
      foreach ($item as $key => $value) $data[$key] = $value;
      if ($tmpl && file_exists($tmpl)) {
        if ($i++ > 0) $mpdf->AddPage();
        ob_start();
        $this->files->include($tmpl, $data);
        $html = ob_get_contents();
        ob_end_clean();
        if ($header != '') $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        if ($footer != '') $mpdf->SetHTMLFooter($footer);
      }
    }

    // Add static pdf files
    if ($pdf_files && count($pdf_files) > 0) {
      foreach ($pdf_files as $file) {
        if (file_exists($file)) {
           // Set the source file
           $source_file = $mpdf->SetSourceFile($file);
          try {
            // Loop through each page of the PDF
            for ($page = 1; $page <= $source_file; $page++) {
              $mpdf->AddPage(); // Add a new page to the mPDF document
              $pdf_page = $mpdf->ImportPage($page); // Import the page
              $mpdf->UseTemplate($pdf_page, 0, 0, $format[0], $format[1]); // Use the template
            }
          } catch (Exception $e) {
            $file_name = basename($file);
            $mpdf->WriteHTML("<div style='padding:20px;color:red;'><b>{$file_name}</b> file is invalid or not supported.</div>");
          }
        }
      }
    }

    // Output
    if (!$output) {
      return $mpdf->Output();
    } elseif ($output == 'INLINE') {
      return $mpdf->Output($file_name . ".pdf", \Mpdf\Output\Destination::INLINE);
    } elseif ($output == 'DOWNLOAD') {
      return $mpdf->Output($file_name . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);
    } elseif ($output == "FILE") {
      return $mpdf->Output("{$dest}{$file_name}.pdf", \Mpdf\Output\Destination::FILE);
    }
  }
}
