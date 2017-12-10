<template>
  <select id="chooser" @change="setRestaurant(restaurant)">
    <option disabled value="">Please select restaurant</option>
    <option
      v-for="restaurant in restaurants"
      value="restaurant"
      :value="restaurant">
      {{restaurant.name}}
    </option>
  </select>
</template>

<script>
  import {db} from '../firebase';

  export default {
    name: 'restaurantChooser',
    data() {
      return {
        restaurants: [],
        restaurant: ''
      }
    },
    methods: {
      getRestaurants() {
        db.ref().on('value', snapshot => {
          snapshot.forEach(child => {
            this.restaurants.push(child.val())
          })
        })
      },
      setRestaurant(event) {
        console.log(event)
        this.$emit('setRestaurant', event)
      }
    },
    mounted() {
      this.getRestaurants()
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
