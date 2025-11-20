const API_URL = 'https://etecread.com/api';

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
    const headers = { 
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers 
    };
    
    if (token) {
      headers['Authorization'] = 'Bearer ' + token;
    }
    
    // Remove Content-Type se for FormData
    if (options.body instanceof FormData) {
      delete headers['Content-Type'];
    }
    
    const url = API_URL + endpoint;
    const response = await fetch(url, { ...options, headers });
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.message || 'Erro na requisição');
    }
    return data;
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};

export const auth = {
  login: async (email, password, rm = null) => {
    const data = await request('/login', { 
      method: 'POST', 
      body: JSON.stringify({ email, password, rm }) 
    });
    if (data.success) {
      await AsyncStorage.setItem('token', data.data.token);
      await AsyncStorage.setItem('user', JSON.stringify(data.data.user));
    }
    return data;
  },
  
  logout: async () => {
    try { 
      await request('/logout', { method: 'POST' }); 
    } catch (error) { 
      console.error('Erro no logout:', error); 
    } finally {
      await AsyncStorage.removeItem('token');
      await AsyncStorage.removeItem('user');
    }
  },
  
  getProfile: async () => {
    return await request('/profile');
  },
  
  updateProfile: async (formData) => {
    return await request('/profile', { 
      method: 'POST',
      body: formData 
    });
  },
};

export const catalog = {
  getBooks: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    const endpoint = query ? '/catalog?' + query : '/catalog';
    return await request(endpoint);
  },
  
  getBook: async (id) => {
    return await request('/catalog/books/' + id);
  },
  
  getStatistics: async () => {
    return await request('/catalog/statistics');
  },
  
  getCategories: async () => {
    return await request('/catalog/categories');
  },
  
  getAuthors: async () => {
    return await request('/catalog/authors');
  },
  
  search: async (query, filter = 'title') => {
    return await request('/catalog/search', { 
      method: 'POST', 
      body: JSON.stringify({ query, filter }) 
    });
  },
};

export const myLoans = {
  getActive: async () => {
    return await request('/my-loans/active');
  },
  
  getHistory: async () => {
    return await request('/my-loans/history');
  },
  
  getSummary: async () => {
    return await request('/my-loans/summary');
  },
};

export const adminDashboard = {
  get: async () => {
    return await request('/admin/dashboard');
  },
};

export const adminLoans = {
  getAll: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    const endpoint = query ? '/admin/loans?' + query : '/admin/loans';
    return await request(endpoint);
  },
  
  getStatistics: async () => {
    return await request('/admin/loans/statistics');
  },
};

export const adminReservations = {
  getAll: async (filters = {}) => {
    const params = prepareParams(filters);
    const query = new URLSearchParams(params).toString();
    const endpoint = query ? '/admin/reservations?' + query : '/admin/reservations';
    return await request(endpoint);
  },
  
  getStatistics: async () => {
    return await request('/admin/reservations/statistics');
  },
};

export const adminReports = {
  getMonthly: async (month, year) => {
    return await request('/admin/reports/monthly?month=' + month + '&year=' + year);
  },
  
  getOverview: async () => {
    return await request('/admin/reports/overview');
  },
  
  downloadPDF: async (month, year) => {
    return await request('/admin/reports/download-pdf?month=' + month + '&year=' + year);
  },
};

export default { 
  auth, 
  catalog, 
  myLoans, 
  adminDashboard, 
  adminLoans, 
  adminReservations, 
  adminReports,
};