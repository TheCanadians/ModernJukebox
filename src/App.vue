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

    --mainColor: #FFDE22;
    --bgColor: #282828;

    --textColorDark: #282828;
    --textColorGray: #8c8c8c;
    --textColorLight: #FFF;

    --title: 1.8rem;
    --normalText: 1rem;
    --smallText: 0.85rem;

    --stageTitle: 2.5rem;
    --stageSubtitle: 1.5rem;
    --button: 1.1rem;

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
    color: var(--textColorLight);
    min-height: 100vh;
    text-decoration-skip: ink;
  }

  img {
    image-rendering: -webkit-optimize-contrast;
  }

</style>
