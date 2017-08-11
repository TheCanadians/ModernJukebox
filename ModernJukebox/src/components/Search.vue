<template>
  <div id="search">
    <div id="searchBox">
      <input id="searchInput" type="text" placeholder="Enter song title" />
      <button>Search</button>
    </div>
  </div>
</template>

<script>
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
  input {
    width: auto;
    display: inline;
    box-shadow: none;
    border: none;
    outline: none;
    background-color: #FFF;
    border-radius: 4px;
    font-size: 1rem;
    padding: 0.5rem 16px;
    border-radius: 8px 0 0 8px;
    font-family: 'Roboto';
    color: #616161;
    margin: 0;
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
    margin: 0;
  }
</style>
