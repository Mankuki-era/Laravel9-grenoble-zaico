<template>
  <main class="login">
    <div class="card">
      <h1>一般ユーザー</h1>
      <div class="icon"><i class="fa-solid fa-user"></i></div>
      <p>教材データおよび入出庫履歴の閲覧のみが可能です。</p>
      <ul>
        <li><a href="" @click.prevent.stop="authLogin">ログイン</a></li>
      </ul>
    </div>
    <div class="card">
      <h1>管理者</h1>
      <div class="icon"><i class="fa-solid fa-user-tie"></i></div>
      <p>一般ユーザーの機能に加えて、教材データの作成・編集・削除および入出庫の登録が可能です。</p>
      <ul>
        <li><a href="" @click.prevent.stop="adminLogin">ログイン</a></li>
      </ul>
    </div>
  </main>
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
      authLogin: function(){
        this.$cookies.set('auth', 'general', this.$root.session_time)
        this.$router.push({
          path: '/'
        });
        this.$emit('header-event');
        this.$emit('message-event', 'ログインしました', true);
      },
      adminLogin: function(){
        this.$emit('open-modal', 'login', null, null);
      },
    }
  }
</script>