function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function insertRandomImages() {
  var container = document.getElementById('photo-tiles');
  for (var i = 0; i < 10; i++) {
    var rand = getRandomInt(1,60).toString();
    var imageNum = rand.length == 1 ? '0' + rand : rand;
    var image = document.createElement('img', {class: 'photo-tile'});
    image.src = 'http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/Ultimate_Campout_Ibex_0' + imageNum + '.jpg';
    container.appendChild(image);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  insertRandomImages();
});
