<!--extends('user.layout')

section('title', 'Чат по заказу')

section('content')
    <--div class="card card-danger direct-chat direct-chat-danger">
        {{-- Заголовок и остальная часть карточки --}}
        <div class="card-body">
            <div class="direct-chat-messages">
                foreach($order->messages as $message)
                    {{-- Проверьте, кто отправитель сообщения, и соответственно измените класс direct-chat-msg --}}
                    <div class="direct-chat-msg { $message->isFromUser ? '' : 'right' }}">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">{ $message->sender->name }}</span>
                            <span class="direct-chat-timestamp float-right">{ $message->created_at }}</span>
                        </div>
                        <img class="direct-chat-img" src="{ $message->sender->profile_image }}" alt="message user image">
                        <div class="direct-chat-text">
                            { $message->text }}
                        </div>
                    </div>
                endforeach
            </div>
        </div>
        <div class="card-footer">
            {{-- Форма отправки сообщения --}}
        </div>
    </div>
endsection-->




@extends('user.layout')

@section('title', "Чат с магазином #{$storeId}")

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Чат с магазином #{{$storeId}}</h1>
                </div><-- /.col ->
            </div><-- /.row ->
        </div><-- /.container-fluid ->
    </div>
    <!-- /.content-header -->
    <meta name="csrf-token" content="{ csrf_token() }}">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="chat-messages" style="height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">

                            </div>

                            <div class="input-group mt-3">
                                <input type="text" id="message" class="form-control" placeholder="Введите сообщение...">
                                <div class="input-group-append">
                                    <button onclick="sendMessage()" class="btn btn-primary">Отправить</button>
                                </div>
                            </div>

                            <form action="{{ route('send.message') }}" method="POST">
                                csrf

                                <input type="text" name="message" />
                                <button type="submit">Отправить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('PUSHER_APP_KEY', {
            cluster: 'PUSHER_APP_CLUSTER',
            encrypted: true
        });

        var channel = pusher.subscribe('chat');
        channel.bind('App\\Events\\MessageSent', function(data) {

            addMessage(data.message);
        });

        function sendMessage() {
            var messageInput = document.getElementById('message');
            var message = messageInput.value;

            if(message.trim() === '') {
                alert('Пожалуйста, введите сообщение');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{ route("send.message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    store_id: storeId,
                    content: message
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка сервера: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    addMessage(data.message);
                    messageInput.value = '';
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

        function addMessage(message) {
            var chatMessages = document.getElementById('chat-messages');
            var messageElement = document.createElement('div');
            messageElement.innerText = message.content;
            chatMessages.appendChild(messageElement);

            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
@endpush

