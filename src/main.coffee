import Vue from "vue/dist/vue.esm.js"
import Cart from "./Cart.vue"
require "./main.scss"
document.addEventListener "DOMContentLoaded", ->
  new Vue
    el: "#v-app"
    components: {Cart}
    data:
      cartedItems: []
    methods:
      add_to_cart: (data)->
        if item= _.findWhere(@cartedItems, id: data.id)
          item.count++
        else
          data.price= +data.price
          data.count= +data.count
          @cartedItems.push data
