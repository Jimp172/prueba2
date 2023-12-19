<?php
// Inicializa la sesión
session_start();
 
// Verifique si el usuario ha iniciado sesión, si no, rediríjalo a la página de inicio de sesión
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
include('db_config.php');

?>
<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Admin Dashboard</title>
        <!-- style css php -->
        <?php include_once 'css_style/style.php';?>
		<!-- end style css php -->
	<body>
		<div id="wrapper">
            <!-- nav -->
            <?php include_once 'sidebar/nav_dashboard.php';?>
			<!-- end nav -->
			<div id="page-wrapper" class="gray-bg dashbard-1">
                <!-- navbar -->
                <?php include_once 'sidebar/navbar.php';?>
                <!-- end navbar -->
				<div class="wrapper wrapper-content">
    <div class="row">
	<?php
// Obtener los últimos valores de la base de datos
$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $latestData = $result->fetch_assoc();

    // Función para obtener la clase de color según las condiciones
    function getColorClass($value, $upperThreshold, $lowerThreshold) {
        if ($value > $upperThreshold) {
            return 'text-danger'; // Rojo
        } elseif ($value < $lowerThreshold) {
            return 'text-warning'; // Anaranjado
        } else {
            return ''; // Sin clase adicional
        }
    }

    // Obtener clases de color según las condiciones
    $phColorClass = getColorClass($latestData['ph_value'], 5, 5);
    $temperatureColorClass = getColorClass($latestData['temperature'], 25, 25);
    $voltageColorClass = getColorClass($latestData['voltage'], 1854.64, 1854.64);

    // Mostrar el valor de pH
    echo '<div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title"> <span class="label label-success float-right">Mensual</span>
                    <h5>Valor pH</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins ' . $phColorClass . '">' . $latestData['ph_value'] . '</h1>
                    <div class="stat-percent font-bold ' . $phColorClass . '">98% <i class="fa fa-bolt"></i></div> <small></small> 
                </div>
            </div>
        </div>';

    // Mostrar el valor de temperatura
    echo '<div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title"> <span class="label label-info float-right">Anual</span>
                    <h5>Valor Temperatura</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins ' . $temperatureColorClass . '">' . $latestData['temperature'] . '</h1>
                    <div class="stat-percent font-bold ' . $temperatureColorClass . '">20% <i class="fa fa-level-up"></i></div> <small></small> 
                </div>
            </div>
        </div>';

    // Mostrar el valor de voltaje
    echo '<div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title"> <span class="label label-danger float-right">Low value</span>
                    <h5>Voltaje</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins ' . $voltageColorClass . '">' . $latestData['voltage'] . '</h1>
                    <div class="stat-percent font-bold ' . $voltageColorClass . '">38% <i class="fa fa-level-down"></i></div> <small></small> 
                </div>
            </div>
        </div>';
} else {
    echo '<p>No hay datos disponibles.</p>';
}
?>
    </div>
</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox ">
								<div class="ibox-title">
									<h5>Grafico de pH</h5>
									<div class="float-right">
										<div class="btn-group">
											<button type="button" class="btn btn-xs btn-white active">Hoy</button>
											<button type="button" class="btn btn-xs btn-white">Mensual</button>
											<button type="button" class="btn btn-xs btn-white">Anual</button>
										</div>
									</div>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-9">
											<div class="flot-chart">
												<div class="flot-chart-content" id="flot-dashboard-chart"></div>
										<div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8">
							<div class="row">
							<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>Detalles incubadora</h5>
            <div class="ibox-tools">
                <a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>
                <a class="close-link"> <i class="fa fa-times"></i> </a>
            </div>
        </div>
        <div class="ibox-content table-responsive">
            <table class="table table-hover no-margins">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Valor Ph</th>
                        <th>Temperatura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 10";
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
            </table>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>Notificaciones</h5>
            <div class="ibox-tools">
                <a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a>
                <a class="close-link"> <i class="fa fa-times"></i> </a>
            </div>
        </div>
        <div class="ibox-content">
            <ul class="todo-list m-t small-list">
                <?php
                $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 10";
                $result = $conn->query($sql);

                // Verificar si la consulta se ejecutó correctamente
                if ($result === false) {
                    echo "Error en la consulta: " . $conn->error;
                } else {
                    while ($row = $result->fetch_assoc()) {
                        // Verificar si el valor de ph_value es mayor a 5.00
                        if ($row['ph_value'] > 5.00) {
                            // Aquí puedes agregar lógica para enviar una notificación
                            echo '<li><a href="#" class="check-link"><i class="fa fa-square-o"></i></a>';
                            echo '<span class="m-l-xs">Valor de pH en Incubadora1 es mayor a 5.00: ' . $row['ph_value'] . '</span></li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>
							</div>
						</div>
					</div>
				</div>
                <!-- foodter -->
                <?php include_once 'foodter/foodter.php';?>
				<!-- end foodter -->
			</div>
            <!-- chart -->
            <?php include_once 'chart/chart.php'; ?>
            <!-- end chart -->
		</div>
        <!-- <script> js php import</script> -->
        <?php include_once 'script/js.php';?>
		<!-- <script> import</script> -->
	</body>
</html>