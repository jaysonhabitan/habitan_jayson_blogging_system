
<template>
  <button @click="selectFiles" class="bg-green-500 hover:bg-green-600 py-1 px-2 rounded text-white text-sm">
    <input
      ref="inputFile"
      type="file"
      style="display: none"
      :accept="accept"
      :multiple="multiple"
      @change="uploadFiles"
    >

    Upload Image
  </button>
</template>

<script>
export default {
  name: 'FileBtnComponent',

  props: {
    accept: {
      type: String,
      default: undefined,
    },

    multiple: {
      type: Boolean,
      default: false,
    },
  },

  methods: {
    selectFiles() {
      const { inputFile } = this.$refs;

      inputFile.value = '';
      inputFile.type = '';
      inputFile.type = 'file';

      inputFile.click();
    },

    uploadFiles({ target, dataTransfer }) {
      const { files } = target || dataTransfer;

      if (files && files.length > 0) {
        this.$emit('upload', files);
      }
    },
  },
};
</script>
