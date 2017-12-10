var SpotifyWebApi = require('spotify-web-api-node');

var spotifyApi = new SpotifyWebApi({
  clientId : '500ecf1e7acc47b7980a91efd66b9a9c',
  clientSecret : '9a3f95e414f2409f9c70490b199e521c',
  redirectUri : 'http://localhost/ModernJukeboxHost/server/forwarding.php'
});

spotifyApi.setAccessToken(accessToken[0]);
spotifyApi.setRefreshToken(refreshToken[0]);

window.replacePlaylist = function() {
  $.ajax({
      type : "POST",
      url : "../script/PHP/database.php",
      data :
      {
        get : "true",
        path : path,
        set : "false",
        playlistID : null
      }
    }).done(function(msg) {
      console.log(msg);
      playlistID = msg;
      if (playlistID != "") {
        console.log(playlistID);
        var parentDIV = document.getElementById("queue");
        var firstID = parentDIV.getElementsByTagName("div")[0].id;
        var secondID = parentDIV.getElementsByTagName("div")[1].id;
        spotifyApi.replaceTracksInPlaylist('guildwhoops', playlistID, [
          'spotify:track:' + firstID,
          'spotify:track:' + secondID
        ]).then(function(data) {
          console.log("Replaced Songs");
          SpotifyPlay();
        }, function(err) {
          console.log("Something went wrong while replacing Songs: ", err);
          if(err.statusCode == "401") {
            refreshToken("replacePlaylist");
          }
        });
      }
      else {
        var trimPath = path.toString().slice(1, -1);
        console.log(trimPath);
        spotifyApi.createPlaylist('guildwhoops', trimPath, {'public' : false}).then(function(data) {
          console.log("Created Playlist!");
          var playlistID = data.body['id'];
          console.log(playlistID);
          $.ajax({
            type: "POST",
            url: "../script/PHP/database.php",
            data: {
              get : "false",
              path : path,
              set : "true",
              playlistID : playlistID
            }
          }).done(function(msg) {
            console.log(msg);
            replacePlaylist();
          });
        }, function(err) {
          console.log("Something went wrong!");
          if(err.statusCode == "401") {
            refreshToken("replacePlaylist");
          }
        })
      }
    });

}

function addToPlaylist() {
  var parentDIV = document.getElementById("queue");
  var thirdID = parentDIV.getElementsByTagName("div")[2].id;
  spotifyApi.addTracksToPlaylist('guildwhoops', playlistID, [
    "spotify:track:" + thirdID
  ]).then(function(data) {
    console.log("Song added");
  }, function(err) {
    console.log("Something went wrong while adding songs!");
    if(err.statusCode == "401") {
      refreshToken("addToPlaylist");
    }
  });
}

function deleteFromPlaylist() {
  console.log("Delete");
  spotifyApi.getPlaylist('guildwhoops', playlistID).then(function(data) {
    console.log(data.body['snapshot_id']);
    snapshot_id = data.body['snapshot_id']
    spotifyApi.removeTracksFromPlaylistByPosition('guildwhoops', '6bQ7gg4w5uTvnVatgtNitu', [0], snapshot_id).then(function(data) {
      console.log("Deleted successfully");
      var parentDIV = document.getElementById("queue");
      var deleteID = parentDIV.getElementsByTagName("div")[0].id;
      deleteFromQueue(deleteID);
    }, function(err) {
      console.log("something went wrong while deleting!", err);
      if(err.statusCode == "401") {
        refreshToken();
      }
    });
  }, function(err) {
    console.log(err);
    if(err.statusCode == "401") {
      refreshToken("deleteFromPlaylist");
    }
  });
}

function timer() {
  spotifyApi.getMyCurrentPlayingTrack().then(function(data) {
    var parentDIV = document.getElementById("queue");
    var firstSongID = parentDIV.getElementsByTagName("div")[0].id;
    if (data.body['item']['id'] == firstSongID) {
      console.log(data.body['item']['duration_ms']);
      console.log(data.body);
      var songLength = data.body['item']['duration_ms'];
      var songID = data.body['item']['id'];
      setPlaying(songID);
      var nextSongID = parentDIV.getElementsByTagName("div")[1].id;
      setNextSong(nextSongID);
      setTimeout(function() {
        addToPlaylist();
        deleteFromPlaylist();
        timer();
      }, songLength + 5000);
    }
    else {
      setTimeout(function() {
        timer();
      }, 1000);
    }
  }, function(err) {
    console.log('Current Song: ', err);
    if(err.statusCode == "401") {
      refreshToken("timer");
    }
  });

}

window.SpotifyPlay = function() {
  spotifyApi.getMyDevices().then(function(data) {
    console.log(data.body);
    var deviceId = data.body['devices'][0].id;
    spotifyApi.play({context_uri : 'spotify:user:guildwhoops:playlist:' + playlistID}).then(function(data) {
      spotifyApi.getMyCurrentPlayingTrack().then(function(data) {
        if (data.statusCode == "204") {
          console.log("204");
          closePlayer();
          setTimeout(function() {
            SpotifyPlay();
          }, 4000);
        }
        else {
          console.log(data.body['item']['duration_ms']);
          var songLength = data.body['item']['duration_ms'];
          timer();
        }
      }, function(err) {
        console.log('Current Song: ', err);
        if(err.statusCode == "401") {
          refreshToken("SpotifyPlay");
        }
      });
    }, function(err) {
      console.log('Couldnt play, cuz: ', err);
      if(err.statusCode == "401") {
        refreshToken("SpotifyPlay");
      }
    });
  }, function(err) {
    console.log(err);
    if(err.statusCode == "401") {
      refreshToken("SpotifyPlay");
    }
  });
}

window.SpotifyPause = function() {
  spotifyApi.pause().then(function(data) {
    console.log("Song Paused");
  }, function(err) {
    console.log("Something went wrong while trying to pause song!");
    if(err.statusCode == "401") {
      refreshToken("SpotifyPause");
    }
  });
}

function refreshToken(name) {
  $.ajax({
    type: "POST",
    url: "../script/PHP/refreshToken.php",
    data: {
      path : path,
    }
  }).done(function(msg) {
    console.log(msg);
    spotifyApi.setAccessToken(msg);
    window[name]();
  });
}
