<template>
  <div id="queue">
    <h2>Queue</h2>
    <ul v-for="song in list">
      <li id="queueSong">
        <div class="infos">
          <img :src="song.image" />
          <p>
            <span class="title">{{song.title}}</span>
            <template v-for='(artist, index) in song.artists'>
             <span class="artist">{{artist}}<template v-if="index + 1 < song.artists.length">, </template></span>
           </template>
          </p>
        </div>
        <div class="votes">
          <button v-if="isVotable(song)" class="btnUpvote" @click="upvoteTrack(song)">
            <div id="heartCount">
            <img src="../assets/ic_heart.svg" />
            <p>{{song.votes * -1}}</p>
            </div>
            <span>vote</span>
            </button>
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
      },
      list: {
        required: true
      },
      restaurant: {
        required: true
      },
      trackToUpvote: {
        required: true
      }
    },
    data() {
      return {
        nowPlaying: ''
      }
    },
    methods: {
      getQueue: function() {
        this.$emit('getQueue')
      },
      isVotable(track) {
        if(track.userid == this.userid) {
          return false;
        }
        else {
          return true;
        }
      },
      upvoteTrack: function(event) {
        event.votes -= 1,
        db.ref(this.restaurant.id).child('songs').child(event.id).update({
          votes: event.votes,
        }),
        db.ref(this.restaurant.id).child('songs').child(event.id).update({
          voters: this.userid
        }),
        this.getQueue()
      }
    },
    updated() {
    }
  }
</script>

<style>

  #queue {
    position: fixed;
    bottom: 0;
    width: 100%;
    padding-top: 24px;
    background-color: #282828;
    max-height: 66vh;
    overflow: scroll;
  }

  .btnUpvote {
    background: none;
    color: #FFDE22;
    font-size: 10.5pt;
    outline: none;
    border: none;
    box-shadow: none;
    width: 48px;
    height: 48px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    cursor: pointer;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .btnUpvote span{
    text-transform: uppercase;
    font-weight: bold;
  }

  #heartCount {
    display: flex;
    flex-direction: row;
  }

  li#queueSong {
    border: none;
    padding: 16px 24px;
  }

  h2 {
    margin-left: 24px;
    font-size: 21pt;
    color: #fff;
    margin-bottom: 8px;
  }

  li {
    border-bottom: 1px solid #E0E0E0;
    padding: 24px 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  li .title {
    color: #fff;
    display: block;
    font-size: 13.5pt;;
    margin-top: 0;
  }

  li .artist {
    color: #8C8C8C;
    display: inline;
    font-size: 10.5pt;
    margin-top: 2px;
  }

  a {
    color: #0097A7;
  }

  .infos {
    display: flex;
    align-items: center;
  }

  .infos img {
    width: 64px;
    height: 64px;
    display: inline;
    margin-right: 16px;
    border-radius: 4px
  }

  .infos p {
    display: inline;
  }

  .votes p{
    margin-top: 0;
    margin-left: 4px
  }
</style>
