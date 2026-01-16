import api from "./api";

export default {
  // Fetch list of books
  async getBooks(type = "latest", limit = 10) {
    try {
      const response = await api.get("/books", {
        params: { type, limit },
      });
      // Laravel response()->json(...) returns the array directly in data
      return response.data;
    } catch (error) {
      console.error("Failed to fetch books:", error);
      return [];
    }
  },

  // Fetch single book details
  async getBookDetails(id) {
    try {
      const response = await api.get(`/books/${id}`);
      return response.data;
    } catch (error) {
      console.error("Failed to fetch book details:", error);
      return null;
    }
  },
  async getChapterContent(bookId, chapterIndex) {
    try {
      const response = await api.get(`/books/${bookId}/read/${chapterIndex}`);
      return response.data;
    } catch (error) {
      console.error("Failed to load chapter", error);
      throw error;
    }
  },

  async saveAnnotation(bookId, data) {
    try {
      const response = await api.post(`/books/${bookId}/annotate`, data);
      return response.data;
    } catch (error) {
      console.error("Failed to save annotation", error);
      throw error;
    }
  },
};
