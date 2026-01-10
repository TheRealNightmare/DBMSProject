export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        "verso-cream": "#FDF6E9", // The background color seen in Auth pages
        "verso-blue": "#4A6FA5", // The primary button/header color
        "verso-dark": "#2D3748", // Text color
      },
    },
  },
  plugins: [],
};
