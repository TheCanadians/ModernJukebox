<template>
    <div class="spotify">
        <h1>{{msg}}</h1>
        <button v-if="!loggedIn" v-on:click="authorize">Authorize</button>
        <button v-if="loggedIn" v-on:click="getTracks">Get Tracks</button>
        <div class="tracks">
        <div class="card" v-for="(t, index) in tracks" :key=t.id>
            <div class="container">
             <img v-bind:src="t.track.album.images[0].url" width="100%" />
             <a v-bind:href="t.track.external_urls.spotify">{{t.track.name}}</a>
             <audio controls>
               <source v-bind:src="t.track.preview_url" type="audio/mp3">
             </audio>
            </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'Spotify',
  data () {
    var accessToken
    var isAccessTokenPresent = window.location.href.indexOf('access_token') !== -1
    if (isAccessTokenPresent) {
      accessToken = window.location.href.split('access_token=')[1].split('&')[0]
    }
    return {
      msg: 'Welcome to Spotify API',
      loggedIn: isAccessTokenPresent,
      accessToken: accessToken,
      tracks: []
    }
  },
  methods: {
    authorize: () => {
      let clientId = 'acda5dc270674c59be88eca853c1d4ff'
      // let clientSecret = '656ebbbf0177484b8f8b774efd204f33'
      let scopes = 'user-read-private user-read-email user-library-read'
      // Authorize Spotify user
      let url = 'https://accounts.spotify.com/authorize?'
      let query = 'response_type=token&client_id=' + clientId + '&scope=' + scopes
      let urlWithQueryString = url + '&' + query
      window.location.assign(urlWithQueryString + '&redirect_uri=' + window.location.href.split('/#')[0])
    },
    getTracks: function () {
      this.axios({
        url: 'https://api.spotify.com/v1/me/tracks',
        headers: {'Authorization': 'Bearer ' + this.accessToken},
        method: 'GET'
      }).then((res) => {
        if (res.status === 401) {
          throw new Error('Unauthorized')
        } else {
          if (res.data !== undefined) {
            this.tracks = res.data.items
          }
        }
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.spotify{
  text-align: center;
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

li {
    display: inline-block;
    margin: 0 10px;
}

a {
    color: #42b983;
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
