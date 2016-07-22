<?php

include('dbconnect.php');

// file_get_contents - reads a file
// json_decode - decodes json obviously, but the "true" turns the json into an associative array.
$json_array = json_decode(file_get_contents('products_big.json'),true);

// These two commands would dump the json array for viewing in a clear manner.
// Only needed for debugging
//echo "<pre>";
//print_r($json_array);

//For each entry in the json_array ... do something with it.
foreach($json_array as $entry){
    //print_r($entry["imgs"][0]).'\n';
    $image = $entry["imgs"][0];
    $modImage = str_replace("160", "~", $image);
    //print_r($modImage).'\n';
    
    $price = $entry["price"];
    $remDollar = str_replace("$", "", $price);
    $remDollar = $remDollar * 1.00;
    //print_r($remDollar);
    
    $category = $entry["category"];
   // print_r($category);
    
    $description = $entry["h2"];
    //print_r($description);
 
 $sql = "INSERT INTO products (id, category, desc, price, img)
VALUES ('','$category', '$description', '$remDollar', '$modImage')";

}
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>