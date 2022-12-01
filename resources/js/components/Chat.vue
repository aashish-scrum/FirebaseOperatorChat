<style>
@import '../../css/chat.css';
.v3-emoji-picker{
    position: absolute;
    text-align: left;
    bottom: 85%;
    left: 2%;
}
.v3-emoji-picker .v3-footer{
    display: none;
}
.unread-badge{
    font-size: 11px;
    border-radius: 25px;
    padding: 3px 5px;
}
</style>
<template>
    <div class="chat-area">
        <!-- chatlist -->
        <div class="chatlist p-0">
            <div class="modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="chat-header p-3">
                        <div class="msg-search">
                            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search"
                                aria-label="search">
                            <a class="add" href="#"><img class="img-fluid"
                                    src="https://mehedihtml.com/chatbox/assets/img/add.svg" alt="add"></a>
                        </div>
                    </div>

                    <div class="modal-body mt-1 border-top">
                        <div class="chat-lists">
                            <div class="chat-list">
                                <!-- <template v-for="visitor in users">
                                    <a href="javascript:void(0)" class="d-flex align-items-center px-3 py-2" v-bind:class="(visitor.id == chatUser.id) ? 'selected-user' : '' "
                                        @click="fetchMessages(visitor)">
                                        <div class="flex-shrink-0">
                                            <img class="img-fluid"
                                                v-bind:src="visitor.avatar"
                                                alt="user img">
                                            <span class="active"></span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{ visitor.name }}</h3>
                                            <p>{{ visitor.email }}</p>
                                        </div>
                                        <span class="badge text-bg-danger unread-badge" v-if="contact.unread_messages_count > 0">{{contact.unread_messages_count}}</span>
                                    </a>
                                </template> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- chatbox -->
        <div class="chatbox">
            <div class="modal-dialog-scrollable">
                <div class="modal-content" id="chatBox">
                    <div class="msg-head">
                        <div class="row">
                            <div class="col-8">
                                    <div class="d-flex align-items-center">
                                        <!-- <span class="chat-icon"><img class="img-fluid avatar"
                                                v-bind:src="chatUser.avatar"
                                                alt="image title"></span>
                                        <div class="flex-shrink-0">
                                            <img class="img-fluid avatar" v-bind:src="chatUser.avatar" alt="user img">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{ chatUser.name }}</h3>
                                            <p>{{ chatUser.email }}</p>
                                        </div> -->
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="scrollable modal-body" ref="hasScrolledToBottom">
                        <div class="msg-body" v-if="state.username === '' || state.username === null">
                            <form class="login-form" @submit.prevent="Login">
                                <div class="form-inner">
                                    <h1>Login to FireChat</h1>
                                    <label for="username">Username</label>
                                    <input type="text" v-model="inputUsername" placeholder="Please enter your username..." />
                                    <input type="submit" value="Login" />
                                </div>
                            </form>
                        </div>
                        <div class="msg-body" v-else>
                            <ul class="">
                                <template v-for="message in state.messages" :key="message.key">
                                    <li :class="(message.username == state.username) ? 'repaly' : 'sender'" >
                                        <p><span class=""> {{ (message.username == state.username) ? 'You' : message.username }} : </span> {{ message.content }} </p>
                                        <!-- <span class="time">{{ message.created_at }}</span> -->
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <!-- <div class="w-100 h-100 d-flex justify-content-center align-items-center" v-else>
                            <h5>Say Hi to <span style="font-weight: 600;">{{ chatUser.name }}</span> 👋</h5>
                        </div> -->
                    </div>

                    <div class="send-box position-relative">
                        <form action="javascript:void(0)" @submit.prevent="SendMessage">
                            <!-- <EmojiPicker :display-recent="true" :disableSkinTones="false" @select="onSelectEmoji" class="d-none" />
                            <a tabindex="0" class="p-2" role="button" @click="showEmoji" >😀</a> -->
                            <input type="text" class="form-control" aria-label="message…" placeholder="Write message…"
                                v-model="inputMessage" @keyup.enter="SendMessage">

                            <button type="submit" id="btn-chat">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, inject, ref, onMounted, onUpdated } from 'vue';
import db from '../db';
import "bootstrap/dist/js/bootstrap.min.js";

export default {
    props: ['user'],
    setup(props) {
        const inputUsername = ref("");
		const inputMessage = ref("");
		let hasScrolledToBottom = ref('')
		const state = reactive({
			username: props.user.operator_id,
			messages: []
		});

		const Login = () => {
			if (inputUsername.value != "" || inputUsername.value != null) {
				state.username = inputUsername.value;
				inputUsername.value = "";
			}
		}

		const Logout = () => {
			state.username = "";
		}

		const SendMessage = () => {
			const messagesRef = db.database().ref("messages");

			if (inputMessage.value === "" || inputMessage.value === null) {
				return;
			}

			const message = {
				username: state.username,
				content: inputMessage.value
			}

			messagesRef.push(message);
			inputMessage.value = "";
		}

		const scrollBottom = () => {
			if (state.messages.length > 1 && state.username != '') {
				let el = hasScrolledToBottom.value;
				el.scrollTop = el.scrollHeight;
			}
		}

		onMounted(() => {
			const messagesRef = db.database().ref("messages");

			messagesRef.on('value', snapshot => {
				const data = snapshot.val();
				let messages = [];

				Object.keys(data).forEach(key => {
					messages.push({
						id: key,
						username: data[key].username,
						content: data[key].content
					});
				});

				state.messages = messages;
			});
		});
		onUpdated(() => {
			scrollBottom();
		})
		return {
			inputUsername,
			Login,
			state,
			inputMessage,
			SendMessage,
			Logout,
			scrollBottom,
			hasScrolledToBottom
		}
    }
}
</script>