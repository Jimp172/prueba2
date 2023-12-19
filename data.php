    <?php
    // Tu código de conexión a la base de datos y consulta SQL aquí
    include('db_config.php');

    $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 20";
    $result = $conn->query($sql);

    header("refresh: 1");

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
                        <thead>
                    </thead>
        
        
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
                backgroundColor: 'rgba(120, 300, 100, 100)',
                borderColor: 'rgba(120, 300, 100, 100)',
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 4.80, // Establece el valor mínimo visible en el eje y
                    suggestedMax: 5.30  // Establece el valor máximo visible en el eje y
                }
            }
        }
    });
    </script>



    </body>

    </html>
