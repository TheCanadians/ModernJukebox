<template>
  <div id="queue">
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
    props: ['userId'],
    data() {
      return {
        queue: ''
      }
    },
    methods: {
      getQueue: function() {
        db.ref('schweinske-dehnhaide').child('songs').orderByChild('votes').on('value', snapshot => {
          console.log(snapshot.val())
          this.queue = snapshot.val()
        })
      },
      upvoteTrack(event) {
        event.votes -= 1,
        db.ref('schweinske-dehnhaide').child('songs').child(event.id).update({
          votes: event.votes,
        }),
        db.ref('schweinske-dehnhaide').child('songs').child(event.id).update({
          voters: this.userId
        }),
        this.getQueue()
      },
      initPlaySong() {
        this.$emit('initSongs', this.songs[0])
      }
    },
    mounted() {
      this.getQueue()
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
