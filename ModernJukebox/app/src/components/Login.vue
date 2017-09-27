<template>
  <div id="login">
    <button @click="login">Login with Spotify</button>
  </div>
</template>

<script>
  export default {
    data() {
      return {
      }
    },
    methods: {
      login() {
        // window.location = authorizeURL;
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
