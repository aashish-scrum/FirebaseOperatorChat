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
    <div class="chat-area border">
        <!-- chatlist -->
        <div class="chatlist p-0">
            <div class="modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="chat-header p-3 border-bottom bg-opacity-50 bg-danger text-center" v-if="state.pendingVisits.length > 0">
                        <p class="text-white fw-bold mb-2" style="font-size: 12px;"> {{ state.pendingVisits.length }} Pending Chat request</p>
                        <button type="button" class="btn btn-danger btn-sm" @click="">Open</button>
                    </div>

                    <div class="modal-body mt-1">
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
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scrollable modal-body" ref="hasScrolledToBottom">
                        <div class="msg-body p-3">
                            <table class="table table-responsive caption-top table-striped border">
                                <caption>List of users</caption>
                                <tbody>
                                    <template v-for="pending in state.pendingVisits" :key="pending.key">
                                        <tr>
                                            <td>{{pending.visitor_id}}</td>
                                            <td>{{pending.message}}</td>
                                            <td>{{pending.timestamp}}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <!-- <ul class="">
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
                            </ul> -->
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
            pendingVisits : [],
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

        const requestVisitor = () => {
            db.collection("visitors")
            .onSnapshot((querySnapshot) => {
                let visits = [];
                querySnapshot.forEach((doc) => {
                    visits.push(doc.data());
                    // console.log(doc.id + '=>' , doc.data().visitor_id);
                });
                state.pendingVisits = visits;
                console.log(state.pendingVisits);
            });
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
            // fetchUsers();
            requestVisitor();
        });

        onUpdated(() => {
            scrollBottom();
        })
        
        return {
            inputUsername,
            requestVisitor,
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