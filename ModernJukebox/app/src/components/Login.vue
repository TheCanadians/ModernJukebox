<template>
  <div id="login">
    <button @click="login">Login with Spotify</button>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        client_id: 'acda5dc270674c59be88eca853c1d4ff', // Your client id
        redirect_uri: 'http://localhost:8080/', // Your redirect uri
        state: this.generateRandomString(16),
        scope: 'user-read-private user-read-email',
        url: 'https://accounts.spotify.com/authorize',
        stateKey: 'spotify_auth_state',
      }
    },
    methods: {
      login() {
        localStorage.setItem(this.stateKey, this.state),
        this.url += '?response_type=token',
        this.url += '&client_id=' + encodeURIComponent(this.client_id),
        this.url += '&scope=' + encodeURIComponent(this.scope),
        this.url += '&redirect_uri=' + encodeURIComponent(this.redirect_uri),
        this.url += '&state=' + encodeURIComponent(this.state),
        window.location = this.url,
        this.$emit('loggedIn')
      },
      generateRandomString(length) {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for (var i = 0; i < length; i++) {
          text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
      },
      getHashParams() {
        var hashParams = {};
        var e, r = /([^&;=]+)=?([^&;]*)/g,
            q = window.location.hash.substring(1);
        while ( e = r.exec(q)) {
           hashParams[e[1]] = decodeURIComponent(e[2]);
        }
        return hashParams;
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
