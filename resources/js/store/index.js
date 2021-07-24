import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    snackbar: {
      isVisible: false,
      text: null,
      status: 'success'
    }
  },

  mutations: {
    SET_SNACKBAR(state, snackbar) {
      state.snackbar = snackbar;
    }
  },

  actions: {
    setSnackbar({commit}, snackbar) {
      commit('SET_SNACKBAR', snackbar);

      setTimeout(() => {
        commit('SET_SNACKBAR', {
          isVisible: false,
          text: null,
        })
      }, 3000)
    }
  }
});