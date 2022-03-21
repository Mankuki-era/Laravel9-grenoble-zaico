<template>
  <div class="wrapper">
    <message-component ref="messageChild"></message-component>
    <loading-component ref="loadingChild"></loading-component>
    <modal-component ref="modalChild" @data-reload="dataReload" @message-event="messageEvent" @header-event="headerEvent" @loading-event="loadingEvent"></modal-component>

    <header-component ref="headerChild" @message-event="messageEvent" @open-modal="openModal"></header-component>
    <router-view ref="mainChild" @open-modal="openModal" @message-event="messageEvent" @header-event="headerEvent" @loading-event="loadingEvent"></router-view>
    <footer-component></footer-component>

  </div>
</template>

<script>
  export default {
    methods: {
      openModal: function(func, id, index){
        this.$refs.modalChild.openModal(func, id, index);
      },
      indexTypeClick: function(){
        this.$refs.mainChild.indexTypeClick();
      },
      dataReload: function(func, index, data){ // モーダルでの変更, 削除をindexに反映
        this.$refs.mainChild.dataReload(func, index, data);
      },
      messageEvent: function(message, bool){
        this.$refs.messageChild.messageEvent(message, bool);
      },
      headerEvent: function(){
        this.$refs.headerChild.checkAuth();
      },
      loadingEvent: function(bool){
        this.$refs.loadingChild.loadingEvent(bool);
      }
    }
  }
</script>
