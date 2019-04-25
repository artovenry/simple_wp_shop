<script lang="coffee">
  import Cart from "./Cart.vue"
  observePostalCode= ->
    PA= postcodejp.address
    autocompletePostalCode= new PA.AutoComplementService(POSTCODE_API_KEY)
      .setAutoComplement(off)
      .setComplementButton("completeAddress")
      .enableOfficeAddress(off)
      .setZipTextbox "customer-postal-code"
      .add(new PA.StateTownStreetTextbox("customer-address"))
      .observe()
  export default
    components: {Cart}
    mounted: observePostalCode
    methods:
      confirm: ->
        # ユーザー入力を検証して、合格したら入力を確認してもらい、オッケーが出たらサーバーに送る
</script>
<template lang="pug">
  #checker
    #order-field
      Cart
    #customer-field
      .control-customer-fullname
        input#customer-fullname(
          type="text"
          autocomplete="name"
          name="full_name"
        )
        label(for="customer-fullname") 名前（フルネーム）
      .control-customer-furigana
        input#customer-furigana(
          type="text"
          name="furigana"
        )
        label(for="customer-furigana") ふりがな
      .control-customer-email
        input#customer-email(
          type="email"
          name="email"
          autocomplete="email"
        )
        label(for="customer-email") メールアドレス
      .control-customer-tel
        input#customer-tel(
          type="tel"
          name="tel"
          autocomplete="tel"
        )
        label(for="customer-tel") 電話番号
      .control-customer-postal-code
        input#customer-postal-code(
          type="text"
          name="postal-code"
          autocomplete="postal-code"
        )
        label(for="customer-postal-code") 郵便番号
      input#completeAddress(type="button" value="住所を自動入力" disabled="***")
      .control-customer-address
        input#customer-address(
          type="text"
          name="address"
        )
        label(for="customer-address") 住所
    a(href="#" click.prevent="confirm") 次へ
    a(href="#" click.prevent="$store.commit('closeChecker')") 買い物を続ける
</template>
