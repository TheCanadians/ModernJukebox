for (i = 0; i < rooms.length; i++) {
  if(i == 0) {
    getRoomName(rooms[i]['roomName'], true);
  }
  else {
    getRoomName(rooms[i]['roomName'], false);
  }

}
function getRoomName(path, active) {
  database.ref('/' + path + '/name').once('value').then(function(snapshot) {
    var roomName = (snapshot.val());
    var listEl = document.createElement("LI");
    listEl.setAttribute("id", path);
    if(active) {
      listEl.setAttribute("class", "active");
      path = '/' + this.path + '/';
    }
    var linkEl = document.createElement("A");
    linkEl.setAttribute("href", "#");
    var roomText = document.createTextNode(roomName);
    linkEl.appendChild(roomText);
    listEl.appendChild(linkEl);
    var list = document.getElementById("rooms");
    list.insertBefore(listEl, list.childNodes[0]);
  });
}
function setRoom(event) {

}

function setPath() {
  var active = document.getElementsByClassName("active");
  var activeID = active[0].id;
  path = '/' + activeID + '/';
  setInfoPath();
}
