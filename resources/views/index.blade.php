<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite('resources/js/app.js')
    <style>
        #chat2 .form-control {
            border-color: transparent;
        }

        #chat2 .form-control:focus {
            border-color: transparent;
            box-shadow: inset 0px 0px 0px 1px transparent;
        }

        #chat2 .card-body {
            overflow-y: auto;
            position: relative;
            height: 400px;
        }

        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
    </style>
</head>

<body>

    <div class="container">
        <section>
            <div class="container py-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-6">

                        <div class="card" id="chat2">
                            <div class="card-header d-flex justify-content-between align-items-center p-3">
                                <h5 class="mb-0">Chat</h5>
                            </div>
                            <div class="card-body" data-mdb-perfect-scrollbar-init
                                style="position: relative; height: 400px" id="messageList">
                                @foreach ($models as $model)
                                    <div class="d-flex flex-row justify-content-start">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                                            alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div>
                                            @if ($model->file)
                                                @php
                                                    $fileExtension = pathinfo($model->file, PATHINFO_EXTENSION);
                                                    $filePath = $model->file;
                                                @endphp

                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                                    <img src="{{ asset($filePath) }}" alt="File Image" class="ms-3"
                                                        style="max-width: 150px; height: auto;">
                                                @elseif (in_array($fileExtension, ['MP4', 'mp4', 'mov', 'avi', 'mkv']))
                                                    <video controls style="max-width:150px;" class="ms-3">
                                                        <source src="{{ asset($filePath) }}"
                                                            type="video/{{ $fileExtension }}">
                                                    </video>
                                                @elseif (in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx']))
                                                    <a href="{{ asset($filePath) }}" target="_blank">Download File</a>
                                                @endif
                                            @endif
                                            @if ($model->text)
                                                <p class="small p-2 ms-3 mb-1 rounded-3 bg-body-tertiary">
                                                    {{ $model->text }}
                                                </p>
                                            @endif
                                            <p class="small ms-3 mb-3 rounded-3 text-muted">
                                                {{ $model->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                                    alt="avatar 3" style="width: 40px; height: 100%;">

                                <form action="{{ route('store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-with-icon">
                                        <div class="d-flex align-items-center">
                                            <textarea name="text" class="form-control form-control-lg mt-1" placeholder="Type message"
                                                style="overflow: hidden;" rows="1" cols="50" oninput="autoResize(this)"></textarea>
                                            <input type="file" id="file-input" name="file"
                                                class="form-control form-control-sm me-2" style="display: none;"
                                                accept="image/*, video/*" onchange="handleFileUpload(event)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16"
                                                onclick="document.getElementById('file-input').click();">
                                                <path
                                                    d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z" />
                                            </svg>
                                            <button type="submit"
                                                class="btn btn-link d-flex align-items-center p-0 ms-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                            <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a>
                            <a class="ms-3" href="#!"><i class="fas fa-paper-plane"></i></a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                console.log("File uploaded:", file.name);
            }
        }

        function autoResize(element) {
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }
    </script>
</body>

</html>
