<template>
  <div id="queue">
    <h2>Now playing:</h2>
    <ul v-model="nowPlaying">
      <li>
        <div class="infos">
          <p>
            {{this.nowPlaying.title}}
            <span>{{this.nowPlaying.artist}}</span>
          </p>
        </div>
        <div class="votes">
          <p>{{this.nowPlaying.votes * -1}}</p>
        </div>
      </li>
    </ul>

    <h2>Coming up:</h2>
    <ul v-for="song in queue">
      <li>
        <div class="infos">
          <p>
            {{song.title}}
            <span>{{song.artist}}</span>
          </p>
        </div>
        <div class="votes">
          <button class="btnUpvote" @click="upvoteTrack(song)"></button>
          <p>{{song.votes * -1}}</p>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  import {db} from '../firebase';

  export default {
    name: 'queue',
    props: {
      userid: {
        required: true
      },
      accessToken: {
        required: true
      }
    },
    data() {
      return {
        queue: [],
        nowPlaying: ''
      }
    },
    methods: {
      getQueue: function() {
        this.queue.length = 0,
        db.ref('schweinske-dehnhaide').child('songs').orderByChild('votes').on('value', snapshot => {
          snapshot.forEach(child => {
            this.queue.push(child.val())
          })
        })
      },
      upvoteTrack: function(event) {
        event.votes -= 1,
        db.ref('schweinske-dehnhaide').child('songs').child(event.id).update({
          votes: event.votes,
        }),
        db.ref('schweinske-dehnhaide').child('songs').child(event.id).update({
          voters: this.userid
        }),
        this.getQueue()
      },
      createPlaylist: function() {
        if(this.userid == '') {
          console.log('No user ID found')
        }
        else {
          this.axios({
            url: 'https://api.spotify.com/v1/users/' + this.userid + '/playlists',
            headers: {'Authorization': 'Bearer ' + this.accessToken},
            method: 'GET'
          }).then((res) => {
            if (res.status === 401) {
              throw new Error('Unauthorized')
            } else {
              if (res.data !== undefined) {
                let playlistIDs = []
                for (var i = 0; i < res.data.items.length; i++) {
                  playlistIDs.push(res.data.items[i].name)
                }
                if(!playlistIDs.includes('jukebox')) {
                  this.axios({
                    url: 'https://api.spotify.com/v1/users/' + this.userid + '/playlists',
                    headers: {'Authorization': 'Bearer ' + this.accessToken},
                    data: {
                      'description': 'jukebox',
                      'public': true,
                      'name': 'jukebox'
                    },
                    method: 'POST'
                  }).then((res) => {
                    if (res.status === 401) {
                      throw new Error('Unauthorized')
                    } else {
                      if (res.data !== undefined) {
                      }
                    }
                  })
                }
              }
            }
          })
        }
      },
      playSong: function() {
        this.getQueue()
        setTimeout(() => {
          var id = this.queue[0].id
          var dur = this.queue[0].duration

          this.axios({
            url: 'https://api.spotify.com/v1/me/player/play',
            headers: {'Authorization': 'Bearer ' + this.accessToken},
            data: {
              'uris': ['spotify:track:' + id]
            },
            method: 'PUT'
          }).then((res) => {
            if (res.status === 401) {
              throw new Error('Unauthorized')
            } else {
              if (res.data !== undefined) {
              }
            }
          })

          db.ref('schweinske-dehnhaide').child('songs').child(id).once('value').then((snapshot) => {
            this.nowPlaying = snapshot.val()
            console.log(this.nowPlaying)
          })
          db.ref('schweinske-dehnhaide').child('songs').child(id).remove()

          setTimeout(() => {
            this.playSong()
          }, dur)
        }, 3000)
      }
    },
    mounted() {
      this.createPlaylist()
      //this.playSong()
    }
  }
</script>

<style>
  #queue {
    position: fixed;
    bottom: 0;
    width: 100%;
    border-top: 4px solid #E0E0E0;
    padding-top: 24px;
    background-color: #fff;
    max-height: 36vh;
    overflow: scroll;
  }
  .btnUpvote {
    background: none;
    outline: none;
    border: none;
    box-shadow: none;
    width: 48px;
    height: 48px;
    background-image: url('../assets/ic_upvote.svg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    cursor: pointer;
  }
  h2 {
    margin-left: 48px;
  }
  li {
      border-bottom: 1px solid #E0E0E0;
      padding: 24px 48px;
      display: flex;
      justify-content: space-between;
      align-items: center;
  }
  li span {
      display: block;
      font-size: 0.75rem;
      margin-top: 0.25rem;
  }

  a {
      color: #0097A7;
  }
  .votes {
    text-align: center;
  }
  .votes p {
    margin-top: -16px;
  }
</style>
