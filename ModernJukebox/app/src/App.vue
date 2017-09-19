<template>
  <div id="app">
    <search v-if="loggedIn"></search>
    <login @loggedIn="login"></login>
  </div>
</template>

<script>


  // import Hello from './components/Hello.vue';
  import Search from './components/Search.vue'
  import Login from './components/Login.vue'

  export default {
    name: 'app',
    data() {
      return {
        loggedIn: false
      }
    },
    components: {
      'search': Search,
      'login': Login
    },
    methods: {
      login() {
        // this.loggedIn = true
      }
    },
    created() {
      this.$http.get('https://api.spotify.com/v1/artists/0TnOYISbd1XYRBk9myaseg')
      // console.log('https://api.spotify.com/v1/artists/0TnOYISbd1XYRBk9myaseg');
    }
  }
</script>

<style>
  @import "~normalize.css/normalize.css";
  @import url('https://fonts.googleapis.com/css?family=Roboto:300,500,700');
  #app {
    font-family: 'Roboto';
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #616161;
    width: 100vw;
  }

  #login {
    display: flex;
    width: 100vw;
    height: 100vh;
    background-color: #263238;
    justify-content: center;
    align-items: center;
  }
  #loginButton {
    display: inline;
    background: none;
    border: none;
    background-color: #0097A7;
    border-radius: 8px;
    font-family: 'Roboto';
    text-transform: uppercase;
    color: #fff;
    font-weight: 700;
    padding: 1rem;
  }
</style>
