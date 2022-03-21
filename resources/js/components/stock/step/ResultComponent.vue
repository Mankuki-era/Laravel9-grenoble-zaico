<template>
  <div class="stock-result">
    <div class="card-main">
      <h1>登録完了</h1>
      <p>入庫/出庫の登録が完了しました。</p>
      <p>詳細は履歴に登録されているためご確認ください。</p>
    </div>
    <ul class="page-button">
      <li><a href="" class="first" @click.prevent.stop="topPage">教材一覧へ</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    initData: function(){
      return {

      };
    },
    data: function(){
      return this.$options.initData();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      topPage: function(){
        this.$router.push({
          path: '/'
        });
        this.$emit('header-event');
      },
    }
  }
</script>