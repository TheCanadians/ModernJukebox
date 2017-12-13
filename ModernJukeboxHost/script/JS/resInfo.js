// get infos from firebase about the restaurant and fill form elements with it
function setInfoPath() {
  setPlaylist();
  blacklist();
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

  var updates = {};
  var checkboxes = document.getElementsByClassName("blacklistCheck");
  for (i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked == true) {
      var id = checkboxes[i].parentElement.parentElement;
      var checkID = id.getAttribute('id');
      updates[path + 'blacklist/' + checkID] = 'true';
    }
    else {
      var id = checkboxes[i].parentElement.parentElement;
      var checkID = id.getAttribute('id');
      updates[path + 'blacklist/' + checkID] = 'false';
    }
  }
  firebase.database().ref().update(updates);
}

function blacklist() {
  spotifyApi.getCategories({
    limit : 50
  }).then(function(data) {
    genres = data.body['categories']['items'];
    for(i = 0; i < genres.length; i++) {
      var element = document.createElement('div');
      element.className = "col-md-12 list-group-item";
      element.id = genres[i].id;

      var p = document.createElement('p');
      p.style.margin = "0px";
      element.appendChild(p);

      var content = document.createTextNode(genres[i].name);
      p.appendChild(content);

      var checkbox = document.createElement('input');
      checkbox.type = "checkbox";
      checkbox.className = "blacklistCheck";
      checkbox.style.cssFloat = "right";
      p.appendChild(checkbox);

      var whitelist = document.getElementById("blacklist");
      whitelist.appendChild(element);

      firebase.database().ref(path + 'blacklist/' + genres[i].id).once('value').then(function(snapshot) {
        firebaseState = snapshot.val();
        firebaseKey = snapshot.key;
        detectState(firebaseKey, firebaseState);
      });
    }
  }, function(err) {
    console.log(err);
    refreshToken('blacklist');
  });
}

function detectState(id, state) {
  if (state == 'true') {
    document.getElementById(id).childNodes[0].childNodes[1].checked = true;
    pushWhitelist(id, 'true');
  }
  else {
    pushWhitelist(id, 'false');
  }
}
