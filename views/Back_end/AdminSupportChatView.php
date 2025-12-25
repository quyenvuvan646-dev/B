<div class="container-fluid" style="padding: 20px;">
    <div class="row">
        <!-- Left Sidebar - User List -->
        <div class="col-md-4 col-lg-3">
            <div class="card" style="height: calc(100vh - 120px);">
                <div class="card-header bg-primary text-white">
                    <h5 style="margin: 0;">
                        <i class="bi bi-chat-dots"></i> Tin Nhắn Hỗ Trợ
                    </h5>
                </div>
                <div class="card-body" style="padding: 0; overflow-y: auto;">
                    <div id="userList">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Đang tải danh sách...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Chat Area -->
        <div class="col-md-8 col-lg-9">
            <div class="card" style="height: calc(100vh - 120px);">
                <div class="card-header bg-success text-white" id="chatHeader">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h5 style="margin: 0;">
                                <i class="bi bi-person-circle"></i> Chọn người dùng để chat
                            </h5>
                        </div>
                        <button class="btn btn-sm btn-light" onclick="refreshChat()" style="display: none;" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise"></i> Làm mới
                        </button>
                    </div>
                </div>
                <div class="card-body" style="height: calc(100vh - 260px); overflow-y: auto; background-color: #f8fafc;" id="adminChatMessages">
                    <div class="text-center" style="padding: 100px 20px; color: #999;">
                        <i class="bi bi-chat-left-dots" style="font-size: 4rem; color: #cbd5e1;"></i>
                        <h4 style="margin-top: 20px; color: #64748b;">Chưa Chọn Cuộc Trò Chuyện</h4>
                        <p>Chọn một người dùng từ danh sách bên trái để xem và phản hồi tin nhắn</p>
                    </div>
                </div>
                <div class="card-footer" id="chatFooter" style="display: none;">
                    <form onsubmit="sendAdminReply(event)" style="display: flex; gap: 10px;">
                        <input 
                            type="text" 
                            id="adminReplyInput" 
                            class="form-control" 
                            placeholder="Nhập phản hồi..."
                            required
                        >
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send-fill"></i> Gửi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-item {
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.user-item:hover {
    background-color: #f1f5f9;
}

.user-item.active {
    background-color: #dbeafe;
    border-left: 4px solid #3b82f6;
}

.user-item .user-email {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 5px;
}

.user-item .last-message {
    font-size: 0.875rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-item .message-count {
    font-size: 0.75rem;
    color: #94a3b8;
}

.user-item .unread-badge {
    background-color: #ef4444;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: bold;
}

.message {
    max-width: 70%;
    margin-bottom: 15px;
    padding: 12px 16px;
    border-radius: 12px;
    word-wrap: break-word;
    display: flex;
    flex-direction: column;
}

.message.user {
    align-self: flex-start;
    background-color: #dcfce7;
    color: #1f2937;
    margin-right: auto;
}

.message.admin {
    align-self: flex-end;
    background-color: #3b82f6;
    color: white;
    margin-left: auto;
}

.message .timestamp {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 5px;
}

#adminChatMessages {
    display: flex;
    flex-direction: column;
    padding: 20px;
}
</style>

<script>
let currentUserEmail = null;
let chatRefreshInterval = null;

// Load user list on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUserList();
});

function loadUserList() {
    fetch('<?php echo APP_URL; ?>/?url=Admin/getSupportUserList')
        .then(res => res.json())
        .then(data => {
            const userListDiv = document.getElementById('userList');
            
            if (data.success && data.users.length > 0) {
                userListDiv.innerHTML = '';
                data.users.forEach(user => {
                    const userItem = document.createElement('div');
                    userItem.className = 'user-item';
                    userItem.onclick = () => selectUser(user.email);
                    
                    const unreadBadge = user.unread_count > 0 
                        ? `<span class="unread-badge float-end">${user.unread_count}</span>` 
                        : '';
                    
                    userItem.innerHTML = `
                        <div class="user-email">
                            <i class="bi bi-person-circle"></i> ${user.email}
                            ${unreadBadge}
                        </div>
                        <div class="last-message">${user.last_message || 'Chưa có tin nhắn'}</div>
                        <div class="message-count">
                            <i class="bi bi-chat"></i> ${user.message_count} tin nhắn
                            <span class="float-end text-muted" style="font-size: 0.7rem;">${user.last_time}</span>
                        </div>
                    `;
                    
                    userListDiv.appendChild(userItem);
                });
            } else {
                userListDiv.innerHTML = '<div class="text-center p-4 text-muted">Chưa có tin nhắn nào</div>';
            }
        })
        .catch(err => {
            console.error('Error loading user list:', err);
            document.getElementById('userList').innerHTML = '<div class="text-center p-4 text-danger">Lỗi khi tải danh sách</div>';
        });
}

function selectUser(email) {
    currentUserEmail = email;
    
    // Update active state
    document.querySelectorAll('.user-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Update header
    document.getElementById('chatHeader').innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="margin: 0;">
                    <i class="bi bi-person-circle"></i> ${email}
                </h5>
                <small>Chat hỗ trợ với khách hàng</small>
            </div>
            <button class="btn btn-sm btn-light" onclick="refreshChat()">
                <i class="bi bi-arrow-clockwise"></i> Làm mới
            </button>
        </div>
    `;
    
    // Show footer
    document.getElementById('chatFooter').style.display = 'block';
    document.getElementById('refreshBtn').style.display = 'block';
    
    // Load chat history
    loadChatWithUser(email);
    
    // Start auto-refresh
    startChatRefresh();
}

function loadChatWithUser(email) {
    const messagesDiv = document.getElementById('adminChatMessages');
    messagesDiv.innerHTML = '<div class="text-center p-4"><i class="bi bi-hourglass-split"></i> Đang tải...</div>';
    
    fetch('<?php echo APP_URL; ?>/?url=Admin/getChatWithUser', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'email=' + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.messages.length > 0) {
            messagesDiv.innerHTML = '';
            data.messages.forEach(msg => {
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message ' + msg.type;
                msgDiv.innerHTML = msg.message + '<div class="timestamp">' + msg.timestamp + '</div>';
                messagesDiv.appendChild(msgDiv);
            });
            // Auto scroll to bottom to show newest messages
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } else {
            messagesDiv.innerHTML = '<div class="text-center p-4 text-muted">Chưa có tin nhắn</div>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        messagesDiv.innerHTML = '<div class="text-center p-4 text-danger">Lỗi khi tải tin nhắn</div>';
    });
}

function sendAdminReply(event) {
    event.preventDefault();
    
    if (!currentUserEmail) return;
    
    const input = document.getElementById('adminReplyInput');
    const message = input.value.trim();
    const messagesDiv = document.getElementById('adminChatMessages');
    
    if (!message) return;
    
    // Add admin message to chat
    const adminMsg = document.createElement('div');
    adminMsg.className = 'message admin';
    adminMsg.innerHTML = message + '<div class="timestamp">' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) + '</div>';
    messagesDiv.appendChild(adminMsg);
    
    input.value = '';
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    // Send to server
    const formData = new FormData();
    formData.append('email', currentUserEmail);
    formData.append('response', message);
    
    fetch('<?php echo APP_URL; ?>/?url=Admin/sendSupportResponse', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log('Reply sent successfully');
            // Refresh user list to update counts
            loadUserList();
        } else {
            alert('Lỗi: ' + (data.error || 'Không thể gửi phản hồi'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Có lỗi xảy ra khi gửi phản hồi');
    });
}

function refreshChat() {
    if (currentUserEmail) {
        loadChatWithUser(currentUserEmail);
        loadUserList();
    }
}

function startChatRefresh() {
    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
    }
    chatRefreshInterval = setInterval(() => {
        if (currentUserEmail) {
            loadChatWithUser(currentUserEmail);
            loadUserList();
        }
    }, 10000); // Refresh every 10 seconds
}
</script>
