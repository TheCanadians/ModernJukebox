<template>
  <div id="queue">
    <h2>Coming up:</h2>
    <ul v-for="song in songs">
      <li>
        <div class="infos">
          <p>
            {{song.title}}
            <span>{{song.artist}}</span>
          </p>
        </div>
        <div class="votes">
          {{song.votes}}
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  import {db} from '../firebase';

  export default {
    name: 'queue',
    data() {
      return {
      }
    },
    methods: {
      getTracks() {
        console.log(this.songs)
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
  }
  h2 {
    margin-left: 48px;
  }
  li {
      border-bottom: 1px solid #E0E0E0;
      padding: 24px 48px;
      display: flex;
      justify-content: space-between;
  }
  li span {
      display: block;
      font-size: 0.75rem;
      margin-top: 0.25rem;
  }

  a {
      color: #0097A7;
  }
</style>
