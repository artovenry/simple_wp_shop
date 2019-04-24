<style lang="scss" scoped>
</style>
<script lang="coffee">
  export default
    props: ["item_id"]
    methods:
      add_to_cart: ->
        console.log @item_id
    render: (h)->
      <article>
        {@$slots.default}
      </article>
</script>
