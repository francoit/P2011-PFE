<?php
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "pdf_clock.php");
pdf_set_info($pdf, "Author", "Uwe Steinmann");
pdf_set_info($pdf, "Title", "Analog Clock");
pdf_begin_page($pdf, 612, 792);
pdf_add_outline($pdf, "Page 1");
pdf_set_font($pdf, "Times-Roman", 16, "host");
pdf_show_xy($pdf, "Times Roman outlined", 50, 750);
//pdf_moveto($pdf, 50, 740);
//pdf_stroke($pdf);
pdf_end_page($pdf);
pdf_close($pdf);
$buf = pdf_get_buffer($pdf);
$len = strlen($buf);
$fp = fopen ("test.pdf", "w");
fputs($fp,$buf,$len);
fclose($fp);
pdf_delete($pdf);
//echo "<A HREF=test.php>finished</A>";
//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=http://'.getenv(SERVER_NAME).'/utilities/test.pdf">';
?>


