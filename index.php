<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', true);

$docRoot = getenv("DOCUMENT_ROOT");

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['email'] != '') {

  //Get our printcatalogs db
  $m = new Mongo('mongodb://ibexProc:1bexw00l@localhost/admin');
  $db = $m->ibexcustomer;
  $ultimatecampoutcoll = $db->ultimatecampout;


  //are they in our database already?
  $search = $ultimatecampoutcoll->findOne(
    array('$or' => array(
      array('email' => $_POST['email']),
      array(
        "name.first" => $_POST['firstname'],
        "name.last" => $_POST['lastname']
      )
    )
  )
);


if(!$search){  //we didn't find them in our database

  $obj = array(
    "name" => array(
      "first"=>trim($_POST['firstname']),
      "last"=>trim($_POST['lastname'])
    ),
    "zip" => trim($_POST['zip']),
    "email" => trim($_POST['email']),
    "date" => date("Y-m-d"),
    "dateadded" => new MongoDate(),
  );
  $newinserted = $ultimatecampoutcoll->insert($obj);
}

}

if($search){
  $submittext = 'Update Details!';
}
else {
  $submittext = 'Sign Up!';
}

?>

<!DOCTYPE HTML>
<html lang = "en">
<head>
  <meta http-equiv="Content-Type" charset = "UTF-8" />
  <title>Ultimate Campout Contest</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="https://cloud.typography.com/6961376/7346172/css/fonts.css" />
  <link rel="stylesheet" type="text/css" href="styles/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="styles/bootstrap-theme.css" />
  <link rel="stylesheet" type="text/css" href="styles/styles.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>
<body>

  <div class="background" style="background-image: url('http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/heros/views-green.jpg')">
  </div>
  <div class="container">
    <div class="header">
      <img class="contest-logo" src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/contestlogo.png" alt="Contest Logo" />
      <h1 class="page-title hidden">Ultimate Campout</h1>
    </div>

    <section class="video-container">
      <iframe src="https://player.vimeo.com/video/203586797" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </section>

    <section class="info">
      <div class="body-text-container">
        <h2>What makes the Ultimate Campout?</h2>
        <p class="body-text">Well-placed tents?<br>
          Flawless campfires?<br>
          Tidy camp kitchens?<br>
          Unbelievable views?</p>
        <p class="body-text">We’re on the hunt for exactly that, and to help you with your quest we’re giving away some of the best camping products in the outdoor industry.</p>
        <h2 class="page-title">How to Enter</h2>

        <h4>Weeks 1-3:</h4>
        <p class="body-text">Fill out the Ultimate Campout contest form and automatically be entered for three prize drawings. We’ll randomly select winners at the end of each week to hike away with that week’s prize package.</p>

        <h4>Week 4:</h4>
        <p class="body-text">Once you’ve entered by filling out the Ultimate Campout contest form, you can start tagging your favorite campsites, best setups, and sharpest views on Instagram using hashtag #ultimatecampout. We’ll select and vote on the places that draw us the most, and award one lucky camper the “Camp One Collection” - a slew of prizes from our outdoor brand friends.</p>

        <p class="body-text">Weekly winners will hike away with the best camping products on the market.</p>

        <p class="body-text">It’s camp season. Make each night outside amazing!</p>
      </div>
    </section>

    <section class="entry-form body-text-container">
      <?php if($newinserted): ?>
        <div>
          <h3>Thank you!</h3>
          <p>We have received your entry for our Ultimate Campout Giveaway.</p>
          <p>The lucky winner will be notified by email on Friday, June 16.</p>
        </div>
      <?php else: ?>
        <form method="post" action="">
          <?php if($search){
            echo '<div class="alert">You already signed up for this contest!.</div>';
          }?>
          <div class="form-group">
            <label>First Name<span class="required">*</span></label>
            <div>
              <input type="text" data-validate="notEmpty" class="form-control" name='firstname' value='<?=$search['name']['first'] ?>' />
            </div>
          </div>
          <div class="form-group">
            <label>Last Name<span class="required">*</span></label>
            <div>
              <input type="text" data-validate="notEmpty"  name='lastname' class="form-control" value='<?=$search['name']['last'] ?>' />
            </div>
          </div>
          <div class="form-group">
            <label>Zip<span class="required">*</span></label>
            <div>
              <input data-validate="zipCode" type="text" name='zip' class="form-control" value='<?=$search['address']['zip'] ?>' />
            </div>
          </div>

          <?php if(!isset($_GET['unsubscribe'])): ?>
            <div class="form-group">
              <label>Email Address<span class="required">*</span></label>
              <div>
                <input type="text" placeholder="sue@example.com" name='email' class="form-control" value='<?=$search['email'] ?>' />
                <h5><small style="color:#999">Your email address will never be <a href="http://shop.ibex.com/ibex/privacy">mis-used</a>.</small></h5>
              </div>
            </div>
          <?php endif; ?>
          <?php if($search){
            echo "<input type='hidden' name='catid' value='".$search['_id']."'/>";
          }?>
          <div class="col-xs-12 submit-container">
            <input type="submit" id="submitbutton" class="bgbutton" value="<?=$submittext;?>">
          </div>
        </form>
        <div class="row bottom"></div>
      <?php endif; ?>
    </section>

    <section class="row instagram-container">
      <h2 class="page-title">#ultimatecampout</h2>
      <div id="photo-tiles" class="photo-tiles"></div>
    </section>
  </div>

  <section class="logos">
    <div class="container">
      <div class="row">
        <div class='logo col-xs-4 col-sm-2 col-md-2'>
          <a href="https://demerbox.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/DemerBox.jpg" alt="DemerBox Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-1'>
          <a href="https://goodto-go.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/Good-To-Go-Logo.jpg" alt="Good To Go Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-2'>
          <a href="https://www.msrgear.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/msr.jpg" alt="MSR Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-3'>
          <a href="http://www.nemoequipment.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/nemo.jpg" alt="Nemo Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-1'>
          <a href="http://theprobar.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/probar.jpg" alt="ProBar Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-2'>
          <a href="http://www.rovrproducts.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/rovr_logo.jpg" alt="ROVR Logo"/>
          </a>
        </div>
        <div class='logo col-xs-4 col-sm-2 col-md-1'>
          <a href="http://www.ruffwear.com/" target="_blank">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/Ruffwear.jpg" alt="Ruffwear Logo"/>
          </a>
        </div>
      </div>
    </div>
  </section>

  <script src="app.js"></script>
</body>
</html>
