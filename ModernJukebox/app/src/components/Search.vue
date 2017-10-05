<template>
  <div id="search">
    <div id="searchBox">
      <form>
        <input
          id="searchInput"
          type="text"
          placeholder="Search for song title"
          v-model="query"
        />
        <button type="submit" @click="searchSong">Search</button>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'app',
    data() {
      return {
        query: ''
      }
    },
    methods: {
      searchSong(event) {
        if (event != null) {
          this.$emit('keyedUp', this.query)
          this.query = ''
        }
      },
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  * {
    margin: 0;
    padding: 0;
  }
  #search {
    box-sizing: border-box;
    width: 100%;
    padding: 16px 24px;
    background-color: #F5F5F5;
    text-align: left;
  }
  #searchBox {
    border-radius: 8px;
    border: 1px solid #E0E0E0;
  }
  form {
    display: flex;
    width: 100%;
  }
  input {
    width: auto;
    display: inline;
    box-shadow: none;
    border: none;
    outline: none;
    background-color: #FFF;
    border-radius: 4px;
    font-size: 1rem;
    padding: 16px 24px;
    border-radius: 8px 0 0 8px;
    font-family: 'Roboto';
    color: #616161;
    margin: 0;
    flex-grow: 2;
  }
  button {
    display: inline;
    background: none;
    border: none;
    background-color: #0097A7;
    border-radius: 0 8px 8px 0;
    height: 100%;
    font-family: 'Roboto';
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    padding: 16px 48px;
    margin: 0;
  }
</style>
