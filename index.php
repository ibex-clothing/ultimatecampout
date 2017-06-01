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
  <link rel="stylesheet" type="text/css" href="styles/normalize.css" />
  <link rel="stylesheet" type="text/css" href="styles/styles.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js"></script>
  <script src="https://player.vimeo.com/api/player.js"></script>
</head>


<body>
  <div id="page">
    <header>
      <a class="enter-link" href="#enter">
        <span class="turning"></span>
        <span class="foreground"></span>
        <span class="text">Enter Now!</span>
      </a>
      <div class="block block__top">
        <div class="hero-background"></div>
        <div class="block__content">
          <div class="contest-details">
            <span>June 2 - 30, 2017</span>
          </div>
          <div class="block__center-content">
            <div class="hero-header">
              <h2 class="pre-title">What makes the</h2>
              <h1 class="logo">
                <span class="title">Ultimate Campout</span>
              </h1>
            </div>
            <div class="hero-copy">
              <span>Well-placed tents?</span>
              <span>Flawless campfires?</span>
              <span>Tidy camp kitchens?</span>
              <span>Unbelievable views?</span>
              <p>We’re on the hunt for exactly that, and to help you with your quest we’re giving away some of the best camping products in the outdoor industry.</p>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section class="block block__alt video">
      <div class="block__center">
        <div class="block__curtain">
          <div class="curtain curtain--scroll">
            <div class="curtain__left">
            </div>
            <div class="curtain__right">
            </div>
            <div class="image-wrapper">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/graphics/filmstill.png" alt="Video Trigger" />
              <div class="modal-open-wrapper">
                <button class="modal-open"><img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/graphics/play.png" alt="play" /></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



    <section class="block__text">
      <div class="block__text-content">
        <div class="headline-wrapper">
          <h2>How to Enter</h2>
        </div>
        <div class="week-desc">
          <h4>Weeks 1-3:</h4>
          <p>Fill out the Ultimate Campout contest form and automatically be entered for three prize drawings. We’ll randomly select winners at the end of each week to hike away with that week’s prize package.</p>
        </div>
        <div class="weeks-desc">
          <h4>Week 4, a.k.a. The "Camp One Collection":</h4>
          <p>Once you’ve entered by filling out the Ultimate Campout contest form, you can start tagging your favorite campsites, best setups, and sharpest views on Instagram using hashtag <a href="https://www.instagram.com/explore/tags/ultimatecampout/" target="_blank"><b>#ultimatecampout</b></a>. We’ll select and vote on the places that draw us the most, and award one lucky camper the “Camp One Collection” - a slew of prizes from our outdoor brand friends.</p>
        </div>

        <p>Weekly winners will hike away with the best camping products on the market.</p>

        <p class="slogan">It’s camp season. Make each night outside amazing!</p>
      </div>
    </section>

    <a name="enter"></a>
    <section class="block enter">
      <div class="block__center">
        <?php if($newinserted): ?>
          <div>
            <h3>Thank you!</h3>
            <p>We have received your entry for our Ultimate Campout Giveaway.</p>
            <p>The lucky winner will be notified by email on Friday, June 16.</p>
          </div>
        <?php else: ?>
          <form method="post" action="" class="entry-form">
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
                  <small>Your email address will never be <a href="http://shop.ibex.com/ibex/privacy">mis-used</a>.</small>
                  <small><a href="/terms.html">Terms &amp; Conditions</a></small>
                </div>
              </div>
            <?php endif; ?>
            <?php if($search){
              echo "<input type='hidden' name='catid' value='".$search['_id']."'/>";
            }?>
            <div class="submit-container">
              <input type="submit" id="submitbutton" class="bgbutton" value="<?=$submittext;?>">
            </div>
          </form>
        <?php endif; ?>
      </div>
    </section>

    <section class="block__text prizes">
      <div class="block__full-content">
        <div class="headline-wrapper">
          <h2>Prizes</h2>
        </div>

        <div class="prize-container">

          <article class="prize-week">
            <h3>Week 1: June 2 - 8</h3>
            <div class="prize-sticker">
              <span>Winner Announced Friday,<br> June 9</span>
            </div>
            <div class="prize">
              <a href="http://www.nemoequipment.com/product/?p=Fillo%20Luxury%20(Moss%20Green)" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/fillopillow.png" alt="NEMO Fillo Luxury">
                <span>NEMO Fillo Luxury</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/probar.png" alt="Pro Bar Variety Pack">
                <span>Pro Bar Variety Pack</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.ibex.com/merino-wool-clothing/mens-short-sleeve-shirts/m-w2-sport-t" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/ibextee.png" alt="Ibex W2 Sport T">
                <span>Ibex W2 Sport T</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/catalog/product/view/id/16277/s/autoflow-gravity-filter/category/7/" target="_blank" alt="MSR AutoFlow Gravity Filter 2L">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/msr2l.png">
                <span>MSR AutoFlow Gravity Filter 2L</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.ruffwear.com/Bivy-Bowl-Collapsible-Dog-Bowl_2?sc=2&category=10" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/ruffwearbowl.png" alt="Ruffwear Bivy Bowl">
                <span>Ruffwear Bivy Bowl</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://goodto-go.com/shop/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/GTG%20Variety.png" alt="Good To-Go Variety">
                <span>Good To-Go Variety</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.ospreypacks.com/us/en/product/daylite-DAYLITE_662.html" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/osprey.png" alt="Osprey Packs Daylite">
                <span>Osprey Packs Daylite</span>
              </a>
            </div>
          </article>
          <hr/>
          <article class="prize-week">
            <h3>Week 2: June 9 - 15</h3>
            <div class="prize-sticker">
              <span>Winner Announced Friday,<br> June 16</span>
            </div>
            <div class="prize">
              <a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/probar.png" alt="Pro Bar Variety Pack">
                <span>Pro Bar Variety Pack</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/stoves/pocketrocket-stove-kit" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/msrcook.png" alt="MSR Pocket Rocket Stove Kit">
                <span>MSR Pocket Rocket Stove Kit</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://goodto-go.com/shop/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/GTG%20Variety.png" alt="Good To-Go Variety">
                <span>Good To-Go Variety</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.ruffwear.com/Bivy-Bowl-Collapsible-Dog-Bowl_2?sc=2&category=10" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/ruffwearbowl.png" alt="Ruffwear Bivy Bowl">
                <span>Ruffwear Bivy Bowl</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.ruffwear.com/Highlands-Bed-Dog-Bed?sc=2&category=17" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/ruffwearbed.png" alt="Ruffwear Highlands Bed">
                <span>Ruffwear Highlands Bed</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.ibex.com/merino-wool-clothing/accessories-hats-gloves-bags/indie-sleep-sack" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/ibexsleepsack.png" alt="Ibex Indie Sleep Sack">
                <span>Ibex Indie Sleep Sack</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.nemoequipment.com/product/?p=Helio%20Pressure%20Shower%20(Ocean)" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/helios.png" alt="NEMO Helio Pressure Shower">
                <span>NEMO Helio Pressure Shower</span>
              </a>
            </div>
          </article>
          <hr/>
          <article class="prize-week">
            <h3>Week 3: June 16 - 22</h3>
            <div class="prize-sticker">
              <span>Winner Announced Friday,<br> June 23</span>
            </div>
            <div class="prize">
              <a href="http://www.nemoequipment.com/product/?p=Rhumba%2030%20Reg" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/sleepingbag.png" alt="NEMO Rhumba Spoon Shaped 650 Fill Power DownTek Sleeping Bag">
                <span>NEMO Rhumba Spoon Shaped 650 Fill Power DownTek Sleeping Bag</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.nemoequipment.com/product/?p=Galaxi%203P%20(Birch%20Leaf%20Green)%20%26%20Footprint" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/tent.png" alt="NEMO Galaxi 3P Backpacking Tent &amp; Footprint">
                <span>NEMO Galaxi 3P Backpacking Tent &amp; Footprint</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://demerbox.com/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/demerbox.png" alt="DEMERBOX Bluetooth Speaker">
                <span>DEMERBOX Bluetooth Speaker</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/stoves/whisperlite-universal" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/msr.png"  alt="MSR WhisperLite Universal (fuel bottles not included)">
                <span>MSR WhisperLite Universal (fuel bottles not included)</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/cookware/ceramic-2-pot-set" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/msrgtgg2.png" alt="MSR Ceramic 2-Pot Set">
                <span>MSR Ceramic 2-Pot Set</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://goodto-go.com/shop/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/GTG%20Variety.png" alt="Good To-Go Variety">
                <span>Good To-Go Variety</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.ibex.com/merino-wool-clothing/womens-short-sleeve-shirts/w-w2-kinetic-t" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/ibexwomenstee.png" alt="Ibex W’s Kinetic T">
                <span>Ibex W’s Kinetic T</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.ospreypacks.com/us/en/product/aether-ag-70-AETHER70_807.html" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/ospreyorange.png" alt="Osprey Packs Aether AG 70">
                <span>Osprey Packs Aether AG 70</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/probar.png" alt="Pro Bar Variety Pack">
                <span>Pro Bar Variety Pack</span>
              </a>
            </div>
          </article>
          <hr/>
          <article class="prize-week">
            <h3>Week 4, The "Camp One Collection"</h3>
            <div class="prize-sticker">
              <span>Winner Announced Saturday,<br> July 15</span>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/catalog/product/view/id/16140/s/windburner/category/5/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/msrwind.png" alt="MSR WindBurner Stove (fuel not included)">
                <span>MSR WindBurner Stove (fuel not included)</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://goodto-go.com/shop/" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/GTG%20Variety.png" alt="Good To-Go Variety">
                <span>Good To-Go Variety</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/probar.png" alt="Pro Bar Variety Pack">
                <span>Pro Bar Variety Pack</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.msrgear.com/water/trailshot" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/portafilter.png" alt="MSR TrailShot Pocket-Sized Water Filter">
                <span>MSR TrailShot Pocket-Sized Water Filter</span>
              </a>
            </div>
            <div class="prize">
              <a href="https://www.ospreypacks.com/us/en/product/talon-22-TALON22_550.html" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/ospreygreen.png" alt="Osprey Packs Talon 22">
                <span>Osprey Packs Talon 22</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.ruffwear.com/Highlands-Bed-Dog-Bed?sc=2&category=17" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/ruffwearbed.png" alt="Ruffwear Highlands Bed">
                <span>Ruffwear Highlands Bed</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://www.nemoequipment.com/product/?p=Apollo%203P" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/awning.png" alt="NEMO Apollo 3P Bikepacking Tent">
                <span>NEMO Apollo 3P Bikepacking Tent</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://shop.ibex.com/merino-wool-clothing/mens-long-sleeve-shirts/m-indie-hoody" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/indie.png" alt="Ibex M’s or W’s Indie Hoody">
                <span>Ibex M’s or W’s Indie Hoody</span>
              </a>
            </div>
            <div class="prize">
              <a href="http://rovrproducts.com/product/8437101315/28106360003" target="_blank">
                <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/rovr.png" alt="RovR RollR 80 Bike Edition Cooler">
                <span>RovR RollR 80 Bike Edition Cooler</span>
              </a>
            </div>
          </article>
        </div>
      </div>
    </section>




    <section class="block__text image-gallery">
      <div class="block__full-content">
        <div class="headline-wrapper">
          <h2 class="page-title">#ultimatecampout</h2>
        </div>
        <div id="photo-tiles" class="photo-tiles"></div>
      </div>
    </section>

    <footer class="block__text partners logos">
      <div class="block__text-content">
        <div class="headline-wrapper">
          <h2>Partners</h2>
        </div>
        <div class="platinum">
          <div class="partner-logo">
            <a href="http://shop.ibex.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/ibex-logo.jpg" alt="Ibex Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="http://www.nemoequipment.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/nemo.jpg" alt="Nemo Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="https://www.msrgear.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/msr.jpg" alt="MSR Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="https://www.ospreypacks.com/us/en/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/osprey-logo.jpg" alt="Osprey Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="http://www.rovrproducts.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/rovr_logo.jpg" alt="ROVR Logo"/>
            </a>
          </div>
          <div class="partner-logo">
            <a href="https://demerbox.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/DemerBox.jpg" alt="DemerBox Logo"/>
            </a>
          </div>
        </div>
        <div class="gold">
          <div  class="partner-logo">
            <a href="https://goodto-go.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/Good-To-Go-Logo.jpg" alt="Good To Go Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="http://theprobar.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/probar.jpg" alt="ProBar Logo"/>
            </a>
          </div>
          <div  class="partner-logo">
            <a href="http://www.ruffwear.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/logos/Ruffwear.jpg" alt="Ruffwear Logo"/>
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
  <div class="video-modal">
    <div class="video-modal__inner">
      <button class="modal-close">x</button>
      <iframe src="https://player.vimeo.com/video/203586797" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
  </div>
  <script src="app.js"></script>
</body>
</html>
