<template>
  <section class="modal-area" :class="{show: fields.showFlag, hide: fields.hideFlag}">
    <div class="modal-mask" @click="closeModal"></div>
    <div class="modal-contena" :class="{ narrow: this.fields.type === 'alert' || this.fields.type === 'login', wide: this.fields.type === 'log' }">
      <div class="modal-box">
        <modal-item-component v-if="fields.type === 'item'" :id="fields.id" :index="fields.index" :func="fields.func" @data-reload="dataReload" @close-modal="closeModal" @message-event="messageEvent"></modal-item-component>
        <modal-import-component v-else-if="fields.type === 'import'" :func="fields.func" @data-reload="dataReload" @close-modal="closeModal" @message-event="messageEvent"></modal-import-component>
        <modal-alert-component v-else-if="fields.type === 'alert'" :id="fields.id" :index="fields.index" :func="fields.func" @data-reload="dataReload" @close-modal="closeModal" @message-event="messageEvent"></modal-alert-component>
        <modal-log-component v-else-if="fields.type === 'log'" :id="fields.id"></modal-log-component>
        <modal-login-component v-else-if="fields.type === 'login'" @close-modal="closeModal" @message-event="messageEvent" @header-event="headerEvent"></modal-login-component>
      </div>
      <div class="close-modal" @click="closeModal">×</div>
    </div>
  </section>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      fields: {
        id: null,
        index: null,
        func: null,
        showFlag: false,
        hideFlag: false,
        type: null
      },
    };
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    openModal: function(func, id, index){
      this.fields.func = func;
      this.fields.id = id;
      this.fields.index = index;
      this.fields.hideFlag = false;
      this.fields.showFlag = true;
      if(func === 'item-create' || func === 'item-update'){
        this.fields.type = 'item';
      }else if(func === 'item-import'){
        this.fields.type = 'import';
      }else if(func === 'item-destroy' || func === 'item-delete'){
        this.fields.type = 'alert';
      }else if(func === 'log-show'){
        this.fields.type = 'log';
      }else if(func === 'log-delete'){
        this.fields.type = 'alert'
      }else if(func === 'login'){
        this.fields.type = 'login'
      }
    },
    closeModal: function(){
      this.fields.showFlag = false;
      this.fields.hideFlag = true;
      setTimeout(() => {
        this.fields.hideFlag = false;
        this.resetData();
      }, 300);
    },
    dataReload: function(func, index, data){
      this.$emit('data-reload', func, index, data);
    },
    loadingEvent: function(bool){
      this.$refs.loadingChild.loadingEvent(bool);
    },
    messageEvent: function(message, bool){
      this.$emit('message-event', message, bool);
    },
    headerEvent: function(){
      this.$emit('header-event');
    }
  }
}
</script>