<template>
  <div id="spotify">
    <div v-if="!loggedIn" id="authorizeContainer">
      <h1>Choose your stuff</h1>
      <button id="authorizeBTN" @click.prevent="authorize">Sign in with Spotify</button>
    </div>

    <restaurant-chooser v-if="loggedIn" @setRestaurant="setRestaurant"></restaurant-chooser>

    <div id="restaurantChosen" v-if="restaurant">
      <transition name="notification">
        <span
          class="notification"
          v-if="notificationShowing"
          enter-active-class="notificationIn"
          leave-active-class="notificationOut">
          {{this.notificationText}}
        </span>
      </transition>

      <search ref="search" v-if="loggedIn" @keyedUp="searchTracks($event)"></search>

      <div id="currentTrack" v-if="!searching && loggedIn">
        <ul>
          <li id="trackInfo">
            <div class="infos">
              <img id="songImage" :src="active.image" />
              <p>
                <span>Now playing:</span>
                <span>{{active.title}} · {{active.artists}}</span>
              </p>
            </div>
          </li>
        </ul>
      </div>

      <ul id="results" v-if="this.searching">
        <li v-for="track in tracks">
          <p>
            <span class="title">{{track.name}}</span>
            <template v-for='(artist, index) in track.artists'>
             <span class="artist">{{artist.name}}<template v-if="index + 1 < track.artists.length">, </template></span>
           </template>
          </p>
          <button @click="addTrack(track)">Add</button>
        </li>
      </ul>

      <queue
        ref="queue"
        v-if="loggedIn && userid!='' && this.list!='Queue is empty. Why not add some songs?' "
        :trackToUpvote="this.trackToUpvote"
        :userid="this.userid"
        :accessToken="this.accessToken"
        :list="this.list"
        :restaurant="this.restaurant"
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
        searching: false,
        trackExists: false,
        trackToUpvote: {},
        vote: false,

        newId: '',
        newTitle: '',
        newArtists: [],
        newDuration: '',
        newVotes: -1,
        newImage: '',
        voters: false,
        userid: '',
        playing: false,
        nextSong: false,

        notificationText: '',
        notificationShowing: false,
        restaurant: false,
        active: false,
        list: [],
        limit: 0,
        maxQueue: 0,
        limitReached: false,
        search: this.$refs.search,
        queue: this.$refs.queue

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
        this.list.length = 0
        this.setLimit()
        db.ref(this.restaurant.id).child('songs').orderByChild('votes').on('value', snapshot => {
          if(snapshot.val() == null) {
            this.list.push('Queue is empty. Why not add some songs?')
          }
          else {
            snapshot.forEach(child => {
              this.list.push(child.val())
            })
          }
        })
        this.checkLimit()
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
      setLimit() {
        db.ref(this.restaurant.id).child('limit').on('value', snapshot => {
          this.limit = snapshot.val()
        })
        db.ref(this.restaurant.id).child('maxQueue').on('value', snapshot => {
          this.maxQueue = snapshot.val()
        })
      },
      checkLimit() {
        let songCounter = 0;
        if (this.list.length >= this.maxQueue) {
          this.limitReached = true
        }
        else {
          for (var i = 0; i < this.list.length; i++) {
            songCounter++;
          }
          if (songCounter < this.limit) {
            this.limitReached = false
          }
          else {
            this.limitReached = true
          }
        }

        return this.limitReached
      },
      addTrack: function(event) {
        this.checkLimit()
        if(!this.limitReached) {
          this.newId = event.id,
          this.newTitle = event.name
          for (var i = 0; i < event.artists.length; i++) {
            this.newArtists.push(event.artists[i].name)
          }
          this.newDuration = event.duration_ms,
          this.newVotes = -1,
          this.newImage = event.album.images[1].url

          for (var i = 0; i < this.list.length; i++) {
            if(this.newId == this.list[i].id) {
              this.trackExists = true
              this.trackToUpvote = this.list[i]
            }
          }

          if(this.trackExists) {
            this.notificationText = 'Song was already in list. Upvoted.'
            this.toggleShow()
            this.$refs.queue.upvoteTrack(this.trackToUpvote)
          }
          else {
            console.log('Track does not exist')
            db.ref(this.restaurant.id).child('songs').child(this.newId).set({
              id: this.newId,
              artists: this.newArtists,
              duration: this.newDuration,
              title: this.newTitle,
              userid: this.userid,
              votes: this.newVotes,
              voters: this.voters,
              playing: this.playing,
              nextSong: this.nextSong,
              image: this.newImage,
            })
            this.notificationText = 'Song added'
            this.toggleShow()
          }

          this.newId = '',
          this.newTitle = '',
          this.newArtists = [],
          this.newDuration = '',
          this.newImage = '',
          this.trackExists = false,
          this.getQueue()
        }
        else {
          this.notificationText = 'Limit reached. Song was not added.',
          this.toggleShow()
        }

        this.searching = false,
        this.$refs.search.clearSearch()
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

        for (var i = 0; i < this.list.length; i++) {
          if(this.list[i].playing)
            this.active = this.list[i]
        }
      }
    },
    mounted() {
      if(this.loggedIn) {
        this.setUserId()
      }
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
    font-family: 'Roboto Condensed', sans-serif;
  }
  button:hover {
      color: #0097A7;
      text-decoration: underline;
      cursor: pointer;
  }

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
    max-width: 200px;
    font-weight: bold;
    font-size: 3rem;
    text-align: center;
    padding-bottom: 3rem;
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
  }

  #restaurantChosen {
    max-width: 100vw;
  }

  #currentTrack {
    background: #FFDE22;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-direction: row;
  }

  #trackInfo {
    padding: 16px 24px;
  }

  #trackInfo .infos {
    display: flex;
  }

  #trackInfo .infos span:first-of-type{
    margin-left: 0;
    font-size: 10pt;
    font-weight: bold;
    text-transform: uppercase;
  }

  #trackInfo .infos span{
    display: block;
    font-size: inherit;
    margin-top: 2px;
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
