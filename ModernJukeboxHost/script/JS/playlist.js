function setPlaylist() {
  //Get Playlist from Firebase
    database.ref(path + 'songs/').orderByChild('votes').on('value', function(snapshot) {
      var playlist = document.getElementById("queue");
      while(playlist.firstChild) {
        playlist.removeChild(playlist.firstChild);
      }
      snapshot.forEach(function(child) {
        var song = child.val();
        console.log(song.artist+': '+song.votes);
        // filter doubles
        if(document.getElementById(song.id) != null) {

        }
        // Create HTML element for every firebase songs entry
        else {
          var element = document.createElement('div');
          element.className = "col-md-12 list-group-item";
          element.id = song.id;

          var p = document.createElement('p');
          p.style.margin = "0px";
          element.appendChild(p);

          var content = document.createTextNode(song.title + " - " + song.artist + " Votes: " + song.votes);
          p.appendChild(content);

          var checkbox = document.createElement('input');
          checkbox.type = "checkbox";
          checkbox.className = "checkbox";
          checkbox.style.cssFloat = "right";
          p.appendChild(checkbox);

          var queue = document.getElementById("queue");
          queue.appendChild(element);
        }
      });
    });

}
