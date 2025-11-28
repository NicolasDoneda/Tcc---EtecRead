import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  ActivityIndicator,
  StyleSheet,
} from 'react-native';
import { Search, Filter, Book, Star } from 'lucide-react-native';
import api from '../../services/api';

export default function AdvancedSearchScreen({ navigation }) {
  const [searchQuery, setSearchQuery] = useState('');
  const [filter, setFilter] = useState('title');
  const [results, setResults] = useState([]);
  const [categories, setCategories] = useState([]);
  const [authors, setAuthors] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searched, setSearched] = useState(false);

  useEffect(() => {
    loadFilters();
  }, []);

  const loadFilters = async () => {
    try {
      const [categoriesRes, authorsRes] = await Promise.all([
        api.catalog.getCategories(),
        api.catalog.getAuthors(),
      ]);

      if (categoriesRes.success) setCategories(categoriesRes.data);
      if (authorsRes.success) setAuthors(authorsRes.data);
    } catch (error) {
      console.error('Erro ao carregar filtros:', error);
    }
  };

  const handleSearch = async () => {
    if (!searchQuery.trim()) {
      alert('Digite algo para buscar');
      return;
    }
    setLoading(true);
    setSearched(true);
    try {
      const response = await api.catalog.search(searchQuery, filter);
      if (response.success) setResults(response.data);
    } catch (error) {
      console.error('Erro na busca:', error);
      alert('Erro ao realizar busca');
    } finally {
      setLoading(false);
    }
  };

  const renderFilterButton = (filterType, label, icon) => (
    <TouchableOpacity
      style={[
        styles.filterButton,
        filter === filterType && styles.filterButtonActive,
      ]}
      onPress={() => setFilter(filterType)}
    >
      <Text style={styles.filterIcon}>{icon}</Text>
      <Text
        style={[
          styles.filterText,
          filter === filterType && styles.filterTextActive,
        ]}
      >
        {label}
      </Text>
    </TouchableOpacity>
  );

  const renderResult = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.cardLeft}>
        <View style={styles.coverPlaceholder}>
          <Book size={24} color="#fff" />
        </View>
      </View>

      <View style={styles.cardRight}>
        <View style={styles.cardHeader}>
          <View style={{ flex: 1 }}>
            <Text style={styles.bookTitle} numberOfLines={1}>
              {item.title}
            </Text>
            <Text style={styles.bookAuthor} numberOfLines={1}>
              {item.authors && item.authors.length > 0
                ? item.authors.map(a => a.name).join(', ')
                : 'Desconhecido'}
            </Text>
          </View>
          <View
            style={[
              styles.badge,
              item.available_quantity > 0
                ? styles.availableBadge
                : styles.unavailableBadge,
            ]}
          >
            <Text style={styles.badgeText}>
              {item.available_quantity > 0 ? 'Disponível' : 'Indisponível'}
            </Text>
          </View>
        </View>

        <View style={styles.cardFooter}>
          <View style={styles.rating}>
            <Star size={12} color="#FACC15" />
            <Text style={styles.ratingText}>{item.rating || '-'}</Text>
          </View>
          <View style={styles.categoryBadge}>
            <Text style={styles.categoryText}>
              {item.category?.name || '-'}
            </Text>
          </View>
          <Text style={styles.stockText}>
            {item.available_quantity}/{item.total_quantity || '-'} disponíveis
          </Text>
        </View>

        <TouchableOpacity
          style={[
            styles.loanButton,
            item.available_quantity === 0 && styles.disabledButton,
          ]}
          disabled={item.available_quantity === 0}
        >
          <Text style={styles.loanButtonText}>
            {item.available_quantity > 0 ? 'Solicitar Empréstimo' : 'Indisponível'}
          </Text>
        </TouchableOpacity>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      {/* Search */}
      <View style={styles.searchContainer}>
        <Search size={20} color="#9CA3AF" style={{ position: 'absolute', left: 15, top: 12 }} />
        <TextInput
          style={styles.searchInput}
          placeholder={`Buscar por ${
            filter === 'title' ? 'título' : filter === 'category' ? 'categoria' : 'autor'
          }`}
          value={searchQuery}
          onChangeText={setSearchQuery}
          onSubmitEditing={handleSearch}
          returnKeyType="search"
        />
        <TouchableOpacity style={styles.filterButtonIcon}>
          <Filter size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      {/* Filters */}
      <View style={styles.filtersContainer}>
        {renderFilterButton('title', 'Título', '📚')}
        {renderFilterButton('category', 'Categoria', '📂')}
        {renderFilterButton('author', 'Autor', '✍️')}
      </View>

      {/* Results */}
      {loading ? (
        <View style={styles.loading}>
          <ActivityIndicator size="large" color="#EF4444" />
        </View>
      ) : searched ? (
        <FlatList
          data={results}
          renderItem={renderResult}
          keyExtractor={(item) => item.id.toString()}
          contentContainerStyle={styles.resultsContainer}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Text style={styles.emptyText}>Nenhum resultado encontrado</Text>
            </View>
          }
        />
      ) : null}
    </View>
  );
}



const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F3F4F6' },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    backgroundColor: '#fff',
    margin: 15,
    borderRadius: 10,
    position: 'relative',
  },
  searchInput: {
    flex: 1,
    padding: 10,
    paddingLeft: 40,
    backgroundColor: '#F9FAFB',
    borderRadius: 8,
    fontSize: 16,
  },
  filterButtonIcon: {
    backgroundColor: '#EF4444',
    padding: 10,
    borderRadius: 8,
    marginLeft: 10,
  },
  filtersContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: 15,
    marginBottom: 10,
  },
  filterButton: {
    flex: 1,
    marginHorizontal: 5,
    padding: 10,
    borderRadius: 8,
    backgroundColor: '#F3F4F6',
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  filterButtonActive: {
    backgroundColor: '#EF4444',
  },
  filterText: { fontSize: 14, color: '#6B7280' },
  filterTextActive: { color: '#fff', fontWeight: 'bold' },
  filterIcon: { marginRight: 5 },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  resultsContainer: { paddingHorizontal: 15, paddingBottom: 20 },
  card: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 12,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowOffset: { width: 0, height: 2 },
    shadowRadius: 4,
    elevation: 2,
  },
  cardLeft: {},
  coverPlaceholder: {
    width: 60,
    height: 90,
    borderRadius: 8,
    backgroundColor: '#9CA3AF',
    justifyContent: 'center',
    alignItems: 'center',
  },
  cardRight: { flex: 1, marginLeft: 12 },
  cardHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  bookTitle: { fontSize: 16, fontWeight: 'bold', color: '#111827' },
  bookAuthor: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  badge: { paddingHorizontal: 6, paddingVertical: 2, borderRadius: 12 },
  availableBadge: { backgroundColor: '#22C55E' },
  unavailableBadge: { backgroundColor: '#EF4444' },
  badgeText: { color: '#fff', fontSize: 12, fontWeight: 'bold' },
  cardFooter: { flexDirection: 'row', alignItems: 'center', marginTop: 6, marginBottom: 8, flexWrap: 'wrap' },
  rating: { flexDirection: 'row', alignItems: 'center', marginRight: 10 },
  ratingText: { fontSize: 12, color: '#6B7280', marginLeft: 3 },
  categoryBadge: { backgroundColor: '#E5E7EB', paddingHorizontal: 6, paddingVertical: 2, borderRadius: 12, marginRight: 10 },
  categoryText: { fontSize: 12, color: '#374151' },
  stockText: { fontSize: 12, color: '#6B7280' },
  loanButton: {
    backgroundColor: '#EF4444',
    paddingVertical: 8,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 5,
  },
  disabledButton: { backgroundColor: '#D1D5DB' },
  loanButtonText: { color: '#fff', fontSize: 14, fontWeight: 'bold' },
  empty: { padding: 50, alignItems: 'center' },
  emptyText: { fontSize: 16, color: '#9CA3AF', fontWeight: 'bold' },
});
