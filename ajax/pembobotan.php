<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'pembobotan';
 
// Table's primary key
$primaryKey = 'id';

//any where clause? wkwk
$ratio = isset($_GET['ratio']) ? $_GET['ratio'] : 1;
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'id', 'dt' => 0),
    array( 'db' => 'data', 'dt' => 1 ),
    array( 'db' => 'tf_positif', 'dt' => 2 ),
    array( 'db' => 'tf_negatif', 'dt' => 3 ),
    array( 'db' => 'df', 'dt' => 4 ),
    array( 'db' => 'idf', 'dt' => 5 ),
    array( 'db' => 'bobot_positif', 'dt' => 6 ),
    array( 'db' => 'bobot_negatif', 'dt' => 7 ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'root',
    'db'   => 'sentimen_prakerja_copy',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, "rasio = '$ratio'" )
);