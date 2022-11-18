<!doctype html>
<html>
<?php 

require_once('include/polyfill.php');
require_once('include/config.php');
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gigamot Charger Lounge</title>
  <link rel="icon" type="image/png" href="<?=$config['logofile']?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link href="css/folders.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

  <?php

  date_default_timezone_set($config['timezone']);
  require_once('include/DB.php');

  $captcha_error = false;

  if (!isset($_POST['inputPhone'])) {
    require_once('include/start.php');
  } else {
    if (!CheckCaptcha()) {
      $captcha_error = true;
      require_once('include/start.php');
    } else {

      $phoneID = $DB->PhoneKnown($_POST['inputPhone']);

      if ($phoneID > 0) {
        if ($DB->HasValidCode($phoneID)) {
          require_once('include/hasCode.php');
        } else {
          require_once('include/sendCode.php');
        }
      } else {
        require_once('include/sendCode.php');
      }
    }
  }

  function CheckCaptcha()
  {
    global $config;
    if (isset($_POST['g-recaptcha-response'])) {
      $captcha = $_POST['g-recaptcha-response'];
    }
    if (!$captcha) {
      return false;
    }
    $ip = $_SERVER['REMOTE_ADDR'];

    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($config['recaptcha_secret']) . '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {

      if ($responseKeys["error-codes"][0] == "timeout-or-duplicate") {
        return true;
      }

      return false;
    }
    return true;
  }

  ?>
  <input  class="fixed-bottom btn btn-lg btn-primary btn-block squared_btn" type="button" id="switch-lang" value="Switch Language" />
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
    crossorigin="anonymous"></script>

  <script>
    $('[lang="en"]').hide();

    $('#switch-lang').click(function () {
      $('[lang="de"]').toggle();
      $('[lang="en"]').toggle();
    });
  </script>
</body>

</html>