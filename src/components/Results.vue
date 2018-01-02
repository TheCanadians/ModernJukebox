<template>
  <ul id="results">
    <div id="resultsHeader">
    <h2 id="resultsTitle">Results</h2>
    </div>
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

</style>
