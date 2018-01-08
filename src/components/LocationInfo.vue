<template>
  <section id="locationInfo">
    <div id="infoInside">
      <img id="closeBTN" src="../assets/ic_close.svg" @click="this.closeLocationInfo" />
      <h3>{{this.name}}</h3>

      <section id="blacklist">
        <p>
          These genres are not allowed:
        </p>
        <ul>
          <li id="genreTag" v-for="genre in blacklist">
            {{genre}}
          </li>
        </ul>
      </section>

      <p>
        The maximum amount of songs you can add: {{this.limit}}
      </p>
      <p>
        The maximum amount of songs in this playlist: {{this.maxQueue}}
      </p>

      <button id="checkoutBTN" @click="this.checkout">Checkout from location</button>
      <button id="logoutBTN" @click="this.logout">Logout from Spotify</button>
    </div>
  </section>
</template>

<script>
  export default {
    name: 'locationInfo',
    props: ['name', 'limit', 'maxQueue', 'blacklist'],
    methods: {
      closeLocationInfo() {
        this.$emit('closedLocationInfo')
      },
      logout() {
        this.$emit('loggedOut')
      },
      checkout() {
        this.$emit('checkedOut')
      }
    }
  }
</script>

<style>

  #locationInfo {
    height: 100vh;
    width: 100vw;
    position: absolute;
    top: 0;
    left: 0;
    background-color: #FFDE22;
    color: var(--textColorDark);
  }

  #infoInside{
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 1rem;
  }

  #closeBTN{
    align-self: flex-end;
    padding: .5rem;
    cursor: pointer;
  }

  #locationInfo h3{
    font-size: var(--title);
  }

  #locationInfo #blacklist p:first-of-type{
    padding-top: 1.5rem;
  }

  #locationInfo #blacklist ul{
    display: flex;
    max-width: 100%;
    flex-wrap: wrap;
  }

  #genreTag{
    list-style: none;
    background: #282828;
    border-radius: 10rem;
    padding: .5rem .75rem;
    font-size: var(--smallText);
    color: #FFDE22;
    text-transform: uppercase;
    font-weight: bold;
    white-space: nowrap;
    margin-top: .5rem;
    margin-right: .5rem;
    cursor: default;
  }

  #locationInfo p{
    padding-top: 1rem;
  }

  #checkoutBTN, #logoutBTN{
    background: #282828;
    color: #FFDE22;
    text-transform: uppercase;
    font-weight: bold;
    border: none;
    border-radius: 10rem;
    padding: 1rem 2rem;
    font-size: var(--button);
    font-family: 'Roboto Condensed', sans-serif;
    align-self: center;
    position: absolute;
    cursor: pointer;
  }

  #checkoutBTN:hover, #logoutBTN:hover{
    background: #1f1f1f;
  }

  #checkoutBTN{
    bottom: 10rem;
  }

  #logoutBTN{
    bottom: 4rem;
  }

</style>
