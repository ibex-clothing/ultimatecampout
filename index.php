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
</head>


<body>
  <div id="page">
    <header>
      <a class="enter-link" href="#enter">
        <span class="turning"></span>
        <span>Enter Now</span>
      </a>
      <div class="block block__top">
        <div class="block__content">
          <div class="contest-details">
            <span>June 2 - 30, 2017</span>
          </div>
          <div class="block__center-content">
            <h2>What makes the</h2>
            <h1>
              <a class="logo" href="/">Ultimate Campout</a>
            </h1>
            <span>Well-placed tents?</span>
            <span>Flawless campfires?</span>
            <span>Tidy camp kitchens?</span>
            <span>Unbelievable views?</span>
            <p>We’re on the hunt for exactly that, and to help you with your quest we’re giving away some of the best camping products in the outdoor industry.</p>
          </div>
          <div id="arrow-down"></div>
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
            <iframe src="https://player.vimeo.com/video/203586797" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </section>



    <section class="block__text">
      <div class="block__text-content">
        <h3>How to Enter</h3>
        <div class="week-desc">
          <h4>Weeks 1-3:</h4>
          <p>Fill out the Ultimate Campout contest form and automatically be entered for three prize drawings. We’ll randomly select winners at the end of each week to hike away with that week’s prize package.</p>
        </div>
        <div class="weeks-desc">
          <h4>Week 4:</h4>
          <p>Once you’ve entered by filling out the Ultimate Campout contest form, you can start tagging your favorite campsites, best setups, and sharpest views on Instagram using hashtag <a href="https://www.instagram.com/explore/tags/ultimatecampout/" target="_blank"><b>#ultimatecampout</b></a>. We’ll select and vote on the places that draw us the most, and award one lucky camper the “Camp One Collection” - a slew of prizes from our outdoor brand friends.</p>
        </div>

        <p>Weekly winners will hike away with the best camping products on the market.</p>

        <p class="slogan">It’s camp season. Make each night outside amazing!</p>
      </div>
    </section>

    <a name="enter"></a>
    <section class="block enter">
      <div class="block__center">
        <h3>Entry Form</h3>
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
      <div class="block__prize-content">
        <h3>Prizes</h3>

        <div class="prize-container">
          <div class="prize-buttons">
            <span>Select a week to see prizes</span>
            <button class="prize-button" data-week="1">Week 1</button>
            <button class="prize-button" data-week="2">Week 2</button>
            <button class="prize-button" data-week="3">Week 3</button>
            <button class="prize-button" data-week="4">Week 4</button>
            <button class="prize-button-all">All Prizes</button>
          </div>

          <div class="prize" data-prizeweek="1">
            <a href="http://www.nemoequipment.com/product/?p=Fillo%20Luxury%20(Moss%20Green)" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/fillopillow.png" alt="NEMO Fillo Luxury">
              <span>NEMO Fillo Luxury</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="1234">
            <a href="https://goodto-go.com/shop/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/GTG%20Variety.png" data-week="1" alt="Good To-Go Variety">
              <span>Good To-Go Variety</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="1">
            <a href="http://shop.ibex.com/merino-wool-clothing/mens-short-sleeve-shirts/m-w2-sport-t" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/ibextee.png" data-week="1" alt="Ibex W2 Sport T">
              <span>Ibex W2 Sport T</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="1">

            <a href="https://www.msrgear.com/catalog/product/view/id/16277/s/autoflow-gravity-filter/category/7/" target="_blank" alt="MSR AutoFlow Gravity Filter 2L">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/msr2l.png" data-week="1">
              <span>MSR AutoFlow Gravity Filter 2L</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="1">
            <a href="https://www.ospreypacks.com/us/en/product/daylite-DAYLITE_662.html" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/osprey.png" data-week="1" alt="Osprey Packs Daylite">
              <span>Osprey Packs Daylite</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="1234">
            <a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/probar.png" data-week="1" alt="Pro Bar Variety Pack">
              <span>Pro Bar Variety Pack</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="12">
            <a href="http://www.ruffwear.com/Bivy-Bowl-Collapsible-Dog-Bowl_2?sc=2&category=10" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week1/ruffwearbowl.png" data-week="1" alt="Ruffwear Bivy Bowl">
              <span>Ruffwear Bivy Bowl</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="2">
            <a href="http://www.nemoequipment.com/product/?p=Helio%20Pressure%20Shower%20(Ocean)" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/helios.png" data-week="2" alt="NEMO Helio Pressure Shower">
              <span>NEMO Helio Pressure Shower</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="2">
            <a href="http://shop.ibex.com/merino-wool-clothing/accessories-hats-gloves-bags/indie-sleep-sack" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/ibexsleepsack.png" data-week="2" alt="Ibex Indie Sleep Sack">
              <span>Ibex Indie Sleep Sack</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="2">
            <a href="https://www.msrgear.com/stoves/pocketrocket-stove-kit" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/msrcook.png" data-week="2" alt="MSR Pocket Rocket Stove Kit">
              <span>MSR Pocket Rocket Stove Kit</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="24">
            <a href="http://www.ruffwear.com/Highlands-Bed-Dog-Bed?sc=2&category=17" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week2/ruffwearbed.png" data-week="2" alt="Ruffwear Highlands Bed">
              <span>Ruffwear Highlands Bed</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="https://demerbox.com/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/demerbox.png" data-week="3" alt="DEMERBOX Bluetooth Speaker">
              <span>DEMERBOX Bluetooth Speaker</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="http://shop.ibex.com/merino-wool-clothing/womens-short-sleeve-shirts/w-w2-kinetic-t" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/ibexwomenstee.png" data-week="3" alt="Ibex W’s Kinetic T">
              <span>Ibex W’s Kinetic T</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="https://www.msrgear.com/stoves/whisperlite-universal" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/msr.png" data-week="3"  alt="MSR WhisperLite Universal (fuel bottles not included)">
              <span>MSR WhisperLite Universal (fuel bottles not included)</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="https://www.msrgear.com/cookware/ceramic-2-pot-set" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/msrgtgg2.png" data-week="3" alt="MSR Ceramic 2-Pot Set">
              <span>MSR Ceramic 2-Pot Set</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="https://www.ospreypacks.com/us/en/product/aether-ag-70-AETHER70_807.html" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/ospreyorange.png" data-week="3" alt="Osprey Packs Aether AG 70">
              <span>Osprey Packs Aether AG 70</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="http://www.nemoequipment.com/product/?p=Rhumba%2030%20Reg" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/sleepingbag.png" data-week="3" alt="NEMO Rhumba Spoon Shaped 650 Fill Power DownTek Sleeping Bag">
              <span>NEMO Rhumba Spoon Shaped 650 Fill Power DownTek Sleeping Bag</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="3">
            <a href="http://www.nemoequipment.com/product/?p=Galaxi%203P%20(Birch%20Leaf%20Green)%20%26%20Footprint" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week3/tent.png" data-week="3" alt="NEMO Galaxi 3P Backpacking Tent &amp; Footprint">
              <span>NEMO Galaxi 3P Backpacking Tent &amp; Footprint</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="http://www.nemoequipment.com/product/?p=Apollo%203P" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/awning.png" data-week="4" alt="NEMO Apollo 3P Bikepacking Tent">
              <span>NEMO Apollo 3P Bikepacking Tent</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="http://shop.ibex.com/merino-wool-clothing/mens-long-sleeve-shirts/m-indie-hoody" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/indie.png" data-week="4" alt="Ibex M’s or W’s Indie Hoody">
              <span>Ibex M’s or W’s Indie Hoody</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="https://www.msrgear.com/catalog/product/view/id/16140/s/windburner/category/5/" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/msrwind.png" data-week="4" alt="MSR WindBurner Stove (fuel not included)">
              <span>MSR WindBurner Stove (fuel not included)</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="https://www.ospreypacks.com/us/en/product/talon-22-TALON22_550.html" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/ospreygreen.png" data-week="4" alt="Osprey Packs Talon 22">
              <span>Osprey Packs Talon 22</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="https://www.msrgear.com/water/trailshot" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/portafilter.png" data-week="4" alt="MSR TrailShot Pocket-Sized Water Filter">
              <span>MSR TrailShot Pocket-Sized Water Filter</span>
            </a>
          </div>

          <div class="prize" data-prizeweek="4">
            <a href="http://rovrproducts.com/product/8437101315/28106360003" target="_blank">
              <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/week4/rovr.png" data-week="4" alt="RovR RollR 80 Bike Edition Cooler">
              <span>RovR RollR 80 Bike Edition Cooler</span>
            </a>
          </div>

<!--

          <div class="prize open">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/Week1.jpg" alt="Week 1 Prizes" class="prize-image"/>
            <ul>
              <li><a href="http://www.nemoequipment.com/product/?p=Fillo%20Luxury%20(Moss%20Green)" target="_blank">NEMO Fillo Luxury
            </a></li>
              <li><a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">Pro Bar Variety Pack</a></li>
              <li><a href="http://shop.ibex.com/merino-wool-clothing/mens-short-sleeve-shirts/m-w2-sport-t" target="_blank">Ibex W2 Sport T</a></li>
              <li><a href="https://www.msrgear.com/catalog/product/view/id/16277/s/autoflow-gravity-filter/category/7/" target="_blank">MSR AutoFlow Gravity Filter 2L</a></li>
              <li><a href="http://www.ruffwear.com/Bivy-Bowl-Collapsible-Dog-Bowl_2?sc=2&category=10" target="_blank">Ruffwear Bivy Bowl</a></li>
              <li><a href="https://goodto-go.com/shop/" target="_blank">Good To-Go Variety</a></li>
              <li><a href="https://www.ospreypacks.com/us/en/product/daylite-DAYLITE_662.html" target="_blank">Osprey Packs Daylite</a></li>
            </ul>
          </div>

          <div class="prize">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/Week2.jpg" alt="Week 2 Prizes" class="prize-image"/>
            <ul>
              <li><a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">Pro Bar Variety Pack</a></li>
              <li><a href="https://www.msrgear.com/stoves/pocketrocket-stove-kit" target="_blank">MSR Pocket Rocket Stove Kit</a></li>
              <li><a href="https://goodto-go.com/shop/" target="_blank">Good To-Go Variety</a></li>
              <li><a href="http://www.ruffwear.com/Bivy-Bowl-Collapsible-Dog-Bowl_2?sc=2&category=10" target="_blank">Ruffwear Bivy Bowl</a></li>
              <li><a href="http://www.ruffwear.com/Highlands-Bed-Dog-Bed?sc=2&category=17" target="_blank">Ruffwear Highlands Bed</a></li>
              <li><a href="http://shop.ibex.com/merino-wool-clothing/accessories-hats-gloves-bags/indie-sleep-sack" target="_blank">Ibex Indie Sleep Sack</a></li>
              <li><a href="http://www.nemoequipment.com/product/?p=Helio%20Pressure%20Shower%20(Ocean)" target="_blank">NEMO Helio Pressure Shower</a></li>
            </ul>
          </div>

          <div class="prize">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/Week3.jpg" alt="Week 3 Prizes" class="prize-image"/>
            <ul>
              <li><a href="http://www.nemoequipment.com/product/?p=Rhumba%2030%20Reg" target="_blank">NEMO Rhumba Spoon Shaped 650 Fill Power DownTek Sleeping Bag</a></li>
              <li><a href="http://www.nemoequipment.com/product/?p=Galaxi%203P%20(Birch%20Leaf%20Green)%20%26%20Footprint" target="_blank">NEMO Galaxi 3P Backpacking Tent &amp; Footprint</a></li>
              <li><a href="https://demerbox.com/" target="_blank">DEMERBOX Bluetooth Speaker</a></li>
              <li><a href="https://www.msrgear.com/stoves/whisperlite-universal" target="_blank">MSR WhisperLite Universal (fuel bottles not included)</a></li>
              <li><a href="https://www.msrgear.com/cookware/ceramic-2-pot-set" target="_blank">MSR Ceramic 2-Pot Set</a></li>
              <li><a href="https://goodto-go.com/shop/" target="_blank">Good To-Go Variety</a></li>
              <li><a href="http://shop.ibex.com/merino-wool-clothing/womens-short-sleeve-shirts/w-w2-kinetic-t" target="_blank">Ibex W’s Kinetic T</a></li>
              <li><a href="https://www.ospreypacks.com/us/en/product/aether-ag-70-AETHER70_807.html" target="_blank">Osprey Packs Aether AG 70</a></li>
              <li><a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">Pro Bar Variety Pack</a></li>
            </ul>
          </div>

          <div class="prize">
            <img src="http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/prizes/Week4.jpg" alt="Week 4 Prizes" class="prize-image"/>
            <ul>
              <li><a href="https://www.msrgear.com/catalog/product/view/id/16140/s/windburner/category/5/" target="_blank">MSR WindBurner Stove (fuel not included)</a></li>
              <li><a href="https://goodto-go.com/shop/" target="_blank">Good To-Go Variety</a></li>
              <li><a href="http://shop.theprobar.com/Products/PROBAR-Variety-Packs" target="_blank">Pro Bar Variety Pack</a></li>
              <li><a href="https://www.msrgear.com/water/trailshot" target="_blank">MSR TrailShot Pocket-Sized Water Filter</a></li>
              <li><a href="https://www.ospreypacks.com/us/en/product/talon-22-TALON22_550.html" target="_blank">Osprey Packs Talon 22</a></li>
              <li><a href="http://www.ruffwear.com/Highlands-Bed-Dog-Bed?sc=2&category=17" target="_blank">Ruffwear Highlands Bed</a></li>
              <li><a href="http://www.nemoequipment.com/product/?p=Apollo%203P" target="_blank">NEMO Apollo 3P Bikepacking Tent</a></li>
              <li><a href="http://shop.ibex.com/merino-wool-clothing/mens-long-sleeve-shirts/m-indie-hoody" target="_blank">Ibex M’s or W’s Indie Hoody</a></li>
              <li><a href="http://rovrproducts.com/product/8437101315/28106360003" target="_blank">RovR RollR 80 Bike Edition Cooler</a></li>
            </ul>
          </div> -->
          <div class="smoke"></div>
        </div>
      </div>
    </section>




    <section class="image-gallery">
      <!-- <section class="row instagram-container">
        <h2 class="page-title">#ultimatecampout</h2>
        <div id="photo-tiles" class="photo-tiles"></div>
      </section> -->
    </section>

    <footer class="block__text partners logos">
      <div class="block__text-content">
        <h3>Partners</h3>
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
  <script src="app.js"></script>
</body>
</html>
