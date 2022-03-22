<template>
  <div class="log">
    <div class="card">
      <li class="pdf-button"><a href="" @click.prevent.stop="downloadPDF()">PDF出力</a></li>
      <div id="log-card" class="card-main">
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
              <th>合計金額</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in data" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="name">{{ item.name }}</td>
              <td class="price">¥ {{ formatNum(item.price) }}</td>
              <td class="amount">{{ item.amount }}</td>
              <td class="stocks">{{ stockString(index) }}</td>
              <td class="stocks">¥ {{ formatNum(item.price * item.amount) }}</td>
            </tr>
            <tr></tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td colspan="2" class="last-sum">最終合計金額</td>
              <td class="last-sum">¥ {{ formatNum(sumPrice) }}</td>
            </tr>
          </tbody>
        </table>
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
    props: ['id'],
    initData: function(){
      return {
        type: '',
        data: [],
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
      stockString(index){
        if(this.type === '入庫'){
          return `${ this.formatNum(this.data[index].stocks - Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
        }else if(this.type === '出庫'){
          return `${ this.formatNum(this.data[index].stocks + Number(this.data[index].amount)) } → ${ this.formatNum(this.data[index].stocks) }`;
        }
      },
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
    }
  }
</script>