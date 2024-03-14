<?php
// Este archivo contiene una vulnerabilidad de XSS y una vulnerabilidad de SQLi

// Conexión a la base de datos (simulada para demostración)
$servername = "localhost";
$username = "usuario";
$password = "contraseña";
$dbname = "basededatos";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener el parámetro 'id' de la URL
$id = $_GET['id']; // Vulnerabilidad de SQLi aquí (línea 17)

// Consulta SQL vulnerable a inyección SQL
$sql = "SELECT * FROM usuarios WHERE id=" . $id; // Vulnerabilidad de SQLi aquí (línea 20)
$result = mysqli_query($conn, $sql);

// Mostrar los datos de la consulta
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row["id"] . " - Nombre: " . $row["nombre"] . "<br>";
    }
} else {
    echo "0 resultados";
}

// Cerrar conexión
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerabilidad de XSS</title>
</head>
<body>
    <!-- Vulnerabilidad de XSS aquí (línea 39) -->
    <h2>Este es un ejemplo de una vulnerabilidad de XSS</h2>
    <p>El parámetro id=<?php echo $id; ?> no está siendo sanitizado adecuadamente.</p>
    <script>
        // Ejemplo de código JavaScript malicioso que podría ser inyectado
        alert("¡Este sitio web es vulnerable a XSS!");
    </script>
</body>
</html>
