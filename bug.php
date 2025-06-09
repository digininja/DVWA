<?php
// Este archivo contiene remediaciones para vulnerabilidades de XSS y SQLi

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

// Obtener el parámetro 'id' de la URL de forma segura
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validar que sea un número entero
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID inválido");
}

// Usar prepared statements para evitar SQL Injection
$stmt = mysqli_prepare($conn, "SELECT * FROM usuarios WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Mostrar los datos de la consulta
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Escapar salida para evitar XSS
        echo "ID: " . htmlspecialchars($row["id"]) . " - Nombre: " . htmlspecialchars($row["nombre"]) . "<br>";
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
    <title>Ejemplo seguro</title>
</head>
<body>
    <h2>Remediación de XSS aplicada</h2>
    <p>El parámetro id=<?php echo htmlspecialchars($id); ?> ha sido validado y escapado correctamente.</p>
</body>
</html>
