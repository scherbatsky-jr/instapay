import {
    availableLocales,
    getPreferredLocale,
    setLocale as setI18nLocale,
} from "@/i18n";
import { defineStore } from "pinia";
import { setLocale as setValidationLocale } from "@vee-validate/i18n";
import storage from "@/storage";

export const useAppStore = defineStore("app", {
actions: {
    setLocale(locale) {
    let language = this.locale;

    if (locale) {
        language = locale;
    }

    setI18nLocale(language);
    setValidationLocale(language);
    this.locale = language;
    storage.setItem("locale", language);
    },
},

getters: {
    availableLocales() {
    return availableLocales;
    },
},

state: () => {
    return {
    app: "broker",
    locale: storage.getItem("locale") || getPreferredLocale(),
    };
},
});
