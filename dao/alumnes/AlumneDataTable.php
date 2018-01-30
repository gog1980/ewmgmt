<?php

/* 
 * Copyright 2017 Oscar.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace LAMgmt\Dao;

use LAMgmt\Util\DataTables\SSP;

final class AlumneDataTable{
    
    static function getAlumnesTable(){

        // DB table to use
        $table = 'alumnes';

        // Table's primary key
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'primer_cognom', 'dt' => 1 ),
            array( 'db' => 'segon_cognom',  'dt' => 2 ),
            array( 'db' => 'nom',   'dt' => 3 ),
            array( 'db' => 'mobil',     'dt' => 4 ),
            array( 'db' => 'telefon',     'dt' => 5 ),
            array( 
                'db' => 'email',   
                'dt' => 6,
                'formatter' => function( $d, $row ) {
                    return "<a href='mailto:$d'>$d</a>";
                }
                ),
            array(
                'db'        => 'data_naixement',
                'dt'        => 7,
                'formatter' => function( $d, $row ) {
                    return date( 'd/m/Y', strtotime($d));
                }
            ),
            array(
                'db'        => 'data_ingres',
                'dt'        => 8,
                'formatter' => function( $d, $row ) {
                    return date( 'd/m/Y', strtotime($d));
                }
            )                    
        );
        header('Content-Type: application/json; charset=utf-8');
        return json_encode(SSP::complex( $_GET, $table, $primaryKey, $columns, null, "esborrat = 0" ));
    }
}    
    
?>