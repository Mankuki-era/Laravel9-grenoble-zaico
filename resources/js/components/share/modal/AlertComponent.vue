<template>
  <form class="alert">
    <p>{{ message }}</p>
    <ul class="button-box">
      <li><a href="" @click.prevent.stop="submitEvent">OK</a></li>
    </ul>
  </form>
</template>

<script>
module.exports = {
  props: ['id', 'index', 'func'],
  initData: function(){
    return {
      message: ''
    };
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    if(this.func === 'item-destroy'){
      this.getItem();
    }else if(this.func === 'item-delete'){
      this.message = '本当に教材データを全て削除しますか？';
    }else if(this.func === 'log-delete'){
      this.message = '本当に履歴データを全て削除しますか？';
    }else if(this.func === 'logout'){
      this.message = 'ログアウトしますか？';
    }
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getItem: function(){
      axios.get(`/api/item/${this.id}`).then((res) => {
        if(res.data){
          this.message = `「${res.data.name}」を削除しますか？`;
        }
      });
    },
    submitEvent: function(){
      if(this.func === 'item-destroy'){
        axios.delete(`/api/item/${this.id}`).then((res) => {
          this.$emit('data-reload', this.func, this.index, null);
          this.$emit('close-modal');
        });
      }else if(this.func === 'item-delete'){
        axios.post('/api/item/delete').then((res) => {
          this.$emit('data-reload', this.func, null, null);
          this.$emit('close-modal');
        });
      }else if(this.func === 'log-delete'){
        axios.post('/api/log/delete').then((res) => {
          this.$emit('data-reload', this.func, null, null);
          this.$emit('close-modal');
        });
      }else if(this.func === 'logout'){
        this.$router.push({
          path: '/login'
        });
        this.$emit('close-modal');
        this.$emit('message-event', 'ログアウトしました', true);
      }
    }
  }
}
</script>