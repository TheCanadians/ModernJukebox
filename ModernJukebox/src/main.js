// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'

var mqtt = require('mqtt')
var client = mqtt.connect('mqtt://broker.mqttdashboard.com:8000/mqtt')



Vue.config.productionTip = false

client.on('connect', function () {
  client.subscribe('ProjectB/ModernJukebox')
  client.publish('ProjectB/ModernJukebox', 'Hello mqtt')
  console.log("Connected");
})

client.on('message', function (topic, message) {
  // message is Buffer
  console.log(message.toString())
  app.msg = message.toString();
})

/* eslint-disable no-new */
var app = new Vue({
  el: '#app',
  data: {
    msg: '',
  }
})
