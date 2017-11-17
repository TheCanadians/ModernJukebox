$(document).ready(function() {
  $('.dropdown-menu').on('click', "a", function() {
    $('.active').removeClass("active");
    $(this).parent().addClass('active');
    setPath();
  });

    $('#play').click(function() {
      var songID = document.getElementById("queue").firstChild.id;
      console.log(songID);
      $.ajax({
        type: "POST",
        url: "functions.php",
        data: {PlayID : songID}
      }).done(function(msg) {
        console.log(msg);
      });
    });

    $('#pause').click(function() {
      var songID = document.getElementById("queue").firstChild.id;
      console.log(songID);
      $.ajax({
        type: "POST",
        url: "functions.php",
        data: {PauseID : songID}
      }).done(function(msg) {
        console.log(msg);
      });
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
