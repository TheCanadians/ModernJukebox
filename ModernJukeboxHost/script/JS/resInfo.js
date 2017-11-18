function setInfoPath() {
  database.ref('/' + path + '/name').once('value').then(function(snapshot) {
    document.getElementById("dropdownButton").innerHTML = (snapshot.val()) + ' <span class="caret"></span>';
  });
  queue = database.ref(path + 'maxQueue');
  limit = database.ref(path + 'limit');
  link = database.ref(path + 'image');
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

function update() {
  var form = document.getElementById("ResInfo");
  var link = form.elements["imgLink"].value;
  var maxQ = form.elements["maxQ"].value;
  var maxSpU = form.elements["maxSpU"].value;

  database.ref(path + 'image').set(link);
  database.ref(path + 'maxQueue').set(maxQ);
  database.ref(path + 'limit').set(maxSpU);
}
