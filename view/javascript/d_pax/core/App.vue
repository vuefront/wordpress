<template>
  <div
    :is="layoutTag"
    id="app"
  />
</template>
<script>
import { isUndefined, isEmpty } from "lodash";
import "~/assets/scss/main.scss";

/**
 * The main component of the application
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */
export default {
  name: "MmApp",
  /**
   * Local data
   */
  data() {
    let layout = "default";
    if (
      !isEmpty(this.$route.matched) &&
      !isUndefined(this.$route.matched[0].components.default.layout)
    ) {
      layout = this.$route.matched[0].components.default.layout;
    }

    return {
      layout: layout
    };
  },
  computed: {
    /**
     * Getting the tag name of the current layout
     */
    layoutTag() {
      return "layout-" + this.layout;
    }
  },
  watch: {
    /**
     * Tracking current url changes for layout update
     */
    $route(to, from) {
      if (!isUndefined(to.matched[0].components.default.layout)) {
        this.layout = to.matched[0].components.default.layout;
      } else {
        this.layout = "default";
      }
    }
  },
  methods: {
    /**
     * Setting current layout
     */
    setLayout(layout) {
      this.layout = layout;
    }
  }
};
</script>
