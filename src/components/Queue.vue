<template>
  <div id="queue">
    <h2>Open for voting</h2>
    <ul v-for="song in queue">
      <li>
        <div class="infos">
          <img :src="song.image" />
          <p>
            {{song.title}}
            <span>{{song.artist}}</span>
          </p>
        </div>
        <div class="votes">
          <button v-if="isVotable(song)" class="btnUpvote" @click="upvoteTrack(song)"></button>
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
      },
      queue: {
        required: true
      },
      restaurant: {
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
  .infos {
    display: flex;
    align-items: center;
  }
  .infos img {
    width: 64px;
    height: 64px;
    display: inline;
    margin-right: 24px;
  }
  .infos p {
    display: inline;
  }
  .votes {
    text-align: center;
  }
  .votes p {
    margin-top: -16px;
  }
</style>
