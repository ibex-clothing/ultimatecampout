// function getRandomNumbers(needed,max) {
//   var arr = []
//   while(arr.length < needed){
//       var randomnumber = Math.ceil(Math.random()*max)
//       if(arr.indexOf(randomnumber) > -1) continue;
//       arr[arr.length] = randomnumber;
//   }
//   return arr;
// }
//
// function insertRandomImages() {
//   var container = document.getElementById('photo-tiles');
//   var imageNums = getRandomNumbers(10,60);
//   for (var i = 0; i < imageNums.length; i++) {
//     var imageNum = imageNums[i].toString();
//     var formatted = imageNum.length == 1 ? '0' + imageNum : imageNum;
//     var div = document.createElement('div');
//     div.className = 'col-xs-6 col-md-3';
//     var innerDiv = document.createElement('div');
//     innerDiv.className = 'thumbnail';
//
//     var image = document.createElement('img');
//     image.src = 'http://assets.ibex.com.s3.amazonaws.com/images/ultimatecampout/Ultimate_Campout_Ibex_0' + formatted + '.jpg';
//     image.className = 'photo-tile';
//     innerDiv.appendChild(image);
//     div.appendChild(innerDiv);
//     container.appendChild(div);
//   }
// }
//
// document.addEventListener('DOMContentLoaded', function() {
//   insertRandomImages();
// });
