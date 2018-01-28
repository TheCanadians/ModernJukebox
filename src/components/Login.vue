<template>
  <div id="authorizeContainer">

    <section class="skew" id="stage">
      <div class="content">
        <h1>ModernJukebox</h1>
        <p>Your Social Jukebox</p>
        <button class="landingButton" id="authorizeBTN" @click.prevent="login">Sign in with Spotify</button>
      </div>
    </section>

    <section class="skew" id="stageImage" />

    <section class="skew" id="productInfo">
      <div class="content">
        <h2>Define the sound of your location</h2>
        <p>ModernJukebox is an app that lets you pick millions of songs from Spotify and add them to the queue of the venue you are in. To use ModernJukebox, you only need a Spotify account.</p>
        <img src="../assets/home_phone.png" alt="">
      </div>
    </section>

    <section class="skew" id="workingInfo">
      <div class="content">
        <h2>How it works</h2>
        <p>If you want to host your own social jukebox, you can use our host app. Create and manage a playlist for your next party, restaurant or wedding. There are endless possibilities as a host.</p>
        <img src="../assets/search_phone.png" alt="">
      </div>
    </section>

    <section class="skew" id="hostInfo">
      <div class="content">
        <h2>Host your own social playlist</h2>
        <p>If you want to host your own social jukebox, you can use our host app. Create and manage a playlist for your next party, restaurant or wedding. There are endless possibilities as a host.</p>
        <div id="buttonWrapper"><button class="landingButton" id="hostAppBTN">start hosting</button></div>
        <img src="../assets/search_phone.png" alt="">
      </div>

      <div id="footer">
        <div><img src="../assets/logo.png" alt=""></div>
        <a href="">legal notice</a>
      </div>
    </section>

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
  section{
    width: 100%;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  button{
    font-family: 'Roboto Condensed', sans-serif;
  }

  .skew{
    margin-top: -4vh;
  }

  .content{
    max-width: 272px;
    margin-top: 4rem;
  }

  .content h2{
    font-size: var(--title);
  }

  .content p{
    margin-top: 1rem;
  }

  .content button{
    margin-top: 2rem;
  }

  .content img{
    margin-top: 2rem;
    width: 100%;
  }

  .landingButton{
    background: #282828;
    color: #FFDE22;
    padding: 1rem 2rem;
    border: none;
    border-radius: 10rem;
    font-size: var(--button);
    text-transform: uppercase;
    font-weight: bold;
  }



  #authorizeContainer {
    min-height: 100vh;
    max-width: 100%;

    overflow: hidden;
  }



  #stage{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  #stage .content{
    min-height: 448px;
    margin-top: 0;

    color: #282828;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  #stage.skew{
    clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 4vh), 0% 100%);
  }

  #stage .content h1{
    font-size: var(--stageTitle);
  }

  #stage .content p{
    font-size: var(--stageSubtitle);
  }



  #stageImage{
    min-height: 448px;

    background-image: url(../assets/people_dancing.png);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  #stageImage.skew{
    clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
  }



  #productInfo.skew{
    clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% calc(100% - 4vh), 0% 100%);
  }

  #productInfo .content{
    max-height: 576px;
    overflow: hidden;
  }



  #workingInfo.skew{
    clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
  }

  #workingInfo .content{
    max-height: 512px;
    overflow: hidden;
  }

  #hostInfo{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  #hostInfo.skew{
    clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% 100%, 0% 100%);
  }

  #hostInfo .content{
    max-height: 640px;
    overflow: hidden;
    color: #282828;
  }

  #buttonWrapper{
    text-align: center;
  }

  #hostAppBTN{
    align-self: center;
  }


  #footer{
    width: 100%;
    padding: 1rem 2rem;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  #footer img{
    height: 40px;
    width: auto;
    margin-right: 2rem;
  }

  @media (min-width: 768px){
    .content{
      max-width: none;
      max-height: 384px !important;

      margin-top: 8rem;

      display: grid;
      grid-template-columns: 272px 272px;
      grid-template-rows: max-content auto;
    }

    .content h2{
      grid-area: headline;
    }

    .content p{
      grid-area: copy;
    }

    .content img{
      grid-area: image;
      margin-top: 0;
    }

    #productInfo .content{
      grid-template-areas: 
        "headline image"
        "copy image";
    }

    #workingInfo .content{
      grid-template-areas: 
        "image headline"
        "image copy";
    }

    #hostInfo .content{
      grid-template-rows: max-content max-content auto;
      grid-template-areas: 
        "headline image"
        "copy image"
        "button image";
    }
  }

</style>
