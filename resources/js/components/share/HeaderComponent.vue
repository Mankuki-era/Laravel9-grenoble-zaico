<template>
  <header>
    <div class="left-box">
      <img src="/img/logo.png" alt="画像">
      <div class="logo-name-box">
        <p>ZAICO</p>
      </div>
    </div>
    <div class="right-box">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <template v-for="(link, index) in linkArray">
          <li class="router-link" :class="{ selected: link.selected }" :key="link.name" v-show="!(link.to === '/stock' && !adminFlag)">
            <a href="" @click.prevent.stop="linkClick(index)"><span v-html="link.icon"></span><span>{{ link.name }}</span></a>
          </li>
        </template>
        <li class="router-link">
          <a @click.prevent.stop="logoutEvent"><span><i class="fa-solid fa-power-off power-icon"></i></span><span>ログアウト</span></a>
        </li>
      </ul>
    </div>
  </header>
</template>

<script>
  export default {
    initData: function(){
      return {
        linkArray: [
          { name: '教材', to: '/' , icon: '<i class="fa-solid fa-book icon"></i>', selected: false},
          { name: '入出庫', to: '/stock', icon: '<i class="fas fa-retweet icon"></i>', selected: false },
          { name: '履歴', to: '/log', icon: '<i class="fa-solid fa-database icon"></i>', selected: false },
        ],
        adminFlag: false
      };
    },
    data: function(){
      return this.$options.initData();
    },
    mounted: function(){
      this.checkAuth();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      linkClick: function(index) {
        this.linkArray.forEach((link, index1) => {
          link.selected = index === index1 ? true : false;
        });
        this.$router.push({
          path: this.linkArray[index].to
        });
      },
      logoutEvent: function(){
        this.$emit('open-modal', 'logout', null, null);
      },
      checkAuth: function(){
        if(this.$cookies.get('auth') === 'admin'){
          this.adminFlag = true;
        }else{
          this.adminFlag = false;
        }
        this.linkArray.forEach(link => {
          link.selected = this.$route.path === link.to ? true : false;
        });
      }
    }
  }
</script>
