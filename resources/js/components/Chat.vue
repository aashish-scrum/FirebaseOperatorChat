<style>
.v3-emoji-picker {
    position: absolute;
    text-align: left;
    bottom: 85%;
    left: 2%;
}

.v3-emoji-picker .v3-footer {
    display: none;
}
</style>
<template>
    <div class="col-lg-3">
        <div class="px-4 pt-4">
            <h4 class="mb-4">Chats</h4>
            <div class="search-box chat-search-box">
                <div class="input-group mb-3 rounded-3">
                    <span class="input-group-text text-muted bg-light pe-1 ps-3" id="basic-addon1">
                        <i class="ri-search-line search-icon font-size-18"></i>
                    </span>
                    <input type="text" class="form-control bg-light" placeholder="Search messages or users"
                        aria-label="Search messages or users" aria-describedby="basic-addon1">
                </div>
            </div> <!-- Search Box-->
        </div>

        <!-- Start chat-message-list -->
        <div>
            <!-- <h5 class="mb-3 px-3 font-size-16">Recent</h5> -->
            <div class="chat-message-list px-2" data-simplebar>
                <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active position-relative" id="active-tab" data-bs-toggle="tab"
                            data-bs-target="#active-tab-pane" type="button" role="tab" aria-controls="active-tab-pane"
                            aria-selected="true">Active
                            <span v-if="state.activeVisits.length  > 0" class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                                {{state.activeVisits.length}}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link position-relative" id="pending-tab" data-bs-toggle="tab"
                            data-bs-target="#pending-tab-pane" type="button" role="tab" aria-controls="pending-tab-pane"
                            aria-selected="false">Request
                            <span v-if="state.pendingVisits.length > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{state.pendingVisits.length}}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link position-relative" id="closed-tab" data-bs-toggle="tab"
                            data-bs-target="#closed-tab-pane" type="button" role="tab" aria-controls="closed-tab-pane"
                            aria-selected="false">Closed
                            <span v-if="state.closedVisits.length > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{state.closedVisits.length}}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="active-tab-pane" role="tabpanel" aria-labelledby="active-tab"
                        tabindex="0">
                        <ul class="list-unstyled chat-list chat-user-list">
                            <template v-for="visitor in state.activeVisits">
                                <li class="unread"
                                    :class="{ 'selected-user active': (visitor.visitor_id == state.currentVisitor.visitor_id) ? true : false, }">
                                    <a href="javascript:void(0)" @click="fetchMessages(visitor)">
                                        <div class="d-flex">
                                            <div class="chat-user-img align-self-center me-3 ms-0 "
                                                :class="{ 'online': (visitor.type == 'active') ? true : false, 'offline': (visitor.type == 'closedbyvisitor') ? true : false, }">
                                                <div class="avatar-xs">
                                                    <span
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        V
                                                    </span>
                                                    <span class="user-status"></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-15 mb-1">{{ visitor.visitor_id }}
                                                </h5>
                                                <p class="chat-user-message text-truncate mb-0">Next meeting tomorrow
                                                    10.00AM
                                                </p>
                                            </div>
                                            <!-- <div class="font-size-11">12:01 PM</div> -->
                                            <div class="unread-message end-0" v-if="visitor.unreadCounts > 0">
                                                <span
                                                    class="badge badge-soft-danger rounded-pill">{{(visitor.unreadCounts.toString().length
                                                    < 2) ? "0" + visitor.unreadCounts : visitor.unreadCounts}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel" aria-labelledby="pending-tab"
                        tabindex="0">
                        <ul class="list-unstyled chat-list chat-user-list">
                            <template v-for="pending in state.pendingVisits">
                                <li class="unread"
                                    :class="{ 'selected-user active': (pending.visitor_id == state.currentVisitor.visitor_id) ? true : false, }">
                                    <a href="javascript:void(0)" @click="fetchMessages(pending)">
                                        <div class="d-flex">
                                            <div class="chat-user-img align-self-center me-3 ms-0 online">
                                                <div class="avatar-xs">
                                                    <span
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        V
                                                    </span>
                                                    <span class="user-status"></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-15 mb-1">{{ pending.visitor_id }}
                                                </h5>
                                                <p class="chat-user-message text-truncate mb-0">Next meeting tomorrow
                                                    10.00AM
                                                </p>
                                            </div>
                                            <!-- <div class="font-size-11">12:01 PM</div> -->
                                            <div class="unread-message end-0" v-if="pending.unreadCounts > 0">
                                                <span
                                                    class="badge badge-soft-danger rounded-pill">{{(pending.unreadCounts.toString().length
                                                    < 2) ? "0" + pending.unreadCounts : pending.unreadCounts}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="closed-tab-pane" role="tabpanel" aria-labelledby="closed-tab"
                        tabindex="0">
                        <ul class="list-unstyled chat-list chat-user-list">
                            <template v-for="closed in state.closedVisits">
                                <li class="unread "
                                    :class="{ 'selected-user active': (closed.visitor_id == state.currentVisitor.visitor_id) ? true : false, }">
                                    <a href="javascript:void(0)" @click="fetchMessages(closed)">
                                        <div class="d-flex">
                                            <div class="chat-user-img align-self-center me-3 ms-0 offline">
                                                <div class="avatar-xs">
                                                    <span
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        V
                                                    </span>
                                                    <span class="user-status"></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-15 mb-1">{{ closed.visitor_id }}
                                                </h5>
                                                <p class="chat-user-message text-truncate mb-0">Next meeting tomorrow
                                                    10.00AM
                                                </p>
                                            </div>
                                            <!-- <div class="font-size-11">12:01 PM</div> -->
                                            <div class="unread-message end-0" v-if="closed.unreadCounts > 0">
                                                <span
                                                    class="badge badge-soft-danger rounded-pill">{{(closed.unreadCounts.toString().length
                                                    < 2) ? "0" + closed.unreadCounts : closed.unreadCounts}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End chat-message-list -->
    </div>
    <div class="col-lg-9">
        <div class="user-chat w-100 overflow-hidden">
            <div class="d-lg-flex" v-if="state.currentVisitor != ''">
                <!-- start chat conversation section -->
                <div class="w-100 overflow-hidden position-relative">
                    <!-- start chat user head -->
                    <div class="p-3 p-lg-4 border-bottom user-chat-topbar">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-8">
                                <div class="d-flex align-items-center">
                                    <div class="d-block d-lg-none me-2 ms-0">
                                        <a href="javascript: void(0);"
                                            class="user-chat-remove text-muted font-size-16 p-2"><i
                                                class="ri-arrow-left-s-line"></i></a>
                                    </div>
                                    <div class="me-3 ms-0 avatar-xs">
                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">V</span>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="font-size-16 mb-0 text-truncate">
                                            <a href="javascript:void(0)" class="text-reset user-profile-show">{{
                                                state.currentVisitor.visitor_id
                                            }}</a>
                                            <i
                                                class="ri-record-circle-fill font-size-10 text-success d-inline-block ms-1"></i>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 col-4">
                                <ul class="list-inline user-chat-nav text-end mb-0">
                                    <li class="list-inline-item">
                                        <div class="dropdown">
                                            <button class="btn nav-btn dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-search-line"></i>
                                            </button>
                                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-md">
                                                <div class="search-box p-2">
                                                    <input type="text" class="form-control bg-light border-0"
                                                        placeholder="Search..">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                        <button type="button" class="btn nav-btn" data-bs-toggle="modal"
                                            data-bs-target="#audiocallModal">
                                            <i class="ri-phone-line"></i>
                                        </button>
                                    </li>

                                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                        <button type="button" class="btn nav-btn" data-bs-toggle="modal"
                                            data-bs-target="#videocallModal">
                                            <i class="ri-vidicon-line"></i>
                                        </button>
                                    </li>

                                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                        <button type="button" class="btn nav-btn user-profile-show" @click="showDetails()">
                                            <i class="ri-user-2-line"></i>
                                        </button>
                                    </li>

                                    <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                        <button type="button" class="btn nav-btn"
                                            @click="saveChat(state.currentVisitor)">
                                            <i class="ri-save-line"></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end chat user head -->

                    <!-- start chat conversation -->
                    <div class="chat-conversation">
                        <div class=" p-3 p-lg-4" style="height: 100%; overflow: hidden scroll;"
                            ref="hasScrolledToBottom">
                            <ul class="list-unstyled mb-0">
                                <template v-for="message in state.messages" :key="message.key">
                                    <li class="right" v-if="message.sender == operator.operator_id">
                                        <div class="conversation-list">
                                            <div class="chat-avatar avatar-sm">
                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">V</span>
                                            </div>

                                            <div class="user-chat-content">
                                                <div class="ctext-wrap">
                                                    <div class="ctext-wrap-content">
                                                        <p class="mb-0">
                                                            {{ message.message }}
                                                        </p>
                                                        <p class="chat-time mb-0">
                                                            <i class="ri-time-line align-middle"></i>
                                                            <span class="align-middle">{{
                                                                new Date(message.timestamp).toLocaleString(undefined, {
                                                                    hour12: true, hour: 'numeric', minute: '2-digit'
                                                                })
                                                            }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="conversation-name text-muted font-size-11">
                                                    {{ operator.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li v-else-if="message.sender == state.currentVisitor.visitor_id">
                                        <div class="conversation-list">
                                            <div class="chat-avatar avatar-sm">
                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">V</span>
                                            </div>
                                            <div class="user-chat-content">
                                                <div class="ctext-wrap">
                                                    <div class="ctext-wrap-content">
                                                        <p class="mb-0">
                                                            {{ message.message }}
                                                        </p>
                                                        <p class="chat-time mb-0">
                                                            <i class="ri-time-line align-middle"></i>
                                                            <span class="align-middle">{{
                                                                new Date(message.timestamp).toLocaleString(undefined, {
                                                                    hour12: true, hour: 'numeric', minute: '2-digit'
                                                                })
                                                            }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="conversation-name text-muted font-size-11">
                                                    {{ state.currentVisitor.visitor_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                                <li v-if="state.currentVisitor.type == 'closedbyvisitor'"
                                    style="border-left: 5px solid #54e1ff; background-color: #54e1ff30; padding: 5px 15px; border-radius: 7px; display: flex; justify-content: space-between; align-items: center;">
                                    <p class="mb-0 fw-bold fs-6">Visitor has ended the chat.</p>
                                    <span class="font-size-11">{{
                                        new
                                                                            Date(state.currentVisitor.timestamp).toLocaleString(undefined, {
                                            hour12: true,
                                            hour: 'numeric', minute: '2-digit', second: '2-digit'
                                        })
                                    }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end chat conversation end -->

                    <!-- start chat input section -->
                    <div class="chat-input-section p-3 p-lg-4 border-top mb-0">
                        <div class="row g-0" v-if="state.currentVisitor.type == 'active'">
                            <div class="col">
                                <input type="text" v-model="inputMessage" @keyup.enter="SendMessage"
                                    class="form-control form-control-lg bg-light border-light"
                                    placeholder="Enter Message...">
                            </div>
                            <div class="col-auto">
                                <div class="chat-input-links ms-md-2 me-md-0">
                                    <form action="javascript:void(0)" @submit.prevent="SendMessage">
                                        <!-- <EmojiPicker :display-recent="true" :disableSkinTones="false" @select="onSelectEmoji" class="d-none" /> -->
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Emoji">
                                                <button type="button" @click="showEmoji"
                                                    class="btn btn-link text-decoration-none font-size-16 btn-lg waves-effect">
                                                    <i class="ri-emotion-happy-line"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Attached File">
                                                <button type="button"
                                                    class="btn btn-link text-decoration-none font-size-16 btn-lg waves-effect">
                                                    <i class="ri-attachment-line"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item">
                                                <button type="submit"
                                                    class="btn btn-primary font-size-16 btn-lg chat-send waves-effect waves-light">
                                                    <i class="ri-send-plane-2-fill"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row g-0" v-else-if="state.currentVisitor.type == 'pending'">
                            <button class="btn btn-primary" @click="joinChat(state.currentVisitor)">Join Chat</button>
                        </div>
                    </div>
                </div>
                <!-- end chat conversation section -->

                <!-- start User profile detail sidebar -->
                <div class="user-profile-sidebar">
                    <div class="px-3 px-lg-4 pt-3 pt-lg-4">
                        <div class="user-chat-nav text-end">
                            <button type="button" class="btn nav-btn" id="user-profile-hide">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-center p-4 border-bottom">
                        <div class="mb-4">
                            <img src="http://192.168.2.116:8000/assets/images/users/avatar-4.jpg" class="rounded-circle avatar-lg img-thumbnail" alt="">
                        </div>
                        <h5 class="font-size-16 mb-1 text-truncate">Doris Brown</h5>
                        <p class="text-muted text-truncate mb-1">
                            <i class="ri-record-circle-fill font-size-10 text-success me-1 ms-0"></i> Active
                        </p>
                    </div>
                    <!-- End profile user -->

                    <!-- Start user-profile-desc -->
                    <div class="p-4 user-profile-desc" id="profile-details" data-simplebar>
                        <div class="text-muted">
                            <p class="mb-4">If several languages coalesce, the grammar of the resulting language
                                is more
                                simple and regular than that of the individual.</p>
                        </div>

                        <div class="accordion" id="myprofile">

                            <div class="accordion-item card border mb-2">
                                <div class="accordion-header" id="about3">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#aboutprofile" aria-expanded="true"
                                        aria-controls="aboutprofile">
                                        <h5 class="font-size-14 m-0">
                                            <i class="ri-user-2-line me-2 ms-0 align-middle d-inline-block"></i>
                                            About
                                        </h5>
                                    </button>
                                </div>
                                <div id="aboutprofile" class="accordion-collapse collapse show" aria-labelledby="about3"
                                    data-bs-parent="#myprofile">
                                    <div class="accordion-body">
                                        <div>
                                            <p class="text-muted mb-1">Name</p>
                                            <h5 class="font-size-14">Doris Brown</h5>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-muted mb-1">Email</p>
                                            <h5 class="font-size-14">adc@123.com</h5>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-muted mb-1">Time</p>
                                            <h5 class="font-size-14">11:40 AM</h5>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-muted mb-1">Location</p>
                                            <h5 class="font-size-14 mb-0">California, USA</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item card border">
                                <div class="accordion-header" id="attachfile3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#attachprofile" aria-expanded="false"
                                        aria-controls="attachprofile">
                                        <h5 class="font-size-14 m-0">
                                            <i class="ri-attachment-line me-2 ms-0 align-middle d-inline-block"></i>
                                            Attached Files
                                        </h5>
                                    </button>
                                </div>
                                <div id="attachprofile" class="accordion-collapse collapse"
                                    aria-labelledby="attachfile3" data-bs-parent="#myprofile">
                                    <div class="accordion-body">
                                        <div class="card p-2 border mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 ms-0">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                        <i class="ri-file-text-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-start">
                                                        <h5 class="font-size-14 mb-1">admin_v1.0.zip</h5>
                                                        <p class="text-muted font-size-13 mb-0">12.5 MB</p>
                                                    </div>
                                                </div>

                                                <div class="ms-4 me-0">
                                                    <ul class="list-inline mb-0 font-size-18">
                                                        <li class="list-inline-item">
                                                            <a href="javascript:void(0)" class="text-muted px-1">
                                                                <i class="ri-download-2-line"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item dropdown">
                                                            <a class="dropdown-toggle text-muted px-1"
                                                                href="javascript:void(0)" role="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-more-fill"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Action</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Another
                                                                    action</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Delete</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card p-2 border mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 ms-0">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-start">
                                                        <h5 class="font-size-14 mb-1">Image-1.jpg</h5>
                                                        <p class="text-muted font-size-13 mb-0">4.2 MB</p>
                                                    </div>
                                                </div>

                                                <div class="ms-4 me-0">
                                                    <ul class="list-inline mb-0 font-size-18">
                                                        <li class="list-inline-item">
                                                            <a href="javascript:void(0)" class="text-muted px-1">
                                                                <i class="ri-download-2-line"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item dropdown">
                                                            <a class="dropdown-toggle text-muted px-1"
                                                                href="javascript:void(0)" role="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-more-fill"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Action</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Another
                                                                    action</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Delete</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card p-2 border mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 ms-0">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-start">
                                                        <h5 class="font-size-14 mb-1">Image-2.jpg</h5>
                                                        <p class="text-muted font-size-13 mb-0">3.1 MB</p>
                                                    </div>
                                                </div>

                                                <div class="ms-4 me-0">
                                                    <ul class="list-inline mb-0 font-size-18">
                                                        <li class="list-inline-item">
                                                            <a href="javascript:void(0)" class="text-muted px-1">
                                                                <i class="ri-download-2-line"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item dropdown">
                                                            <a class="dropdown-toggle text-muted px-1"
                                                                href="javascript:void(0)" role="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-more-fill"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Action</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Another
                                                                    action</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Delete</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card p-2 border mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3 ms-0">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded font-size-20">
                                                        <i class="ri-file-text-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-start">
                                                        <h5 class="font-size-14 mb-1">Landing-A.zip</h5>
                                                        <p class="text-muted font-size-13 mb-0">6.7 MB</p>
                                                    </div>
                                                </div>

                                                <div class="ms-4 me-0">
                                                    <ul class="list-inline mb-0 font-size-18">
                                                        <li class="list-inline-item">
                                                            <a href="javascript:void(0)" class="text-muted px-1">
                                                                <i class="ri-download-2-line"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item dropdown">
                                                            <a class="dropdown-toggle text-muted px-1"
                                                                href="javascript:void(0)" role="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-more-fill"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Action</a>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Another
                                                                    action</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item"
                                                                    href="javascript:void(0)">Delete</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile-user-accordion -->
                        </div>
                        <!-- end user-profile-desc -->
                    </div>
                    <!-- end User profile detail sidebar -->
                </div>
            </div>
            <!-- End User chat -->

            <!-- audiocall Modal -->
            <div class="modal fade" id="audiocallModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center p-4">
                                <div class="avatar-lg mx-auto mb-4">
                                    <img src="http://192.168.2.116:8000/assets/images/users/avatar-4.jpg" alt="" class="img-thumbnail rounded-circle">
                                </div>

                                <h5 class="text-truncate">Doris Brown</h5>
                                <p class="text-muted">Start Audio Call</p>

                                <div class="mt-5">
                                    <ul class="list-inline mb-1">
                                        <li class="list-inline-item px-2 me-2 ms-0">
                                            <button type="button" class="btn btn-danger avatar-sm rounded-circle"
                                                data-bs-dismiss="modal">
                                                <span class="avatar-title bg-transparent font-size-20">
                                                    <i class="ri-close-fill"></i>
                                                </span>
                                            </button>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <button type="button" class="btn btn-success avatar-sm rounded-circle">
                                                <span class="avatar-title bg-transparent font-size-20">
                                                    <i class="ri-phone-fill"></i>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- audiocall Modal -->

            <!-- videocall Modal -->
            <div class="modal fade" id="videocallModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center p-4">
                                <div class="avatar-lg mx-auto mb-4">
                                    <img src="http://192.168.2.116:8000/assets/images/users/avatar-4.jpg" alt="" class="img-thumbnail rounded-circle">
                                </div>

                                <h5 class="text-truncate">Doris Brown</h5>
                                <p class="text-muted mb-0">Start Video Call</p>

                                <div class="mt-5">
                                    <ul class="list-inline mb-1">
                                        <li class="list-inline-item px-2 me-2 ms-0">
                                            <button type="button" class="btn btn-danger avatar-sm rounded-circle"
                                                data-bs-dismiss="modal">
                                                <span class="avatar-title bg-transparent font-size-20">
                                                    <i class="ri-close-fill"></i>
                                                </span>
                                            </button>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <button type="button" class="btn btn-success avatar-sm rounded-circle">
                                                <span class="avatar-title bg-transparent font-size-20">
                                                    <i class="ri-vidicon-fill"></i>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal -->
        </div>
    </div>
</template>

<script>
import { reactive, inject, ref, onMounted, onUpdated } from 'vue';
import db from '../db';
export default {
    props: ['user'],
    setup(props) {
        const thisProperty = props.user.account ?? 'WDuQoN8x6Iioj6TosQc5nbrS';
        const thisWidget = props.user.widget ?? 'WDuQoN8';

        const sendMessageSound = new Audio(`http://192.168.2.116:8000/sounds/message-send-notification.mp3`);
        const newMessageSound = new Audio(`http://192.168.2.116:8000/sounds/unread-message-notification.wav`);

        const inputUsername = ref("");
        const inputMessage = ref("");
        let hasScrolledToBottom = ref("");
        let operator = ref(props.user);

        let state = reactive({
            currentVisitor: '',
            pendingVisits: [],
            activeVisits: [],
            closedVisits: [],
            endVisits: [],
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
                operator: operator.value.operator_id,
                visitor: visitor.visitor_id,
                property_id : thisProperty,
                widget_id : thisWidget,
            },
            message = {
                sender: visitor.visitor_id,
                receiver: operator.value.operator_id,
                message: visitor.message,
                read: 1,
                timestamp: visitor.timestamp,
            };
            visitor.receiver = operator.value.operator_id;
            db.collection("chat_room").add(chatRoom)
                .then((docRef) => {
                    db.collection("chat_room").doc(docRef.id).collection('messages').add(message).then(() => {
                        let dataChanging = {
                            visitor_id: visitor.visitor_id,
                            operator_id: operator.value.operator_id,
                            chat_room_id: docRef.id,
                            type: 'active',
                            property_id : thisProperty,
                            widget_id : thisWidget,
                        };
                        db.collection("visitors").doc(visitor.visitor_id).set(dataChanging).then(() => {
                            fetchMessages(dataChanging);
                        });
                    });
                });
        }

        const SendMessage = () => {
            const messagesRef = db.collection('chat_room').doc(state.currentVisitor.chat_room_id).collection('messages');

            if (inputMessage.value.trim().length == 0) {
                inputMessage.value = "";
                return;
            }

            const message = {
                sender: operator.value.operator_id,
                receiver: state.currentVisitor.visitor_id,
                message: inputMessage.value,
                read: 0,
                timestamp: Date.now()
            }

            messagesRef.add(message);
            sendMessageSound.play();
            sendMessageSound.currentTime = 0;
            inputMessage.value = "";
        }

        const scrollBottom = async () => {
            if (state.messages.length > 1 && state.operator != '' && state.currentVisitor != '') {
                db.collection("chat_room").doc(state.currentVisitor.chat_room_id)
                    .collection('messages').where("read", "==", 0).where("sender", "==", state.currentVisitor.visitor_id)
                    .onSnapshot((querySnapshot) => {
                        querySnapshot.forEach((doc) => {
                            db.collection("chat_room").doc(state.currentVisitor.chat_room_id).collection('messages').doc(doc.id).update({ read: 1 });
                        });
                    });
                let el = hasScrolledToBottom.value;
                el.scrollTop = el.scrollHeight;
            }
        }

        const fetchMessages = (visitor) => {
            state.currentVisitor = visitor;
            if(state.currentVisitor.type == 'pending'){
                state.messages = [{
                    message : state.currentVisitor.message,
                    sender : state.currentVisitor.visitor_id,
                    receiver : '',
                    timestamp : state.currentVisitor.timestamp,
                    read : 1 
                }];
                return;
            }
            db.collection('chat_room').doc(state.currentVisitor.chat_room_id)
                .collection('messages').orderBy("timestamp")
                .onSnapshot((querySnapshot) => {
                    let messages = [];
                    querySnapshot.forEach((messageDoc) => {
                        messages.push(messageDoc.data());
                    });
                    state.messages = messages;
                });
        }

        const countUnread = () => {
            state.activeVisits.forEach((visitor, key) => {
                if (state.currentVisitor.visitor_id != visitor.visitor_id) {
                    db.collection("chat_room").doc(visitor.chat_room_id).collection('messages')
                        .where("read", "==", 0).where("sender", "==", visitor.visitor_id)
                        .onSnapshot((querySnapshot) => {
                            state.activeVisits[key].unreadCounts = querySnapshot.size;
                            if (querySnapshot.size > 0 && state.currentVisitor.visitor_id != visitor.visitor_id) {
                                newMessageSound.play();
                                newMessageSound.currentTime = 0;
                            }
                        });
                }
            });
        }

        const fetchUsers = async () => {
            db.collection("visitors")
                .where("property_id", "==", thisProperty)
				.where("widget_id", "==", thisWidget)
                .onSnapshot((querySnapshot) => {
                    let pendingVisits = [];
                    let activeVisits = [];
                    let closedVisits = [];
                    let docData;
                    querySnapshot.forEach((doc) => {
                        docData = doc.data();
                        docData.doc_id = doc.id;
                        if (docData.type == 'pending')
                            pendingVisits.push(docData);
                        if (operator.value.operator_id == docData.operator_id && docData.type == 'active')
                            activeVisits.push(docData);
                        if (operator.value.operator_id == docData.operator_id && docData.type != 'active' && docData.type != 'pending')
                            closedVisits.push(docData);
                        if(operator.value.operator_id != docData.operator_id && state.currentVisitor != '' && docData.visitor_id == state.currentVisitor.visitor_id)
                            state.currentVisitor = '';
                    });
                    state.pendingVisits = pendingVisits;
                    state.activeVisits = activeVisits;
                    state.closedVisits = closedVisits;
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
                        visitor_id: element.visitor_id,
                        operator_id: element.operator_id,
                        messages: messages
                    }
                    axios.post('/visitor/chat-end', data);
                    let updateClosed = 'closedbyoperator';
                    if (element.type == 'closedbyvisitor') {
                        updateClosed = 'closedbyvisitor';
                    }
                    db.collection('visitors').doc(element.visitor_id).update({ type: updateClosed, 'messages': JSON.stringify(messages) })
                        .then(() => {
                            setTimeout(() => {
                                db.collection('visitors').doc(element.visitor_id).delete();
                            }, 5000);
                        });
                    db.collection('chat_room').doc(element.chat_room_id).delete();
                    state.currentVisitor = '';
                });
        }

        const showDetails = () => {
            console.log('clicked');
            window.document.querySelector('#profile-details').classList.add('d-block')
        }

        onMounted(() => {
            countUnread();
            fetchUsers();
        });

        onUpdated(() => {
            scrollBottom().then(() => {
                countUnread();
            });
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
            Logout,
            scrollBottom,
            showDetails,
            hasScrolledToBottom
        }
    }
}
</script>