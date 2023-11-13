const parse = (value, fallback) => {
    if (typeof value === "undefined") {
      return fallback;
    }
  
    switch (typeof fallback) {
      case "boolean":
        return !!JSON.parse(value);
  
      case "number":
        return JSON.parse(value);
  
      default:
        return value;
    }
  };
  
  const config = {
    apiBaseUrl: import.meta.env.VITE_API_BASE_URL,
    appVersion: `${import.meta.env.VITE_APP_VERSION}-${import.meta.env.VITE_APP_BUILD}`,
    features: {},
    locales: import.meta.env.VITE_LOCALES || "en:English",
    localStorageNamespace:
      import.meta.env.VITE_LOCALE_STORAGE_NAMESPACE || "instapy__",
    wwwUrl: import.meta.env.VITE_WWW_URL
  };
  
  const feature = (name) => {
    return config.features[name];
  };
  
  export { config, feature };
  
  export default {
    install: (app) => {
      app.config.globalProperties.$appConfig = config;
      app.config.globalProperties.$feature = feature;
    },
  };
  