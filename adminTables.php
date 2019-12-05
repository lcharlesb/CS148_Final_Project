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
$count1 = 1;
foreach($tableNames as $table){
    print '<h2>' . $table[0] . '</h2>';
    print '<table>';
    

    $query = 'SHOW COLUMNS FROM ' . $table[0];
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $columns = $thisDatabaseReader->select($query);
        foreach($columns as $column)
            $numOfCols++;
        

    $query = 'SELECT ';
    foreach($columns as $column){
        if($numOfCols == $count1){
            $query.= $column["Field"] . " ";
            
        }
        else{
            $query.= $column["Field"] . ", ";
            
        }
        $count1++;
    }

    $query .= 'FROM ' . $table[0];

    if ($thisDatabaseReader->querySecurityOk($query, 0)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $tableInfo = $thisDatabaseReader->select($query,'');
        
        
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