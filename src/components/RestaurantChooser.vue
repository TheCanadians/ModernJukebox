<template>
  <div>
    <select id="chooser" v-model="restaurant">
      <option disabled value="">Please select restaurant</option>
      <option
        v-for="restaurant in restaurants"
        value="restaurant"
        :value="restaurant">
        {{restaurant.name}}
      </option>
    </select>
  </div>
</template>

<script>
  import {db} from '../firebase';

  export default {
    name: 'restaurantChooser',
    data() {
      return {
        restaurants: [],
        restaurant: 'false'
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
      setRestaurant() {
        if(this.restaurant != null) {
          this.$emit('setRestaurant', this.restaurant)
        }
      }
    },
    mounted() {
      this.getRestaurants()
    },
    updated() {
      this.setRestaurant()
    }
  }
</script>

<style>

  h2 {
    margin-left: 48px;
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
