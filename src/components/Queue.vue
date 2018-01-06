<template>
    <div id="queueList">
      <ul v-for="song in list">
        <li class="songListItem" id="queueSong">
          <img id="songImage" :src="song.image" />
          <p>
            <span class="title">{{song.title}}</span>
            <template v-for='(artist, index) in song.artists'>
              <span class="artist">{{artist}}<template v-if="index + 1 < song.artists.length">, </template></span>
            </template>
          </p>
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
    width: 100%;
    background-color: #282828;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  #emptyQueue{
    align-self: center;
    margin: auto;
    text-align: center;
    font-size: var(--normalText);
    padding: 0 1.5rem;
    line-height: 1.5rem;
    font-weight: lighter;
  }

  #emptyQueue span{
    line-height: 2rem;
  }

  #queueList{
    overflow: scroll;
    flex-grow: 1;
  }

  #btnUpvote {
    background: none;
    color: #FFDE22;
    font-size: var(--normalText);
    outline: none;
    border: none;
    box-shadow: none;
    height: 48px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    cursor: pointer;
    margin: auto;

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
    grid-area: button;
    color: #8c8c8c;
    display: flex;
    flex-direction: row;
    justify-content: center;
    margin: auto;
  }

  li.songListItem {
    border: none;
    padding: 1rem 1.5rem;
    display: grid;
    grid-template-columns: 64px auto 48px;
    grid-template-rows: auto;
    grid-column-gap: 1rem;
    grid-template-areas: "image infos button";
    align-items: center;
  }

  li .title {
    color: #fff;
    display: block;
    font-size: var(--normalText);
    margin-top: 0;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  li .artist{
    color: #8c8c8c;
    text-overflow: ellipsis;
    overflow: hidden;
    font-size: var(--smallText)
  }

  a {
    color: #0097A7;
  }

  li.songListItem #songImage {
    width: 64px;
    height: 64px;
    border-radius: 4px;
    grid-area: image;
  }

  li.songListItem p{
    grid-area: infos;
  }

  li.songListItem #votes{
    grid-area: button;
  }

  .votes p{
    margin-top: 0;
    margin-left: 4px
  }
</style>
