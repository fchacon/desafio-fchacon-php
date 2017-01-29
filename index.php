<?php
// connect to mongodb
$m = new MongoClient();
echo "Connection to database successfully".PHP_EOL;

// select a database
$db = $m->mydb;
echo "Database mydb selected".PHP_EOL;
$collection = $db->createCollection("person");
echo "Collection created succsessfully".PHP_EOL;

$data = array("run" => "16426627-3", "fullName" => "Felipe Chacón", "birthDate" => "14-05-1986", "gender" => "m");
$response = CallAPI("GET", "http://localhost/desafio-fchacon/REST/person/getAge", $data);

echo "Response JSON: ".$response."".PHP_EOL;
$response = json_decode($response, true);

if(isset($response['status']) && trim($response['status']) == "OK" && isset($response['data'])) {
        $collection->insert($response['data']);
        echo "Document inserted successfully".PHP_EOL;
}
else {
        echo "Error calling WS".PHP_EOL;
}

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function CallAPI($method, $url, $data = false) {
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}