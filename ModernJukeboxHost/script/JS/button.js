$(document).ready(function() {
  // Sets active restaurant in dropdown
  $('.dropdown-menu').on('click', "a", function() {
    $('.active').removeClass("active");
    $(this).parent().addClass('active');
    setPath();
  });
  // JQuery for play button
    $('#play').click(function() {
      replacePlaylist();
      setPlayer();
    });
  // JQuery for open Webplayer button
    $('#player').click(function() {
      checkPlayer();
    }),
  // JQuery for Pause button
    $('#pause').click(function() {
      SpotifyPause();
    });
  // JQuery for delete Selected button
    $('#deleteSel').click(function() {
        var updates = {};
        var checkboxes = document.getElementsByClassName("checkbox");
        for (i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].checked == true) {
            var id = checkboxes[i].parentElement.parentElement;
            var checkID = id.getAttribute('id');
            updates[path + 'songs/' + checkID] = null;
          }
        }
        firebase.database().ref().update(updates);
    });
  // JQuery for Clear playlist button
    $('#clearPlaylist').click(function() {
        database.ref(path + 'songs/').remove();
    });

});
