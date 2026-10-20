<?php
$fileName = "./kalyanData.json";

// 2. Check if the target file actually exists on the disk
if (!file_exists($fileName)) {
    die("Error: The file '$fileName' does not exist in this directory.");
}

// 3. Read the file content raw string
$raw_content = file_get_contents($fileName);
if (strncmp($raw_content, "\xEF\xBB\xBF", 3) === 0) {
    $raw_content = substr($raw_content, 3);
}
$data = json_decode($raw_content);
if (!is_array($data) || !isset($data[0])) {
    die("Error: The JSON file is invalid or contains no records. " . json_last_error_msg());
}
//print_r($data);


// 1. Grab the raw POST payload
$raw_input = file_get_contents('php://input');
$decoded_input = json_decode($raw_input, true);

// 2. Read the local storage data sent by JavaScript
if (isset($decoded_input['localData'])) {
    $my_local_data = $decoded_input['localData'];
    
    // Now you can safely use it in PHP
    //echo "PHP successfully received: " . var_dump($my_local_data);
    //echo "PHP successfully received: " . var_dump($data);
    array_push($data, $my_local_data);
    //print_r(json_encode($data, JSON_UNESCAPED_SLASHES));
    echo "PHP successfully received and added to JSON: " . json_encode($data);
    if(file_put_contents($fileName, json_encode($data, JSON_UNESCAPED_SLASHES))) {
        echo "Data successfully written to $fileName.";
    } else {
        echo "Error writing data to $fileName.";
    }
} else {
    echo "No local storage data received.";
}






?>
