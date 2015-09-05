<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = UC_DB_HOSTNAME;//'192.168.35.115';
$db['default']['username'] = UC_DB_USERNAME;//'root';
$db['default']['password'] = UC_DB_PASSWORD;//'quanshi';
$db['default']['database'] = UC_DB_DATABASE;//'statusnet';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = false;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

//域分配数据库连接
$db['domain']['hostname'] = DOMAIN_DB_HOSTNAME;//'192.168.35.115';
$db['domain']['username'] = DOMAIN_DB_USERNAME;//'root';
$db['domain']['password'] = DOMAIN_DB_PASSWORD;//'quanshi';
$db['domain']['database'] = DOMAIN_DB_DATABASE;//'statusnet';
$db['domain']['dbdriver'] = 'mysql';
$db['domain']['dbprefix'] = '';
$db['domain']['pconnect'] = false;
$db['domain']['db_debug'] = TRUE;
$db['domain']['cache_on'] = FALSE;
$db['domain']['cachedir'] = '';
$db['domain']['char_set'] = 'utf8';
$db['domain']['dbcollat'] = 'utf8_general_ci';
$db['domain']['swap_pre'] = '';
$db['domain']['autoinit'] = TRUE;
$db['domain']['stricton'] = FALSE;
//mss邮件数据库连接
$db['mss']['hostname'] = MSS_DB_HOSTNAME;//'192.168.35.115';
$db['mss']['username'] = MSS_DB_USERNAME;//'root';
$db['mss']['password'] = MSS_DB_PASSWORD;//'quanshi';
$db['mss']['database'] = MSS_DB_DATABASE;//'mss';
$db['mss']['dbdriver'] = 'mysql';
$db['mss']['dbprefix'] = '';
$db['mss']['pconnect'] = false;
$db['mss']['db_debug'] = TRUE;
$db['mss']['cache_on'] = FALSE;
$db['mss']['cachedir'] = '';
$db['mss']['char_set'] = 'utf8';
$db['mss']['dbcollat'] = 'utf8_general_ci';
$db['mss']['swap_pre'] = '';
$db['mss']['autoinit'] = TRUE;
$db['mss']['stricton'] = FALSE;
//webpower邮件数据库连接
/*
$db['email']['hostname'] = EMAIL_DB_HOSTNAME;//'192.168.35.115';
$db['email']['username'] = EMAIL_DB_USERNAME;//'root';
$db['email']['password'] = EMAIL_DB_PASSWORD;//'quanshi';
$db['email']['database'] = EMAIL_DB_DATABASE;//'mss';
$db['email']['dbdriver'] = 'mysql';
$db['email']['dbprefix'] = '';
$db['email']['pconnect'] = TRUE;
$db['email']['db_debug'] = TRUE;
$db['email']['cache_on'] = FALSE;
$db['email']['cachedir'] = '';
$db['email']['char_set'] = 'utf8';
$db['email']['dbcollat'] = 'utf8_general_ci';
$db['email']['swap_pre'] = '';
$db['email']['autoinit'] = TRUE;
$db['email']['stricton'] = FALSE;
*/
//自定义


/* End of file database.php */
/* Location: ./application/config/database.php */