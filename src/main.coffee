import Vue from "vue/dist/vue.esm.js"
import Vuex from "vuex"
import Cart from "./Cart.vue"
import Checker from "./Checker.vue"
require "./main.scss"

Vue.use Vuex
store= new Vuex.Store
  state: cartedItems: [], isCheckerOpen: off
  getters:
    totalCount: (state)->
      _.inject state.cartedItems, ((m, item)->m + item.count), 0
    totalPrice: (state)->
      _.inject state.cartedItems, ((m, item)->m + item.price * item.count), 0
  mutations:
    openChecker: (state)->
      state.isCheckerOpen= on
    closeChecker: (state)->
      state.isCheckerOpen= off
    add: (state, data)->
      if item= _.findWhere(state.cartedItems, id: data.id)
        item.count++
      else
        +data.price; +data.count
        state.cartedItems.push data
    remove: (state, id)->
      index= _.findIndex(state.cartedItems, (item)->item.id is id)
      if --state.cartedItems[index].count is 0
        state.cartedItems.splice(index, 1)
    removeAll: (state)->
      state.cartedItems.splice(0)

jQuery ->
  new Vue
    el: "#v-app"
    components: {Cart, Checker}
    store: store
    data: isCheckerOpen: yes
    methods: addToCart: (id, title, price)->
      @$store.commit 'add', id: id, title: title, price: price, count: 1
