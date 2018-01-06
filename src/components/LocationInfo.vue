<template>
  <div id="locationInfo">
    <button @click="this.closeLocationInfo">Close</button>
    <h3>{{this.name}}</h3>

    <section id="blacklist">
      <p>
        These genres are not allowed:
      </p>
      <ul>
        <li v-for="genre in blacklist">
          {{genre}}
        </li>
      </ul>
    </section>

    <p>
      The maximum amount of songs you can add: {{this.limit}}
    </p>
    <p>
      The maximum amount of songs in this playlist: {{this.maxQueue}}
    </p>
  </div>
</template>

<script>
  export default {
    name: 'locationInfo',
    props: ['name', 'limit', 'maxQueue', 'blacklist'],
    methods: {
      closeLocationInfo() {
        this.$emit('closedLocationInfo')
      }
    }
  }
</script>

<style>
  #locationInfo {
    height: 100vh;
    width: 100vw;
    position: absolute;
    top: 0;
    left: 0;
    background-color: yellow;
  }
</style>
