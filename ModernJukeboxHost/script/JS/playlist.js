//Get Playlist from Firebase
  database.ref(path + 'songs/').orderByChild('votes').on('value', function(snapshot) {
    var playlist = document.getElementById("queue");
    while(playlist.firstChild) {
      playlist.removeChild(playlist.firstChild);
    }
    snapshot.forEach(function(child) {
      var song = child.val();
      console.log(song.artist+': '+song.votes);
      if(document.getElementById(song.id) != null) {

      }
      else {
        var element = document.createElement('div');
        element.className = "col-md-12";
        element.id = song.id;
        element.style.border = "thin solid #000";
        element.style.padding = "2px";

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
