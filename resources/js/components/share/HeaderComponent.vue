<template>
  <header>
    <div class="left-box">
      <img src="/img/logo.png" alt="画像">
      <div class="logo-name-box">
        <p class="sub-name">グルノーブル</p>
        <p class="main-name">ZAICO</p>
      </div>
    </div>
    <div class="right-box">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <template v-for="(link, index) in linkArray">
          <li class="router-link" :class="{ selected: link.selected }" :key="link.name" v-show="!(link.to === '/stock' && !adminFlag)">
            <a href="" @click.prevent.stop="linkClick(index)"><span v-html="link.icon"></span>{{ link.name }}</a>
          </li>
        </template>
        <li class="user-link">
          <a href="javascript:void(0)"><i class="fa-solid fa-circle-user user-icon"></i></a>
        </li>
        <ul class="user-menu">
          <li>
            <a @click.prevent.stop="logoutEvent"><i class="fa-solid fa-power-off power-icon"></i>ログアウト</a>
          </li>
        </ul>
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
          { name: '入庫/出庫', to: '/stock', icon: '<i class="fas fa-retweet icon"></i>', selected: false },
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
      $(function(){
        $(document).on('click',function(e) {
          if(!$(e.target).closest('.user-link').length) {
            $('.user-menu').fadeOut(200);
          }
        });
        $('.user-link').click(function(){
          $('.user-menu').fadeToggle(200);
        });
      });
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
        $('.user-menu').hide();
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
