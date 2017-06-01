function getRandomNumbers(needed,max) {
  var arr = []
  while(arr.length < needed){
      var randomnumber = Math.ceil(Math.random()*max)
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

    var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
    return isVisible;
}

function removeOpenClasses(elems) {
  for (var i = 0; i < elems.length; i++) {
    elems[i].classList.remove('open');
  }
}

window.onload = function() {
  var leftCurtain = document.getElementsByClassName('curtain__left')[0];
  var rightCurtain = document.getElementsByClassName('curtain__right')[0];
  TweenLite.to(rightCurtain, 2, {right: "0%"});
  TweenLite.to(leftCurtain, 2, {left: "0%"});

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
