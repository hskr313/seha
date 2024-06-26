<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo isset($title) ? $title : 'Service Exchange'; ?></title>
    <link href="/seha/public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="/seha/public/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e3e6f0;
        }
        .message-item:hover {
            background-color: #f8f9fc;
        }
        .message-details {
            display: flex;
            flex-direction: column;
        }
        .message-sender {
            font-weight: bold;
            color: #4e73df;
        }
        .message-content {
            color: #858796;
            font-size: 0.85rem;
        }
        .badge-counter {
            position: absolute;
            transform: translate(-50%, -50%);
            top: 20%;
            right: 30%;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/seha/public">
            <div class="sidebar-brand-icon">
                <i class="fab fa-sellcast"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Seha <sup>v1</sup></div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="/seha/public">
                <i class="fa fa-exchange-alt"></i>
                <span>Marketplace</span></a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Utilities</div>
        <li class="nav-item">
            <a class="nav-link" href="/seha/public/service">
                <i class="fas fa-star fa-sm fa-fw"></i>
                <span>My Services</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/seha/public/message/getAllConversations">
                <i class="fas fa-envelope fa-sm fa-fw"></i>
                <span>My Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/seha/public/service/requests">
                <i class="fas fa-comments fa-sm fa-fw"></i>
                <span>My Requests</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Utilities</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Utilities:</h6>
                    <a class="collapse-item" href="/seha/public/utilities-color">Colors</a>
                    <a class="collapse-item" href="/seha/public/utilities-border">Borders</a>
                    <a class="collapse-item" href="/seha/public/utilities-animation">Animations</a>
                    <a class="collapse-item" href="/seha/public/utilities-other">Other</a>
                </div>
            </div>
        </li>
        <div class="text-center d-none d-md-inline mt-5">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <span class="badge badge-danger badge-counter" id="notificationCount">0</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">Message Center</h6>
                            <form id="searchUserForm" class="px-3 pb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchUserName" placeholder="Search users by username">
                                </div>
                            </form>
                            <div id="searchResults" class="px-3"></div>
                            <div id="conversationsList" class="px-3">
                                <!-- Conversations will be loaded here dynamically -->
                            </div>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                        </div>
                    </li>
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php $user = AuthMiddleware::getUser(); ?>
                            <span class="mr-2 d-none d-lg-inline text-gray-600 medium"><?php echo $user->username; ?></span>
                            <img class="img-profile rounded-circle" src="/seha/public/img/undraw_profile.svg">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/seha/public/user/profile">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                My Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <?php echo $content; ?>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; Service Exchange 2024</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="/seha/public/auth/logout">Logout</a>
            </div>
        </div>
    </div>
</div>
<script src="/seha/public/vendor/jquery/jquery.min.js"></script>
<script src="/seha/public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/seha/public/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/seha/public/js/sb-admin-2.min.js"></script>
<script src="/seha/public/vendor/chart.js/Chart.min.js"></script>
<script src="/seha/public/js/demo/chart-area-demo.js"></script>
<script src="/seha/public/js/demo/chart-pie-demo.js"></script>
<script>
    document.getElementById('searchUserName').addEventListener('input', function() {
        const username = this.value;
        if (username.length > 2) {
            fetch(`/seha/public/message/searchUsers?username=${username}`)
                .then(response => response.json())
                .then(data => {
                    const searchResults = document.getElementById('searchResults');
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(user => {
                            const userElement = document.createElement('div');
                            userElement.classList.add('dropdown-item', 'd-flex', 'align-items-center');
                            userElement.innerHTML = `
                                <div>
                                    <span>${user.username}</span>
                                    <a href="/seha/public/message/getConversation?user_id=${user.id}" class="btn btn-sm btn-primary ml-2">Message</a>
                                </div>`;
                            searchResults.appendChild(userElement);
                        });
                    } else {
                        searchResults.innerHTML = '<div class="dropdown-item text-center">No users found</div>';
                    }
                });
        }
    });

    function loadConversations() {
        fetch(`/seha/public/message/getAllConversations`)
            .then(response => response.json())
            .then(data => {
                const conversationsList = document.getElementById('conversationsList');
                conversationsList.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(conversation => {
                        const userId = conversation.sender_id == <?php echo $_SESSION['user_id']; ?> ? conversation.receiver_id : conversation.sender_id;
                        const userElement = document.createElement('div');
                        userElement.classList.add('message-item');
                        userElement.innerHTML = `
                            <div class="message-details">
                                <span class="message-sender">User ${userId}</span>
                                <span class="message-content">${conversation.content}</span>
                            </div>
                            <a href="/seha/public/message/getConversation?user_id=${userId}" class="btn btn-sm btn-primary ml-2">View</a>`;
                        conversationsList.appendChild(userElement);
                    });
                } else {
                    conversationsList.innerHTML = '<div class="dropdown-item text-center">No conversations found</div>';
                }
            });
    }

    function updateNotificationCount() {
        fetch('/seha/public/message/getUnreadMessageCount')
            .then(response => response.json())
            .then(data => {
                document.getElementById('notificationCount').textContent = data.unreadCount;
                document.getElementById('messageCounter').textContent = data.unreadCount;
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadConversations();
        updateNotificationCount();
    });
</script>
</body>
</html>
