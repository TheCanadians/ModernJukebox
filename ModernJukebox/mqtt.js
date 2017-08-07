// im Terminal:
// browserify mqtt.js -o bundle.js

var mqtt = require('mqtt')
var client = mqtt.connect('mqtt://broker.mqttdashboard.com')

client.on('connect', function () {
  client.subscribe('presence')
  client.publish('presence', 'Hello mqtt')
})

client.on('message', function (topic, message) {
  // message is Buffer
  console.log(message.toString())
  client.end()
})
