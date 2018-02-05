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
        <h2>Define the sound of your location.</h2>
        <p>ModernJukebox is an app that lets you pick millions of songs from Spotify and add them to the queue of the venue you are in. To use ModernJukebox, you only need a Spotify account.</p>
        <img src="../assets/home_phone.png" alt="">
      </div>
    </section>

    <section class="skew" id="workingInfo">
      <div class="content">
        <h2>Get the party started.</h2>
        <p>Using ModernJukebox is easy as pie! You just log in to Spotify, check in at a venue that has a running ModernJukebox system and your good to go. Search and vote for songs and bring up the mood at your location!</p>
        <img src="../assets/search_phone.png" alt="">
      </div>
    </section>

    <section class="skew" id="hostInfo">
      <div class="content">
        <h2>Host your own social playlist.</h2>
        <p>If you want to host your own social jukebox, you can use our host app. Create and manage a playlist for your next party, restaurant or wedding. There are endless possibilities as a host.</p>
        <a href="http://dennisschmidt.net/jukebox" class="landingButton" id="hostAppBTN">start hosting</a>
        <img src="../assets/host_phone.png" alt="">
      </div>

      <div id="footer">
        <div><img src="../assets/logo.png" alt=""></div>
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

    display: -webkit-box;

    display: -ms-flexbox;

    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }

  button{
    font-family: 'Roboto Condensed', sans-serif;
  }

  .skew{
    margin-top: -4vh;
  }

  #stage.skew{
    -webkit-clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 4vh), 0% 100%);
            clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 4vh), 0% 100%);
  }

  #stageImage.skew{
    -webkit-clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
            clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
  }

  #productInfo.skew{
    -webkit-clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% calc(100% - 4vh), 0% 100%);
            clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% calc(100% - 4vh), 0% 100%);
  }

  #workingInfo.skew{
    -webkit-clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
            clip-path: polygon( 0% calc(0% + 4vh), 100% 0%, 100% 100%, 0% calc(100% - 4vh));
  }

  #hostInfo.skew{
    -webkit-clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% 100%, 0% 100%);
            clip-path: polygon( 0% 0%, 100% calc(0% + 4vh), 100% 100%, 0% 100%);
  }

  .content{
    max-width: 272px;
    margin-top: 64px;
    margin-top: 4rem;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
  }

  .content h2{
    font-size: var(--title);
  }

  .content p{
    margin-top: 16px;
    margin-top: 1rem;
  }

  .content img{
    margin-top: 32px;
    margin-top: 2rem;
    width: calc(100% - 3rem);
    -ms-flex-item-align: center;
        align-self: center;
  }

  .landingButton{
    margin-top: 32px;
    margin-top: 2rem;
    background: #282828;
    color: #FFDE22;
    padding: 16px 32px;
    padding: 1rem 2rem;
    border: none;
    border-radius: 10rem;
    font-size: var(--button);
    text-transform: uppercase;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
  }

  .landingButton:hover{
    background: #1E1E1E;
  }



  #authorizeContainer {
    min-height: 100vh;
    max-width: 100%;

    overflow: hidden;
  }



  #stage{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), -webkit-linear-gradient(top, #FFDE22 2%, #E69D00 100%);
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: 200%;
    background-repeat: no-repeat;
    background-position: center;
  }

  #stage .content{
    min-height: 448px;
    margin-top: 0;

    color: #282828;

    display: -webkit-box;

    display: -ms-flexbox;

    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
  }

  #stage .content h1{
    font-size: var(--stageTitle);
  }

  #stage .content p{
    font-size: var(--stageSubtitle);
    font-weight: lighter;
  }



  #stageImage{
    min-height: 448px;

    background-image: url(../assets/people_dancing.png);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }



  #productInfo .content{
    max-height: 544px;
    overflow: hidden;
  }



  #workingInfo .content{
    max-height: 544px;
    overflow: hidden;
  }

  #hostInfo{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), -webkit-linear-gradient(top, #FFDE22 2%, #E69D00 100%);
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  #hostInfo .content{
    max-height: 640px;
    overflow: hidden;
    color: #282828;
  }

  #hostAppBTN{
    -ms-flex-item-align: center;
        align-self: center;
  }

  #footer{
    width: 100%;
    padding: 32px;
    padding: 2rem;

    display: -webkit-box;

    display: -ms-flexbox;

    display: flex;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }

  #footer img{
    height: 40px;
    width: auto;
  }

  @media (min-width: 768px){
    .content{
      max-width: none;
      max-height: 384px !important;

      margin-top: 8rem;

      display: -ms-grid;

      display: grid;
      -ms-grid-columns: 272px 272px;
          grid-template-columns: 272px 272px;
      -ms-grid-rows: max-content auto;
          grid-template-rows: max-content auto;
    }

    .content img{
      grid-area: image;
      margin-top: 0;
      padding: 0 1.5rem
    }

    .landingButton{
      margin-top: 4rem;
    }

    .skew{
      margin-top: -8vh;
    }

    #stage.skew{
      -webkit-clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 8vh), 0% 100%);
              clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 8vh), 0% 100%);
    }

    #stageImage.skew{
      -webkit-clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
              clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
    }

    #productInfo.skew{
      -webkit-clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% calc(100% - 8vh), 0% 100%);
              clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% calc(100% - 8vh), 0% 100%);
    }

    #workingInfo.skew{
      -webkit-clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
              clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
    }

    #hostInfo.skew{
      -webkit-clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% 100%, 0% 100%);
              clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% 100%, 0% 100%);
    }

    

    #stage{
      background-size: 150%;
    }

    #stage .content{
      min-height: 704px;
    }

    #stage .content h1{
      font-size: 4rem;
    }

    #stage .content p{
      font-size: 2.4rem;
    }

    #stageImage {
      min-height: 512px;
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

    #hostInfo{
      background-size: 150%;
    }

    #hostInfo .content{
      margin-bottom: 5rem;

      -ms-grid-columns: 272px 272px;

          grid-template-columns: 272px 272px;
      -ms-grid-rows: max-content max-content auto;
          grid-template-rows: max-content max-content auto;
      grid-template-areas: 
        "headline image"
        "copy image"
        "button image";
    }

    #hostAppBTN{
      -ms-flex-item-align: start;
          align-self: flex-start;
      justify-self: center;
    }
  }

  @media (min-width: 1024px){
    .content{
      margin-top: 12rem;
      -ms-grid-columns: 400px 400px;
          grid-template-columns: 400px 400px;
      max-height: 512px !important;
    }

    .content h2{
      grid-area: headline;
      font-size: 3.5rem;
    }

    .content p{
      grid-area: copy;
      font-size: 1.4rem;
    }

    .content img{
      width: calc(100% - 6rem);
      padding: 0 3rem
    }

    #stageImage {
      min-height: 704px;
    }

    #hostInfo .content{
      margin-bottom: 7rem;

      -ms-grid-columns: 400px 400px;

          grid-template-columns: 400px 400px;
    }
  }

</style>
