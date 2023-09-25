# MakePDF

ProcessWire PDF generation module using mPDF library. 

### HTML 2 PDF
Use this method to simply convert any html mark into pdf file.

```php
// Get the module
$pdf = $modules->get('MakePDF');
// Set the options
$options = [];
// Generate PDF
$pdf->html2pdf("<h1>hello world</h1>", $options);
```

### GeneratePDF
Use this method to generate multi-page PDF
```php
// Get the module
$pdf = $modules->get('MakePDF');

// Set the pages
$pdf_pages = [
  'page_1' => [
    'tmpl' => __DIR__ . "/pdf/page-1.php",
    'title' => "Page 1",
  ],
  'page_2' => [
    'tmpl' => __DIR__ . "/pdf/page-2.php",
    'title' => "Page 2",
  ],
];

// Include static pdf files
$pdf_files = [
  __DIR__ . "/pdf/file-1.pdf",
  __DIR__ . "/pdf/file-2.pdf",
  __DIR__ . "/pdf/file-3.pdf",
];

// Set the options
$options = [
  "font" => "mono",
  "header" => "<div style='background: #f8f8f8;padding: 30px;'>Header</div>",
  "footer" => "<div style='background: #f8f8f8;padding: 30px;'>Footer</div>",
  "margin_top" => 60,
  "margin_bottom" => 60,
];

// Generate PDF
$pdf->generatePDF($pdf_pages, $pdf_files, $options);


```

### Options
| Option | Description | Default Value |
|--------|-------------|---------------|
| `mode` | The encoding mode of the generated PDF file. | `utf-8` |
| `format` | The format of the generated PDF file. Can be either `[210, 297]` or `[612, 792]`. | `[210, 297]` |
| `orientation` | The orientation of the generated PDF file. Can be either `P` (portrait) or `L` (landscape). | `P` |
| `margin_top` | The top margin of the PDF file. | `20` |
| `margin_bottom` | The bottom margin of the PDF file. | `20` |
| `margin_left` | The left margin of the PDF file. | `20` |
| `margin_right` | The right margin of the PDF file. | `20` |
| `margin_header` | The header margin of the PDF file. | `20` |
| `margin_footer` | The footer margin of the PDF file. | `20` |
| `output` | The output mode of the generated PDF file. Can be either `INLINE` or `DOWNLOAD`. | `INLINE` |
| `dest` | The destination path of the generated PDF file. | The temporary directory path |
| `file_name` | The filename of the generated PDF file. | The current timestamp |
| `header` | The HTML code for the header of the PDF file. | An empty string |
| `footer` | The HTML code for the footer of the PDF file. | An empty string |
| `font` | The font family of the generated PDF file. Can be either `sans`, `condensed`, `serif`, or `slab`. | `sans` |
| `debug` | Whether to enable debug mode. | `false` |

### CSS
Modules includes basic css utility classes that you can use to style your pdf.

| Class | Description |
|-------|-------------|
| `.align_center`, `.text-center` | Centers text. |
| `.align_left`, `.text-left` | Aligns text to the left. |
| `.align_justify`, `.text-justify` | Justifies text. |
| `.align_right`, `.text-right` | Aligns text to the right. |
| `.bg-muted` | Sets the background color to a muted gray. |
| `.bg-white` | Sets the background color to white. |
| `.color-white` | Sets the color of text to white. |
| `.color-white-light` | Sets the color of text to a light white. |
| `.float-left` | Floats an element to the left. |
| `.float-right` | Floats an element to the right. |
| `.line-height-1` | Sets the line height to 1. |
| `.line-height-15` | Sets the line height to 1.5. |
| `.list` | Removes the default list styles and sets the padding and margin to 0. |
| `.margin` | Sets the margin to 20px on top and bottom, and 0px on left and right. |
| `.margin-auto` | Centers an element horizontally. |
| `.margin-bottom` | Sets the bottom margin to 20px. |
| `.margin-remove` | Removes all margins. |
| `.margin-remove-bottom` | Removes the bottom margin. |
| `.margin-remove-top` | Removes the top margin. |
| `.margin-small` | Sets the margin to 10px on top and bottom, and 0px on left and right. |
| `.margin-small-bottom` | Sets the bottom margin to 10px. |
| `.margin-small-top` | Sets the top margin to 10px. |
| `.margin-top` | Sets the top margin to 20px. |
| `.padding` | Sets the padding to 20px. |
| `.padding-small` | Sets the padding to 10px. |
| `.table` | Sets the styles of a table. |
| `.table-reset` | Resets the styles of a table. |
| `.text-bold` | Sets the font weight to bold. |
| `.text-down` | Converts text to lowercase. |
| `.text-italic` | Sets the font style to italic. |
| `.text-large` | Sets the font size to 1.2em. |
| `.text-lite` | Sets the font weight to light. |
| `.text-muted` | Sets the color of text to a muted gray. |
| `.text-rotate-90` | Rotates text 90 degrees. |
| `.text-small` | Sets the font size to 0.8em. |
| `.text-underlined` | Underlines text. |
| `.text-up` | Converts text to uppercase. |
| `.v-align-bottom` | Vertically aligns an element to the bottom. |
| `.v-align-middle` | Vertically aligns an element to the middle. |
| `.v-align-top` | Vertically aligns an element to the top. |
| `.width-10` | Sets the width to 10%. |
| `.width-100` | Sets the width to 100%. |
| `.width-20` | Sets the width to 20%. |
| `.width-30` | Sets the width to 30%. |
| `.width-40` | Sets the width to 40%. |
| `.width-48` | Sets the width to 48%. |
| `.width-49` | Sets the width to 49%. |
| `.width-50` | Sets the width to 50%. |
| `.width-60` | Sets the width to 60%. |
| `.width-70` | Sets the width to 70%. |
| `.width-80` | Sets the width to 80%. |
| `.width-90` | Sets the width to 90%. |