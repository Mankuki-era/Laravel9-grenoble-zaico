<template>
  <main class="item-index">
    <div class="card">
      <h1 class="page-name">教材一覧</h1>
      <div class="card-header">
        <div class="left-box">
          <p class="amount">{{ items.length }}件</p>
        </div>
        <div class="right-box">
          <ul class="link-box" v-show="adminFlag">
            <li><a href="" @click.prevent.stop="dataReload('item-reload',null,null)" class="second"><i class="fa-solid fa-arrow-rotate-right reload-icon"></i>再読込</a></li>
            <li><a href="javascript:void(0)" class="select-link second">その他</a></li>
            <ul class="select-menu" id="select_menu">
              <li><a href="" @click.prevent.stop="openModal('item-import', null, null)"><i class="fa-solid fa-plus plus-icon"></i>一括追加</a></li>
              <li v-show="items.length > 0"><a href="" @click.prevent.stop="openModal('item-delete', null, null)"><i class="fa-solid fa-trash-can trash-icon"></i>一括削除</a></li>
            </ul>
            <li><a href="" class="first" @click.prevent.stop="openModal('item-create', null, null)"><i class="fa-solid fa-plus plus-icon"></i>新規追加</a></li>
          </ul>
        </div>
      </div>
      <div class="card-main">
        <table id="table-data">
          <thead>
            <tr>
              <th class="favorite"></th>
              <th class="name">教材名</th>
              <th class="stocks">在庫数</th>
              <th class="price">価格</th>
              <th class="updated_at">最終更新日時</th>
              <th class="action" v-show="adminFlag">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in filterItems" :key="item.id">
              <td class="favorite">
                <a href="" v-show="item.favorite === '0'" @click.prevent.stop="favoriteEvent(item.id, '1', index)" :class="{ auth: !adminFlag }"><i class="far fa-star fa-lg star-icon"></i></a>
                <a href="" v-show="item.favorite === '1'" @click.prevent.stop="favoriteEvent(item.id, '0', index)" :class="{ auth: !adminFlag }"><i class="fas fa-star fa-lg star-icon check"></i></a>
              </td>
              <td class="name">{{ item.name }}</td>
              <td class="stocks" v-show="item.stocks !== null">{{ formatNum(item.stocks) }}</td>
              <td class="stocks" v-show="item.stocks === null">ー</td>
              <td class="price" v-show="item.price !== null">¥ {{ formatNum(item.price) }}</td>
              <td class="price" v-show="item.price === null">ー</td>
              <td class="updated_at">{{ formatDate(item.updated_at) }}</td>
              <td class="action" v-show="adminFlag">
                <a href="" class="edit" @click.prevent.stop="openModal('item-update', item.id, index)"><i class="fa-solid fa-pen pen-icon"></i></a>
                <a href="" class="destroy" @click.prevent.stop="openModal('item-destroy', item.id, index)"><i class="fa-solid fa-trash-can trash-icon"></i></a>
              </td>
            </tr>
            <tr v-show="items.length === 0">
              <td colspan="6" class="no-data">データはありません</td>
            </tr>
          </tbody>
        </table>
        <div class="table">
          <div class="tbody">
            <div class="tr" v-for="(item, index) in filterItems" :key="item.id">
              <div class="favorite">
                <a href="" v-show="item.favorite === '0'" @click.prevent.stop="favoriteEvent(item.id, '1', index)" :class="{ auth: !adminFlag }"><i class="far fa-star fa-lg star-icon"></i></a>
                <a href="" v-show="item.favorite === '1'" @click.prevent.stop="favoriteEvent(item.id, '0', index)" :class="{ auth: !adminFlag }"><i class="fas fa-star fa-lg star-icon check"></i></a>
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
              <div class="item-destroy">
                <li><a href="" class="destroy" @click.prevent.stop="openModal('item-destroy', item.id, index)"><i class="fa-solid fa-trash-can trash-icon"></i></a></li>
              </div>
              <div class="action" @click.prevent.stop="openModal('item-update', item.id, index)"></div>
            </div>
            <div class="no-data" v-show="items.length === 0">
              <p>データはありません</p>
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
    </div>
    <div class="bottom-header">
      <ul class="link-box" v-show="this.$route.path !== '/login'">
        <li><a href="" @click.prevent.stop="dataReload('item-reload',null,null)" class="second"><span><i class="fa-solid fa-arrow-rotate-right reload-icon"></i></span><span>再読込み</span></a></li>
        <li><a href="" class="first" @click.prevent.stop="openModal('item-create', null, null)"><span><i class="fa-solid fa-plus plus-icon"></i></span><span>新規追加</span></a></li>
        <li v-show="items.length > 0"><a href="" @click.prevent.stop="openModal('item-delete', null, null)"><span><i class="fa-solid fa-trash-can trash-icon"></i></span><span>一括削除</span></a></li>
      </ul>
    </div>
  </main>
</template>

<script>
  export default {
    initData: function(){
      return {
        columnArray: ['教材名','在庫数','価格','更新日'],
        items: [],
        currentPage: 1, // 現在のページ番号
        perPage: 20, // 1ページ毎の表示件数
        totalPage: 1, // 総ページ数,
        adminFlag: false,
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
      this.adminFlag = this.$cookies.get('auth') === 'admin';
      this.getItems();

      $(function(){
        $(document).on('click',function(e) {
          if(!$(e.target).closest('.select-link').length) {
            $('.select-menu').fadeOut(200);
          }
        });
        $('.select-link').click(function(){
          $('.select-menu').fadeToggle(200);
        });
      });
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      getItems: function(){
        this.$emit('loading-event', true);
        axios.get('/api/item').then((res) => {
          if(res.data.length > 0){
            this.items = res.data;
            this.totalPage = Math.ceil(this.items.length / this.perPage);
            if(this.totalPage < this.currentPage) this.currentPage = this.totalPage;
          };
          this.$emit('loading-event', false);
        });
      },
      favoriteEvent: function(id, favorite, index){
        axios.put(`/api/item/favorite/${id}`,{
          favorite: favorite,
        }).then((res) => {
          this.items[(this.currentPage - 1) * this.perPage + index].favorite = favorite;
        });
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
        if(num !== null) return Number(num).toLocaleString();
      },
      onPrev: function() {
        this.currentPage = Math.max(this.currentPage - 1, 1);
      },
      onNext: function() {
        this.currentPage = Math.min(this.currentPage + 1, this.totalPage);
      },
      openModal: function(func, id, index){
        this.$emit('open-modal', func, id, index);
      },
      dataReload: function(func, index, data){ // modalでの変更、削除を反映   配列は$set, $delete使わないと反映されない
        if(func === 'item-create'){
          this.getItems();
          this.$emit('message-event', '教材情報を登録しました', true);
        }else if(func === 'item-update'){
          this.getItems();
          this.$emit('message-event', '教材情報を更新しました', true);
        }else if(func === 'item-destroy'){
          this.getItems();
          this.$emit('message-event', '教材情報を削除しました', true);
        }else if(func === 'item-delete'){
          this.resetData();
          this.adminFlag = this.$cookies.get('auth') === 'admin';
          this.$emit('message-event', '教材情報を全て削除しました', true);
        }else if(func === 'item-import'){
          this.getItems();
          this.$emit('message-event', '教材情報を一括登録しました', true);
        }else if(func === 'item-reload'){
          this.getItems();
          this.$emit('message-event', '教材情報を再読込みしました', true);
        }
      },
    }
  }
</script>