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
.visitor-avatar {
    width: 25px;
    height: 25px;
    background-color: coral;
    margin-right: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: aliceblue;
    font-weight: 600;
    border-radius: 5px;
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
                                <template v-for="visitor in state.activeVisits">
                                    <a href="javascript:void(0)" class="d-flex align-items-center px-3 py-2"
                                        v-bind:class="(visitor.visitor_id == state.currentVisitor.visitor_id) ? 'selected-user' : ''"
                                        @click="fetchMessages(visitor)">
                                        <div class="flex-shrink-0">
                                            <div class="visitor-avatar">V</div>
                                            <!-- <span class="active"></span> -->
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3>{{ visitor.visitor_id }}</h3>
                                            <!-- <p>{{ visitor.email }}</p> -->
                                        </div>
                                        <!-- <span class="badge text-bg-danger unread-badge" style="position: absolute;right: 3%;top: 25%;"
                                            v-if="visitor.messages_count > 0">{{ visitor.messages_count }}</span> -->
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
                    <div class="msg-head" v-if="state.currentVisitor != ''">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center ms-3">
                                    <div class="visitor-avatar">V</div><p>{{ state.currentVisitor.visitor_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" @click="saveChat(state.currentVisitor)" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">End Chat</button>
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
                                            <td><a type="button" class="btn btn-sm btn-success" @click="joinChat(pending)">Join Chat</a></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <ul class="">
                                <template v-for="message in state.messages" :key="message.key">
                                    <li class="sender" v-if="message.sender == state.currentVisitor.visitor_id">
                                        <p> {{ message.message }} </p>
                                        <span class="time">{{ new Date(message.timestamp).toLocaleString(undefined, { hour12: true, hour: 'numeric', minute: '2-digit', second: '2-digit',}) }} </span>
                                    </li>
                                    <li class="repaly" v-else-if="message.sender == operator.operator_id">
                                        <p> {{ message.message }} </p>
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
        let operator = ref(props.user);
        let state = reactive({
            currentVisitor: '',
            pendingVisits : [],
            activeVisits : [],
            endVisits : [],
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

        const joinChat = (visitor) => {
            let chatRoom = {
                operator : operator.value.operator_id,
                visitor : visitor.visitor_id,
            },
            message = {
                sender : visitor.visitor_id,
                receiver : operator.value.operator_id,
                message : visitor.message,
                read : 1,
                timestamp : visitor.timestamp,
            };
            console.log(chatRoom);
            visitor.receiver = operator.value.operator_id;
            db.collection("chat_room").add(chatRoom)
            .then((docRef) => {
                db.collection("chat_room").doc(docRef.id).collection('messages').add(message).then(()=>{
                    let dataChanging = {
                        visitor_id : visitor.visitor_id,
                        operator_id : operator.value.operator_id,
                        chat_room_id : docRef.id,
                        type : 'active',
                    };
                    db.collection("visitors").doc(visitor.visitor_id).set(dataChanging).then(()=>{
                        fetchMessages(dataChanging);
                    });
                });
            });
        }

        const SendMessage = () => {
            const messagesRef = db.collection('chat_room').doc(state.currentVisitor.chat_room_id).collection('messages');

            if (inputMessage.value === "" || inputMessage.value === null) {
                return;
            }

            const message = {
                sender: operator.value.operator_id,
                receiver: state.currentVisitor.visitor_id,
                message: inputMessage.value,
                read: 1,
                timestamp: Date.now()
            }

            messagesRef.add(message);
            sendMessageSound.play();
            sendMessageSound.currentTime = 0;
            inputMessage.value = "";
        }

        const scrollBottom = () => {
            if (state.messages.length > 1 && state.operator != '') {
                // state.messages.forEach(row => {
                //     if (row.read == 0 && row.sender == state.visitor) {
                //         db.database().ref("messages/" + row.id).update({
                //             read: 1,
                //         });
                //     }
                // });
                let el = hasScrolledToBottom.value;
                el.scrollTop = el.scrollHeight;
            }
        }

        const fetchMessages = (visitor) => {
            state.currentVisitor = visitor;
            db.collection('chat_room').doc(visitor.chat_room_id)
                .collection('messages').orderBy("timestamp").onSnapshot((querySnapshot) => {
                    let messages = [];
                    querySnapshot.forEach((doc) => {
                        messages.push(doc.data());
                    });
                    state.messages = messages;
            });
            document.querySelector('#chatBox').classList.remove('d-none');
        }

        const fetchUsers = async () => {
            db.collection("visitors")
            .onSnapshot((querySnapshot) => {
                let pendingVisits = [];
                let activeVisits = [];
                let endVisits = [];
                let docData;
                querySnapshot.forEach((doc) => {
                    docData = doc.data();
                    docData.doc_id = doc.id;
                    if(docData.type == 'pending')
                        pendingVisits.push(docData);
                    if(docData.type == 'active' && docData.operator_id == operator.value.operator_id)
                        activeVisits.push(docData);
                    if(docData.type == 'closed' && docData.operator_id == operator.value.operator_id)
                        endVisits.push(docData);
                    // console.log(doc.id + '=>' , doc.data());
                });
                state.pendingVisits = pendingVisits;
                state.activeVisits = activeVisits;
                state.endVisits = endVisits;
                if(state.endVisits.length > 0){
                    endChat();
                }
            });
        }

        const saveChat = (element) => {
            db.collection('chat_room').doc(element.chat_room_id)
                .collection('messages').orderBy("timestamp").get().then((querySnapshot) => {
                    let messages = [];
                    querySnapshot.forEach((doc) => {
                        messages.push(doc.data());
                        db.collection('chat_room').doc(element.chat_room_id)
                        .collection('messages').doc(doc.id).delete();
                    });
                    let data = {
                        visitor_id : element.visitor_id,
                        operator_id : element.operator_id,
                        messages : messages
                    }
                    axios.post('/visitor/chat-end', data);
                    db.collection('chat_room').doc(element.chat_room_id).delete();
                    db.collection('visitors').doc(element.visitor_id).delete();
                    state.currentVisitor = '';
                    document.querySelector('#chatBox').classList.add('d-none');
                });
        }

        const endChat = () => {
            state.endVisits.forEach(element => {
                saveChat(element);
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
            joinChat,
            operator,
            saveChat,
            Login,
            state,
            inputMessage,
            SendMessage,
            fetchMessages,
            endChat,
            Logout,
            scrollBottom,
            hasScrolledToBottom
        }
    }
}
</script>