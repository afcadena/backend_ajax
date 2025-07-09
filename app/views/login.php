<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #f4f4f4;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      margin: 0;
    }

    .login-container {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 350px;
    }

    .login-container h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Iniciar Sesión</h2>

  <?php if (isset($_GET['error'])): ?>
    <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
  <?php endif; ?>

  <form method="POST" action="/backend_ajax/app/controllers/authController.php">
    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Ingresar</button>
  </form>
</div>

</body>
</html>
