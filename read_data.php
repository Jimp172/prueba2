<?php
include('db_config.php');

$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 20";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
