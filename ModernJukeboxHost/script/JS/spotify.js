var SpotifyWebApi = require('spotify-web-api-node');

  spotifyApi = new SpotifyWebApi({
    clientId : cliID,
    clientSecret : cliSecret,
    redirectUri : 'http://localhost/ModernJukeboxHost/server/forwarding.php'
  });
  // set access and refresh token
  spotifyApi.setAccessToken(accessToken[0]);
  spotifyApi.setRefreshToken(refreshToken[0]);

  id = "";

  spotifyApi.getMe().then(function(data) {
    id = data.body.id;
  }, function(err) {
    console.log(err);
    if(err.statusCode == "401") {
      refreshToken("getUserID");
    }
  });

// get user id
window.getUserID = function() {
    spotifyApi.getMe().then(function(data) {
      id = data.body.id;
    }, function(err) {
      console.log(err);
      if(err.statusCode == "401") {
        refreshToken("getUserID");
      }
    });
  }

// replace spotify playlist with first two songs from playlist
window.replacePlaylist = function() {
  // check if a spotify playlist ID exists in database
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
      playlistID = msg;
      // replace tracks if ID exists
      if (playlistID != "") {
        var parentDIV = document.getElementById("queue");
        var first = parentDIV.getElementsByTagName("div")[0];
        var second = parentDIV.getElementsByTagName("div")[1];
        if (first == null && second == null) {
          test = addSpotifyPlaylistSong(2);
        }
        else if (second == null && first != null) {
          test = addSpotifyPlaylistSong(1);
          secondID = test[1];
        }
        else {
          var firstID = parentDIV.getElementsByTagName("div")[0].id;
          var secondID = parentDIV.getElementsByTagName("div")[1].id;
          setPlaying(firstID);
          setNextSong(secondID);
        }
        setTimeout(function() {
          if (firstID === undefined) {
            firstID = test[0];
            secondID = test[1];
          }
          else if (secondID === undefined) {
            secondID = test[1];
          }
          spotifyApi.replaceTracksInPlaylist(id, playlistID, [
            'spotify:track:' + firstID,
            'spotify:track:' + secondID
          ]).then(function(data) {
            console.log("Replaced Songs");
            setTimeout(function() {
              SpotifyPlay();
            }, 1000)

          }, function(err) {
            console.log("Something went wrong while replacing Songs: ", err);
            if(err.statusCode == "401") {
              refreshToken("replacePlaylist");
            }
          });
        }, 1000);
      }
      // create new spotify playlist and return id
      else {
        var trimPath = path.toString().slice(1, -1);
        spotifyApi.createPlaylist(id, trimPath, {'public' : false}).then(function(data) {
          console.log("Created Playlist!");
          var playlistID = data.body['id'];
          // save id in database
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
          console.log("Something went wrong!", err);
          if(err.statusCode == "401") {
            refreshToken("replacePlaylist");
          }
        })
      }
    });

}

window.addSpotifyPlaylistSong = function(int) {
  songs = [];
  spotifyApi.getPlaylist('spotify', '37i9dQZF1DX274mITVX0K3').then(function(data) {
    var playlistLength = data.body.tracks.items.length;
    for (i = int; i > 0; i--) {
      var randomSong = Math.floor(Math.random() * playlistLength);
      try {
        while (randomSong == lastSongNumber) {
          var randomSong = Math.floor(Math.random() * playlistLength);
        }
      }
      catch(err) {
        console.log(err);
      }
      lastSongNumber = randomSong;
      var playlist = data.body.tracks.items[randomSong].track;
      artists = [];
      for (j = 0; j < playlist['artists'].length; j++) {
        artists.push(playlist['artists'][j]['name']);
      }
      var duration = playlist['duration_ms'];
      var id = playlist['id'];
      //var image = playlist[''];
      if (i == 2) {
        var nextSong = "false";
        var playing = "true";
        firstID = id;
        songs[0] = id;
      }
      else {
        var nextSong = "true";
        var playing = "false";
        secondID = id;
        songs[1] = id;
      }
      var title = playlist['name'];
      addSong(artists, duration, id, nextSong, playing, title);
    }
  }, function(err) {
    console.log(err);
    if(err.statusCode == "401") {
      refreshToken("addSpotifyPlaylistSong");
    }
  });
  return songs;
}

// add new song to spotify playlist
window.addToPlaylist = function() {
  var nextID = "";
  var parentDIV = document.getElementById("queue");
  var next = parentDIV.getElementsByTagName("div")[0];
  if (next == null) {
    //add song from open spotify playlist
    test = addSpotifyPlaylistSong(1);
  }
  else {
    nextID = parentDIV.getElementsByTagName("div")[0].id;
  }
  setTimeout(function() {
    if (nextID == "" || nextID === undefined) {
      nextID = test[1];
    }
    spotifyApi.addTracksToPlaylist(id, playlistID, [
      "spotify:track:" + nextID
    ]).then(function(data) {
      console.log("Song added");
      setNextSong(nextID);
      SpotifyPlay();
    }, function(err) {
      console.log("Something went wrong while adding songs!", err);
      if(err.statusCode == "401") {
        refreshToken("addToPlaylist");
      }
    });
  }, 1000);
}
// delete first song from spotify playlist
window.deleteFromPlaylist = function() {
  spotifyApi.getPlaylist(id, playlistID).then(function(data) {
    snapshot_id = data.body['snapshot_id']
    spotifyApi.removeTracksFromPlaylistByPosition(id, playlistID, [0], snapshot_id).then(function(data) {
      console.log("Deleted successfully");
      var deleteID = document.getElementById("playing").className;
      deleteFromQueue(deleteID);
      var songID = document.getElementById("next").className;
      setPlaying(songID);
      addToPlaylist();
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
// timer function to check when song finished playling
window.timer = function(progress = 0) {
  var firstSongID = document.getElementById("playing").className;
  // get song length of current song
  spotifyApi.getMyCurrentPlayingTrack().then(function(data) {
      if (data.body['item']['id'] == firstSongID) {
        var songLength = parseInt(data.body['item']['duration_ms'], 10) - progress;
        console.log("Länge des Timers: " + songLength);
        // wait for song length + 5 seconds
        setTimeout(function() {
          spotifyApi.getMyCurrentPlayingTrack().then(function(data) {
            if (data.body['item'] == null) {
              setTimeout(function() {}, 200);
            }
            var currentID = data.body['item']['id'];
            if (currentID == firstSongID) {
              var progressMS = data.body['progress_ms'];
              timer(progressMS);
            }
            else {
              deleteFromPlaylist();
            }
          }, function(err) {
            console.log('Current Song Timer: ', err);
            if(err.statusCode == "401") {
              refreshToken("timer");
            }
          });
        }, songLength);
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

window.resumePlay = function() {
  spotifyApi.play().then(function(data) {
    pause = false;
  }, function(err) {
    console.log('Current Song: ', err);
    if(err.statusCode == "401") {
      refreshToken("SpotifyPlay");
    }
  });
}

// start playing spotify playlist
window.SpotifyPlay = function() {
  spotifyApi.getMyDevices().then(function(data) {
    var deviceID = data.body['devices'][0].id;
      spotifyApi.play({context_uri : 'spotify:user:' + id + ':playlist:' + playlistID}).then(function(data) {
        spotifyApi.getMyCurrentPlayingTrack().then(function(data) {
          // if status code of response is 204 restart webplayer and try again (works sporadically)
          if (data.statusCode == "204") {
            console.log("204");
            alert("Please start a song manually!");
            //closePlayer();
            setTimeout(function() {
              SpotifyPlay();
            }, 4000);

          }
          else {
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

// stop playback of spotify playlist
window.SpotifyPause = function() {
  pause = true;
  spotifyApi.pause().then(function(data) {
    console.log("Song Paused");
  }, function(err) {
    console.log("Something went wrong while trying to pause song!");
    if(err.statusCode == "401") {
      refreshToken("SpotifyPause");
    }
  });
}
// refresh access token then call function that called this function
window.refreshToken = function(name) {
  $.ajax({
    type: "POST",
    url: "../script/PHP/refreshToken.php",
    data: {
      path : path,
    }
  }).done(function(msg) {
    console.log(msg);
    spotifyApi.setAccessToken(msg);
    // recall function that called this
    window[name]();
  });
}
