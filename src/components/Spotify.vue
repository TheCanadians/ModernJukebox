<template>
  <div id="spotify">
    <button v-if="!loggedIn" @click.prevent="authorize">Authorize</button>

    <restaurantChooser v-if="loggedIn" @setRestaurant="setRestaurant"></restaurantChooser>

    <div id="restaurantChosen" v-if="restaurant">
      <transition name="notification">
        <div
          class="notification"
          v-if="notificationShowing"
          enter-active-class="notificationIn"
          leave-active-class="notificationOut">
          Song added
        </div>
      </transition>

      <search v-if="loggedIn" @keyedUp="searchTracks($event)" @cleared="clearSearch()"></search>

      <div id="currentTrack" v-if="!searching && loggedIn">
        <h2>Now playing:</h2>
        <ul>
          <li>
            <div class="infos">
              <p>
                {{active.title}}
                <span>{{active.artist}}</span>
              </p>
            </div>
          </li>
        </ul>
      </div>

      <ul id="results" v-if="searching">
        <li v-for="track in tracks">
          <button @click="addTrack(track)">{{track.name}} &ndash; {{track.artists[0].name}}</button>
        </li>
      </ul>

      <queue
        v-if="loggedIn && userid!='' && queue!=''"
        :userid="this.userid"
        :accessToken="this.accessToken"
        :queue="this.queue"
        @getQueue="this.getQueue"
      ></queue>
    </div>
  </div>
</template>

<script>
  import Search from './Search.vue';
  import RestaurantChooser from './RestaurantChooser.vue';
  import Queue from './Queue.vue';
  import {db} from '../firebase';

  export default {
    name: 'Spotify',
    components: {
      'search': Search,
      'restaurantChooser': RestaurantChooser,
      'queue': Queue
    },
    firebase: {
      queue: db.ref('schweinske-dehnhaide').child('songs').orderByChild('votes')
    },
    data () {
      var accessToken
      var isAccessTokenPresent = window.location.href.indexOf('access_token') !== -1
      if (isAccessTokenPresent) {
        accessToken = window.location.href.split('access_token=')[1].split('&')[0];
        this.$router.push('/');
      }
      return {
        loggedIn: isAccessTokenPresent,
        accessToken: accessToken,
        tracks: [],
        searchQuery: '',
        newId: '',
        newTitle: '',
        newArtist: '',
        newDuration: '',
        newVotes: 0,
        voters: false,
        userid: '',
        playing: false,
        nextSong: false,
        notificationShowing: false,
        searching: false,
        restaurant: false,
        active: false
      }
    },
    methods: {
      authorize: () => {
        let clientId = 'acda5dc270674c59be88eca853c1d4ff'
        let scopes = 'user-read-private user-read-email user-read-birthdate user-library-read streaming user-read-playback-state user-modify-playback-state user-read-currently-playing playlist-modify-public playlist-modify-private'
        // Authorize Spotify user
        let url = 'https://accounts.spotify.com/authorize?'
        let query = 'response_type=token&client_id=' + clientId + '&scope=' + scopes
        let urlWithQueryString = url + '&' + query
        window.location.assign(urlWithQueryString + '&redirect_uri=' + window.location.href.split('#/')[0])
      },
      getQueue: function() {
        this.queue.length = 0,
        db.ref('schweinske-dehnhaide').child('songs').orderByChild('votes').on('value', snapshot => {
          snapshot.forEach(child => {
            this.queue.push(child.val())
          })
        }),
        this.setCurrentTrack()
      },
      setUserId: function() {
        this.axios({
          url: 'https://api.spotify.com/v1/me/',
          headers: {'Authorization': 'Bearer ' + this.accessToken},
          method: 'GET'
        }).then((res) => {
          if (res.status === 401) {
            throw new Error('Unauthorized')
          } else {
            if (res.data !== undefined) {
              this.userid = res.data.id
            }
          }
          return this.userid
        })
      },
      clearSearch: function() {
        this.searching = false
      },
      searchTracks: function (query) {
        this.searching = true,
        this.tracks = [],
        this.axios({
          url: 'https://api.spotify.com/v1/search?q=' + query + '&type=track&market=DE',
          headers: {'Authorization': 'Bearer ' + this.accessToken},
          method: 'GET'
        }).then((res) => {
          if (res.status === 401) {
            throw new Error('Unauthorized')
          } else {
            if (res.data !== undefined) {
              this.tracks = res.data.tracks.items
            }
          }
        })
      },
      addTrack: function(event) {
        this.newId = event.id,
        this.newTitle = event.name,
        this.newArtist = event.artists[0].name,
        this.newDuration = event.duration_ms,
        this.newVotes = 0,
        db.ref('schweinske-dehnhaide').child('songs').child(this.newId).set({
          id: this.newId,
          artist: this.newArtist,
          duration: this.newDuration,
          title: this.newTitle,
          userid: this.userid,
          votes: this.newVotes,
          voters: this.voters,
          playing: this.playing,
          nextSong: this.nextSong
        }),
        this.toggleShow(),
        this.newId = '',
        this.newTitle = '',
        this.newArtist = '',
        this.newDuration = '',
        this.getQueue(),
        this.searching = false
      },
      toggleShow: function() {
        this.notificationShowing = !this.notificationShowing,
        setTimeout(() => this.notificationShowing = !this.notificationShowing, 3000);
      },
      setRestaurant(restaurant) {
        this.restaurant = restaurant
        this.getQueue()
      },
      setCurrentTrack() {
        this.active = false

        for (var i = 0; i < this.queue.length; i++) {
          if(this.queue[i].playing)
            this.active = this.queue[i]
        }
      }
    },
    mounted() {
      this.setUserId()
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .notification {
    background-color: #424242;
    color: #fff;
    font-size: 1rem;
    border-radius: 400px;
    display: inline-block;
    padding: 16px 24px;
    position: absolute;
    top: 120px;
    right: 48px;
  }
  .tracks {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      color: green;
      background-color: white;
      border: black;
  }

  h1,
  h2 {
      font-weight: normal;
  }

  ul {
      list-style-type: none;
      padding: 0;
  }

  button {
    background: none;
    box-shadow: none;
    outline: none;
    border: none;
    font-size: 1rem;
    font-family: 'Roboto', sans-serif;
  }
  button:hover {
      color: #0097A7;
      text-decoration: underline;
      cursor: pointer;
  }

  /* Add some padding inside the card container */
  .container {
      padding: 2px 16px;
      display: flex;
      flex-direction: column;

  }
  .container a {
    text-align: left;
    order:2;
  }
  .container img {
    order:1;
  }
  .container audio {
    order:3;
  }

  .notificationIn {
    animation: notificationIn 0.3s linear both;
  }
  .notificationOut {
    animation: notificationOut 0.3s linear both;
  }

  @-webkit-keyframes notificationIn {
    0%   { right: -200px; }
    100% { right: 48px; }
  }
  @-webkit-keyframes notificationOut {
    0%   { right: 48px; }
    100% { right: -200px; }
  }
</style>
