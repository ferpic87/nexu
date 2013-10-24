<?php
/* Data base details */
$dsn='mysql:host=localhost;dbname=elgg'; //DSN
$db_user='root'; //DB username
$db_pass='Oralsex'; //DB password    
$driver='Elgg'; //Integration driver
$db_prefix='elgg_'; //prefix used for tables in database
$uid='526130e91a76f'; //Any random unique number

$PATH = 'mod/freichat/'; // Use this only if you have placed the freichat folder somewhere else
$installed=true; //make it false if you want to reinstall freichat
$admin_pswd='modern'; //backend password 

$debug = false;
$custom_error_handling='NO'; // used during custom installation

$use_cookie=false;

/* email plugin */
$smtp_username = '';
$smtp_password = '';

$force_load_jquery = 'NO';

/* Custom driver */
$usertable='login'; //specifies the name of the table in which your user information is stored.
$row_username='root'; //specifies the name of the field in which the user's name/display name is stored.
$row_userid='loginid'; //specifies the name of the field in which the user's id is stored (usually id or userid)
$avatar_field_name = 'avatar';