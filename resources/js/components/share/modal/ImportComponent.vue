<template>
  <div class="modal-import">
    <h1 class="modal-name">一括追加</h1>
    <ul class="sample-area">
      <li class="sample-download"><a href="/doc/format.xlsx" download><img src="/img/excel.png" alt="画像">XLSXサンプルをダウンロード</a></li>
    </ul>
    <form>
      <div class="file-area">
        <p class="msg" :class="{success: fields.type === 'success', err: fields.type === 'err'}">{{ fields.msg }}</p>
        <label><input type="file" id="file" @change="handleFile">ファイル選択</label>
        <p class="file-name">{{ fields.fileName }}</p>
      </div>
      <ul class="button-box">
        <li><a href="" :class="{disabled: data.length === 0 || fields.errFlag}" @click.prevent.stop="createData">追加</a></li>
      </ul>
    </form>
  </div>
</template>

<script>
module.exports = {
  props: ['func'],
  initData: function(){
    return {
      columnArray: ['教材名','在庫数','価格'],
      data: [],
      fields: {
        fileName: '選択されていません',
        errFlag: false,
        type: '',
        msg: 'サンプルのフォーマットに従って教材をインポートしてください'
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
    fixdata: function(resultData) {
      let o = "",
          l = 0,
          w = 10240;
      for (; l < resultData.byteLength / w; ++l) o += String.fromCharCode.apply(null, new Uint8Array(resultData.slice(l * w,
          l * w + w)));
      o += String.fromCharCode.apply(null, new Uint8Array(resultData.slice(l * w)));
      return o;
    },
    to_json: function(workbook, X) {
      let result = {};
      workbook.SheetNames.forEach(function (sheetName) {
        const roa = X.utils.sheet_to_json(
          workbook.Sheets[sheetName],
          {
              raw: true,
          });
        if (roa.length > 0) {
          result[sheetName] = roa;
        }
      });
      return result;
    },
    handleFile: function(e) {
      this.data = [];
      this.fields.errFlag = false;
      const X = XLSX;
      const files = e.target.files;
      const f = files[0];
      this.fields.fileName = f.name;
      const reader = new FileReader();
      const loadReader = (e) => {
        const resultData = e.target.result;
        const arr = this.fixdata(resultData);
        const wb = X.read(btoa(arr), {
          type: 'base64',
          cellDates: true,
        });
        const output = this.to_json(wb, X);
        let index = 1;
        for (let row of output['Sheet1']) {
          index += 1;
          this.checkData(row, index);
          this.data.push(row);
        }
        if(!this.fields.errFlag){
          this.fields.msg = 'データは正常です';
          this.fields.type = 'success';
        }
      };
      reader.readAsArrayBuffer(f);
      reader.onload = loadReader;
      document.getElementById("file").value = '';
    },
    checkData: function(row, index){
      this.columnArray.forEach(value => {
        if((value === '教材名') && row[value] === undefined){
          if(!this.fields.errFlag){
            this.fields.msg = `${index}行目の教材名が未入力です`;
            this.fields.type = 'err';
            this.fields.errFlag = true; 
          }
        };
      });
    },
    createData: function(){
      this.$emit('loading-event', true);
      axios.post('/api/import',{
        data: this.data
      }).then(e => {
        this.resetData();
        this.$emit('data-reload', this.func, null, null);
        this.$emit('close-modal');
        this.$emit('loading-event', false);
      });
    },
  }
}
</script>