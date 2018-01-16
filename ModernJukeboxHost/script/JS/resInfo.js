// get infos from firebase about the restaurant and fill form elements with it
function setInfoPath() {
  clearAttribute();
  blacklist();
  setTimeout(function() {
    setPlaylist();
  }, 1000);
  database.ref('/' + path + '/name').once('value').then(function(snapshot) {
    document.getElementById("dropdownButton").innerHTML = (snapshot.val()) + ' <span class="caret"></span>';
  });
  queue = database.ref(path + 'maxQueue');
  limit = database.ref(path + 'limit');
  link = database.ref(path + 'image');
  whitelistFirebase = database.ref(path + 'whitelist');
  queue.on('value', function(snapshot) {
    document.getElementById("maxQ").value = snapshot.val();
  });
  limit.on('value', function(snapshot) {
    document.getElementById("maxSpU").value = snapshot.val();
  });
  link.on('value', function(snapshot) {
    document.getElementById("imgLink").value = snapshot.val();
  });
}
// update firebase entrys on button press
function update() {
  var form = document.getElementById("ResInfo");
  var link = form.elements["imgLink"].value;
  var maxQ = form.elements["maxQ"].value;
  var maxSpU = form.elements["maxSpU"].value;

  database.ref(path + 'image').set(link);
  database.ref(path + 'maxQueue').set(maxQ);
  database.ref(path + 'limit').set(maxSpU);

  var updates = [];
  var checkboxes = document.getElementsByClassName("blacklistCheck");
  for (i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked == true) {
      var id = checkboxes[i].parentElement.parentElement;
      var checkID = id.getAttribute('id');
      updates.push(checkID);
    }
  }
  firebase.database().ref(path + 'blacklist').set(updates);
}

function blacklist() {
  spotifyApi.getAvailableGenreSeeds().then(function(data) {
    genres = data.body['genres'];
    for(i = 0; i < genres.length; i++) {
      var element = document.createElement('div');
      element.className = "col-md-12 list-group-item";
      element.id = genres[i];

      var p = document.createElement('p');
      p.style.margin = "0px";
      element.appendChild(p);

      var content = document.createTextNode(genres[i]);
      p.appendChild(content);

      var checkbox = document.createElement('input');
      checkbox.type = "checkbox";
      checkbox.className = "blacklistCheck";
      checkbox.style.cssFloat = "right";
      p.appendChild(checkbox);

      var whitelist = document.getElementById("blacklist");
      whitelist.appendChild(element);
      detectState(genres[i]);
    }
  }, function(err) {
    console.log(err);
    if(err.statusCode == "401") {
      refreshToken("blacklist");
    }
  });
}

function toggleSearch() {
  var searchDIV = document.getElementById("searchDIV");
  if (searchDIV.style.display === "none") {
    searchDIV.style.display = "block";
  }
  else {
    searchDIV.style.display = "none";
  }
}

function searchGenres() {
  var input, filter, parent, genres;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  parent = document.getElementById("blacklist");
  genres = parent.getElementsByTagName("div");

  for (i = 0; i < genres.length; i++) {
    if (genres[i].id.toUpperCase().indexOf(filter) > -1) {
      genres[i].style.display = "";
    }
    else {
      genres[i].style.display = "none";
    }
  }
}

function detectState(id) {
  database.ref(path + 'blacklist').once('value').then(function(snapshot) {
    snapshot.forEach(function(child) {
      if(child.val() == id) {
        document.getElementById(id).childNodes[0].childNodes[1].checked = true;
      }
    });
  });
}
