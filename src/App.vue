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
  @import url('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700');

  :root{

    --smallText: 0.85rem;
    --normalText: 1rem;
    --title: 1.8rem;
    --button: 1.1rem;
    --stageTitle: 3rem;

  }

  * {
    margin: 0;
    padding: 0;
  }
  html, body {
    min-height: 100vh;
    position: relative;
  }

  #app {
    background: #282828;
    font-family: 'Roboto Condensed', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #282828;
    min-height: 100vh;
  }

  img {
    image-rendering: -webkit-optimize-contrast;
  }
  
</style>
