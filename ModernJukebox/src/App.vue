<template>
  <div id="app">
    <search></search>
  </div>
</template>

<script>
  // import Hello from './components/Hello.vue';
  import Search from './components/Search.vue'

  export default {
    name: 'app',
    components: {
      // 'hello': Hello,
      'search': Search
    }
  }
</script>

<style>
  @import "~normalize.css/normalize.css";
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
</style>
