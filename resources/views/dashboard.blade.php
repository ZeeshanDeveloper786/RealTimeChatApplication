<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="js/bootstrap.js" rel="stylesheet" >  --}}
    {{-- <script src="js/bootstrap.js">  --}}

    <style>
        li:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- logout --}}
        <div class="d-flex justify-content-end">
            <button id="logoutBtn" class="btn btn-danger mt-5">Logout</button>
        </div>

        {{-- chat --}}
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Message</div>
                    <div class="card-body p-0">
                        <ul class="list-unstyled" id="messages-list" style="height:300px; overflow-y:scroll;">

                            {{-- <li class="p-2"> 
              <strong>User-1</strong>
              message text
            </li> --}}

                        </ul>
                    </div>

                    <input id="messageInput" type="text" name="message" placeholder="Enter your message....."
                        class="form-control">
                    <span class="text-muted">user is typing.......</span>
                </div>
            </div>

            {{-- active users  --}}
            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">Active Users</div>
                    <div class="card-body">
                        <ul id="user">
                            {{-- <li class="py-2">users-2</li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var active_id = null;
        // echo code for listening messageSent even

        // receive message






        $(document).ready(function() {
            // for channel
            window.Echo.join('chat')
                .here(user => {
                    $.each(user, function() {
                        $('#user').append($('<li>', {
                            class: 'list-group-item',
                            id: this.id,
                            text: this.name
                        }));
                    });
                })
                .joining(user => {
                    $.each(user, function() {
                        $('#user').append($('<li>', {
                            class: 'list-group-item',
                            id: this.id,
                            text: this.name,
                        }));
                    });
                })
                .leaving(user => {
                    console.log('leaving');
                    $.each(user, function() {
                        if ($('#user li').text() === user.name) {
                            $('#user li').detach().insertAfter($('#user li').last());
                        }
                    });
                })
                .listen('.messageSent', function(event) {
                    // $('#messages-list').html('');
                    console.log(event.message);
                    var message = event.message;
                    var listItem = $('<li>', {
                        class: 'list-group-item',
                        text: message.user.name + ": " + message.message
                    });
                    $('#messages-list').append(listItem);
                });




            // logout funcitonality
            $('#logoutBtn').click(function() {
                // Retrieve the access token from local storage
                var accessToken = localStorage.getItem('access_token');

                $.ajax({
                    url: 'http://127.0.0.1:8000/api/logout',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + accessToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        // Clear the access token from local storage
                        localStorage.removeItem('access_token');

                        window.location.href = '/signin';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON.error);
                        // Handle the error
                    }
                });
            });



            // receiver id get



            // send message
            $('#messageInput').keyup(function(event) {
                if (event.keyCode === 13) { // 13 is the Enter key code
                    sendMessage();
                }
            });

            // when click on active one
            $('#user').on('click', 'li', function() {
                active_id = $(this).attr('id');


                fetchMessages();

            function fetchMessages() {
                var accessToken = localStorage.getItem('access_token');

                $.ajax({
                    url: 'http://127.0.0.1:8000/api/fetchmessages',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Bearer ' + accessToken,
                        'Accept': 'application/json'
                    },
                    data: {
                        active_id: active_id
                    },
                    success: function(response) {
                        // console.log(response.user.name);
                        var messages = response;
                        var messagesList = $('#messages-list');
                        messagesList.empty();

                        messages.forEach(function(message) {
                            var listItem = $('<li>', {
                                class: 'list-group-item',
                                text: message.user.name + ': ' + message.message
                            });
                            messagesList.append(listItem);
                        });

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        // Handle error
                    }
                });
            }
            });

            function sendMessage() {
                // console.log(active_id);
                var message = $('#messageInput').val();

                var accessToken = localStorage.getItem('access_token');

                $.ajax({
                    url: 'http://127.0.0.1:8000/api/messages',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Bearer ' + accessToken,
                        'Accept': 'application/json'
                    },
                    data: {
                        message: message,
                        active_id: active_id
                    },
                    success: function(response) {
                        console.log(response);
                        $('#messageInput').val(''); // Clear the input field

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        // Handle error
                    }
                });


            }


            
        });
    </script>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>
