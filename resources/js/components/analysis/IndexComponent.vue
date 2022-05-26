<template>
  <main class="analysis-index">
    <div class="card narrow">
      <h1 class="page-name">データ解析</h1>
      <div class="card-main">
        <p class="step-name" v-if="mode === 1">■ STEP１ <br><span>出庫数の解析を行う期間を選択してください</span></p>
        <p class="step-name" v-if="mode === 2">■ STEP２ <br><span class="date">{{ period[0] }} 〜 {{ period[1] }}</span></p>
        <div class="form" v-if="mode === 1">
          <div class="date-form">
            <input type="date" name="" id="" v-model="period[0]">
            <span>〜</span>
            <input type="date" name="" id="" v-model="period[1]">
          </div>
        </div>
        <table v-if="mode === 2">
          <thead>
            <tr>
              <th class="name">教材名</th>
              <th class="amount">合計出庫数</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(data, index) in filterDatas" :key="index">
              <td class="name">{{ data.name }}</td>
              <td class="amount">{{ data.amount }}</td>
            </tr>
            <tr v-show="datas.length === 0">
              <td colspan="4" class="no-data">データはありません</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="sub-loader" v-bind:class="{none: noneFlag}">
        <div class="loading"></div>
      </div>
      <div class="pagination" v-if="mode === 2">
        <div class="amount">{{ datas.length }}件</div>
        <div class="page-box">
          <a href="#" class="prev" @click.prevent.stop="onPrev"><i class="fas fa-chevron-left"></i></a>
          <p class="total">{{ currentPage }} / {{ totalPage }}</p>
          <a href="#" class="next" @click.prevent.stop="onNext"><i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
      <ul class="page-button">
        <li><a href="" v-show="mode === 1" :class="{disabled: period[0] === '' || period[1] === ''}" class="first" @click.prevent.stop="startAnalysis">解析開始</a></li>
        <li><a href="" v-show="mode === 2" class="second" @click.prevent.stop="backPage">戻る</a></li>
      </ul>
    </div>
  </main>
</template>

<script>
  export default {
    initData: function(){
      return {
        datas: [],
        period: ['', ''],
        currentPage: 1, // 現在のページ番号
        perPage: 20, // 1ページ毎の表示件数
        totalPage: 1, // 総ページ数,
        adminFlag: false,
        mode: 1,
        noneFlag: true,
        testPeriod: ''
      };
    },
    data: function(){
      return this.$options.initData();
    },
    computed: {
      filterDatas(){
        return this.datas.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
      }
    },
    mounted: function(){
      this.adminFlag = this.$cookies.get('auth') === 'admin';
      this.$set(this.period, 0, this.getDate());
      this.$set(this.period, 1, this.getDate());
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      getDate: function(){
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
        return `${y}-${m}-${d}`;
      },
      startAnalysis: function(){
        var date1 = new Date(this.period[0]);
        var date2 = new Date(this.period[1]);

        if (date1 > date2) {
          this.$emit('message-event', '期間の指定が間違えています', false);
        }else{
          this.noneFlag = false;
          axios.get('/api/analysis', {
            params: {
              from: this.period[0],
              to: this.period[1]
            }
          }).then((res) => {
            if(res.data.length > 0){
              this.datas = res.data;
            };
            setTimeout(() => {
              this.noneFlag = true;
              this.mode = 2;
            }, 1000);
          });
        }
      },
      onPrev: function() {
        this.currentPage = Math.max(this.currentPage - 1, 1);
      },
      onNext: function() {
        this.currentPage = Math.min(this.currentPage + 1, this.totalPage);
      },
      backPage: function(){
        this.mode = 1;
      },
    }
  }
</script>