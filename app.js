function insertRandomImages() {
  var container = document.getElementById('photo-tiles');
  for (var i = 0; i < 10; i++) {
    var image = document.createElement('img')
    image.src = 'http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/Ultimate_Campout_Ibex_00' + i + '.jpg';
    container.appendChild(image);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  insertRandomImages();
});
