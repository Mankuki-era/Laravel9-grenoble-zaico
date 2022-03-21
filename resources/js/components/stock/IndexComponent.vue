<template>
  <main class="stock">
    <div class="card">
      <h1 class="page-name">入庫/出庫</h1>
      <div class="step-box">
        <div class="icon-box">
          <div :class="{ current: step.input === 'current', check: step.input === 'check' }" v-html="icon.input"></div>
          <p class="check">入力</p>
        </div>
        <div class="bar-box">
          <div :class="{ check: step.input === 'check', go: animation.bar01 === 'go', back: animation.bar01 === 'back' }"></div>
          <div class="animation-bar" :class="{ go: animation.bar01 === 'go', back: animation.bar01 === 'back' }"></div>
        </div>
        <div class="icon-box">
          <div :class="{ current: step.confirm === 'current', check: step.confirm === 'check' }" v-html="icon.confirm"></div>
          <p :class="{ check: step.confirm === 'current' || step.confirm === 'check' }">確認</p>
        </div>
        <div class="bar-box">
          <div :class="{ check: step.confirm === 'check', go: animation.bar02 === 'go' }"></div>
          <div class="animation-bar" :class="{ go: animation.bar02 === 'go' }"></div>
        </div>
        <div class="icon-box">
          <div :class="{ check: step.result === 'current' }" v-html="icon.result"></div>
          <p :class="{ check: step.result === 'current' }">完了</p>
        </div>
      </div>
      <stock-input-component v-if="step.input === 'current'" :type="type" :data="items" @forward-page="confirmPage" @message-event="messageEvent" @header-event="headerEvent" @loading-event="loadingEvent"></stock-input-component>
      <stock-confirm-component v-else-if="step.confirm === 'current'" :type="type" :data="data"   @forward-page="resultPage" @back-page="inputPage"></stock-confirm-component>
      <stock-result-component v-else-if="step.result === 'current'" @header-event="headerEvent"></stock-result-component>
    </div>
  </main>
</template>

<script>
  export default {
    initData: function(){
      return {
        step: {
          input: 'current',
          confirm: '',
          result: ''
        },
        icon: {
          input: '',
          confirm: '',
          result: ''
        },
        animation: {
          bar01: '',
          bar02: ''
        },
        type: '出庫',
        items: [],
        data: []
      };
    },
    data: function(){
      return this.$options.initData();
    },
    methods: {
      resetData: function(){
        Object.assign(this.$data, this.$options.initData());
      },
      confirmPage: function(type, items, data){
        this.type = type;
        this.items = items;
        this.data = data;
        this.animation = {
          bar01: 'go',
          bar02: ''
        };
        this.step = {
          input: 'check',
          confirm: '',
          result: ''
        };
        this.icon = {
          input: '<i class="fa-solid fa-check"></i>',
          confirm: '',
          result: ''
        };
        setTimeout(() => {
          this.step = {
            input: 'check',
            confirm: 'current',
            result: ''
          };
        }, 1000);
      },
      inputPage: function(){
        this.animation = {
          bar01: 'back',
          bar02: ''
        };
        this.step = {
          input: 'check',
          confirm: '',
          result: ''
        };
        setTimeout(() => {
          this.step = {
            input: 'current',
            confirm: '',
            result: ''
          };
          this.icon = {
            input: '',
            confirm: '',
            result: ''
          };
        }, 1000);
      },
      resultPage: function(){
        this.animation = {
          bar01: '',
          bar02: 'go'
        };
        this.step = {
          input: 'check',
          confirm: 'check',
          result: ''
        };
        this.icon = {
          input: '<i class="fa-solid fa-check"></i>',
          confirm: '<i class="fa-solid fa-check"></i>',
          result: ''
        };
        setTimeout(() => {
          this.step = {
            input: 'check',
            confirm: 'check',
            result: 'current'
          };
          this.icon = {
            input: '<i class="fa-solid fa-check"></i>',
            confirm: '<i class="fa-solid fa-check"></i>',
            result: '<i class="fa-solid fa-check"></i>'
          };
        }, 1000);
      },
      messageEvent: function(message, bool){
        this.$emit('message-event', message, bool);
      },
      headerEvent: function(){
        this.$emit('header-event');
      },
      loadingEvent: function(bool){
        this.$emit('loading-event', bool);
      }
    }
  }
</script>