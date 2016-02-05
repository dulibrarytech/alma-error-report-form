<?php
/**
 * Created by PhpStorm.
 * User: kvu
 * Date: 10/17/2014
 * Time: 3:54 PM
 */
require_once 'src/OpenURL/ContextObject.php';
require_once 'src/OpenURL/Entity.php';
require_once('src/recaptchalib.php');
include('config/settings.php');

$openurlraw = $_SERVER['QUERY_STRING'];
$description = $first_name = $last_name = $phone = $email = $summary = "";
$to = $from = $subject = "";
$body = "";
$submitted = "";
$recaptcha_failed = "";

$privatekey = $publickey = "";
# the error code from reCAPTCHA, if any
$error = null;
# the response from reCAPTCHA
$resp = null;

$privatekey = $recaptcha_private_key;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Let's save input data so that when a data-validation failure should occur, we can pre-fill the forms
    $description = $_POST["description"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $summary = $_POST["summary"];
    if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {
            // Will need to pre-fill form.  (Note: This form, except reCAPTCHA, is validated using jQuery from http://ajax.aspnetcdn.com.)
            $recaptcha_failed = 'Please retry.  reCAPTCHA said: "' . $resp->error . '"';
        }
        else {
            // Validate input data
            $description = test_input($_POST["description"]);
            $first_name = test_input($_POST["first_name"]);
            $last_name = test_input($_POST["last_name"]);
            $phone = test_input($_POST["phone"]);
            $email = test_input($_POST["email"]);
            $summary = test_input($_POST["summary"]);
            $openurlclean = test_input($openurlraw);
            $openurlclean = str_replace("amp;", "", $openurlclean);
            // Send email
            $body = compose_mail($description, $first_name, $last_name, $phone, $email, $summary, $openurlraw, $openurlclean);
            $to = $email_destinations;
            $subject = "Link Resolver Problem: " . $summary;
            mail($to, $subject, $body, "From: {$email}");
            $submitted = "<div class=\"alert alert-success\" role=\"alert\">Thank you for reporting this issue. We will respond to you as soon as possible.</div>";
            // Clear input data when submitted
            $description = $first_name = $last_name = $phone = $email = $summary = "";
        }
    }
    else {
        // Will need to pre-fill form (Note: This form, except reCAPTCHA, is validated using jQuery from http://ajax.aspnetcdn.com.)
        $recaptcha_failed = 'This field is a required.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Report a Problem</title>
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.0/flatly/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
  <style type="text/css">
    label.valid {
      display: none !important;
    }
    label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }
    h4 {
        color: red;
    }
    em {
      color: red;
    }
  </style>
  <script type="text/javascript">
    var RecaptchaOptions = {
      theme : 'clean'
    };
  </script>
</head>
<body>
  <div class="container">
    <div class="row">
      <h1>Report a Problem</h1>
      <?php echo $submitted; ?>
      <div class="col-md-7 well">
        <form role="form" method="post" action="" id="contact-form" name="contact-form" class="form-horizontal">
          <div class="form-group has-feedback">
            <label class="control-label col-md-2" for="summary">Summary <em>*</em></label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="summary" name="summary" value="<?php echo $summary; ?>" placeholder="">
              <i class="fa form-control-feedback"></i>
            </div>
          </div>
          <div class="form-group has-feedback">
            <label class="control-label col-md-2" for="description">Description <em>*</em></label>
            <div class="col-md-10">
              <textarea class="form-control" id="description" name="description" rows="5"><?php echo $description; ?></textarea>
              <i class="fa form-control-feedback"></i>
            </div>
          </div>
          <div class="form-group has-feedback">
            <label class="control-label col-md-2" for="email">E-mail <em>*</em></label>
            <div class="col-md-7">
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="">
              <i class="fa form-control-feedback"></i>
            </div>
          </div>
          <div class="form-group has-feedback">
            <label class="control-label col-md-2">Name </label>
            <div class="col-md-4">
              <label class="control-label sr-only" for="first_name">First</label>
              <input type="text" class="form-control " id="first_name" name="first_name" placeholder="First Name" value="<?php echo $first_name; ?>">
              <i class="fa form-control-feedback"></i>
            </div>
            <div class="col-md-5">
              <label class="control-label sr-only" for="last_name">Last</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name">
              <i class="fa form-control-feedback"></i>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="phone">Phone </label>
            <div class="col-md-5">
              <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="">
              <i class="fa form-control-feedback"></i>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label sr-only" for="openURL"></label>
            <input type="hidden" name="openURL" value="<?php echo $openurlraw; ?>">
          </div>
          <div class="form-group">
            <label class="control-label col-md-2" for="recaptcha">Help stop spam<em>*</em></label>
            <div class="col-md-10">
              <?php
                $publickey = $recaptcha_public_key;
                echo recaptcha_get_html($publickey, $error, "true");
              ?>
              <h4><?php echo $recaptcha_failed;?></h4>
            </div>
          </div>
          <div class="col-md-10 col-md-offset-2">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <div class="col-md-5">
      </div>
    </div>
  </div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#contact-form').validate(
        {
          rules: {
            email: {
              required: true,
              email: true
            },
            description: {
              required: true
            },
            summary: {
              required: true
            },
            recaptcha: { // jQuery.validate from http://ajax.aspnetcdn.com does not work with recaptcha.
              required: true
            }
          },
          highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            $(element).closest('.form-control-feedback').removeClass('fa-check').addClass('fa-remove');
          },
          success: function (element) {
            $(element).addClass('valid').closest('.form-group').removeClass('error').addClass('success has-success');
            $(element).closest('.form-control-feedback').removeClass('fa-remove').addClass('fa-check');
          }
        });
    });
  </script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '<?php echo $google_analytics_id;?>', 'auto');
    ga('send', 'pageview');

  </script>
</body>
</html>
<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function compose_mail($description, $first_name, $last_name, $phone, $email, $summary, $openurlraw, $openurlclean) {
    $body = "";
    $body = $body . "Sender: \t\t" . $first_name . " " . $last_name . "\n\n";
    $body = $body . "Sender Contact Info: \n\n";
    $body = $body . "Email: \t" . $email . "\n\n";
    $body = $body . "Phone: \t" . $phone . "\n\n";
    $body = $body . "Summary: \t\t" . $summary . "\n\n";
    $body = $body . "Description: \t\t" . $description . "\n\n";
    $body = $body . "OpenURL: \t\thttp://primo-pmtna01.hosted.exlibrisgroup.com/openurl/CALS_USM/cals_usm_services_page?debug=true&" . $openurlclean . "\n\n";
    $body = $body . "IP Address: \t\t" . $_SERVER['REMOTE_ADDR'] . "\n\n";
    $body = $body . "User Agent [Browser]: \t\t" . $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    $body = $body . "Project: link-resolver\n\n";
    $body = $body . "Tracker: Bug\n\n";
    $body = primo_retrieval($body, $openurlraw);
    return $body;
}
function primo_retrieval($body, $openurlraw) {
    if(empty($openurlraw)) {
        $body = $body . "OpenURL is empty\n";
    } else {
        $ctx = \OpenURL\ContextObject::loadKev($openurlraw);
        $body = $body . "Genre: " . $ctx->getReferent()->getValue('genre') . "\n";
        $body = $body . "Journal Title: " . $ctx->getReferent()->getValue('jtitle') . "\n";
        $body = $body . "Book Title: " . $ctx->getReferent()->getValue('title') . "\n";
        $body = $body . "Article Title: " . $ctx->getReferent()->getValue('atitle') . "\n";
        $body = $body . "spage: " . $ctx->getReferent()->getValue('spage') . "\n";
        $body = $body . "isbn: " . $ctx->getReferent()->getValue('isbn') . "\n";
        $body = $body . "stitle: " . $ctx->getReferent()->getValue('stitle') . "\n";
        $body = $body . "btitle: " . $ctx->getReferent()->getValue('btitle') . "\n";
        $body = $body . "year: " . $ctx->getReferent()->getValue('year') . "\n";
        $body = $body . "issue: " . $ctx->getReferent()->getValue('issue') . "\n";
        $body = $body . "Author: " . $ctx->getReferent()->getValue('au') . "\n";
        $body = $body . "Volume: " . $ctx->getReferent()->getValue('volume') . "\n";
        $body = $body . "rfr_id: " . $ctx->getReferent()->getValue('rfr_id') . "\n";
        $body = $body . $ctx->toKev();
    }
    return $body;
}

?>
