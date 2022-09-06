<template>
  <div class="log">
    <div class="card">
      <li class="pdf-button" v-show="func === 'log-show'"><a href="" @click.prevent.stop="downloadPDF()">PDF出力</a></li>
      <div id="log-card" class="card-main">
        <h1>{{ type }}表</h1>
        <p class="date">{{ created_at }}</p>
        <table v-show="func === 'log-show'">
          <thead>
            <tr>
              <th>No.</th>
              <th>教材名</th>
              <th>価格</th>
              <th>{{ type }}数量</th>
              <th>合計金額</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in data" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="name">{{ item.name }}</td>
              <td class="price">¥ {{ formatNum(item.price) }}</td>
              <td class="amount">{{ item.amount }}</td>
              <td class="stocks">¥ {{ formatNum(item.price * item.amount) }}</td>
            </tr>
            <tr></tr>
            <tr class="sum-row">
              <td></td>
              <td></td>
              <td></td>
              <td colspan="1" class="last-sum">総計</td>
              <td class="last-sum">¥ {{ formatNum(sumPrice) }}</td>
            </tr>
          </tbody>
        </table>
        <table v-show="func === 'log-update'">
          <thead>
            <tr>
              <th>No.</th>
              <th>教材名</th>
              <th>価格</th>
              <th>{{ type }}数量</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in data" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="name">{{ item.name }}</td>
              <td class="price">¥ {{ formatNum(item.price) }}</td>
              <td class="amount"><input type="number" v-model="item.amount"></td>
            </tr>
          </tbody>
        </table>
        <ul v-show="func === 'log-update'">
          <li><a href="" @click.prevent.stop="updateData">更新</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
h1 {
  font-size: 25px;
  text-align: center;
  color: #333;
  width: 150px;
  margin: 0 auto 20px;
  border-bottom: 1px solid #666;
}
.date {
  color: #333;
  border-bottom: 1px solid #666;
  margin-bottom: 10px;
  width: 230px;
  font-size: 15px;
  text-align: center;
}
table {
  border-collapse: collapse;
  width: 98%;
}
tr {
  height: 30px;
  text-align: left;
}
th, td {
  padding-left: 15px;
  line-height: 30px;
}
td {
  padding-right: 15px;
  color: #555;
}
th, tr:not(:last-of-type) td {
  border: 1px solid #444;
  color: #333;
}
thead tr {
  font-size: 10px;
  background-color: #ddd;
}
thead tr th {
  font-weight: normal;
}
tbody tr {
  font-size: 10px;
}
td.price, td.stocks, td.action, td.amount {
  width: 50px;
}
tr:not(:last-of-type) td, tr:last-of-type td.last-sum {
  border: 1px solid #444;
}
</style>

<script>
  export default {
    props: ['id', 'func'],
    initData: function(){
      return {
        type: '',
        data: [],
        originData: [],
        created_at: '',
        sumPrice: 0
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
            this.originData = JSON.parse(JSON.stringify(this.data));;   // 元配列が参照されない複製
            this.created_at = this.formatDate(res.data.created_at);
            this.data.forEach(val => {
              this.sumPrice += val.price * Number(val.amount);
            });
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
        var h = toDoubleDigits(date.getHours());
        var mi = toDoubleDigits(date.getMinutes());
        var s = toDoubleDigits(date.getSeconds());
        return `${y}年${m}月${d}日 ${h}:${mi}:${s}`;
      },
      formatNum: function(num){
        return num.toLocaleString();
      },
      // stockString(index){
      //   if(this.type === '入庫'){
      //     return `${ this.formatNum(this.data[index].stocks - Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
      //   }else if(this.type === '出庫'){
      //     return `${ this.formatNum(this.data[index].stocks + Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
      //   }
      // },
      downloadPDF: function(){
        const element = document.getElementById('log-card');
        const option = {
          margin: 1,
          filename: `zaico_${this.created_at}.pdf`,
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2},
          // jsPDF: { format: 'a2', orientation: 'portrait' },
          jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
        };

        html2pdf().from(element).set(option).save().then(() => {
          
        })
        .catch((e) => {
          
        });
      },
      updateData: function(){
        var array = [];
        this.data.forEach((val, index) => {
          if(val.amount !== this.originData[index].amount){
            if(this.type === '出庫'){
              var changeStocks = this.originData[index].amount - val.amount;
            }else if(this.type === '入庫'){
              var changeStocks = val.amount - this.originData[index].amount;
            };
            array.push({'id': val.id, 'changeStocks': changeStocks});
          };
        });
        if(array.length > 0){
          axios.put(`/api/log/${this.id}`,{
            data: this.data,
            array: array
          }).then((res) => {
            this.$emit('message-event', '数量を変更しました', true);
            this.$emit('close-modal');
          });
        }else{
          this.$emit('message-event', '数量が変更されていません', false);
        }
      }
    }
  }
</script>