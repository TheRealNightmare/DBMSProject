import api from "./api";

export default {
  async toggleFollow(authorId) {
    try {
      const response = await api.post(`/authors/${authorId}/follow`);
      return response.data;
    } catch (error) {
      console.error("Error toggling follow:", error);
      throw error;
    }
  },
};
