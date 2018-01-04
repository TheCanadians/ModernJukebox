<template>
  <div id="authorizeContainer">
    <h1>Define the sound of the venue you're in</h1>
    <p>
      ModernJukebox is an app that lets you pick millions of songs from Spotify and add them to the quere of the venue you are in.
    </p>
    <p>
      To use ModernJukebox, you only need a Spotify account.
    </p>
    <button id="authorizeBTN" @click.prevent="login">Sign in with Spotify</button>
    <p>It's free.</p>
  </div>
</template>

<script>
  export default {
    name: 'login',
    methods: {
      login() {
        this.$emit('loggingIn')
      }
    }
  }
</script>

<style>
  #authorizeContainer {
    height: 100vh;
    width: 100vw;

    background: linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);

    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  #authorizeContainer h1{
    max-width: 320px;
    padding: 0rem 2rem;
    font-weight: bold;
    font-size: 2rem;
    text-align: center;
    padding-bottom: 1rem;
  }

  #authorizeBTN {
    background: #2B2B2B;
    color: #FFDE22;
    box-shadow: none;
    outline: none;
    border: none;
    border-radius: 10rem;
    text-transform: uppercase;
    padding: 1rem 2rem;
    font-size: 1rem;
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: bold;
    margin-top: 1rem;
  }

  #authorizeContainer p{
    font-size: 1rem;
    max-width: 320px;
    padding: 0rem 1rem;
    line-height: 1.6rem;
    padding-top: 1rem;
  }
</style>
