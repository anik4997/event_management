<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Include Google reCAPTCHA script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Ollyo</b> event management system</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to attend an event</p>
      <form id="loginForm">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <span class="text-danger d-block" id="emailError"></span>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <span class="text-danger d-block" id="passwordError"></span>
        <!-- google recaptcha -->
        <div class="g-recaptcha" data-sitekey="6LcNDfopAAAAAPSt0hfRSPa9Modg-eCiaS7zpzXF"></div>
        <span class="text-danger d-block" id="captchaError"></span>
        <div class="row justify-content-center">
          <div class="col-8">
            <button type="submit" class="btn btn-primary btn-block mt-2" name='submit'>Sign In</button>
          </div>
          <p class="mt-2">
            <a href="add_user.php" class="text-center">Register a new membership</a>
          </p>
          <p class="mt-2">
            <a href="event_list_guest.php" class="text-center">Click to see the event list</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $("#loginForm").submit(function (e) {
      e.preventDefault(); 

      var formData = $(this).serialize(); 

      $.ajax({
        url: "login_ajax.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            window.location.href = "event_list.php"; 
          } else {
            if (response.errors.email) {
              $("#emailError").text(response.errors.email);
            }
            if (response.errors.password) {
              $("#passwordError").text(response.errors.password);
            }
            if (response.errors.captcha) {
              $("#captchaError").text(response.errors.captcha);
            }
          }
        },
        error: function () {
          alert("An error occurred. Please try again.");
        },
      });
    });
  });
</script>
</body>
</html>
