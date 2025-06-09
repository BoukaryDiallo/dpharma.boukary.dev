declare module 'vue-i18n' {
    interface DefineLocaleMessage {
        [key: string]: string | DefineLocaleMessage
    }
}