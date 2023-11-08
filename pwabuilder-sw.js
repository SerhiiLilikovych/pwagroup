self.addEventListener("install", () => {
  return true;
});

self.addEventListener("activate", () => {
  return true;
});

self.addEventListener("sync", () => {
  return true;
});

self.addEventListener("fetch", () => {
  return true;
});

self.addEventListener("push", function (event) {
  const { title, ...options } = event.data.json();
  self.registration.showNotification(title, options);
  fetch(event.target.registration.scope + "pwas/pushes/showed", {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
    },
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body: JSON.stringify(options.data),
  });
  return true;
});

self.addEventListener("notificationclick", function (event) {
  fetch(event.target.registration.scope + "pwas/pushes/clicked", {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
    },
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body: JSON.stringify(event.notification.data),
  });
  event.notification.close();
  event.waitUntil(clients.openWindow(event.notification.data.url));
  return true;
});

self.addEventListener("notificationclose", function (event) {
  fetch(event.target.registration.scope + "pwas/pushes/capped", {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
    },
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body: JSON.stringify(event.notification.data),
  });
  return true;
});
