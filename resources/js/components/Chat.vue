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
                                <template v-for="visitor in visitors">
                                    <a href="javascript:void(0)" class="d-flex align-items-center px-3 py-2" v-bind:class="(visitor.visitor_id == state.visitor) ? 'selected-user' : '' "
                                        @click="fetchMessages(visitor.visitor_id,visitor.name,visitor.email)">
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
                                        <!-- <span class="badge text-bg-danger unread-badge" v-if="contact.unread_messages_count > 0">{{contact.unread_messages_count}}</span> -->
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- chatbox -->
        <div class="chatbox">
            <div class="modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="msg-head">
                        <div class="row">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h3>{{ state.visitorname }}</h3>
                                        <p>{{ state.visitoremail }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control ms-auto" @change="changeStatus">
                                    <option value="1" :selected="(operatorStatus == 1) ? true : false">Online</option>
                                    <option value="0" :selected="(operatorStatus == 0) ? true : false">Offline</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="scrollable modal-body" ref="hasScrolledToBottom">
                        <div class="msg-body">
                            <ul class="">
                                <template v-for="message in state.messages" :key="message.key">
                                    <li class="sender" v-if="message.sender == state.visitor">
                                        <p><span class=""> </span> {{ message.content }} </p>
                                        <!-- <span class="time">{{ message.created_at }}</span> -->
                                    </li>
                                    <li class="repaly" v-else-if="message.sender == state.operator">
                                        <p><span class=""> You : </span> {{ message.content }} </p>
                                        <!-- <span class="time">{{ message.created_at }}</span> -->
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <div class="send-box position-relative d-none" id="chatBox">
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
		let hasScrolledToBottom = ref("")
		let operatorStatus = ref(props.user.status);
		let visitors = ref([]);
		let state = reactive({
			operator: props.user.operator_id,
            visitor : '',
            visitorname : '',
            visitoremail : '',
			messages: []
		});

		const Login = () => {
			if (inputUsername.value != "" || inputUsername.value != null) {
				state.operator = inputUsername.value;
				inputUsername.value = "";
			}
		}

		const Logout = () => {
			state.operator = "";
			state.visitor = "";
		}

		const SendMessage = () => {
			const messagesRef = db.database().ref("messages");

			if (inputMessage.value === "" || inputMessage.value === null) {
				return;
			}

			const message = {
				operator: state.operator,
				visitor: state.visitor,
                sender : state.operator,
                receiver : state.visitor,
				content: inputMessage.value
			}

			messagesRef.push(message);
			inputMessage.value = "";
		}

		const scrollBottom = () => {
			if (state.messages.length > 1 && state.operator != '') {
				let el = hasScrolledToBottom.value;
				el.scrollTop = el.scrollHeight;
			}
		}

        const fetchMessages = (visitor,name,email) => {
            state.visitor = visitor;
            state.visitorname = name;
            state.visitoremail = email;
            const messagesRef = db.database().ref("messages");
			messagesRef.on('value', snapshot => {
				const data = snapshot.val();
				let messages = [];
				Object.keys(data).forEach(key => {
                    if((data[key].sender == state.visitor && data[key].receiver == state.operator) || (data[key].sender == state.operator && data[key].receiver == state.visitor)){
                        messages.push({
                            id: key,
                            operator: data[key].operator,
                            visitor: data[key].visitor,
                            sender : data[key].sender,
                            receiver : data[key].receiver,
                            content: data[key].content
                        });
                    }
				});
				state.messages = messages;
                document.querySelector('#chatBox').classList.remove('d-none');
			});
        }

        const fetchUsers = async () => {
            axios.get('/chat/visitors').then(response => {
                visitors.value = response.data;
            });
        }
        const changeStatus = (event) => {
            axios.get('/chat/operator/status/'+state.operator+'/'+event.target.value).then(response => {
                if(response.data.status == 'success'){
                    operatorStatus.value = event.target.value;
                }
            });
        }

		onMounted(() => {
            fetchUsers();
		});
		onUpdated(() => {
			scrollBottom();
		})
		return {
			inputUsername,
            visitors,
			Login,
			state,
            operatorStatus,
			inputMessage,
			SendMessage,
            fetchMessages,
            changeStatus,
			Logout,
			scrollBottom,
			hasScrolledToBottom
		}
    }
}
</script>