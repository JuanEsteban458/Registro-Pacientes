<?php
include '../includes/auth.php';
include '../includes/conexion.php';

if (!isset($_GET['id'])) {
    die("ID del paciente no recibido.");
}

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clave = $_POST['clave'];
    $usuario = $_SESSION['usuario'];

    $stmt = $conn->prepare("SELECT contraseña_hash FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($datos && password_verify($clave, $datos['contraseña_hash'])) {
        header("Location: eliminar.php?id=$id");
        exit;
    } else {
        $mensaje = "Contraseña incorrecta. No se puede eliminar el paciente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/eliminar.css">
</head>

<body>

    <h2>Confirmar Eliminación de Paciente</h2>

    <?php if ($mensaje): ?>
        <p class="mensaje error"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Ingresa tu contraseña:</label>
        <input type="password" name="clave" required>
        <br><br>
        <button type="submit">Eliminar Paciente</button>
        <a href="listar.php" class="btn-volver">Cancelar</a>
    </form>

    <script>
        const TIEMPO_MAX_INACTIVIDAD = 15 * 60 * 1000;
        let temporizador;

        function cerrarSesion() {
            window.location.href = '../logout.php?mensaje=sesion_expirada';
        }

        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(cerrarSesion, TIEMPO_MAX_INACTIVIDAD);
        }
        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeydown = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
        document.onscroll = reiniciarTemporizador;
    </script>

</body>
</html>