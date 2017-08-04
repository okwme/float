function showResult(textTop, textBottom, textBottomBottom) {
  $('#result-panel-text-top').text(textTop);
  $('#result-panel-text-bottom').text(textBottom);
  $('#result-panel-text-bottom').append($("<br>"));
  $('#result-panel-text-bottom').append(textBottomBottom);
  $('#result-panel-wrapper').fadeIn();
}

function closeResult() {
  hideLoader();
  $('#result-panel-wrapper').fadeOut();
  var original = '/img/smiley.svg';
  $('#new-img-target').attr('src', original);
  closeSplash();
}

function checkRating(photo) {
  var rating = photo.Photo.rating;
  // var html = photo.output;

  // In top
  if(rating > $('#best .photo-row').eq(4).data('rating')) {

    // The absolute best
    if (rating > $('#best .photo-row').first().data('rating')) {
      showResult('Congratulations', 'You\'re the new leader!');
    }
    // In the top 3
    else {
      showResult('Well done.', 'You\'re in the top five!');  
    }
    positionPhoto($('#best .photo-row').eq(4), '#best', photo.output);
    console.log('BETTER');
  }

  // In bottom
  else if(rating < $('#worst .photo-row').eq(0).data('rating')) {

    // The absolute worst
    if (rating < $('#worst .photo-row').last().data('rating')) {
      showResult('Congratulations', 'You\'ve uploaded the ugliest photo of all time!');
    }

    // In bottom 3
    else {
      showResult('Well done', 'You\'ve made it to the bottom!');
    }
    positionPhoto($('#worst .photo-row').eq(0), '#worst', photo.output);
    console.log('WORSE');
  }

  // Didn't rank
  else {
    var rating = Math.round( (photo.Photo.rating*100) * 100) / 100
    showResult('Sorry, try again', 'With a rank of '+(rating)+' your photo isn\'t the best or worst');
    console.log('8=====D');
  }
}

function positionPhoto($element, list, html) {
  $element.remove()
  $(list).append(html);
  sortonUpdate(list)
  $('img.unveil').unveil(200, function() {
    $(this).load(function() {
      $(this).removeClass('min-height');
      this.style.opacity = 1;
    });
  })
  setTimeout(function() {
    sortonUpdate(list)
  }, 20);
}

function sortonUpdate(list) {
  console.log('sorting updated list');
  $(list).children('.photo-row').sort(sort_li).appendTo(list);
  function sort_li(a, b) {
    return parseFloat($(a).data('rating')) < parseFloat($(b).data('rating')) ? 1 : -1;
  }
  $(list).children('.flipped').appendTo(list);
  setRanks()
}

function closeSplash() {
  $('.splash-item').fadeOut();
}

function setLoaderText(message) {
  $('.loader-text').text(message);
}

function showLoader(message) {
  if (message) {
    setLoaderText(message);  
  } else {
    setLoaderText('Loading...');
  }
  $('.loader-progress').fadeIn();
  $('.loader-item').fadeIn();
  $('body').addClass('fixed');
}

function hideLoader() {
  $('.loader-item').fadeOut();
  $('.loader-progress').fadeOut().find('div').width(0);
  $('body').removeClass('fixed');
}

function submitEmail() {
  $.ajax({
    url: '/profile',
    type: 'POST',
    beforeSend: function () {
      showLoader();
      closeSplash();
      console.log('sending');
    },
    success: function () {
      console.log('done');
      window.location.href = '/';
    },
    data: $("#splashProfile").serialize()
  })
}

function pollResults(url){
  $.ajax({
    url: url,
    type: 'POST',
    xhr: function() {
      var myXhr = $.ajaxSettings.xhr();
      return myXhr;
    },
    dataType:"json",
    beforeSend: function () {
      showLoader('Checking Rating...');
    },
    success: function (photo) {
      checkRating(photo);
      console.log(photo);
      // hideLoader();
    },
    error: function (result) {
      console.log(result);
      //  setTimeout(function () {
        pollResults(url);
      // }, 5000);
      console.log('there was an error, trying again')
    },
    cache: false,
    contentType: false,
    processData: false
  });
}

var dataURLToBlob = function(dataURL) {
    var BASE64_MARKER = ';base64,';
    if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        var raw = parts[1];

        return new Blob([raw], {type: contentType});
    }

    var parts = dataURL.split(BASE64_MARKER);
    var contentType = parts[0].split(':')[1];
    var raw = window.atob(parts[1]);
    var rawLength = raw.length;

    var uInt8Array = new Uint8Array(rawLength);

    for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], {type: contentType});
}
function getOrientation(file, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {

        var view = new DataView(e.target.result);
        if (view.getUint16(0, false) != 0xFFD8) return callback(-2);
        var length = view.byteLength;
        var offset = 2;
        while (offset < length) {
            var marker = view.getUint16(offset, false);
            offset += 2;
            if (marker == 0xFFE1) {
                var little = view.getUint16(offset += 8, false) == 0x4949;
                offset += view.getUint32(offset + 4, little);
                var tags = view.getUint16(offset, little);
                offset += 2;
                for (var i = 0; i < tags; i++)
                    if (view.getUint16(offset + (i * 12), little) == 0x0112)
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
            }
            else if ((marker & 0xFF00) != 0xFF00) break;
            else offset += view.getUint16(offset, false);
        }
        return callback(-1);
    };
    reader.readAsArrayBuffer(file.slice(0, 64 * 1024));
}
window.uploadPhotos = function(url){
    // Read in file
    var file = event.target.files[0];
    getOrientation(file, function(orientation) {
      console.log("orientation");
      console.log(orientation);
   
      console.log(file);
      // Ensure it's an image
      if(file.type.match(/image.*/)) {
        console.log('An image has been loaded');

        // Load the image
        var reader = new FileReader();
        reader.onload = function (readerEvent) {
          console.log("reader on load");
            var image = new Image();
            //var exif = EXIF.readFromBinaryFile(new BinaryFile(this.result));
            //console.log(exif);
            image.onload = function (imageEvent) {
              console.log("image onload");
                // Resize the image
                var canvas = document.createElement('canvas'),
                    max_size = 800,// TODO : pull max size from a site config
                    width = image.width,
                    height = image.height;
                    ctx = canvas.getContext('2d');
                    console.log("orientation: "+orientation);
                    



                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }

                }



              
               switch(orientation){
                    case(3):
                      canvas.width = width;
                      canvas.height = height;
                      //canvas.getContext('2d').rotate(180*Math.PI/180);
                      canvas.getContext('2d').translate(canvas.width, canvas.height);
                      canvas.getContext('2d').rotate(Math.PI);
                    break;
                    case(6):
                      canvas.width = height;
                      canvas.height = width;
                     // canvas.getContext('2d').rotate(-90*Math.PI/180);
                      canvas.getContext('2d').rotate(0.5 * Math.PI);
                      canvas.getContext('2d').translate(0, -canvas.width);
      /*                foo = width;
                      width = height;
                      height = foo;*/
                    break;
                    case(8):
                      canvas.width = height;
                      canvas.height = width;
                      canvas.getContext('2d').rotate(-0.5 * Math.PI);
                      canvas.getContext('2d').translate(-canvas.height, 0);
                    break;
                    default:
                      canvas.width = width;
                      canvas.height = height;
                  }
                

                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                var dataUrl = canvas.toDataURL('image/jpeg');
                var resizedImage = dataURLToBlob(dataUrl);
                console.log(url);
                console.log(resizedImage);
                $.event.trigger({
                    type: "imageResized",
                    blob: resizedImage,
                    url: url
                });
            }
            image.src = readerEvent.target.result;
        }
        reader.readAsDataURL(file);
      }
   });
};

$(document).on("imageResized", function (event) {
  console.log("imageResized");
  console.log(event);
  console.log(event.blob);
  console.log(event.url);
  var formElement = $("#PhotoImage").clone(true, true);
  $("#PhotoImage").remove();
  console.log($("#PhotoIndexForm")[0]);
  var data = new FormData($("#PhotoIndexForm")[0]);
  console.log(data);
  if (event.blob && event.url) {
    console.log("happening");

      data.append('data[Photo][image]', event.blob);


    //function uploadPic(thang) {
      var original = '/img/smiley.svg';
      $('#new-img-target').attr('src', original);
      //var formData = new FormData($('#PhotoIndexForm')[0]);
      var formData = data;


      $.ajax({
        url: '/photos/upload',
        type: 'POST',
        xhr: function() {
          var myXhr = $.ajaxSettings.xhr();
          if(myXhr.upload){
            myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
          }
          return myXhr;
        },
        dataType:"json",
        beforeSend: function () {
          showLoader('Uploading...');
        },
        success: function (photo) {
          console.log(photo);
          var id = photo.Photo.id;
          var src = photo.Photo.amazonUrl;
          var url = "/photos/pollRating/"+id;
          
          pollResults(url);
          $('#new-img-target').attr('src', src);
          $("#PhotoIndexForm label").append(formElement);
        },
        error: function () {
          console.log('there was an error')
          hideLoader();
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
      });

      function progressHandlingFunction(e) {
        console.log('progress', e.loaded, e.total)
        if(e.lengthComputable){
          var percentComplete = (e.loaded / e.total) * 100;
          $('.loader-progress > div').css({
            width: percentComplete + '%'
          })
          if(percentComplete == 100){
            showLoader('Storing File...');
          }
        }
      }
   }else{
    console.log("not happening");
   }
});

function initScrollEffect() {
  var controller = new ScrollMagic.Controller();

  // build scene
  var bgColor = new ScrollMagic.Scene({triggerElement: "#container", duration: $('#container').height() })
  .setTween("body", {backgroundColor: "#FF0000"})
  .triggerHook(0)
  .addTo(controller);
}

function setRanks() {
  // fake home ranks
  bestCount = 1;
  worstCount = $('#worst').data('totalphotos') - 4;

  $('#best div.photo-row').each(function(){
    $(this).find('span.rank').text('Ranked #'+bestCount+' ');
    bestCount++;
  });

  $('#worst div.photo-row').each(function(){
    $(this).find('span.rank').text('Ranked #'+worstCount+' ');
    worstCount++;
  });

}

$(document).ready(function(){
  $(document).foundation();
  // initScrollEffect();
  // $('.splash-item').delay(1000).fadeOut(1000);
  $('#PhotoImage').on('change', function (event) {
    console.log('changed input');
   // uploadPic()
    uploadPhotos("/photos/upload");
  })
  $('#signup-email').focus(function(){
    $(this).attr('placeholder', '');
  });
  $('#signup-email').blur(function(){
    $(this).attr('placeholder', 'Enter your email address');
  });
  // $('#splashProfile').on('submit', function (event) {
    // event.preventDefault()
    // console.log(event);
    // submitEmail()
  // })
  $('img.unveil').unveil(200, function() {
    $(this).load(function() {
      $(this).removeClass('min-height');
      this.style.opacity = 1;
    });
  });

  // ios fix for label click
  var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  if ( iOS ) {
    var labelID;
    $('i.material-icons').click(function(e) {

     // labelID = $(this).attr('for');
      //console.log($(this).find("input"));
      $(this).siblings("input").click();
      //$('#'+labelID).trigger('click');
    });
  }

  $('#show-profile-editor').click(function(e) {
    e.preventDefault();

    $('#profile-editor').slideDown();
  });

  $('body').on('click', 'a.show-tags', function(e) {
    e.preventDefault();
    $(this).next('div.tags').slideToggle();
  })

  setRanks()

});
