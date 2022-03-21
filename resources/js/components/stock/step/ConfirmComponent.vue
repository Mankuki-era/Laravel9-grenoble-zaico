<template>
  <div class="stock-confirm">
    <div class="card-main">
      <h1>{{ type }}表</h1>
      <p class="date">{{ getUpdatedAt() }}</p>
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
    </div>
    <ul class="page-button">
      <li><a href="" class="second" @click.prevent.stop="backPage">入力画面に戻る</a></li>
      <li><a href="" class="first" @click.prevent.stop="forwardPage">確定する</a></li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['type', 'data'],
    initData: function(){
      return {
        itemData: []
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
      getUpdatedAt: function(){
        var toDoubleDigits = function(num){
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
        return `${y}年${m}月${d}日`;
      },
      formatNum: function(num){
        return num.toLocaleString();
      },
      stockString(index){
        if(this.type === '入庫'){
          return `${ this.formatNum(this.itemData[index].stocks - this.itemData[index].amount) } → ${ this.formatNum(this.itemData[index].stocks) }`;
        }else if(this.type === '出庫'){
          return `${ this.formatNum(this.itemData[index].stocks + this.itemData[index].amount) } → ${ this.formatNum(this.itemData[index].stocks) }`;
        }
      },
      backPage: function(){
        this.$emit('back-page');
      },
      forwardPage: function(){
        axios.post('/api/item/stock',{
          data: this.itemData
        }).then((res) => {
          axios.post('/api/log',{
            type: this.type,
            data: this.itemData
          }).then((res) => {
            this.$emit('forward-page');
          });
        });
      }
    }
  }
</script>