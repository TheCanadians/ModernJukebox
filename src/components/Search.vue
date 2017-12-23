<template>
  <div id="search">
    <div id="searchBox">
      <form>
        <input
          id="searchInput"
          type="text"
          placeholder="Search for song title"
          v-model="query"
        ></input>
        <div id="clearBtn" @click="clearSearch"></div>
        <input id="submitBtn" type="submit" @click.prevent="searchSong" value="" />
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'app',
    data() {
      return {
        searching: false,
        query: ''
      }
    },
    methods: {
      searchSong(event) {
        if (event != null) {
          this.searching = true
          this.$emit('keyedUp', this.query)
        }
      },
      clearSearch() {
        this.query = '',
        this.searching = false
        this.$emit("searchCleared")
      }
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
    padding: 24px 24px;
    background-color: #282828;
    text-align: left;
  }
  #searchBox {
    border-radius: 10rem;
  }
  form {
    display: flex;
    width: 100%;
    align-items: center;
  }
  input {
    width: auto;
    height: 20px;
    display: inline;
    box-shadow: none;
    border: none;
    outline: none;
    background-color: rgba(255, 255, 255, 0.12);
    border-radius: 4px;
    font-size: 12.75pt;
    padding: 10px 20px;
    border-radius: 10rem 0 0 10rem;
    font-family: 'Roboto Condensed';
    color: rgba(255, 255, 255, 1);
    margin: 0;
    flex-grow: 2;
  }
  #submitBtn {
    background-color: #FFDE22;
    background-image: url('../assets/ic_search.svg');
    widows: auto;
    height: 100%;
    border-radius: 0 10rem 10rem 0;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: calc(50% - 2px);
    outline: none;
    border: none;
    box-shadow: none;
    cursor: pointer;
    flex-grow: 0.15;
  }
  #clearBtn {
    background-color: rgba(255, 255, 255, 0.12);
    background-image: url('../assets/ic_clear.svg');
    width: 20px;
    height: 20px;
    padding: 10px;
    background-size: contain;
    background-repeat: no-repeat;
    outline: none;
    border: none;
    box-shadow: none;
    cursor: pointer;
  }
</style>
