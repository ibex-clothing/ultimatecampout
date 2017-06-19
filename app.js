function getRandomNumber(max) {
  return Math.ceil(Math.random()*max);
}

function getRandomNumbers(needed,max) {
  var arr = []
  while(arr.length < needed){
    var randomnumber = getRandomNumber(max);
    if(arr.indexOf(randomnumber) > -1) continue;
    arr[arr.length] = randomnumber;
  }
  return arr;
}

function insertRandomImages() {
  var container = document.getElementById('photo-tiles');
  var imageNums = getRandomNumbers(9,60);
  for (var i = 0; i < imageNums.length; i++) {
    var imageNum = imageNums[i].toString();
    var formatted = imageNum.length == 1 ? '0' + imageNum : imageNum;
    var div = document.createElement('div');
    div.className = 'thumbnail';

    var image = document.createElement('img');
    image.src = 'http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/Ultimate_Campout_Ibex_0' + formatted + '.jpg';
    image.className = 'photo-tile';
    div.appendChild(image);
    container.appendChild(div);
  }
}

function isScrolledIntoView(el) {
    var elemTop = el.getBoundingClientRect().top;
    var elemBottom = el.getBoundingClientRect().bottom;
    var offset = 200;

    var isVisible = (elemTop >= -offset) && (elemBottom <= window.innerHeight + offset);
    return isVisible;
}

function removeOpenClasses(elems) {
  for (var i = 0; i < elems.length; i++) {
    elems[i].classList.remove('open');
  }
}

function tweenIntroContent() {
  var introContent = document.getElementsByClassName('hero-copy')[0].children;
  for (var i = 0; i < introContent.length; i++) {
    TweenLite.to(introContent[i], .5, {delay: (i * .5), opacity: 1});
  }
}

window.onload = function() {
  var leftCurtain = document.getElementsByClassName('curtain__left')[0];
  var rightCurtain = document.getElementsByClassName('curtain__right')[0];

  if (!isScrolledIntoView(leftCurtain)) {
    TweenLite.to(rightCurtain, 2, {right: "0%"});
    TweenLite.to(leftCurtain, 2, {left: "0%"});
  }

  var modalCloseButton = document.getElementsByClassName('modal-close')[0];
  var modal = document.getElementsByClassName('video-modal')[0];
  var modalOpenButton = document.getElementsByClassName('modal-open')[0];
  var iframe = document.querySelector('iframe');
  var player = new Vimeo.Player(iframe);


  modalCloseButton.addEventListener('click', function() {
    modal.classList.remove('open');
    player.pause();
  });

  modal.addEventListener('click', function() {
    modal.classList.remove('open');
    player.pause();
  });

  modalOpenButton.addEventListener('click', function() {
    modal.classList.add('open');
    player.unload().then(function() {
      player.play();
    });
  });

  insertRandomImages();
  tweenIntroContent();

  var hero = document.getElementsByClassName('block__top')[0];
  var width = window.innerWidth;

  for (var i = 0; i < 20; i++) {
    var star = document.createElement('div');
    star.className = 'shooting-star';
    hero.appendChild(star);
    var rand = getRandomNumber(400);
    var size = getRandomNumber(15);
    var waitRandomizer = getRandomNumber(50);
    var time = getRandomNumber(4);
    var wait = (i * 2) * (1 + (waitRandomizer/100));
    var top = rand - 50;
    star.style.width = size;
    star.style.height = size;
    star.style.top = rand;
    star.style.left = 0;

    TweenMax.to(star, time, {bezier:[{top:rand, left:0}, {top: top, left: width/2}, {top:rand, left:(width + 10)}], ease:Power1.easeInOut, delay: wait, visibility: 'visible', onComplete: function() {
      this.target.style.visibility = 'hidden';
    }});
  }
};


window.onscroll = function (e) {
  var leftCurtain = document.getElementsByClassName('curtain__left')[0];
  var rightCurtain = document.getElementsByClassName('curtain__right')[0];

  if (isScrolledIntoView(leftCurtain)) {
    TweenLite.to(rightCurtain, 2, {right: "-48%"});
    TweenLite.to(leftCurtain, 2, {left: "-48%"});
  } else {
    TweenLite.to(rightCurtain, 2, {right: "0%"});
    TweenLite.to(leftCurtain, 2, {left: "0%"});
  }

}
