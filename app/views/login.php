<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>

<form method="POST" action="../controllers/authController.php">
    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Contraseña:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Iniciar sesión</button>
</form>

</body>
</html>
