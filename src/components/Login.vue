<template>
      <div id="authorizeContainer">
        <section id="stage">
          <div class="skew">
            <div class="inside">
              <h1>qBox</h1>
              <h2>Your Social Jukebox</h2>
              <button id="authorizeBTN" @click.prevent="login">Sign in with Spotify</button>
            </div>
          </div>
        </section>

        <section id="stageImage">
          <div class="skew">
            <div class="pseudoContent"></div>
          </div>
        </section>

        <section id="productInfo">
          <div class="skew">
            <div class="inside">
              <h1>Define the sound of your location.</h1>
              <p>ModernJukebox is an app that lets you pick millions of songs from Spotify and add them to the queue of the venue you are in.
                <br>
                <br>
                To use ModernJukebox, you only need a Spotify account.
              </p>
              <img class="productImage" src="../assets/home_phone.png" />
            </div>
          </div>
        </section>

        <section id="howTo">
          <div class="skew">
            <div class="inside">
              <h1>How it works</h1>
              <p>If you want to host your own social jukebox, you can use our host app. Create and manage a playlist for your next party, restaurant or wedding. There are endless possibilities as a host.</p>
              <img class="productImage" src="../assets/search_phone.png" />
            </div>
          </div>
        </section>

        <section id="hostInfo">
          <div class="skew">
            <div class="inside">
              <h1>Host your own social playlist.</h1>
              <p>If you want to host your own social jukebox, you can use our host app. Create and manage a playlist for your next party, restaurant or wedding. There are endless possibilities as a host.</p>
              <img class="productImage" src="../assets/home_phone.png" />
            </div>
          </div>
          <div id="footer">
            <img src="../assets/placeholder.png"/>
            <p>© 2018 [Name]</p>
            <a>legal notice</a>
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
  .pseudoContent{
    height: 512px;
  }

  .skew{
    margin-top: -8.1vh;
    overflow: hidden;
  }

  .skew:before {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    overflow: visible;
    width: 100%;
    min-height: 768px;
    z-index: -1;
    -webkit-backface-visibility: hidden;
    backface-visibility: initial;
  }

  .inside{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 512px;

    margin: auto;
  }

  .productImage{
    grid-area: image;
    width: 100%;
  }



  
  #authorizeContainer {
    min-height: 100vh;
    max-width: 100%;

    overflow: hidden;

    background: #fff;
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;

    display: flex;
    flex-direction: column;
  }

  #authorizeContainer h1{
    max-width: 100%;
    font-weight: bold;
    font-size: var(--stageTitle);
    text-align: center;
    grid-area: title;
    margin-top: 2rem;
    padding: 0 1rem;
    color: var(--textColorDark);
  }

  #authorizeContainer h2{
    font-size: var(--stageSubtitle);
    color: var(--textColorDark);
    font-weight: lighter;
    margin-top: 1.5rem;
  }

  #stage {
    z-index: 1;
    position: relative;
  }

  #stage .skew:before{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: 150% auto;
    background-repeat: no-repeat;
    background-position: center;

    height: 768px;

    clip-path: polygon( 0% 0%, 100% 0%, 100% calc(100% - 8vh), 0% 100%);
  }

  #authorizeBTN {
    background: #2B2B2B;
    color: var(--mainColor);
    box-shadow: none;
    outline: none;
    border: none;
    border-radius: 10rem;
    text-transform: uppercase;
    padding: 1rem 2rem;
    font-size: var(--stageButton);
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: bold;
    cursor: pointer;
    margin-top: 5rem;
  }

  #authorizeBTN:hover{
    background: #1f1f1f;
  }

  #stageImage{
    z-index: 2;
    position: relative;
  }

  #stageImage .skew:before{
    min-height: 512px;

    background-image: url(../assets/people_dancing.png);
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-position: center;

    clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
  }

  #productInfo{
    z-index: 3;
    position: relative;
  }

  #productInfo .skew:before{
    background-color: #282828;

    overflow: hidden;

    clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% calc(100% - 8vh), 0% 100%);
  }

  #productInfo h1{
    grid-area: headline;
    color: #fff;
  }

  #productInfo p{
    grid-area: copy;
  }

  #howTo{
    z-index: 4;
    position: relative;
  }

  #howTo .skew:before{
    background-color: #282828;
    height: 1024px;

    overflow: hidden;

    clip-path: polygon( 0% calc(0% + 8vh), 100% 0%, 100% 100%, 0% calc(100% - 8vh));
  }

  #howTo h1{
    grid-area: headline;
    color: #fff;
  }

  #howTo p{
    grid-area: copy;
  }

  #hostInfo{
    z-index: 5;
    position: relative;
  }

  #hostInfo .skew:before{
    background: #FFDE22;
    background-image: url("../assets/login_background_bubbles.svg"), linear-gradient(-180deg, #FFDE22 2%, #E69D00 100%);
    background-size: 150% auto;
    background-repeat: no-repeat;
    background-position: center;

    height: calc(1024px + 296px);

    overflow: hidden;

    clip-path: polygon( 0% 0%, 100% calc(0% + 8vh), 100% 100%, 0% 100%);
  }

  #hostInfo h1{
    grid-area: headline;
  }

  #hostInfo p{
    grid-area: copy;
    color: #282828;
  }

  @media (min-width: 768px){
    .inside{
      min-height: 768px;
    }

    .pseudoContent{
      height: 768px;
    }

    #authorizeContainer{
      background-size: auto 200%;
    }

    #authorizeContainer h1{
      max-width: 400px;
      padding: 0 2rem;
    }

    #stageImage .skew:before{
      min-height: 768px;
    }

    #productInfo h1{
      padding: 0;
      margin: 0;
      max-width: none;
      text-align: left;
    }

    #productInfo p {
      font-size: var(--landingCopy);
    }

    #productInfo .skew:before{
      height: 1024px;
    }

    #productInfo .inside{
      display: grid;
      grid-template-columns: 440px 576px;
      grid-template-rows: 160px auto;
      grid-column-gap: 60px;
      grid-row-gap: 40px;
      grid-template-areas: 
        "headline image"
        "copy image";

      padding: 200px 0 0;
      align-items: initial;

      height: calc(1024px - 200px);
    }

    #howTo h1{
      padding: 0;
      margin: 0;
      max-width: none;
      text-align: left;
    }

    #howTo p {
      font-size: var(--landingCopy);
    }

    #howTo .inside{
      display: grid;
      grid-template-columns: 576px 440px;
      grid-template-rows: 80px auto;
      grid-column-gap: 60px;
      grid-row-gap: 40px;
      grid-template-areas: 
        "image headline"
        "image copy";

      padding: 200px 0 0;
      align-items: initial;

      height: calc(1024px - 200px);
    }

    #hostInfo h1{
      padding: 0;
      margin: 0;
      max-width: none;
      text-align: left;
    }

    #hostInfo p {
      font-size: var(--landingCopy);
    }

    #hostInfo .inside{
      display: grid;
      grid-template-columns: 440px 576px;
      grid-template-rows: 160px auto;
      grid-column-gap: 60px;
      grid-row-gap: 40px;
      grid-template-areas: 
        "headline image"
        "copy image";

      padding: 200px 0 0;
      align-items: initial;

      height: calc(1024px - 200px);
    }

    #footer{
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 4rem;
    }

    #footer p{
      margin-top: 1rem;
    }
    
    #footer a{
      margin-top: 1rem;
      color: #282828;
      font-weight: lighter;
      font-size: var(--landingCopy);
      text-decoration: underline;
      text-decoration-skip: ink;
      cursor: pointer;
    }

    #footer a:hover{
      font-weight: normal;
    }
  }

  @media (min-width: 1140px){
    #stageImage .skew:before{
      background-size: 100% auto;
    }
  }

</style>
