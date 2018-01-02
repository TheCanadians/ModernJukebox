<template>
  <div>
    <h3>{{restaurant.name}}</h3>
    <p>
      The maximum amount of songs you can add: {{restaurant.limit}}
    </p>
    <p>
      The maximum amount of songs in this playlist: {{restaurant.limit}}
    </p>
  </div>
</template>

<script>
  export default {
    name: 'restaurantInfo',
    props: ['name', 'limit', 'maxQueue']
  }
</script>

<style>
</style>
