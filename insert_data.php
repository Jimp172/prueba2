<?php
//include('db_config.php');

// Inicializar $response
$response = array();

// Verificar si se están enviando datos a través de GET o POST
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = ($_SERVER['REQUEST_METHOD'] === 'GET') ? $_GET : $_POST;

    if (isset($requestData['phValue']) && isset($requestData['voltage']) && isset($requestData['temperature'])) {
        $phValue = floatval($requestData['phValue']);
        $voltage = floatval($requestData['voltage']);
        $temperature = floatval($requestData['temperature']);

        $timestamp = date('Y-m-d H:i:s');

        $sql = "INSERT INTO sensor_data (timestamp, ph_value, voltage, temperature) VALUES ('$timestamp', $phValue, $voltage, $temperature)";

        if ($conn->query($sql) === TRUE) {
            $response = array(
                'status' => 'success',
                'message' => 'Datos insertados exitosamente.',
                'data' => array(
                    'timestamp' => $timestamp,
                    'ph_value' => $phValue,
                    'voltage' => $voltage,
                    'temperature' => $temperature
                )
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Error al insertar datos: ' . $conn->error
            );
        }
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'La solicitud no es de tipo GET o POST.'
    );
}

file_put_contents('log.txt', print_r($requestData, true), FILE_APPEND);

// Devolver datos en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
