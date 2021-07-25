import Compressor from 'compressorjs';

export default {
  compressImage(image, options = {}) {
    const compressorOptions = {
      ...options,
      quality: 0.6,
      convertSize: 2000000,
    };

    return new Promise((resolve, reject) => {
      return new Compressor(image, {
        success: resolve,
        error: reject,
        ...compressorOptions,
      });
    });
  },

  loadImage(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();

      reader.onload = () => resolve(reader.result);
      reader.onerror = reject;

      reader.readAsDataURL(file);
    });
  },
}