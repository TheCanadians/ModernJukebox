<template>
  <main id="spotify">
    <login v-if="!loggedIn" @loggingIn="authorize"></login>

    <div v-if="loggedIn">
      <locationInfo
        v-if="this.locationInfo==true"
        :name="this.restaurant.name"
        :limit="this.limit"
        :maxQueue="this.maxQueue"
        :blacklist="this.blacklist"
        @closedLocationInfo="this.hideLocationInfo"
        @loggedOut="this.logout"
        @checkedOut="this.checkout">
      </locationInfo>

      <qrcode-reader
        v-if="!this.restaurant"
        @decode="this.onDecode"
      ></qrcode-reader>

      <restaurant-chooser v-if="!restaurant" @setRestaurant="setRestaurant"></restaurant-chooser>

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

        <section id="currentTrack">
          <!-- <img id="" :src="active.image" /> -->
          <img id="restaurantInfoIcon" src="../assets/ic_info.svg" @click="this.showLocationInfo" />
          <p>
            <span id="nowPlaying">
              {{this.restaurant.name}} <span v-if="active">— Now playing:</span>
            </span>
            <div v-if="active">
              {{active.title}} <span id="separator">·</span>
              <template v-for='(artist, index) in active.artists'>
                <span class="artist"> {{artist}}<template v-if="index + 1 < active.artists.length">, </template></span>
              </template>
            </div>
          </p>
        </section>

        <section id="search">
          <div v-if="!limitReached && !maxQueueReached">
            <search ref="search" @keyedUp="searchTracks($event)" @searchCleared="clearSearch" ></search>
            <p class="searchDisclaimer" id="songsLeft">You can add {{this.calcSongsLeft}} more songs.</p>
          </div>
          <p class="searchDisclaimer" v-if="limitReached">You have added your maximum amount of songs. Wait until one of your songs has been played.</p>
          <p class="searchDisclaimer" v-if="maxQueueReached">The queue is currently full. You can add more songs when the current song is finished.</p>
        </section>

        <results
          v-if="this.searching"
          :tracks="this.tracks"
          @addTrack="addTrack($event)"
        ></results>

        <section v-if="!this.searching">
          <section id="nextSong" v-if="this.nextSong">
            <h2>Coming Up</h2>
            <div id="nextSongInfo">
              <img id="songImage" :src="this.nextSong.image" />
              <li id="infos">
                <span class="title">{{this.nextSong.title}}</span>
                <template v-for='(artist, index) in this.nextSong.artists'>
                  <span class="artist">{{artist}}<template v-if="index + 1 < nextSong.artists.length">, </template></span>
                </template>
              </li>
            </div>
          </section>

          <section id="queue">
            <h2>Queue</h2>
            <p id="emptyQueue" v-if="this.songList=='empty'">
              <span>The queue is empty.</span> <br/> Search for and add a song to get the party started!
            </p>
            <queue
              ref="queue"
              v-if="userid!='' && this.songList!='empty' && !searching "
              :trackToUpvote="this.trackToUpvote"
              :userid="this.userid"
              :accessToken="this.accessToken"
              :songList="this.songList"
              :restaurant="this.restaurant"
            ></queue>
          </section>
        </section>
      </div>
    </div>
  </main>
</template>

<script>
  import Login from './Login.vue';
  import Results from './Results.vue';
  import Search from './Search.vue';
  import RestaurantChooser from './RestaurantChooser.vue';
  import Queue from './Queue.vue';
  import LocationInfo from './LocationInfo.vue';
  import {db} from '../firebase';

  export default {
    name: 'Main',
    components: {
      'login': Login,
      'results': Results,
      'search': Search,
      'restaurantChooser': RestaurantChooser,
      'LocationInfo': LocationInfo,
      'queue': Queue
    },
    firebase: function() {
      return {
        songList: {
          source: db.ref(this.restaurant.id).child('songs').orderByChild('votes')
        }
      }
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
        voters: [],
        userid: '',
        songList: [],

        notificationText: '',
        notificationShowing: false,
        restaurants: [],
        restaurant: false,
        active: false,
        limit: 0,
        maxQueue: 0,
        limitReached: false,
        maxQueueReached: false,
        search: this.$refs.search,
        queue: this.$refs.queue,
        songsAdded: 0,
        songsLeft: 0,
        locationInfo: false,
        blacklist: []

      }
    },
    methods: {
      clearSearch() {
        this.searching = false
      },

      authorize: () => {
        let clientId = 'acda5dc270674c59be88eca853c1d4ff'
        let scopes = 'user-read-private user-read-email user-read-birthdate user-library-read streaming user-read-playback-state user-modify-playback-state user-read-currently-playing playlist-modify-public playlist-modify-private'
        // Authorize Spotify user
        let url = 'https://accounts.spotify.com/authorize?'
        let query = 'response_type=token&client_id=' + clientId + '&scope=' + scopes
        let urlWithQueryString = url + '&' + query
        window.location.assign(urlWithQueryString + '&redirect_uri=' + window.location.href.split('#/')[0])
      },
      logout() {
        this.accessToken = '',
        this.isAccessTokenPresent = false,
        this.loggedIn = false
      },
      checkout() {
        this.restaurant = null,
        this.locationInfo = false
      },
      showLocationInfo() {
        this.locationInfo = true
      },
      hideLocationInfo() {
        this.locationInfo = false
      },
      onDecode(content) {
        this.setRestaurant(content)
      },
      getRestaurants() {
        db.ref().on('value', snapshot => {
          snapshot.forEach(child => {
            this.restaurants.push(child.val())
          })
        })
      },
      setRestaurant(content) {
        for (var i = 0; i < this.restaurants.length; i++) {
          if(this.restaurants[i].id == content) {
            this.restaurant = this.restaurants[i]
          }
        }
        this.getBlacklist()
        this.setLimit()
      },
      getBlacklist() {
        this.blacklist = [],
        db.ref(this.restaurant.id).child('blacklist').on('value', snapshot => {
          snapshot.forEach(child => {
            this.blacklist.push(child.val())
          })
        })
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
        this.songsAdded = 0;
        if (this.songList.length >= this.maxQueue) {
          this.maxQueueReached = true
        }
        else {
          this.maxQueueReached = false

          for (var i = 0; i < this.songList.length; i++) {
            if(this.songList[i].userid == this.userid) {
              this.songsAdded++;
            }
          }
          if (this.songsAdded < this.limit) {
            this.limitReached = false
          }
          else {
            this.limitReached = true
          }
        }
      },
      addTrack: function(event) {
        this.checkLimit()
        if(!this.limitReached && !this.maxQueueReached) {
          this.newId = event.id,
          this.newTitle = event.name
          for (var i = 0; i < event.artists.length; i++) {
            this.newArtists.push(event.artists[i].name)
          }
          this.newDuration = event.duration_ms,
          this.newVotes = -1,
          this.newImage = event.album.images[1].url

          for (var i = 0; i < this.songList.length; i++) {
            if(this.newId == this.songList[i].id) {
              this.trackExists = true
              this.trackToUpvote = this.songList[i]
            }
          }

          if(this.trackExists) {
            this.notificationText = 'Song was already in list. Upvoted.'
            this.toggleShow()
            this.$refs.queue.upvoteTrack(this.trackToUpvote)
          }
          else {
            this.voters.push(this.userid)
            db.ref(this.restaurant.id).child('songs').child(this.newId).set({
              id: this.newId,
              artists: this.newArtists,
              duration: this.newDuration,
              title: this.newTitle,
              userid: this.userid,
              votes: this.newVotes,
              voters: this.voters,
              playing: false,
              nextSong: false,
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
          this.trackExists = false
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
      setCurrentTrack() {
        this.active = false

        for (var i = 0; i < this.songList.length; i++) {
          if(this.songList[i].playing) {
            this.active = this.songList[i]
          }
        }

        return this.active;
      },
      setNextTrack() {
        this.nextSong = false

        for (var i = 0; i < this.songList.length; i++) {
          if(this.songList[i].nextSong) {
            this.nextSong = this.songList[i]
          }
        }

        return this.nextSong;
      }
    },
    computed: {
      calcSongsLeft() {
        if(this.limit < this.maxQueue) {
          return this.songsLeft = this.limit - this.songsAdded
        }
        else {
          return this.songsLeft = this.maxQueue - this.songsAdded
        }
      },

      sortedList() {
        function compare(a, b) {
          if (a.votes < b.votes)
            return -1;
          if (a.votes > b.votes)
            return 1;
          return 0;
        }

        return this.songList.sort(compare)
      }
    },
    watch: {
      restaurant(restaurantObject) {
        this.$unbind('songList')
        this.$bindAsArray('songList', db.ref(restaurantObject.id).child('songs'))
        db.ref(restaurantObject.id + '/songs/').orderByChild('votes').off();
      },

      songList(songListObject) {
        this.setNextTrack(),
        this.setCurrentTrack(),
        this.checkLimit(),
        this.sortedList()
      }
    },
    mounted() {
      if(this.loggedIn) {
        this.setUserId(),
        this.getRestaurants()
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #spotify{
    min-height: 100vh;
    padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
  }

  #songsLeft{
    padding: 0rem 2rem 1.5rem 2rem;
  }

  .searchDisclaimer{
    padding: 1.5rem;
    color: var(--textColorLight);
  }

  .notification {
    background-color: #424242;
    color: var(--textColorLight);
    font-size: var(--normalText);
    border-radius: 400px;
    display: inline-block;
    padding: 16px 24px;
    position: absolute;
    top: 120px;
    right: 48px;
  }

  h2 {
    margin-left: 24px;
    font-size: var(--title);
    color: var(--textColorLight);
    margin-bottom: .5rem;
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
    font-size: var(--normalText);
    font-family: 'Roboto Condensed', sans-serif;
  }

  #restaurantChosen {
    max-width: 100vw;
    min-height: 100vh;
    max-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;

  }

  #currentTrack {
    background: #FFDE22;
    display: grid;
    grid-template-columns: auto 1.5rem;
    grid-template-rows: auto;
    grid-template-areas: "infos button";
    grid-column-gap: 1rem;
    padding: 1rem 1rem;
    align-items: center;
    color: var(--textColorDark);
  }

  #currentTrack p{
    grid-area: infos;
    font-size: var(--normalText);
    width: auto;
    margin: 0;
    order: 2;
  }

  #currentTrack img#restaurantInfoIcon{
    grid-area: button;
    height: 1rem;
    cursor: pointer;
    padding: .25rem;
  }

  #currentTrack p #nowPlaying{
    text-transform: uppercase;
    font-weight: bold;
    font-size: var(--smallText);
    display: block;
  }

  #separator{
    font-weight: bold;
  }

  #songImage {
    width: 64px;
    height: 64px;
    display: inline;
    margin-right: 16px;
    border-radius: 4px;
  }

  #nextSong{
    padding: 1rem 0;
  }

  #nextSongInfo{
    padding: 1rem 1.5rem;
    display: grid;
    grid-template-columns: 64px auto;
    grid-template-rows: auto;
    grid-column-gap: 1rem;
    grid-template-areas: "image info";
    list-style: none;
    align-items: center;
  }

  #nextSongInfo #songImage{
    grid-area: image;
  }

  #nextSongInfo #infos{
    grid-area: info;
  }

  /* Add some padding inside the card container */
  .container {
      padding: 16px 24px;
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

  /*Now Playing Safe area for iPhone X*/
  @media only screen
    and (device-width : 375px)
    and (device-height : 812px)
    and (-webkit-device-pixel-ratio : 3) {
      #currentTrack {
        padding-top: 3rem;
      }
    }

  /* Disable restaurant chooser for mobile devices */
  /* @media screen and (device-max-width: 769px) {
    restaurant-chooser {
      display: none;
    }
  } */

  /* Disable QR Code reader for non-mobile devices */
  @media screen and (min-width: 769px) {
    .qrcode-reader {
      display: none;
    }
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
