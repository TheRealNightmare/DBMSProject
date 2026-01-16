import api from "./api";

export default {
  // Fetch books from your Laravel Backend
  async getBooks(type = "latest", limit = 10) {
    try {
      // Passes 'type' (e.g., 'classic', 'business') to Laravel
      const response = await api.get("/books", {
        params: { type, limit },
      });
      return response.data;
    } catch (error) {
      console.error("Failed to fetch books:", error);
      return [];
    }
  },

  async getBookDetails(id) {
    try {
      const response = await api.get(`/books/${id}`);
      return response.data;
    } catch (error) {
      console.error("Failed to fetch book details:", error);
      return null;
    }
  },
};
