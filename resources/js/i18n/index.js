import { createI18n } from "vue-i18n";

// Traduções
import en from "./locales/en.json";
import pt from "./locales/pt.json";

export default createI18n({
    legacy: false, // importante para Vue 3 + Composition API
    locale: "en", // idioma padrão
    fallbackLocale: "en",
    messages: {
        en,
        pt,
    },
});
