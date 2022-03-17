<template>
  <main class="log-index">
    <div class="card narrow">
      <h1 class="page-name">履歴</h1>
      <div class="card-header">
        <div class="left-box"></div>
        <div class="right-box">
          <ul class="link-box" v-show="adminFlag">
            <li><a href="" class="first" @click.prevent.stop="openModal('log-delete', null, null)"><i class="fa-solid fa-trash-can trash-icon"></i>一括削除</a></li>
          </ul>
        </div>
      </div>
      <div class="card-main">
        <table>
          <thead>
            <tr>
              <th class="type">種別</th>
              <th class="created_at">日時</th>
              <th class="user_name">対応者</th>
              <th class="action">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in filterLogs" :key="log.id">
              <td class="type">{{ log.type }}</td>
              <td class="created_at">{{ formatDate(log.created_at) }}</td>
              <td class="user_name">{{ log.user_name }}</td>
              <td class="action">
                <a href="" @click.prevent.stop="openModal('log-show', log.id, null)"><i class="fa-solid fa-magnifying-glass glass-icon"></i></a>
              </td>
            </tr>
            <tr v-show="logs.length === 0">
              <td colspan="4" class="no-data">データはありません</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="pagination">
        <a href="#" class="prev" @click.prevent.stop="onPrev"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="next" @click.prevent.stop="onNext"><i class="fas fa-chevron-right"></i></a>
        <p class="total">{{ currentPage }} / {{ totalPage }}</p>
      </div>
    </div>
  </main>
</template>

<script>
  export default {
    initData: function(){
      return {
        logs: [],
        currentPage: 1, // 現在のページ番号
        perPage: 10, // 1ページ毎の表示件数
        totalPage: 1, // 総ページ数,
        adminFlag: false
      };
    },
    data: function(){
      return this.$options.initData();
    },
    computed: {
      filterLogs(){
        return this.logs.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
      }
    },
    mounted: function(){
      this.adminFlag = this.$cookies.get('auth') === 'admin';
      this.getLogs();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      getLogs: function(){
        axios.get('/api/log').then((res) => {
          if(res.data.length > 0){
            this.logs = res.data;
            this.totalPage = Math.ceil(this.logs.length / this.perPage);
          };
        });
      },
      openModal: function(func, id, index){
        this.$emit('open-modal', func, id, index);
      },
      onPrev: function() {
        this.currentPage = Math.max(this.currentPage - 1, 1);
      },
      onNext: function() {
        this.currentPage = Math.min(this.currentPage + 1, this.totalPage);
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
      openModal: function(func, id, index){
        this.$emit('open-modal', func, id, index);
      },
      dataReload: function(func, index, data){ 
        if(func === 'log-delete'){
          this.resetData();
        }
      },
    }
  }
</script>