self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }
    var data = {};

    const sendNotification = payload => {
        const title = payload.title;
        var message = payload.message;

        return self.registration.showNotification(title, {
          body: message,
          icon: 'images/icon.png',
          badge: 'images/badge.png',
          lang: 'AR'
        });
    };

    if (event.data) {
         data = event.data.json();

        event.waitUntil(sendNotification(data));
    }
});

self.addEventListener('notificationclick', function(event) {
  console.log('[Service Worker] Notification click Received.');

  event.notification.close();

  event.waitUntil(
    clients.openWindow('https://enty.tv/')
  );
});
