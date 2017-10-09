<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: 'app',
    data() {
      return {
        data: ''
      }
    }
  }
</script>

<style>
  @import url('https://fonts.googleapis.com/css?family=Roboto:400,700');
  * {
    margin: 0;
    padding: 0;
  }
  html, body {
    height: 100%;
    position: relative;
  }
  #app {
    font-family: 'Roboto', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
    min-height: 100%;
  }
</style>
