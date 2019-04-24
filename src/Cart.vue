<template lang="pug">
  #cart
    p cart
    .account-detail
      template(v-if="items.length")
        .item(v-for="item in items")
          span.item-name {{item.title}} :
          span.item-price &yen;{{item.price}} *
          span.item-count {{item.count}}
        .total
          .total-count TotalCount: {{totalCount}}
          .total-price TotalPrice: {{totalPrice}}
      template(v-else)
        .no-item cart is empty
</template>
<style lang="scss" scoped>
</style>

<script lang="coffee">
  export default
    props: ["items"]
    computed:
      totalCount: ->
        _.inject @items, ((m, item)->m + item.count), 0
      totalPrice: ->
        _.inject @items, ((m, item)->m + item.price * item.count), 0
</script>
