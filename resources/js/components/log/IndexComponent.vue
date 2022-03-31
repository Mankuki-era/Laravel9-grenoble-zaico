<template>
  <main class="log-index">
    <div class="card narrow">
      <h1 class="page-name">履歴</h1>
      <div class="card-header">
        <div class="left-box">
          <p class="amount">{{ logs.length }}件</p>
        </div>
        <div class="right-box">
          <ul class="link-box" v-show="adminFlag">
            <li v-show="logs.length > 0"><a href="" class="second" @click.prevent.stop="openModal('log-delete', null, null)"><i class="fa-solid fa-trash-can trash-icon"></i>一括削除</a></li>
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
        <div class="table">
          <div class="thead">
            <div class="tr">
              <div class="type"><p>種別</p></div>
              <div class="created_at"><p>日時</p></div>
              <div class="user_name"><p>対応者</p></div>
            </div>
          </div>
          <div class="tbody">
            <div class="tr" v-for="log in filterLogs" :key="log.id">
              <div class="type">
                <p>{{ log.type }}</p></div>
              <div class="created_at">
                <p>{{ formatDate(log.created_at) }}</p>
              </div>
              <div class="user_name">
                <p>{{ log.user_name }}</p>
              </div>
              <div class="action" @click.prevent.stop="openModal('log-show', log.id, null)"></div>
            </div>
            <div class="no-data" v-show="logs.length === 0">
              <p>データはありません</p>
            </div>
          </div>
        </div>
      </div>
      <div class="pagination">
        <div class="amount">{{ logs.length }}件</div>
        <div class="page-box">
          <a href="#" class="prev" @click.prevent.stop="onPrev"><i class="fas fa-chevron-left"></i></a>
          <p class="total">{{ currentPage }} / {{ totalPage }}</p>
          <a href="#" class="next" @click.prevent.stop="onNext"><i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
    </div>
    <div class="bottom-header">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <li><a href="" @click.prevent.stop="dataReload('log-reload',null,null)" class="second"><span><i class="fa-solid fa-arrow-rotate-right reload-icon"></i></span><span>再読込み</span></a></li>
        <li v-show="logs.length > 0"><a href="" class="second" @click.prevent.stop="openModal('log-delete', null, null)"><span><i class="fa-solid fa-trash-can trash-icon"></i></span><span>一括削除</span></a></li>
      </ul>
    </div>
  </main>
</template>

<script>
  export default {
    initData: function(){
      return {
        logs: [],
        currentPage: 1, // 現在のページ番号
        perPage: 20, // 1ページ毎の表示件数
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
        this.$emit('loading-event', true);
        axios.get('/api/log').then((res) => {
          if(res.data.length > 0){
            this.logs = res.data;
            this.totalPage = Math.ceil(this.logs.length / this.perPage);
          };
          this.$emit('loading-event', false);
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
          this.$emit('message-event', '履歴データを削除しました', true);
        }else if(func === 'log-reload'){
          this.getLogs();
          this.$emit('message-event', '履歴情報を再読込みしました', true);
        }
      },
    }
  }
</script>