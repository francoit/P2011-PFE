<?PHP

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

// *****************************
// * Database section
// *****************************

//where the mysql server resides
  define('DB_SERVER', '127.0.0.1');

//the mysql username to use when connecting to the server
  define('DB_SERVER_USERNAME', 'root');

//the mysql password to go along with that username
  define('DB_SERVER_PASSWORD', '');

//the name of the database you created for nola
  define('DB_DATABASE', 'aria');

//Encryption key is the private key used to encrypt passwords with the blowfish encryption algorithm, if your system supports it.  You do /NOT/ want to change this if you already have users entered.  Only change prior to installation.  You also do not want to give this out, as it would make decryption of your password database much simpler ;)
  define('ENCRYPTION_KEY', '8aw3wb7v35n9awv48b7o8c7a4bd');

PHP?>
