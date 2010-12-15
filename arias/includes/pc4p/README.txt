You need the following packages:

pdflib-3.02 or higher with switches:
   --enable-shared-pdflib
   --with-tifflib=path
   --with-zlib=path
   --with-pnglib=path

php-4.0.4 or higher with switches:
   --with-pdflib[=DIR]
   --with-zlib-dir[=DIR]
   --with-jpeg-dir[=DIR]
   --with-png-dir[=DIR]
   --with-tiff-dir[=DIR]

Please be careful, that pdflib and php use the same image-libs!!!
If you have these packages installed, copy include/ and examples/
in a Webserver-Directory and have a look at examples/print.php.
If the file is displayed, PC4P is correctly installed :-).

If any problems occur, please feel free to send me an email to
wirtz@web-active.com

Have fun!
   Alexander
