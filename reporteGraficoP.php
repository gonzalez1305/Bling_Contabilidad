<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Estadístico de Inventario</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Reporte Estadístico de Productos</h1>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Obtener los datos de la base de datos
            $.ajax({
                url: 'get_data_producto.php',
                method: 'GET',
                success: function (data) {
                    const parsedData = JSON.parse(data);
                    const labels = parsedData.map(item => item.categorias);
                    const values = parsedData.map(item => item.cantidad);

                    // Crear la gráfica
                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'bar', // Puedes cambiar a 'line' para una gráfica de líneas
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Cantidad Disponible por Categoria',
                                data: values,
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
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        });
    </script>
</body>
</html>
