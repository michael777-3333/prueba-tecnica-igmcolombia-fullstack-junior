import {
  DynamicDialogEventBus
} from "./chunk-6XTFEDF7.js";
import "./chunk-GFKAIPHP.js";
import {
  markRaw
} from "./chunk-CEBIFPJU.js";

// node_modules/primevue/usedialog/index.mjs
var PrimeVueDialogSymbol = Symbol();

// node_modules/primevue/dialogservice/index.mjs
var DialogService = {
  install: function install(app) {
    var DialogService2 = {
      open: function open(content, options) {
        var instance = {
          content: content && markRaw(content),
          options: options || {},
          data: options && options.data,
          close: function close(params) {
            DynamicDialogEventBus.emit("close", {
              instance,
              params
            });
          }
        };
        DynamicDialogEventBus.emit("open", {
          instance
        });
        return instance;
      }
    };
    app.config.globalProperties.$dialog = DialogService2;
    app.provide(PrimeVueDialogSymbol, DialogService2);
  }
};
export {
  DialogService as default
};
//# sourceMappingURL=primevue_dialogservice.js.map
