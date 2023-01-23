require('./bootstrap');
require('alpinejs');

import { createApp } from 'vue';

import chat from "./components/Chat.vue";
import EmojiPicker from 'vue3-emoji-picker';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = createApp({});

app.component('chat', chat);
app.component('EmojiPicker', EmojiPicker);

app.mount("#app");