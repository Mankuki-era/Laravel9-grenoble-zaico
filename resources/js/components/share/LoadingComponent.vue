<template>
  <div class="loader" v-bind:class="{hidden: hideFlag, none: noneFlag}">
    <div class="loading"></div>
  </div>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      hideFlag: false,
      noneFlag: true
    }
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    loadingEvent: function(bool){
      if(bool){
        this.hideFlag = false;
        this.noneFlag = false;
      }else{
        if(!this.hideFlag && !this.noneFlag){
          this.hideFlag = true;
          setTimeout(() => {
            this.noneFlag = true;
            this.hideFlag = false;
          }, 300);
        }
      }
    }
  }
}
</script>