import axios from 'axios';

const API_BASE_URL = '/api';

export const apiService = {
  async get(endpoint: string) {
    try {
      const response = await axios.get(`${API_BASE_URL}/${endpoint}`);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  async post(endpoint: string, data: any) {
    try {
      const response = await axios.post(`${API_BASE_URL}/${endpoint}`, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  async put(endpoint: string, data: any) {
    try {
      const response = await axios.put(`${API_BASE_URL}/${endpoint}`, data);
      return response.data;
    } catch (error) {
      throw error;
    }
  },

  async delete(endpoint: string) {
    try {
      const response = await axios.delete(`${API_BASE_URL}/${endpoint}`);
      return response.data;
    } catch (error) {
      throw error;
    }
  },
};
