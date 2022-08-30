<template>
  <div class="update-items">
    <h1 class="modal-name">一括編集</h1>
    <table>
      <thead>
        <tr>
          <th class="no">No.</th>
          <th class="name">教材名</th>
          <th class="stocks">在庫数</th>
          <th class="price">価格</th>
        </tr>
      </thead>
    </table>
    <div class="card-main">
      <table>
        <tbody>
          <tr v-for="(item, index) in items" :key="item.id">
            <td class="no">{{ index + 1 }}</td>
            <td class="name">{{ item.name }}</td>
            <td class="stocks">
              <input type="number" v-model="item.stocks">
            </td>
            <td class="price" v-show="item.price !== null">¥ {{ formatNum(item.price) }}</td>
            <td class="price" v-show="item.price === null">ー</td>
          </tr>
          <tr v-show="items.length === 0">
            <td colspan="5" class="no-data">データはありません</td>
          </tr>
        </tbody>
      </table>
    </div>
    <ul class="page-button">
      <li v-show="items.length > 0"><a href="" class="first" @click.prevent.stop="updateData">保存</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['func'],
    initData: function(){
      return {
        items: [],
        originItems: [],
        currentPage: 1, // 現在のページ番号
        perPage: 20, // 1ページ毎の表示件数
        totalPage: 1, // 総ページ数,
        mode: '',
      };
    },
    data: function(){
      return this.$options.initData();
    },
    computed: {
      filterItems(){
        return this.items.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
      }
    },
    mounted: function(){
      this.getItems();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      getItems: function(){
        this.$emit('loading-event', true);
        axios.get('/api/item').then((res) => {
          if(res.data.length > 0){
            res.data.forEach(val => {
              val.amount = ""
            });
            this.items = JSON.parse(JSON.stringify(res.data)); // formとoriginFormで同期を切り離す
            this.originItems = JSON.parse(JSON.stringify(res.data));
            this.totalPage = Math.ceil(this.items.length / this.perPage);
          };
          this.$emit('loading-event', false);
        });
      },
      onPrev: function() {
        this.currentPage = Math.max(this.currentPage - 1, 1);
      },
      onNext: function() {
        this.currentPage = Math.min(this.currentPage + 1, this.totalPage);
      },
      formatDate: function(dd){ // 2021-02-07 10:20:00
        if(dd.split(' ')[1] === undefined){
          var toDoubleDigits = function(num) {
            num += "";
            if (num.length === 1){
              num = "0" + num;
            }
            return num;     
          };
          var date = new Date(dd);
          var y = date.getFullYear();
          var m = toDoubleDigits(date.getMonth() + 1);
          var d = toDoubleDigits(date.getDate());
          var h = toDoubleDigits(date.getHours());
          var mi = toDoubleDigits(date.getMinutes());
          var s = toDoubleDigits(date.getSeconds());
          return `${y}年${m}月${d}日 ${h}:${mi}`;
        }else{
          return dd;
        }
      },
      formatNum: function(num){
        if(num !== null) return num.toLocaleString();
      },
      selectItems: function(){
        var data = [];
        this.items.forEach(val => {
          if(val.amount !== ""){
            data.push(val);
          }
        });
        return data;
      },
      forwardPage: function(){
        if(JSON.stringify(this.items) !== JSON.stringify(this.originItems)) {// 連想配列の比較
          var errType = '';
          this.selectItems().forEach(item => {
            if(errType === '' && (item.stocks === null || item.price === null)){
              errType = 'type01';
            }
            if(errType === '' && this.mode === '出庫' && item.stocks < item.amount){
              errType = 'type02';
            }
          });
          if(errType === 'type01'){
            this.$emit('message-event', '在庫数または価格が登録されていません', false);
          }else if(errType === 'type02'){
            this.$emit('message-event', '数量が在庫数を超過しています', false);
          }else{
            this.$emit('forward-page', this.mode, this.items, this.selectItems());
          } 
        }else{
          this.$emit('message-event', '数量を入力してください', false);
        }
      },
      backPage: function(){
        this.$router.push({
          path: '/'
        });
        this.$emit('header-event');
      },
      updateData: function(){
        axios.post('/api/update',{
          data: this.items
        }).then(e => {
          this.$emit('data-reload', this.func, null, null);
          this.$emit('close-modal');
          this.$emit('loading-event', false);
        });
      }
    }
  }
</script>