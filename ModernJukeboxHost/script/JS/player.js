// Open Spotify Player
function openPlayer() {
    win = window.open('https://open.spotify.com/browse/featured', 'player', 'height=400,width=400');

    document.getElementById("player").className = "btn btn-success";
}
// Check if Player is open
function checkPlayer() {
  try {
    if (win.closed) {
      document.getElementById("player").className = "btn btn-danger";
      openPlayer();
    }
  }
  catch (e) {
    openPlayer();
    intervalID = setInterval(setButton, 1000);
  }
}
// Set Button Color
function setButton() {
  if (win.closed) {
    document.getElementById("player").className = "btn btn-danger";
  }
}

function closePlayer() {
  win.close();
  checkPlayer();
}
