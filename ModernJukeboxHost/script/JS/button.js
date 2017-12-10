$(document).ready(function() {
  $('.dropdown-menu').on('click', "a", function() {
    $('.active').removeClass("active");
    $(this).parent().addClass('active');
    setPath();
  });

    $('#play').click(function() {
      replacePlaylist();
    });

    $('#player').click(function() {
      checkPlayer();
    }),

    $('#pause').click(function() {
      SpotifyPause();
    });

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

    $('#clearPlaylist').click(function() {
        database.ref(path + 'songs/').remove();
    });

});
