<template>
  <div id="authorizeContainer">
    <div id="grid">
      <h1>Define the sound of your location.</h1>
      <div id="infoContainer">
        <p>
          ModernJukebox is an app that lets you pick millions of songs from Spotify and add them to the quere of the venue you are in.
        </p>
        <p>
          To use ModernJukebox, you only need a Spotify account.
        </p>
      </div>
      <div id="buttonContainer">
        <button id="authorizeBTN" @click.prevent="login">Sign in with Spotify</button>
        <p>It's free.</p>
      </div>
      <div id="imageContainer">
        <img id="appPreview" />
      </div>
    </div>
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
    min-height: 100vh;
    max-width: 100%;

    overflow: hidden;

    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;


    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  #grid{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  #authorizeContainer h1{
    max-width: 100%;
    font-weight: bold;
    font-size: var(--stageTitle);
    text-align: center;
    grid-area: title;
    margin-top: 2rem;
    padding: 0 1rem;
  }

  #buttonContainer{
    grid-area: button;
    justify-content: center;
    align-items: center;
    display: flex;
    flex-direction: column;
  }

  #buttonContainer p{
    margin-top: .25rem;
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
    font-size: var(--button);
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: bold;
    cursor: pointer;
  }

  #authorizeBTN:hover{
    background: #1f1f1f;
  }

  #infoContainer p{
    font-size: var(--normalText);
    line-height: 1.6rem;
    margin-top: 1rem;
  }

    #infoContainer p:first-of-type{
      margin-top: 0;
    }

  #infoContainer{
    grid-area: infos;
    padding: 0 1rem;
    max-width: 320px;
    margin: 2rem 0;
  }

  #imageContainer{
    grid-area: image;
  }

  #appPreview{
    max-width: 256px;
    margin-top: 2rem;
    margin-bottom: -1rem;
    content:url("../assets/screenPreview.png");
  }

  @media (min-height: 768px){

    #appPreview{
      content:url("../assets/screenPreviewFull.png");
    }

  }

  @media (min-width: 768px){

    #authorizeContainer{
      background-size: auto 200%;
    }

    #grid{
      display: grid;
      grid-template-columns: 40% 40%;
      grid-template-rows: auto auto auto;
      grid-column-gap: 2rem;
      grid-row-gap: 2rem;
      grid-template-areas:
        "title image"
        "infos image"
        "button image";
    }

    #authorizeContainer h1{
      max-width: 800px;
      margin: auto;
      padding: 0 2rem;
    }

    #infoContainer{
      max-width: 800px;
      margin: auto;
      padding: 0 1rem;
    }

    #infoContainer p{
      margin-top: 1rem;
    }

    #infoContainer p:first-of-type{
      margin-top: 0;
    }

    #imageContainer{
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #appPreview{
      content:url("../assets/screenPreviewFull.png");
      margin-top: 0;
    }
  }
</style>
