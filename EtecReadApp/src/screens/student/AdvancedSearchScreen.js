import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  Image,
  StyleSheet,
  ActivityIndicator,
} from 'react-native';
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
      if (response.success) {
        setResults(response.data);
      }
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
    <TouchableOpacity style={styles.resultCard}>
      <Image
        source={{ uri: item.cover_image || 'https://via.placeholder.com/60x90' }}
        style={styles.cover}
      />
      <View style={styles.resultInfo}>
        <Text style={styles.resultTitle} numberOfLines={2}>
          {item.title}
        </Text>
        <Text style={styles.resultCategory}>{item.category}</Text>
        {item.authors && (
          <Text style={styles.resultAuthors} numberOfLines={1}>
            {item.authors}
          </Text>
        )}
        <Text
          style={[
            styles.availability,
            item.available_quantity > 0 ? styles.available : styles.unavailable,
          ]}
        >
          {item.available_quantity > 0 ? '✓ Disponível' : '✗ Indisponível'}
        </Text>
      </View>
    </TouchableOpacity>
  );

  const renderSuggestions = () => {
    if (filter === 'category') {
      return (
        <View style={styles.suggestionsContainer}>
          <Text style={styles.suggestionsTitle}>Categorias populares:</Text>
          <View style={styles.suggestionsGrid}>
            {categories.slice(0, 6).map((category) => (
              <TouchableOpacity
                key={category.id}
                style={styles.suggestionChip}
                onPress={() => {
                  setSearchQuery(category.name);
                  handleSearch();
                }}
              >
                <Text style={styles.suggestionText}>{category.name}</Text>
                <Text style={styles.suggestionCount}>({category.books_count})</Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>
      );
    }

    if (filter === 'author') {
      return (
        <View style={styles.suggestionsContainer}>
          <Text style={styles.suggestionsTitle}>Autores populares:</Text>
          <View style={styles.suggestionsGrid}>
            {authors.slice(0, 6).map((author) => (
              <TouchableOpacity
                key={author.id}
                style={styles.suggestionChip}
                onPress={() => {
                  setSearchQuery(author.name);
                  handleSearch();
                }}
              >
                <Text style={styles.suggestionText}>{author.name}</Text>
                <Text style={styles.suggestionCount}>({author.books_count})</Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>
      );
    }

    return null;
  };

  return (
    <View style={styles.container}>
      <View style={styles.searchContainer}>
        <TextInput
          style={styles.searchInput}
          placeholder={`Buscar por ${filter === 'title' ? 'título' : filter === 'category' ? 'categoria' : 'autor'}...`}
          value={searchQuery}
          onChangeText={setSearchQuery}
          onSubmitEditing={handleSearch}
          returnKeyType="search"
        />
        <TouchableOpacity style={styles.searchButton} onPress={handleSearch}>
          <Text style={styles.searchButtonText}>🔍</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.filtersContainer}>
        {renderFilterButton('title', 'Título', '📚')}
        {renderFilterButton('category', 'Categoria', '📂')}
        {renderFilterButton('author', 'Autor', '✍️')}
      </View>

      {loading ? (
        <View style={styles.loading}>
          <ActivityIndicator size="large" color="#007AFF" />
        </View>
      ) : searched ? (
        <FlatList
          data={results}
          renderItem={renderResult}
          keyExtractor={(item) => item.id.toString()}
          contentContainerStyle={styles.resultsContainer}
          ListHeaderComponent={
            <Text style={styles.resultsCount}>
              {results.length} resultado(s) encontrado(s)
            </Text>
          }
          ListEmptyComponent={
            <View style={styles.empty}>
              <Text style={styles.emptyText}>Nenhum resultado encontrado</Text>
              <Text style={styles.emptyHint}>Tente buscar por outro termo</Text>
            </View>
          }
        />
      ) : (
        renderSuggestions()
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  searchContainer: {
    flexDirection: 'row',
    padding: 15,
    backgroundColor: 'white',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  searchInput: {
    flex: 1,
    backgroundColor: '#f0f0f0',
    padding: 12,
    borderRadius: 8,
    marginRight: 10,
    fontSize: 16,
  },
  searchButton: {
    backgroundColor: '#007AFF',
    padding: 12,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    width: 50,
  },
  searchButtonText: {
    fontSize: 20,
  },
  filtersContainer: {
    flexDirection: 'row',
    padding: 15,
    backgroundColor: 'white',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  filterButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 10,
    marginHorizontal: 5,
    backgroundColor: '#f0f0f0',
    borderRadius: 8,
  },
  filterButtonActive: {
    backgroundColor: '#007AFF',
  },
  filterIcon: {
    fontSize: 18,
    marginRight: 5,
  },
  filterText: {
    fontSize: 14,
    color: '#666',
  },
  filterTextActive: {
    color: 'white',
    fontWeight: 'bold',
  },
  loading: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  resultsContainer: {
    padding: 15,
  },
  resultsCount: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  resultCard: {
    flexDirection: 'row',
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 12,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cover: {
    width: 60,
    height: 90,
    borderRadius: 5,
  },
  resultInfo: {
    flex: 1,
    marginLeft: 12,
  },
  resultTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 4,
  },
  resultCategory: {
    fontSize: 13,
    color: '#666',
    marginBottom: 2,
  },
  resultAuthors: {
    fontSize: 12,
    color: '#999',
    fontStyle: 'italic',
    marginBottom: 6,
  },
  availability: {
    fontSize: 12,
    fontWeight: 'bold',
  },
  available: {
    color: '#4CAF50',
  },
  unavailable: {
    color: '#F44336',
  },
  empty: {
    padding: 50,
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#999',
    marginBottom: 5,
  },
  emptyHint: {
    fontSize: 14,
    color: '#ccc',
  },
  suggestionsContainer: {
    padding: 15,
  },
  suggestionsTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  suggestionsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
  },
  suggestionChip: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'white',
    padding: 10,
    borderRadius: 20,
    marginRight: 10,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 2,
    elevation: 2,
  },
  suggestionText: {
    fontSize: 14,
    color: '#333',
    marginRight: 5,
  },
  suggestionCount: {
    fontSize: 12,
    color: '#999',
  },
});