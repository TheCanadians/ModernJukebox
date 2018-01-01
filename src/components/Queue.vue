<template>
  <div id="queue">
    <div id="queueList">
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
            <button v-if="isVotable(song)" id="btnUpvote" @click="upvoteTrack(song)">
              <div id="heartCount">
                <img src="../assets/ic_heart_outline.svg" />
                <p>{{song.votes * -1}}</p>
              </div>
              <span>vote</span>
            </button>
            <div v-if="!isVotable(song)" id="heartCountNotVotable">
                <img src="../assets/ic_heart_gray.svg" />
                <p>{{song.votes * -1}}</p>
            </div>
          </div>
        </li>
      </ul>
    </div>
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
        if(track.userid == this.userid || track.voters.includes(this.userid)) {
          return false;
        }
        else {
          return true;
        }
      },
      upvoteTrack: function(event) {
        event.votes -= 1,
        event.voters.push(this.userid)
        db.ref(this.restaurant.id).child('songs').child(event.id).update({
          votes: event.votes,
        }),
        db.ref(this.restaurant.id).child('songs').child(event.id).update({
          voters: event.voters,
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
    top: 200;
    width: 100%;
    background-color: #282828;
  }

  #queueList{
    overflow: scroll;
    max-height: calc(100vh - 248px);
  }

  #btnUpvote {
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
    width: 80px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  #btnUpvote span{
    text-transform: uppercase;
    font-weight: bold;
    font-family: 'Roboto Condensed', sans-serif;
    margin-top: 4px;
  }

  #heartCount {
    display: flex;
    flex-direction: row;
    font-family: 'Roboto Condensed', sans-serif;
  }

  #heartCountNotVotable{
    width: 80px;
    color: #8c8c8c;
    display: flex;
    flex-direction: row;
    justify-content: center;
  }

  li#queueSong {
    border: none;
    padding: 16px 24px;
  }

  li {
    padding: 16px 24px;
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
