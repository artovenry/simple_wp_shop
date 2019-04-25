<template lang="pug">
  div
    template(v-if="$store.state.cartedItems.length")
      .item(v-for="item in $store.state.cartedItems")
        span.item-name {{item.title}} :
        span.item-price &yen;{{item.price}} *
        span.item-count {{item.count}}
        a(href="#" @click.prevent="$store.commit('add', item)") add
        a(href="#" @click.prevent="$store.commit('remove', item.id)") remove
      .total
        .total-count TotalCount: {{$store.getters.totalCount}}
        .total-price TotalPrice: {{$store.getters.totalPrice}}
      a(href="#" @click.prevent="$store.commit('removeAll')") remove all
    template(v-else)
      .no-item cart is empty
</template>
