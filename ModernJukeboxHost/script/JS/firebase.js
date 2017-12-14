  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyCW-yHDVzJT7IgK6exI1AElYZ85BKiKeBc",
    authDomain: "modern-jukebox.firebaseapp.com",
    databaseURL: "https://modern-jukebox.firebaseio.com",
    storageBucket: "",
    messagingSenderId: "560034994179"
  };
  firebase.initializeApp(config);

  var database = firebase.database();
// delete single song from firebase songs array
function deleteFromQueue(id) {
  database.ref(path + 'songs/' + id).remove();
}
// set firebase attributes of currently playling song
function setPlaying(id) {
  database.ref(path + 'songs/' + id + '/playing').set('true');
  database.ref(path + 'songs/' + id + '/nextSong').set('false');
}
// set firebase attribute of next song
function setNextSong(id) {
  database.ref(path + 'songs/' + id + '/nextSong').set('true');
}

function pushWhitelist(id, state) {
  database.ref(path + 'blacklist/' + id).set(state);
}

function clearAttribute() {
  database.ref(path + 'songs/').once('value').then(function(snapshot) {
    snapshot.forEach(function(child) {
      var entry = child.val();
      database.ref(path + 'songs/' + entry.id + '/playing').set('false');
      database.ref(path + 'songs/' + entry.id + '/nextSong').set('false');
    });
  });
}
