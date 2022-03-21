<template>
  <div class="log">
    <div class="card">
      <div class="card-main">
        <h1>{{ type }}表</h1>
        <p class="date">{{ created_at }}</p>
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
            <tr v-for="(item, index) in data" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="name">{{ item.name }}</td>
              <td class="price">¥ {{ formatNum(item.price) }}</td>
              <td class="amount">{{ item.amount }}</td>
              <td class="stocks">{{ stockString(index) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['id'],
    initData: function(){
      return {
        type: '',
        data: [],
        created_at: ''
      };
    },
    data: function(){
      return this.$options.initData();
    },
    mounted: function(){
      this.getLog();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      getLog: function(){
        axios.get(`/api/log/${this.id}`).then((res) => {
          if(res.data){
            this.type = res.data.type;
            this.data = res.data.data;
            this.created_at = this.formatDate(res.data.created_at);
          }
        });
      },
      formatDate: function(dd){
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
      formatNum: function(num){
        return num.toLocaleString();
      },
      stockString(index){
        if(this.type === '入庫'){
          return `${ this.formatNum(this.data[index].stocks - Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
        }else if(this.type === '出庫'){
          return `${ this.formatNum(this.data[index].stocks + Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
        }
      },
    }
  }
</script>