// src/services/api.js
const API_URL = 'http://192.168.18.14:8000/api';

import AsyncStorage from '@react-native-async-storage/async-storage';

const prepareParams = (params) => {
  const cleaned = {};
  Object.keys(params).forEach(key => {
    const value = params[key];
    if (value === null || value === undefined) return;
    if (typeof value === 'boolean') {
      cleaned[key] = value ? 'true' : 'false';
    } else if (typeof value === 'number') {
      cleaned[key] = String(value);
    } else {
      cleaned[key] = String(value);
    }
  });
  return cleaned;
};

const request = async (endpoint, options = {}) => {
  try {
    const token = await AsyncStorage.getItem('token');
    const headers = { 'Content-Type': 'application/json', ...options.headers };
    if (token) headers['Authorization'] = `Bearer ${token}`;
    if (options.headers?.['Content-Type'] === 'multipart/form-data') delete headers['Content-Type'];
    const response = await fetch(`${API_URL}${endpoint}`, { ...options, headers });
    const data = await response.json();
    if (!response.ok) throw new Error(data.message || 'Erro na requisição');
    return data;
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};

export const auth = {
  login: async (email, password, rm = null) => {
    const data = await request('/login', { method: 'POST', body: JSON.stringify({ email, password, rm }) });
    if (data.success) {
      await AsyncStorage.setItem('token', data.data.token);
      await AsyncStorage.setItem('user', JSON.stringify(data.data.user));
    }
    return data;
  },
  logout: async () => {
    try { await request('/logout', { method: 'POST' }); } 
    catch (error) { console.error('Erro no logout:', error); } 
    finally {
      await AsyncStorage.removeItem('token');
      await AsyncStorage.removeItem('user');
    }
  },
  getProfile: async () => await request('/profile'),
  updateProfile: async (formData) => await request('/profile', { method: 'POST', headers: { 'Content-Type': 'multipart/form-data' }, body: formData }),
};

export const catalog = {
  getBooks: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    return await request(`/catalog${query ? '?' + query : ''}`);
  },
  getBook: async (id) => await request(`/catalog/books/${id}`),
  getStatistics: async () => await request('/catalog/statistics'),
  getCategories: async () => await request('/catalog/categories'),
  getAuthors: async () => await request('/catalog/authors'),
  search: async (query, filter = 'title') => await request('/catalog/search', { method: 'POST', body: JSON.stringify({ query, filter }) }),
};

export const myLoans = {
  getActive: async () => await request('/my-loans/active'),
  getHistory: async () => await request('/my-loans/history'),
  getSummary: async () => await request('/my-loans/summary'),
};

export const adminDashboard = {
  get: async () => await request('/admin/dashboard'),
};

export const adminLoans = {
  getAll: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    return await request(`/admin/loans${query ? '?' + query : ''}`);
  },
  getStatistics: async () => await request('/admin/loans/statistics'),
};

export const adminReservations = {
  getAll: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    return await request(`/admin/reservations${query ? '?' + query : ''}`);
  },
  getStatistics: async () => await request('/admin/reservations/statistics'),
};

export const adminReports = {
  getMonthly: async (month, year) => await request(`/admin/reports/monthly?month=${month}&year=${year}`),
  getOverview: async () => await request('/admin/reports/overview'),
  downloadPDF: async (month, year) => await request(`/admin/reports/download-pdf?month=${month}&year=${year}`),
};

export const adminSettings = {
  get: async () => await request('/admin/settings'),
  update: async (settings) => await request('/admin/settings', { method: 'PUT', body: JSON.stringify(settings) }),
  getSystemInfo: async () => await request('/admin/settings/system-info'),
  createBackup: async () => await request('/admin/settings/backup', { method: 'POST' }),
  clearCache: async () => await request('/admin/settings/clear-cache', { method: 'POST' }),
};

export default { auth, catalog, myLoans, adminDashboard, adminLoans, adminReservations, adminReports, adminSettings };