<?php
// Tu código de conexión a la base de datos y consulta SQL aquí
include('db_config.php');

$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 20";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'id' => $row['id'],
        'timestamp' => $row['timestamp'],
        'ph_value' => $row['ph_value'],
        'voltage' => $row['voltage'],
        'temperature' => $row['temperature']
    );
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Sensor</title>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- Puedes agregar estilos personalizados aquí -->
    <style>
        /* Estilos personalizados si es necesario */
    </style>
</head>

<body>

<div class="container mt-5">
    
        <div class="card shadow border-0">
            <div class="card-body">
                <h2>Datos del Sensor</h2>
                <table class="table table-bordered">
                    <div id="data"></div>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Timestamp</th>
                            <th>pH Value</th>
                            <th>Temperature</th>
                        </tr>
                </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 20";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
    ?>
        <tr>
            <th scope="row"><?php echo $row['id'] ?></th>
            <td><?php echo $row['timestamp'] ?></td>
            <td><?php echo $row['ph_value'] ?></td>
            <td><?php echo $row['temperature'] ?></td>
        </tr>
    <?php } ?>
    </tbody>
    
</div>
<div>
    <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
    const ctx = document.getElementById('myChart');

    // Obtén los datos desde PHP
    const labels = <?php
        $labels = array();
        $dataPoints = array();

        $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 20";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['timestamp'];
            $dataPoints[] = $row['ph_value'];
        }

        echo json_encode($labels);
    ?>;

    const dataPoints = <?php echo json_encode($dataPoints); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'pH Value',
                data: dataPoints,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



</body>

</html>
