<template>
  <div class="stock-confirm">
    <div class="card-main">
      <h1>{{ type }}表</h1>
      <p class="date">{{ formatDate(date) }}</p>
      <table>
        <thead>
          <tr>
            <th>No.</th>
            <th>教材名</th>
            <th>価格</th>
            <th>{{ type }}数量</th>
            <th>在庫数</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in itemData" :key="item.id">
            <td>{{ index + 1 }}</td>
            <td class="name">{{ item.name }}</td>
            <td class="price">¥ {{ formatNum(item.price) }}</td>
            <td class="amount">{{ item.amount }}</td>
            <td class="stocks">{{ stockString(index) }}</td>
          </tr>
        </tbody>
      </table>
      <div class="table">
        <div class="thead">
          <div class="tr">
            <div class="no"><p>No.</p></div>
            <div class="item-info"><p>教材</p></div>
            <div class="item-stocks"><p>在庫数</p></div>
            <div class="item-amount"><p>数量</p></div>
          </div>
        </div>
        <div class="tbody">
          <div class="tr" v-for="(item, index) in itemData" :key="item.id">
            <div class="no">
              <p>{{ index + 1 }}</p>
            </div>
            <div class="item-info">
              <div class="first-row">
                <p>{{ item.name }}</p>
              </div>
              <div class="second-row">
                <p>¥ {{ formatNum(item.price) }}</p>
              </div>
            </div>
            <div class="item-stocks">
              <p>{{ stockString(index) }}</p>
            </div>
            <div class="item-amount">
              <p>{{ formatNum(item.amount) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="sub-loader" v-bind:class="{none: noneFlag}">
      <div class="loading"></div>
    </div>
    <div class="bottom-header">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <li><a href="" @click.prevent.stop="backPage"><span><i class="fa-solid fa-circle-chevron-left"></i></span><span>入力画面へ</span></a></li>
        <li><a href="" @click.prevent.stop="forwardPage"><span><i class="fa-solid fa-circle-chevron-right"></i></span><span>確定する</span></a></li>
      </ul>
    </div>
    <ul class="page-button">
      <li><a href="" class="second" @click.prevent.stop="backPage">入力画面に戻る</a></li>
      <li><a href="" class="first" @click.prevent.stop="forwardPage">確定する</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['type', 'data', 'date'],
    initData: function(){
      return {
        itemData: [],
        noneFlag: true
      };
    },
    data: function(){
      return this.$options.initData();
    },
    mounted: function(){
      this.itemData = JSON.parse(JSON.stringify(this.data));
      this.itemData.forEach((val, index) => {
        if(val.stocks !== null){
          if(this.type === '入庫'){
            this.itemData[index].stocks = val.stocks + Number(val.amount);
          }else if(this.type === '出庫'){
            this.itemData[index].stocks = val.stocks - Number(val.amount);
          }
        }
      });
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      formatDate: function(dd){ // 2021-02-07
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
        return `${y}年${m}月${d}日`;
      },
      getHmis: function(){ // 10:20:00
        var toDoubleDigits = function(num) {
          num += "";
          if (num.length === 1){
            num = "0" + num;
          }
          return num;     
        };
        var date = new Date();
        var h = toDoubleDigits(date.getHours());
        var mi = toDoubleDigits(date.getMinutes());
        var s = toDoubleDigits(date.getSeconds());
        return `${h}:${mi}:${s}`;
      },
      formatNum: function(num){
        return num.toLocaleString();
      },
      stockString(index){
        if(this.type === '入庫'){
          return `${ this.formatNum(this.itemData[index].stocks - Number(this.itemData[index].amount)) } → ${ this.formatNum(this.itemData[index].stocks) }`;
        }else if(this.type === '出庫'){
          return `${ this.formatNum(this.itemData[index].stocks + Number(this.itemData[index].amount)) } → ${ this.formatNum(this.itemData[index].stocks) }`;
        }
      },
      backPage: function(){
        this.$emit('back-page');
      },
      forwardPage: function(){
        this.noneFlag = false;
        axios.post('/api/item/stock',{
          data: this.itemData
        }).then((res) => {
          axios.post('/api/log',{
            date: `${this.date} ${this.getHmis()}`,
            type: this.type,
            data: this.itemData
          }).then((res) => {
            this.noneFlag = true;
            this.$emit('forward-page');
          });
        });
      }
    }
  }
</script>