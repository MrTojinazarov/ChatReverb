import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel('xabar')
    .listen('MessageEvent', (event) => {
        console.log('New Message Event:', event);

        const messageList = document.getElementById('messageList');

        if (!messageList) {
            console.error('Element with ID "messageList" not found.');
            return;
        }

        const newMessage = document.createElement('li');
        newMessage.classList.add('message-item', 'p-3', 'mb-2', 'border', 'rounded');

        if (event.message) {
            const messageText = document.createElement('p');
            messageText.classList.add('mb-2');
            messageText.innerText = event.message;
            newMessage.appendChild(messageText);
        }

        if (event.file) {
            const fileExtension = event.file.split('.').pop().toLowerCase();
            const fileContainer = document.createElement('div');

            if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp'].includes(fileExtension)) {
                const imgPreview = document.createElement('img');
                imgPreview.src = event.file;
                imgPreview.alt = 'Image Preview';
                imgPreview.style.maxWidth = '200px';
                imgPreview.style.display = 'block';
                imgPreview.classList.add('mt-2', 'rounded');
                fileContainer.appendChild(imgPreview);
            } else if (['mp4', 'mov', 'avi', 'mkv'].includes(fileExtension)) {
                const videoPreview = document.createElement('video');
                videoPreview.src = event.file;
                videoPreview.controls = true;
                videoPreview.style.maxWidth = '200px';
                videoPreview.classList.add('mt-2');
                fileContainer.appendChild(videoPreview);
            } else {
                const fileLink = document.createElement('a');
                fileLink.href = event.file;
                fileLink.target = '_blank';
                fileLink.innerText = 'Download File';
                fileLink.classList.add('d-block', 'mt-2', 'text-decoration-none', 'text-primary');
                fileContainer.appendChild(fileLink);
            }

            newMessage.appendChild(fileContainer);
        }

        if (event.time) {
            const timestamp = document.createElement('span');
            timestamp.classList.add('message-time', 'text-muted', 'd-block', 'mt-1', 'small');
            timestamp.innerText = `Yuborilgan vaqt: ${event.time}`;
            newMessage.appendChild(timestamp);
        }

        messageList.prepend(newMessage);
    });


