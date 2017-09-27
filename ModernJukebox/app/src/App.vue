<template>
  <div id="app">
    <search v-if="loggedIn"></search>
    <button @click="login">Login</button>
  </div>
</template>

<script>
  // import Hello from './components/Hello.vue';
  import Search from './components/Search.vue'
  // import Login from './components/Login.vue'

  var SpotifyWebApi = require('spotify-web-api-node');

  var scopes = ['user-read-private', 'user-read-email'],
  state = 'some-state-of-my-choice';

  // credentials are optional
  var spotifyApi = new SpotifyWebApi({
    clientId : 'acda5dc270674c59be88eca853c1d4ff',
    clientSecret : '804ae867f1ad414ab4b2f1a3b68f4bcc',
    redirectUri : 'http://localhost:8080/'
  });

  // Create the authorization URL
  var authorizeURL = spotifyApi.createAuthorizeURL(scopes, state);

  // https://accounts.spotify.com:443/authorize?client_id=5fe01282e44241328a84e7c5cc169165&response_type=code&redirect_uri=https://example.com/callback&scope=user-read-private%20user-read-email&state=some-state-of-my-choice
  // console.log(authorizeURL);

  export default {
    name: 'app',
    data() {
      return {
        loggedIn: false,
        access_token: '',
        data: '',
        code: '',
      }
    },
    components: {
      'search': Search
      // 'login': Login
    },
    methods: {
      login() {
        window.location = authorizeURL,

        this.$http.post('https://accounts.spotify.com/api/token', {
          form: {
            grant_type: 'authorization_code',
            code: window.location.search.replace("?code=", "").split("&")[0],
            redirect_uri: redirectUri
          },
          headers: {
            Authorization: 'Basic ' + (new Buffer(clientId + ':' + clientSecret).toString('base64'))
          }
        }),

        // this.access_token = 'BQDuPEerrxdttIcSHbZCYlre3tngjI_9UX1Nt4AezRIbgy-vJndUhjTDeQZJjcS_8YntE7OLoqhK3GI1aZaXg-eoCze8s9M3rMyzmEefgE-ZIHEJrFkAhOgoO69sllTkS82TwIIWdwuEpqE',
        // spotifyApi.setAccessToken(this.access_token),
        // console.log(this.access_token),
        spotifyApi.getArtistAlbums('43ZHCT0cAZBISjO8DG9PnE')
          .then(function(data) {
            console.log('Artist albums', data.body);
          }, function(err) {
            console.error(err);
          })
      }
    },
    created() {
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
