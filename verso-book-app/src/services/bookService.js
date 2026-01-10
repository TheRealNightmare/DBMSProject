import axios from "axios";

const API_URL = "https://openlibrary.org";
const COVER_URL = "https://covers.openlibrary.org/b/id";

export default {
  // Fetch books by query (subject, title, etc.)
  async getBooks(query, limit = 10) {
    try {
      const response = await axios.get(`${API_URL}/search.json`, {
        params: { q: query, limit },
      });

      return response.data.docs.map((doc) => ({
        id: doc.key.replace("/works/", ""),
        title: doc.title,
        author: doc.author_name ? doc.author_name[0] : "Unknown Author",
        // Use Open Library covers or a fallback
        image: doc.cover_i
          ? `${COVER_URL}/${doc.cover_i}-M.jpg`
          : "https://placehold.co/150x220?text=No+Cover",
        rating: doc.ratings_average ? Math.round(doc.ratings_average) : 0,
        year: doc.first_publish_year,
      }));
    } catch (error) {
      console.error("API Error:", error);
      return [];
    }
  },

  // Get specific book details
  async getBookDetails(id) {
    try {
      const response = await axios.get(`${API_URL}/works/${id}.json`);
      const data = response.data;

      let description = "No description available.";
      if (typeof data.description === "string") description = data.description;
      else if (data.description?.value) description = data.description.value;

      return {
        id: id,
        title: data.title,
        description: description,
        image: data.covers
          ? `${COVER_URL}/${data.covers[0]}-L.jpg`
          : "https://placehold.co/300x450?text=No+Cover",
        authors: data.authors, // Authors need another fetch in OpenLibrary usually, but we'll handle simplistically
      };
    } catch (error) {
      console.error(error);
      return null;
    }
  },
};
