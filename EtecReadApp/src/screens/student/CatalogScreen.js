// src/screens/student/CatalogScreen.js
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, Image, TouchableOpacity, TextInput, StyleSheet, ActivityIndicator, RefreshControl, Platform } from 'react-native';
import api from '../../services/api';

const shadow = (elevation = 3) => Platform.select({
  ios: { shadowColor: '#000', shadowOffset: { width: 0, height: elevation / 2 }, shadowOpacity: 0.1, shadowRadius: elevation },
  android: { elevation },
  web: { boxShadow: `0 ${elevation}px ${elevation * 2}px rgba(0,0,0,0.1)` },
});

export default function CatalogScreen() {
  const [books, setBooks] = useState([]);
  const [statistics, setStatistics] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [search, setSearch] = useState('');
  const [imageCache, setImageCache] = useState({});

  useEffect(() => { loadData(); }, []);

  const loadData = async () => {
    try {
      const [booksRes, statsRes] = await Promise.all([
        api.catalog.getBooks(), 
        api.catalog.getStatistics()
      ]);
      
      if (booksRes.success) {
        console.log('📚 Total de livros:', booksRes.data.length);
        if (booksRes.data.length > 0) {
          console.log('🖼️  Exemplo de URL:', booksRes.data[0].cover_image);
        }
        setBooks(booksRes.data);
        
        // PRÉ-CARREGAR IMAGENS
        booksRes.data.forEach(book => {
          if (book.cover_image) {
            preloadImage(book.cover_image);
          }
        });
      }
      
      if (statsRes.success) setStatistics(statsRes.data);
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  // PRÉ-CARREGAR IMAGEM
  const preloadImage = async (url) => {
    try {
      await Image.prefetch(url);
      console.log('✅ Imagem pré-carregada:', url);
    } catch (error) {
      console.log('❌ Erro ao pré-carregar:', url);
    }
  };

  const onRefresh = () => { 
    setRefreshing(true); 
    loadData(); 
  };

  const handleSearch = async () => {
    if (!search.trim()) { 
      loadData(); 
      return; 
    }
    try {
      const response = await api.catalog.search(search, 'title');
      if (response.success) setBooks(response.data);
    } catch (error) { 
      console.error('Erro na busca:', error); 
    }
  };

  const renderStatistics = () => (
    <View style={styles.statsContainer}>
      <View style={styles.statCard}>
        <Text style={styles.statNumber}>{statistics?.total_books || 0}</Text>
        <Text style={styles.statLabel}>Total de Livros</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={styles.statNumber}>{statistics?.available_books || 0}</Text>
        <Text style={styles.statLabel}>Disponíveis</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={styles.statNumber}>{statistics?.total_categories || 0}</Text>
        <Text style={styles.statLabel}>Categorias</Text>
      </View>
    </View>
  );

  const renderBook = ({ item }) => {
    let imageUri = item.cover_image || 'https://via.placeholder.com/80x120/e0e0e0/666?text=Sem+Capa';
    
    return (
      <TouchableOpacity style={styles.bookCard}>
        <Image 
          source={{ uri: imageUri }}
          style={styles.cover}
          resizeMode="cover"
          
          cachePolicy="none"
        />
        <View style={styles.bookInfo}>
          <Text style={styles.title} numberOfLines={2}>{item.title}</Text>
          <Text style={styles.category}>{item.category.name}</Text>
          <Text style={styles.authors} numberOfLines={1}>{item.authors_names}</Text>
          <View style={styles.availabilityContainer}>
            <View style={[
              styles.availabilityBadge, 
              item.available_quantity > 0 ? styles.available : styles.unavailable
            ]}>
              <Text style={styles.availabilityText}>
                {item.available_quantity > 0 ? 'Disponível' : 'Indisponível'}
              </Text>
            </View>
            <Text style={styles.quantity}>
              {item.available_quantity}/{item.total_quantity}
            </Text>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#007AFF" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.searchContainer}>
        <TextInput 
          style={styles.searchInput} 
          placeholder="Buscar livros..." 
          value={search} 
          onChangeText={setSearch} 
          onSubmitEditing={handleSearch} 
          returnKeyType="search" 
        />
        <TouchableOpacity style={styles.searchButton} onPress={handleSearch}>
          <Text style={styles.searchButtonText}>🔍</Text>
        </TouchableOpacity>
      </View>
      
      <FlatList 
        data={books} 
        renderItem={renderBook} 
        keyExtractor={(item) => item.id.toString()} 
        ListHeaderComponent={statistics && renderStatistics()} 
        contentContainerStyle={styles.listContent} 
        refreshControl={
          <RefreshControl 
            refreshing={refreshing} 
            onRefresh={onRefresh} 
          />
        } 
        ListEmptyComponent={
          <View style={styles.empty}>
            <Text style={styles.emptyText}>Nenhum livro encontrado</Text>
          </View>
        } 
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  searchContainer: { flexDirection: 'row', padding: 15, backgroundColor: 'white', borderBottomWidth: 1, borderBottomColor: '#ddd' },
  searchInput: { flex: 1, backgroundColor: '#f0f0f0', padding: 10, borderRadius: 8, marginRight: 10 },
  searchButton: { backgroundColor: '#007AFF', padding: 10, borderRadius: 8, justifyContent: 'center', alignItems: 'center', width: 45 },
  searchButtonText: { fontSize: 20 },
  listContent: { padding: 10 },
  statsContainer: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 15 },
  statCard: { flex: 1, backgroundColor: 'white', padding: 15, borderRadius: 10, marginHorizontal: 5, alignItems: 'center', ...shadow(2) },
  statNumber: { fontSize: 24, fontWeight: 'bold', color: '#007AFF' },
  statLabel: { fontSize: 12, color: '#666', marginTop: 5, textAlign: 'center' },
  bookCard: { flexDirection: 'row', backgroundColor: 'white', borderRadius: 10, padding: 12, marginBottom: 10, ...shadow(3) },
  cover: { width: 70, height: 105, borderRadius: 5, backgroundColor: '#e0e0e0' },
  bookInfo: { flex: 1, marginLeft: 12, justifyContent: 'space-between' },
  title: { fontSize: 16, fontWeight: 'bold', color: '#333' },
  category: { fontSize: 13, color: '#666', marginTop: 4 },
  authors: { fontSize: 12, color: '#999', fontStyle: 'italic' },
  availabilityContainer: { flexDirection: 'row', alignItems: 'center', marginTop: 8 },
  availabilityBadge: { paddingHorizontal: 8, paddingVertical: 4, borderRadius: 5, marginRight: 10 },
  available: { backgroundColor: '#4CAF50' },
  unavailable: { backgroundColor: '#F44336' },
  availabilityText: { color: 'white', fontSize: 11, fontWeight: 'bold' },
  quantity: { fontSize: 12, color: '#666' },
  empty: { padding: 50, alignItems: 'center' },
  emptyText: { fontSize: 16, color: '#999' },
});