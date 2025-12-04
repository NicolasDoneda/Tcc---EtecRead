import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  ActivityIndicator,
  StyleSheet,
  Image,
  Modal,
} from 'react-native';
import { Search, Filter, X } from 'lucide-react-native';
import Svg, { Path, Circle } from 'react-native-svg';
import api from '../../services/api';

// Ícones SVG
const BookIcon = ({ size = 20, color = "#6B7280" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M6.5 2H20V22H6.5C5.83696 22 5.20107 21.7366 4.73223 21.2678C4.26339 20.7989 4 20.163 4 19.5V4.5C4 3.83696 4.26339 3.20107 4.73223 2.73223C5.20107 2.26339 5.83696 2 6.5 2Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const FolderIcon = ({ size = 20, color = "#6B7280" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M22 19C22 19.5304 21.7893 20.0391 21.4142 20.4142C21.0391 20.7893 20.5304 21 20 21H4C3.46957 21 2.96086 20.7893 2.58579 20.4142C2.21071 20.0391 2 19.5304 2 19V5C2 4.46957 2.21071 3.96086 2.58579 3.58579C2.96086 3.21071 3.46957 3 4 3H9L11 6H20C20.5304 6 21.0391 6.21071 21.4142 6.58579C21.7893 6.96086 22 7.46957 22 8V19Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const PenIcon = ({ size = 20, color = "#6B7280" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M12 19L19 12L22 15L15 22L12 19Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M18 13L16.5 5.5L2 2L5.5 16.5L13 18L18 13Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M2 2L9.586 9.586" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Circle cx="11" cy="11" r="2" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

export default function AdvancedSearchScreen({ navigation }) {
  const [searchQuery, setSearchQuery] = useState('');
  const [filter, setFilter] = useState('title');
  const [results, setResults] = useState([]);
  const [categories, setCategories] = useState([]);
  const [authors, setAuthors] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searched, setSearched] = useState(false);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [showCategoryModal, setShowCategoryModal] = useState(false);

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

  const handleFilterChange = (newFilter) => {
    setFilter(newFilter);
    if (newFilter === 'category') {
      setShowCategoryModal(true);
      setSearchQuery('');
      setSelectedCategory(null);
    } else {
      setSelectedCategory(null);
      setSearchQuery('');
    }
  };

  const handleCategorySelect = (category) => {
    setSelectedCategory(category);
    setSearchQuery(category.name);
    setShowCategoryModal(false);

    performSearch(category);
  };

  const performSearch = async (category = null) => {
    const query = category ? category.name : searchQuery;
    
    if (!query.trim() && filter !== 'category') {
      alert('Digite algo para buscar');
      return;
    }

    setLoading(true);
    setSearched(true);
    
    try {
      let response;
      
      if (filter === 'category' && (category || selectedCategory)) {
        const categoryId = category?.id || selectedCategory?.id;
        response = await api.catalog.getBooks({ 
          category_id: categoryId 
        });
      } else {
        response = await api.catalog.search(query, filter);
      }
      
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

  const handleSearch = () => {
    performSearch();
  };

  const getTotalQuantity = (item) => {
    return item.total_quantity || null;
  };

  const getAvailableQuantity = (item) => {
    return item.available_quantity || 0;
  };

  const getAuthorsNames = (item) => {
    if (item.authors_names) {
      return item.authors_names;
    }
    
    if (item.authors && Array.isArray(item.authors) && item.authors.length > 0) {
      return item.authors.map(a => a.name).join(', ');
    }
    
    return 'Desconhecido';
  };

  const renderFilterButton = (filterType, label, IconComponent) => (
    <TouchableOpacity
      style={[
        styles.filterButton,
        filter === filterType && styles.filterButtonActive,
      ]}
      onPress={() => handleFilterChange(filterType)}
    >
      <View style={styles.filterContent}>
        <IconComponent 
          size={18} 
          color={filter === filterType ? '#fff' : '#6B7280'} 
        />
        <Text
          style={[
            styles.filterText,
            filter === filterType && styles.filterTextActive,
          ]}
        >
          {label}
        </Text>
      </View>
    </TouchableOpacity>
  );

  const renderResult = ({ item }) => {
    const coverUri = item.cover_image || 
      "https://via.placeholder.com/100x150/ddd/666?text=Capa";

    const availableQty = getAvailableQuantity(item);
    const totalQty = getTotalQuantity(item);

    return (
      <View style={styles.card}>
        <View style={styles.cardLeft}>
          <Image
            source={{ uri: coverUri }}
            style={styles.coverImage}
            resizeMode="cover"
          />
        </View>

        <View style={styles.cardRight}>
          <View style={styles.cardHeader}>
            <View style={{ flex: 1 }}>
              <Text style={styles.bookTitle} numberOfLines={1}>
                {item.title}
              </Text>
              <Text style={styles.bookAuthor} numberOfLines={1}>
                {getAuthorsNames(item)}
              </Text>
            </View>
            <View
              style={[
                styles.badge,
                availableQty > 0
                  ? styles.availableBadge
                  : styles.unavailableBadge,
              ]}
            >
              <Text style={styles.badgeText}>
                {availableQty > 0 ? 'Disponível' : 'Indisponível'}
              </Text>
            </View>
          </View>

          <View style={styles.cardFooter}>
            <View style={styles.categoryBadge}>
              <Text style={styles.categoryText}>
                {item.category?.name || '-'}
              </Text>
            </View>
            <Text style={styles.stockText}>
              {totalQty !== null 
                ? `${availableQty}/${totalQty} disponíveis`
                : `${availableQty} disponível${availableQty !== 1 ? '(is)' : ''}`
              }
            </Text>
          </View>
        </View>
      </View>
    );
  };

  return (
    <View style={styles.container}>
      {/* Search */}
      <View style={styles.searchContainer}>
        <Search size={20} color="#9CA3AF" style={{ position: 'absolute', left: 15, top: 12 }} />
        <TextInput
          style={styles.searchInput}
          placeholder={
            filter === 'category' 
              ? 'Selecione uma categoria' 
              : `Buscar por ${filter === 'title' ? 'título' : 'autor'}`
          }
          value={searchQuery}
          onChangeText={setSearchQuery}
          onSubmitEditing={handleSearch}
          returnKeyType="search"
          editable={filter !== 'category'}
          onFocus={() => {
            if (filter === 'category') {
              setShowCategoryModal(true);
            }
          }}
        />
        <TouchableOpacity style={styles.filterButtonIcon} onPress={handleSearch}>
          <Search size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      {/* Filters */}
      <View style={styles.filtersContainer}>
        {renderFilterButton('title', 'Título', BookIcon)}
        {renderFilterButton('category', 'Categoria', FolderIcon)}
        {renderFilterButton('author', 'Autor', PenIcon)}
      </View>

      {/* Selected Category Badge */}
      {selectedCategory && filter === 'category' && (
        <View style={styles.selectedCategoryContainer}>
          <View style={styles.selectedCategoryBadge}>
            <Text style={styles.selectedCategoryText}>
              {selectedCategory.name}
            </Text>
            <TouchableOpacity onPress={() => setShowCategoryModal(true)}>
              <Text style={styles.changeCategoryText}>Alterar</Text>
            </TouchableOpacity>
          </View>
        </View>
      )}

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
          extraData={results}
          contentContainerStyle={styles.resultsContainer}
          ListEmptyComponent={
            <View style={styles.empty}>
              <Text style={styles.emptyText}>Nenhum resultado encontrado</Text>
            </View>
          }
        />
      ) : null}

      {/* Category Modal */}
      <Modal
        visible={showCategoryModal}
        transparent={true}
        animationType="slide"
        onRequestClose={() => setShowCategoryModal(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Selecione uma Categoria</Text>
              <TouchableOpacity onPress={() => setShowCategoryModal(false)}>
                <X size={24} color="#6B7280" />
              </TouchableOpacity>
            </View>
            
            <FlatList
              data={categories}
              keyExtractor={(item) => item.id.toString()}
              renderItem={({ item }) => (
                <TouchableOpacity
                  style={styles.categoryItem}
                  onPress={() => handleCategorySelect(item)}
                >
                  <Text style={styles.categoryItemText}>{item.name}</Text>
                  <Text style={styles.categoryItemCount}>
                    {item.books_count || 0} livros
                  </Text>
                </TouchableOpacity>
              )}
            />
          </View>
        </View>
      </Modal>
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
    placeholderTextColor: 'black',

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
  },

  filterButtonActive: {
    backgroundColor: '#EF4444',
  },

  filterContent: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 6,
  },

  filterText: {
    fontSize: 14,
    color: '#6B7280',
  },

  filterTextActive: {
    color: '#fff',
    fontWeight: 'bold',
  },

  selectedCategoryContainer: {
    paddingHorizontal: 15,
    marginBottom: 10,
  },

  selectedCategoryBadge: {
    backgroundColor: '#FEE2E2',
    borderColor: '#EF4444',
    borderWidth: 1,
    borderRadius: 8,
    padding: 12,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },

  selectedCategoryText: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#991B1B',
  },

  changeCategoryText: {
    fontSize: 14,
    color: '#EF4444',
    fontWeight: 'bold',
  },

  loading: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },

  resultsContainer: {
    paddingHorizontal: 15,
    paddingBottom: 20,
  },

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

  coverImage: {
    width: 60,
    height: 90,
    borderRadius: 8,
    backgroundColor: '#E5E7EB',
  },

  cardRight: {
    flex: 1,
    marginLeft: 12,
  },

  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },

  bookTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
  },

  bookAuthor: {
    fontSize: 13,
    color: '#6B7280',
    marginTop: 2,
  },

  badge: {
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 12,
  },

  availableBadge: {
    backgroundColor: '#22C55E',
  },

  unavailableBadge: {
    backgroundColor: '#EF4444',
  },

  badgeText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },

  cardFooter: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 6,
    marginBottom: 8,
    flexWrap: 'wrap',
  },

  categoryBadge: {
    backgroundColor: '#E5E7EB',
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 12,
    marginRight: 10,
  },

  categoryText: {
    fontSize: 12,
    color: '#374151',
  },

  stockText: {
    fontSize: 12,
    color: '#6B7280',
  },

  empty: {
    padding: 50,
    alignItems: 'center',
  },

  emptyText: {
    fontSize: 16,
    color: '#9CA3AF',
    fontWeight: 'bold',
  },

  // Modal Styles
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    justifyContent: 'flex-end',
  },

  modalContent: {
    backgroundColor: '#fff',
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    paddingTop: 20,
    maxHeight: '70%',
  },

  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingBottom: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#E5E7EB',
  },

  modalTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#111827',
  },

  categoryItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 15,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#F3F4F6',
  },

  categoryItemText: {
    fontSize: 16,
    color: '#111827',
    fontWeight: '500',
  },

  categoryItemCount: {
    fontSize: 14,
    color: '#6B7280',
  },
});