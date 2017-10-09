<template>
    <div class="spotify">
        <search v-if="loggedIn" @keyedUp="searchTracks($event)"></search>
        <ul class="results">
          <li v-for="track in tracks">
            <button @click="addTrack(track)">{{track.name}} &ndash; {{track.artists[0].name}}</button>
          </li>
        </ul>
        <queue v-if="loggedIn"></queue>
        <button v-if="!loggedIn" @click="authorize">Authorize</button>
        <!-- <div class="card" v-for="(t, index) in tracks" :key=t.id>
            <div class="container">
             <img v-bind:src="t.track.album.images[0].url" width="100%" />
             <a v-bind:href="t.track.external_urls.spotify">{{t.track.name}}</a>
             <audio controls>
               <source v-bind:src="t.track.preview_url" type="audio/mp3">
             </audio>
            </div>
        </div> -->
      </div>
    </div>
</template>

<script>
  import Search from './Search.vue';
  import Queue from './Queue.vue';
  import {db} from '../firebase';

  export default {
    name: 'Spotify',
    components: {
      'search': Search,
      'queue': Queue
    },
    data () {
      var accessToken
      var isAccessTokenPresent = window.location.href.indexOf('access_token') !== -1
      if (isAccessTokenPresent) {
        accessToken = window.location.href.split('access_token=')[1].split('&')[0]
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
        newVotes: 0
      }
    },
    firebase: {
      songs: {
        source: db.ref('schweinske-dehnhaide').child('songs'),
        // Optional, allows you to handle any errors.
        cancelCallback(err) {
          console.error(err);
        }
      }
    },
    methods: {
      authorize: () => {
        let clientId = 'acda5dc270674c59be88eca853c1d4ff'
        let scopes = 'user-read-private user-read-email user-library-read'
        // Authorize Spotify user
        let url = 'https://accounts.spotify.com/authorize?'
        let query = 'response_type=token&client_id=' + clientId + '&scope=' + scopes
        let urlWithQueryString = url + '&' + query
        window.location.assign(urlWithQueryString + '&redirect_uri=' + window.location.href.split('/#')[0])
      },
      searchTracks: function (query) {
        // console.log(query)
        this.tracks = []
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
              // console.log(this.tracks)
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
        this.$firebaseRefs.songs.child(this.newId).set({
          artist: this.newArtist,
          duration: this.newDuration,
          title: this.newTitle,
          votes: 0
        })
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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

  a {
      color: #0097A7;
  }

  button {
    background: none;
    box-shadow: none;
    outline: none;
    border: none;
    font-size: 1rem;
    font-family: 'Roboto', sans-serif;
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
</style>
