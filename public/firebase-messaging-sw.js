importScripts('https://www.gstatic.com/firebasejs/3.5.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.5.2/firebase-messaging.js');

firebase.initializeApp({
    'messagingSenderId': '823793769083'
});

const messaging = firebase.messaging();

// Installs service worker
self.addEventListener('install', (event) => {
    console.log('Service worker installed');
});


self.addEventListener('notificationclick', function (event) {
    console.log('On notification click: ', event);

    var eventURL = event.notification.data

    event.notification.close();

    // This looks to see if the current is already open and
    // focuses if it is
    event.waitUntil(
        clients.matchAll({
            type: "window"
        })
            .then(function (clientList) {
                for (var i = 0; i < clientList.length; i++) {
                    var client = clientList[i];
                    if (client.url == '/' && 'focus' in client)
                        return client.focus();
                }
                if (clients.openWindow) {
                    return clients.openWindow(eventURL.button_action);
                }
            })
    );
});


messaging.setBackgroundMessageHandler((payload) => {
    // Parses data received and sets accordingly
    const data = JSON.parse(payload.data.notification);
    const notificationTitle = data.title;
    const notificationOptions = {
        body: data.body,
        icon: data.icon
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});
