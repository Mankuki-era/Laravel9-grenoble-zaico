<template>
  <div class="stock-input">
    <div class="card-header">
      <div class="form-contena" v-show="items.length > 0">
        <div class="form-box">
          <input type="radio" id="in" value="入庫" v-model="mode">
          <label for="in">入庫</label>
        </div>
        <div class="form-box">
          <input type="radio" id="out" value="出庫" v-model="mode">
          <label for="out">出庫</label>
        </div>
      </div>
    </div>
    <div class="card-main">
      <table>
        <thead>
          <tr>
            <th class="favorite"></th>
            <th class="name">教材名</th>
            <th class="stocks">在庫数</th>
            <th class="price">価格</th>
            <th class="amount">数量</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filterItems" :key="item.id">
            <td class="favorite">
              <a href="" v-show="item.favorite === '0'"><i class="far fa-star fa-lg star-icon"></i></a>
              <a href="" v-show="item.favorite === '1'"><i class="fas fa-star fa-lg star-icon check"></i></a>
            </td>
            <td class="name">{{ item.name }}</td>
            <td class="stocks" v-show="item.stocks !== null">{{ formatNum(item.stocks) }}</td>
            <td class="stocks" v-show="item.stocks === null">ー</td>
            <td class="price" v-show="item.price !== null">¥ {{ formatNum(item.price) }}</td>
            <td class="price" v-show="item.price === null">ー</td>
            <td class="amount">
              <input type="number" v-model="item.amount">
            </td>
          </tr>
          <tr v-show="items.length === 0">
            <td colspan="5" class="no-data">データはありません</td>
          </tr>
        </tbody>
      </table>
      <div class="table">
        <div class="tbody">
          <div class="tr" v-for="item in filterItems" :key="item.id">
            <div class="favorite">
              <a href="" v-show="item.favorite === '0'"><i class="far fa-star fa-lg star-icon"></i></a>
              <a href="" v-show="item.favorite === '1'"><i class="fas fa-star fa-lg star-icon check"></i></a>
            </div>
            <div class="item-info">
              <div class="first-row">
                <p>{{ item.name }}</p>
              </div>
              <div class="second-row">
                <p>¥ {{ formatNum(item.price) }}</p>
                <p>{{ formatDate(item.updated_at) }}</p>
              </div>
            </div>
            <div class="item-stocks">
              <p>{{ formatNum(item.stocks) }}</p>
            </div>
            <div class="item-stocks-input">
              <input type="number" v-model="item.amount">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="pagination">
      <div class="amount">{{ items.length }}件</div>
      <div class="page-box">
        <a href="#" class="prev" @click.prevent.stop="onPrev"><i class="fas fa-chevron-left"></i></a>
        <p class="total">{{ currentPage }} / {{ totalPage }}</p>
        <a href="#" class="next" @click.prevent.stop="onNext"><i class="fas fa-chevron-right"></i></a>
      </div>
    </div>
    <div class="bottom-header">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <li><a href="" @click.prevent.stop="backPage"><span><i class="fa-solid fa-circle-chevron-left"></i></span><span>教材一覧へ</span></a></li>
        <div class="form-contena" v-show="items.length > 0">
          <div class="form-box">
            <input type="radio" id="in" value="入庫" v-model="mode">
            <label for="in">入庫</label>
          </div>
          <div class="form-box">
            <input type="radio" id="out" value="出庫" v-model="mode">
            <label for="out">出庫</label>
          </div>
        </div>
        <li v-show="items.length > 0"><a href="" @click.prevent.stop="forwardPage"><span><i class="fa-solid fa-circle-chevron-right"></i></span><span>確認画面へ</span></a></li>
      </ul>
    </div>
    <ul class="page-button">
      <li><a href="" class="second" @click.prevent.stop="backPage">教材一覧へ</a></li>
      <li v-show="items.length > 0"><a href="" class="first" @click.prevent.stop="forwardPage">確認画面に進む</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['type', 'data'],
    initData: function(){
      return {
        items: [],
        originItems: [],
        currentPage: 1, // 現在のページ番号
        perPage: 10, // 1ページ毎の表示件数
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
      this.mode = this.type;
      if(this.data.length === 0){
        this.getItems();
      }else{
        this.items = this.data;
        this.totalPage = Math.ceil(this.items.length / this.perPage);
      }
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
    }
  }
</script>