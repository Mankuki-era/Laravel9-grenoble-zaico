<template>
  <div class="modal-login">
    <h1 class="modal-name">Basic認証</h1>
    <form>
      <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" class="form-control" id="password" v-model="password">
      </div>
      <ul>
        <li><a href="" :class="{disabled: password === ''}" @click.prevent.stop="adminLogin">OK</a></li>
      </ul>
    </form>
  </div>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      password: '',
      correctPassword: ''
    };
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    document.getElementById("password").focus();
    this.getInfo();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getInfo: function(){
      axios.get('/api/info/1').then((res) => {
        if(res.data){
          this.correctPassword = res.data.password;
        }
      });
    },
    adminLogin: function(){
      if(this.password === this.correctPassword){
        this.$cookies.set('auth', 'admin', this.$root.session_time)
        this.$router.push({
          path: '/'
        });
        this.$emit('header-event');
        this.$emit('close-modal');
        this.$emit('message-event', 'ログインしました', true);
      }else{
        this.$emit('message-event', 'パスワードが違います', false);
        this.password = '';
      }
    },
  }
}
</script>