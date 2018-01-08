<template>
  <div id="chooserContainer">
    <h3>Please select the venue you're in.</h3>
    <select id="chooser" v-model="restaurant">
      <option selected disabled value="">Choose Venue</option>
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
  #chooserContainer{
    min-height: 100vh;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem 0;
  }

  #chooserContainer h3{
    font-size: 1.3rem;
    font-weight: bold;
  }

  #chooser{
    appearance: none;

    margin-top: 2rem;

    font-size: var(--button);
    color: var(--textColorDark);
    font-family: 'Roboto Condensed', sans-serif;
    text-transform: uppercase;
    font-weight: bold;

    border: none;
    border-radius: 0;
    padding: 1rem 2rem;
    background-color: var(--mainColor);
    background-image:url("../assets/ic_arrow_drop_down.svg");
    background-position: right;
    background-repeat: no-repeat;
    }
</style>
