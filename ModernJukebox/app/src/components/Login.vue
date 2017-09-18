<template>
  <div id="login">
    <button @click="login">Login with Spotify</button>
  </div>
</template>

<script>
  var SpotifyWebApi = require('spotify-web-api-node');

  var scopes = ['user-read-private', 'user-read-email'],
    redirectUri = 'http://localhost:8080/',
    clientId = 'acda5dc270674c59be88eca853c1d4ff',
    state = 'some-state-of-my-choice';

  // Setting credentials can be done in the wrapper's constructor, or using the API object's setters.
  var spotifyApi = new SpotifyWebApi({
    redirectUri: redirectUri,
    clientId: clientId
  });

  // Create the authorization URL
  var authorizeURL = spotifyApi.createAuthorizeURL(scopes, state);

  // window.location = authorizeURL;

  // https://accounts.spotify.com:443/authorize?client_id=5fe01282e44241328a84e7c5cc169165&response_type=code&redirect_uri=https://example.com/callback&scope=user-read-private%20user-read-email&state=some-state-of-my-choice
  console.log(authorizeURL);

  export default {
    data() {
      return {
      }
    },
    methods: {
      login() {
        window.location = authorizeURL;
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #login {
    display: flex;
    width: 100vw;
    height: 100vh;
    background-color: #263238;
    justify-content: center;
    align-items: center;
  }
  button {
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
