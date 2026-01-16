import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const createEcho = () => {
  const token = localStorage.getItem("auth_token");

  return new Echo({
    broadcaster: "reverb",
    key: "app-key", // Default Reverb key is often 'app-key' locally
    wsHost: "localhost", // Or your backend IP
    wsPort: 8080, // Default Reverb port
    wssPort: 8080,
    forceTLS: false, // Set to true if using https in production
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
