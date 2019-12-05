<?php

include 'top.php';

if ($isAdmin){
   
print '<article>';

$query = 'SHOW TABLES';
$tableNames = '';
if ($thisDatabaseReader->querySecurityOk($query, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $tableNames = $thisDatabaseReader->select($query);

}

foreach($tableNames as $table){
    print '<h2>' . $table[0] . '</h2>';
    print '<table id ="admin-table">';
    
    //Count the number of columns in each table.
    $query = "SELECT count(*) ";
    $query .= "FROM information_schema.columns ";
    $query .= "WHERE table_name = ?";

    $tblName = $table[0];
    $table_array = array($tblName);
    if ($thisDatabaseReader->querySecurityOk($query, 1)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $result = $thisDatabaseReader->select($query,$table_array);
        
        
    }
    $numOfCols = (int) $result[0][0];// num of columns.


    // get the names of the colums in each table.
    $query = 'SHOW COLUMNS FROM ' . $table[0];
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $columns = $thisDatabaseReader->select($query);
    
    $query = 'SELECT ';
    for($z = 1; $z <= $numOfCols; $z++){
        if($z == $numOfCols){
            $query.= $columns[$z-1]["Field"] . " ";  
        }
        else{
            $query.= $columns[$z-1]["Field"] . ", ";
        }
    }
    $query .= 'FROM ' . $table[0];

    if ($thisDatabaseReader->querySecurityOk($query, 0)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $tableInfo = $thisDatabaseReader->select($query);
        
    }
   
    if (!empty($tableInfo)){
        $th = '<tr>';
        $td = '<tr>';
        // foreach loop that represents the number of columns (i = 0; i < numOfCols; i++)
        foreach($columns as $column){
            $th .= '<th>' . $column["Field"] . "</th>";
            
        }
        $th .= "</tr>";
        print $th;
        // for every row in the table
        foreach($tableInfo as $row){
            print '<tr>';
            // print each column value for the row.
            for($i = 0; $i < $numOfCols; $i++){
                
                print '<td>' . $row[$i] . '</td>';
            }
            print '</tr>';
        }
    }
    else{
        print 'Empty Table.';
    }
    
    
    print '</table>';
   

}
} //admin bracket
else {
    print 'You are not allowed in this page.';
}