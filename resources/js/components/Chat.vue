<style>
@import '../../css/chat.css';

.v3-emoji-picker {
    position: absolute;
    text-align: left;
    bottom: 85%;
    left: 2%;
}

.v3-emoji-picker .v3-footer {
    display: none;
}

.unread-badge {
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
                                    <a href="javascript:void(0)" class="d-flex align-items-center px-3 py-2"
                                        v-bind:class="(visitor.visitor_id == state.visitor) ? 'selected-user' : ''"
                                        @click="fetchMessages(visitor.visitor_id, visitor.name, visitor.email)">
                                        <div class="flex-shrink-0">
                                            <img class="img-fluid" v-bind:src="visitor.avatar" alt="user img">
                                            <!-- <span class="active"></span> -->
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{ visitor.name }}</h3>
                                            <p>{{ visitor.email }}</p>
                                        </div>
                                        <span class="badge text-bg-danger unread-badge" style="position: absolute;right: 3%;top: 25%;"
                                            v-if="visitor.messages_count > 0">{{ visitor.messages_count }}</span>
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
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h3>{{ state.visitor_name }}</h3>
                                        <p>{{ state.visitor_email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-end">
                                    <button type="button" @click="endChat()" class="btn btn-sm btn-danger"
                                        v-if="state.visitor != '' && state.messages.length > 0">End Chat</button>
                                    <div class="position-relative">
                                    <select class="form-control-sm ms-1" @change="changeStatus">
                                        <option value="1" :selected="(operatorStatus == 1) ? true : false">Online </option>
                                        <option value="0" :selected="(operatorStatus == 0) ? true : false">Offline </option>
                                    </select>
                                        <span class="badge rounded-pill text-bg-primary position-absolute" style="top:-10px;right:-10px">{{ assignedVisitors }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scrollable modal-body" ref="hasScrolledToBottom">
                        <div class="msg-body">
                            <ul class="">
                                <template v-for="message in state.messages" :key="message.key">
                                    <li class="sender" v-if="message.sender == state.visitor">
                                        <p> {{ message.content }} </p>
                                        <span class="time">{{ new Date(message.timestamp).toLocaleString(undefined, { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit',}) }} {{ (message.read == 1) ? 'read' : 'unread' }}</span>
                                    </li>
                                    <li class="repaly" v-else-if="message.sender == state.operator">
                                        <p> {{ message.content }} </p>
                                        <span class="time">{{ new Date(message.timestamp).toLocaleString(undefined, { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit', }) }} {{ (message.read == 1) ? 'read' : 'unread' }}</span>
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
        const sendMessageSound = new Audio("./sounds/message-send-notification.mp3");
        const newMessageSound = new Audio("./sounds/unread-message-notification.wav");

        const inputUsername = ref("");
        const inputMessage = ref("");
        let hasScrolledToBottom = ref("")
        let operatorStatus = ref(props.user.status);
        let assignedVisitors = ref(props.user.assigned_visitors);
        let visitors = ref([]);
        let state = reactive({
            operator: props.user.operator_id,
            visitor: '',
            operator_name: props.user.name,
            visitor_name: '',
            visitor_email: '',
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
                operator_name: state.operator_name,
                visitor_name: state.visitor_name,
                sender: state.operator,
                receiver: state.visitor,
                content: inputMessage.value,
                read: 0,
                timestamp: Date.now()
            }

            messagesRef.push(message);
            sendMessageSound.play();
            sendMessageSound.currentTime = 0;
            inputMessage.value = "";
        }

        const scrollBottom = () => {
            if (state.messages.length > 1 && state.operator != '') {
                state.messages.forEach(row => {
                    if (row.read == 0 && row.sender == state.visitor) {
                        db.database().ref("messages/" + row.id).update({
                            read: 1,
                        });
                    }
                });
                let el = hasScrolledToBottom.value;
                el.scrollTop = el.scrollHeight;
            }
        }

        const fetchMessages = (visitor, name, email) => {
            state.visitor = visitor;
            state.visitor_name = name;
            state.visitor_email = email;
            const messagesRef = db.database().ref("messages");
            messagesRef.on('value', snapshot => {
                const data = snapshot.val();
                let messages = [];
                let newMessages = [];
                Object.keys(data).forEach(key => {
                    if ((data[key].sender == state.visitor && data[key].receiver == state.operator) || (data[key].sender == state.operator && data[key].receiver == state.visitor)) {
                        messages.push({
                            id: key,
                            operator_name: data[key].operator_name,
                            visitor_name: data[key].visitor_name,
                            sender: data[key].sender,
                            receiver: data[key].receiver,
                            content: data[key].content,
                            read: data[key].read,
                            timestamp: data[key].timestamp
                        });
                    }else if (data[key].sender != state.visitor && data[key].sender != state.operator && data[key].receiver == state.operator && data[key].read == 0) {
                        let sub = newMessages.findIndex(x => x.sender == data[key].sender);
                        if (newMessages.length == 0 ||  sub == -1) {
                            newMessages.push({
                                id: key,
                                sender: data[key].sender,
                                message_count: 1,
                            });
                        } else {
                            newMessages[sub].message_count++;
                        }
                    }

                    if(data[key].sender != state.visitor && data[key].sender != state.operator && data[key].receiver == state.operator && data[key].read == 0){
                        newMessageSound.play();
                        newMessageSound.currentTime = 0;
                    }
                });
                newMessages.forEach(element => {
                    let sub = visitors.value.findIndex(x => x.visitor_id == element.sender);
                    if (sub == -1) {
                        fetchUsers();
                    }
                });
                let finded = undefined;
                visitors.value.forEach((visitor,key) => {
                    finded = newMessages.findIndex(x => x.sender == visitor.visitor_id);
                    if(finded !== -1){
                        visitors.value[key].messages_count =  newMessages[finded].message_count;
                    }else{
                        visitors.value[key].messages_count =  0;
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
            axios.get('/chat/operator/status/' + state.operator + '/' + event.target.value).then(response => {
                if (response.data.status == 'success') {
                    operatorStatus.value = event.target.value;
                }
            });
        }

        const endChat = () => {
            let end = {
                operator_id: state.operator,
                visitor_id: state.visitor,
                messages: state.messages,
            }
            axios.post('/visitor/chat-end', end).then(response => {
                if (response.data.status == 'success') {
                    state.messages.forEach(row => {
                        db.database().ref("messages/" + row.id).remove();
                    });
                    assignedVisitors.value--;
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
            assignedVisitors,
            Login,
            state,
            operatorStatus,
            inputMessage,
            SendMessage,
            fetchMessages,
            changeStatus,
            endChat,
            Logout,
            scrollBottom,
            hasScrolledToBottom
        }
    }
}
</script>