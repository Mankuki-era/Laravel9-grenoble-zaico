<template>
  <div class="message" :class="{ animation: fields.animationFlag, success: fields.typeFlag, error: !fields.typeFlag }">
    <p>
      <span v-html="fields.icon"></span>
      <span>{{ fields.message }}</span>
    </p>
  </div>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      fields: {
        message: '',
        typeFlag: false,
        animationFlag: false,
        icon: ''
      },
    }
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    messageEvent: function(message, bool){
      this.fields.animationFlag = false;
      setTimeout(() => {
        this.fields.message = message;
        this.fields.typeFlag = bool;
        if(this.fields.typeFlag){
          this.fields.icon = `<i class="fa-solid fa-circle-check fa-lg check-icon"></i>`;
        }else{
          this.fields.icon = `<i class="fas fa-times-circle fa-lg cross-icon"></i>`;
        }
        this.fields.animationFlag = true;
      }, 300);
      setTimeout(() => {
        this.fields.animationFlag = false;
      }, 2800);
    },
  }
}
</script>