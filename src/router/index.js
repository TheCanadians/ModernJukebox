import Vue from 'vue'
import Router from 'vue-router'
import Spotify from '@/components/Spotify'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Spotify',
      component: Spotify
    },
    {
      path: '/:id',
      name: 'Spotify',
      component: Spotify
    },
    {
      path: '/spotify',
      name: 'Spotify',
      component: Spotify
    }
  ]
})
