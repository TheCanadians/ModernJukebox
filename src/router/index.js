import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/components/Main'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Main',
      component: Main
    },
    {
      path: '/:id',
      name: 'Main',
      component: Main
    },
    {
      path: '/main',
      name: 'Main',
      component: Main
    }
  ]
})
