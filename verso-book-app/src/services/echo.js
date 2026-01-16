import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const createEcho = () => {
  const token = localStorage.getItem("auth_token");

  return new Echo({
    broadcaster: "reverb",
    key: "verso-app-key", // Must match REVERB_APP_KEY in .env
    wsHost: "localhost",
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ["ws", "wss"],
    authEndpoint: "http://localhost:8000/api/broadcasting/auth",
    auth: {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
      },
    },
  });
};

export default createEcho;
