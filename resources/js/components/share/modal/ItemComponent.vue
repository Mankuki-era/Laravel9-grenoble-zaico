<template>
  <div class="modal-item">
    <h1 class="modal-name" v-show="func === 'item-create'">新規追加</h1>
    <h1 class="modal-name" v-show="func === 'item-update'">更新</h1>
    <form>
      <div class="form-group">
        <label for="name">教材名<span>必須</span></label>
        <input type="text" class="form-control" id="name" v-model="form.name">
      </div>
      <div class="form-group">
        <label for="stocks">在庫数</label>
        <input type="number" class="form-control" id="stocks" v-model="form.stocks">
      </div>
      <div class="form-group">
        <label for="price">価格</label>
        <input type="number" class="form-control" id="price" v-model="form.price">
      </div>
      <ul>
        <li><a href="" v-show="this.func === 'item-create'" :class="{disabled: !form.name || form.name === ''}" @click.prevent.stop="createData">追加</a></li>
        <li><a href="" v-show="this.func === 'item-update'" :class="{disabled: !form.name || form.name === '' || !fields.changeFlag}" @click.prevent.stop="updateData">更新</a></li>
      </ul>
    </form>
  </div>
</template>

<script>
module.exports = {
  props: ['id', 'index', 'func'],
  initData: function(){
    return {
      fields: {
        changeFlag: false,
      },
      form: {
        favorite: '0',
        name: null,
        stocks: null,
        price: null,
        updated_at: null
      },
      originForm: {}
    };
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    if(this.func === 'item-update') this.getItem();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getUpdatedAt: function(){
      var toDoubleDigits = function(num) {
        num += "";
        if (num.length === 1){
          num = "0" + num;
        }
        return num;     
      };
      var date = new Date();
      var y = date.getFullYear();
      var m = toDoubleDigits(date.getMonth() + 1);
      var d = toDoubleDigits(date.getDate());
      var h = toDoubleDigits(date.getHours());
      var m = toDoubleDigits(date.getMinutes());
      return `${y}年${m}月${d}日 ${h}:${m}`;
    },
    getItem: function(){
      axios.get(`/api/item/${this.id}`).then((res) => {
        if(res.data){
          this.form = JSON.parse(JSON.stringify(res.data)); // formとoriginFormで同期を切り離す
          this.originForm = JSON.parse(JSON.stringify(res.data));
          // watchでformを監視
          this.$watch('form', function() {
            this.fields.changeFlag = JSON.stringify(this.form) !== JSON.stringify(this.originForm); // 連想配列の比較
          },{
            immediate: false,
            deep: true
          });
        }
      });
    },
    createData: function(){
      axios.post(`/api/item`,{
        data: this.form
      }).then((res) => {
        // var data = JSON.parse(JSON.stringify(this.form));
        this.form.updated_at = this.getUpdatedAt();
        this.$emit('data-reload', this.func, null, this.form);
        this.$emit('close-modal');
      });
    },
    updateData: function(){
      axios.put(`/api/item/${this.id}`,{
        data: this.form
      }).then((res) => {
        this.form.updated_at = this.getUpdatedAt();
        this.$emit('data-reload', this.func, this.index, this.form);
        this.$emit('close-modal');
      });
    },
  }
}
</script>