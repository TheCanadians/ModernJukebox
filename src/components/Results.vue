<template>
  <ul id="results">
    <h2 id="resultsTitle">Results</h2>
    <div id="resultsList">
    <li v-for="track in tracks">
      <div class="infos">
      <img id="songImage" :src="track.album.images[1].url" />
      <p>
        <span class="title">{{track.name}}</span>
        <template v-for='(artist, index) in track.artists'>
         <span class="artist">{{artist.name}}<template v-if="index + 1 < track.artists.length">, </template></span>
       </template>
      </p>
      </div>
      <button id="addBTN" @click="addTrack(track)">
        <img src="../assets/ic_add.svg" />
        <span>add</span>
      </button>
    </li>
    </div>
  </ul>
</template>

<script>
  export default {
    name: 'results',
    props: {
      tracks: {
        required: true
      }
    },
    methods: {
      addTrack(track) {
        this.$emit('addTrack', track)
      }
    }
  }
</script>

<style>
  #addBTN {
    display: flex;
    flex-direction: column;
    color: #FFDE22;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 1rem;
    align-items: center;
    justify-content: center;
    font-family: 'Roboto Condensed', sans-serif;
    background: none;
    border: none;
  }

  #addBTN span{
    margin-top: 8px;
  }

  #resultsTitle{
    display: inline;
    margin: 0;
    color: #FFF;
    font-size: 2rem;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
  }

  #resultsList{
    overflow: scroll;
  }

  #results{
    display: flex;
    flex-direction: column;
  }
</style>
