// Open Spotify Player
function openPlayer() {
    win = window.open('https://open.spotify.com/browse/featured', '_blank');
    setInterval(checkPlayer(), 5000);
    if (win.closed) {
      document.getElementById("player").className = "btn btn-danger";
    }
    else {
      document.getElementById("player").className = "btn btn-success";
    }
}
// Check if Player is open
function checkPlayer() {
  console.log(win);
  if (win.closed) {
    document.getElementById("player").className = "btn btn-danger";
  }
  else {
    document.getElementById("player").className = "btn btn-success";
  }
}
