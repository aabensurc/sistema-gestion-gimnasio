<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Gimnasio | Login</title>

  <!-- Google Fonts: Poppins (Modern & Clean) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }

    .login-box {
      width: 400px;
      padding: 0 20px;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      overflow: hidden;
    }

    .card-header {
      background: transparent;
      border-bottom: none;
      padding-top: 30px;
      text-align: center;
    }

    .card-header img {
      max-width: 100px;
      border-radius: 50%;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 15px;
    }

    .card-header h3 {
      color: #333;
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 5px;
    }

    .card-header p {
      color: #777;
      font-size: 14px;
    }

    .card-body {
      padding: 30px 40px;
    }

    .form-control {
      background-color: #f5f6fa;
      border: 2px solid transparent;
      border-radius: 10px;
      height: 50px;
      padding-left: 20px;
      font-size: 14px;
      transition: all 0.3s;
    }

    .form-control:focus {
      background-color: #fff;
      border-color: #764ba2;
      box-shadow: none;
    }

    .input-group {
      margin-bottom: 20px;
      position: relative;
    }

    .input-group-text {
      background: transparent;
      border: none;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      z-index: 10;
    }

    .btn-login {
      background: linear-gradient(to right, #667eea, #764ba2);
      border: none;
      border-radius: 10px;
      height: 50px;
      font-weight: 600;
      font-size: 16px;
      letter-spacing: 0.5px;
      box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(118, 75, 162, 0.6);
      background: linear-gradient(to right, #5a6fd1, #6b4394);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    /* Loader styles */
    #global-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      z-index: 9999;
      display: none;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #764ba2;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 15px;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

</head>

<body class="hold-transition login-page">

  <!-- GLOBAL SPINNER -->
  <div id="global-loader">
    <div class="spinner"></div>
    <div style="color: #764ba2; font-weight: 600;">Iniciando sesión...</div>
  </div>

  <div class="login-box">
    <?php
    session_start();
    if (isset($_SESSION['mensaje'])) {
      $respuesta = $_SESSION['mensaje']; ?>
      <script>
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "<?php echo $respuesta; ?>",
          showConfirmButton: false,
          timer: 1500
        });
      </script>
      <?php
      $_SESSION['mensaje'] = null;
    }
    ?>

    <div class="card">
      <div class="card-header">
        <img src="../public/images/iconjpg.jpg" alt="Logo Gimnasio">
        <h3>Bienvenido</h3>
        <p>Ingresa a tu cuenta para continuar</p>
      </div>

      <div class="card-body">
        <form action="../app/controllers/login/ingreso.php" method="post" id="loginForm">
          <div class="input-group">
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>

          <div class="input-group">
            <input type="password" name="password_user" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block btn-login">Ingresar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <script src="../public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function () {
      // Input interaction effects
      $('.form-control').on('focus', function () {
        $(this).parent().find('.input-group-text').css('color', '#764ba2');
      }).on('blur', function () {
        $(this).parent().find('.input-group-text').css('color', '#aaa');
      });

      $('#loginForm').on('submit', function () {
        if (this.checkValidity()) {
          $('#global-loader').css('display', 'flex');
        }
      });
    });
  </script>

</body>

</html>