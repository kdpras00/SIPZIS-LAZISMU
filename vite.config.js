import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
      detectTls: false,
    }),
  ],
  server: {
    host: 'localhost',
    port: 5173,
    strictPort: false,
    hmr: {
      host: 'localhost',
      protocol: 'ws',
    },
    cors: {
      origin: true, // Allow all origins (including ngrok)
      credentials: true,
    },
    // Allow ngrok and other tunnel services
    allowedHosts: [
      'localhost',
      '127.0.0.1',
      '.ngrok.io',
      '.ngrok-free.app',
      '.ngrok.app',
    ],
    watch: {
      usePolling: true, // Better for ngrok/tunnel services
    },
  },
});
