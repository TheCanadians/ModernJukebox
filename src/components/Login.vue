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
</style>
