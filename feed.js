$(function() {

  getTweets();

});

function getTweets() {
  $.ajax({
    url: "getfeed.php",
    success: function(data) {
      var array = JSON.parse(data).statuses;
      console.log(array);
      printTweets(array);
    },
    error: function(error) {
      console.log(error);
    }
  });
}

function printTweets(tweets) {

  var html = '';

  $(tweets).each(function(i, tweet) {


    //html += '<div>' + i + '</div>';

    html += '<div class="item">'+

              '<div class="profile-image" style="background-image: url(' + tweet.user.profile_image_url.replace("_normal", "") + ');"></div>'+
              '<p>' + tweet.user.name + '</p>'+
              '<p>' + tweet.text + '</p>'+

            '</div>';
  });

  $("#content").append( $(html) );
}
