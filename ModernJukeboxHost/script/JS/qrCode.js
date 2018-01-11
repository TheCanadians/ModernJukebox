
// QR code
function qrCode() {
  // Create  DIV node
  var qrNode = document.createElement("div");
  qrNode.setAttribute("id", "qrcode");
  var parentNode = document.getElementById("qrCodeHolder");
  parentNode.appendChild(qrNode);

  database.ref(path + 'name').once('value').then(function(snapshot) {
    var roomName = (snapshot.val());
    var resField = document.createElement("p");
    var resName = document.createTextNode("Ihr QR Code für: " + roomName);
    resField.appendChild(resName);
    qrNode.appendChild(resField);
  });

  // Create QR Code
  var qrCodePic = new QRCode(document.getElementById("qrcode"), {
    width : 400,
    height : 400
  });
  if(path == null) {
    alert("No Restaurant chosen");
    return;
  }
  var venueName = String(path);
  venueName = venueName.slice(1, -1);
  qrCodePic.makeCode(venueName);
  // Select Canvas
  var canvas = document.querySelector('#qrcode canvas');
  var imgData = canvas.toDataURL("image/jpeg", 1.0);
  // Make PDF out of the Canvas
  var qrCodeDoc = new jsPDF();
  qrCodeDoc.addHTML($('#qrcode'), 15, 15, {
    'background' : '#fff',
  }, function() {
    qrCodeDoc.addImage(imgData, 'JPEG', 0, 0);

    qrCodeDoc.save(path + '.pdf');
  });

  // Remove DIV Node
  var parent = document.getElementById("qrCodeHolder");
  var child = document.getElementById("qrcode");
  parent.removeChild(child);
}
