<template>
    <div class="spotify">
        <search v-if="loggedIn" @keyedUp="searchTracks($event)"></search>
        <ul class="results">
          <li v-for="track in tracks">
            <a :href="track.external_urls.spotify">{{track.name}} &ndash; {{track.artists[0].name}}</a>
            <span>ID: {{track.id}}</span>
          </li>
        </ul>
        <queue v-if="loggedIn"></queue>
        <button v-if="!loggedIn" v-on:click="authorize">Authorize</button>
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
        searchQuery: ''
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
              console.log(this.tracks)
            }
          }
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

li {
    border-bottom: 1px solid #E0E0E0;
    padding: 24px 48px;
}
li span {
    display: block;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

a {
    color: #0097A7;
}
.card {
    /* Add shadows to create the "card" effect */
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    justify-content: center;
    width: 25%;
}

/* On mouse-over, add a deeper shadow */
.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
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
