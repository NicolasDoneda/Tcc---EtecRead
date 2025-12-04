import React, { useState, useEffect } from 'react';
import {
  View, Text, FlatList, Image, TouchableOpacity,
  TextInput, StyleSheet, ActivityIndicator,
  RefreshControl, Platform
} from 'react-native';

import api from '../../services/api';

import { Book, Search, Star } from "lucide-react-native";

const shadow = (elevation = 3) => Platform.select({
  ios: { shadowColor: '#000', shadowOffset: { width: 0, height: elevation / 2 }, shadowOpacity: 0.08, shadowRadius: elevation },
  android: { elevation },
  web: { boxShadow: `0 ${elevation}px ${elevation * 2}px rgba(0,0,0,0.08)` },
});

export default function CatalogScreen() {
  const [books, setBooks] = useState([]);
  const [statistics, setStatistics] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [search, setSearch] = useState('');

  useEffect(() => { loadData(); }, []);

  const loadData = async () => {
    try {
      const [booksRes, statsRes] = await Promise.all([
        api.catalog.getBooks(),
        api.catalog.getStatistics()
      ]);

      if (booksRes.success) setBooks(booksRes.data);
      if (statsRes.success) setStatistics(statsRes.data);

    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => { 
    setRefreshing(true); 
    loadData(); 
  };

  const handleSearch = async () => {
    if (!search.trim()) return loadData();

    const response = await api.catalog.search(search, 'title');
    if (response.success) setBooks(response.data);
  };

  const renderStatistics = () => (
    <View style={styles.statsContainer}>

      <View style={styles.statCard}>
        <Book size={22} color="#ef4444" />
        <Text style={styles.statNumber}>{statistics?.total_books || 0}</Text>
        <Text style={styles.statLabel}>Total de Livros</Text>
      </View>

      <View style={styles.statCard}>
        <Book size={22} color="#16a34a" />
        <Text style={styles.statNumber}>{statistics?.available_books || 0}</Text>
        <Text style={styles.statLabel}>Disponíveis</Text>
      </View>

      <View style={styles.statCard}>
        <Book size={22} color="#6b7280" />
        <Text style={styles.statNumber}>{statistics?.total_categories || 0}</Text>
        <Text style={styles.statLabel}>Categorias</Text>
      </View>

    </View>
  );

  const renderBook = ({ item }) => {

    const uri = item.cover_image ||
      "https://via.placeholder.com/100x150/ddd/666?text=Capa";

    return (
      <TouchableOpacity style={styles.bookCard}>

        <Image
          source={{ uri }}
          style={styles.cover}
          resizeMode="cover"
        />

        <View style={styles.bookInfo}>
          <Text style={styles.title} numberOfLines={2}>{item.title}</Text>
          <Text style={styles.category}>{item.category.name}</Text>
          <Text style={styles.authors} numberOfLines={1}>{item.authors_names}</Text>

          <View style={styles.rowBetween}>
            <View style={styles.availabilityRow}>
              <View style={[
                styles.badge,
                item.available_quantity > 0
                  ? styles.badgeAvailable
                  : styles.badgeUnavailable
              ]}>
                <Text style={styles.badgeText}>
                  {item.available_quantity > 0 ? "Disponível" : "Indisponível"}
                </Text>
              </View>
              <Text style={styles.quantity}>{item.available_quantity}/{item.total_quantity}</Text>
            </View>

          </View>

        </View>

      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#ef4444" />
      </View>
    );
  }

  return (
    <View style={styles.container}>

      {/* === BUSCA === */}
      <View style={styles.searchContainer}>
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar livros..."
          value={search}
          onChangeText={setSearch}
          onSubmitEditing={handleSearch}
        />
        <TouchableOpacity style={styles.searchButton} onPress={handleSearch}>
          <Search size={20} color="white" />
        </TouchableOpacity>
      </View>

      <FlatList
        data={books}
        renderItem={renderBook}
        keyExtractor={(i) => i.id.toString()}
        contentContainerStyle={{ padding: 12 }}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
        ListHeaderComponent={statistics && renderStatistics()}
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

  container: {
    flex: 1,
    backgroundColor: "#f8f8f8",
  },

  loading: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },

  // BUSCA
  searchContainer: {
    flexDirection: "row",
    padding: 12,
    backgroundColor: "white",
    borderBottomWidth: 1,
    borderColor: "#e5e7eb",
  },

  searchInput: {
    flex: 1,
    backgroundColor: "#f3f4f6",
    padding: 10,
    borderRadius: 10,
    placeholderTextColor: 'black',

  },

  searchButton: {
    backgroundColor: "#ef4444",
    padding: 10,
    borderRadius: 10,
    marginLeft: 8,
    justifyContent: "center",
    alignItems: "center",
  },

  // ESTATÍSTICA
  statsContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 18,
  },

  statCard: {
    flex: 1,
    backgroundColor: "white",
    marginHorizontal: 4,
    paddingVertical: 14,
    borderRadius: 12,
    alignItems: "center",
  },

  statNumber: {
    fontSize: 20,
    fontWeight: "700",
    color: "#111827",
    marginTop: 4,
  },

  statLabel: {
    color: "#6b7280",
    fontSize: 11,
    marginTop: 2,
  },

  // LIVROS
  bookCard: {
    flexDirection: "row",
    backgroundColor: "white",
    marginBottom: 12,
    padding: 12,
    borderRadius: 14,
  },

  cover: {
    width: 75,
    height: 110,
    borderRadius: 10,
    backgroundColor: "#e5e7eb",
  },

  bookInfo: {
    flex: 1,
    marginLeft: 12,
  },

  title: {
    fontSize: 16,
    fontWeight: "600",
    color: "#111827",
  },

  category: {
    fontSize: 12,
    color: "#6b7280",
    marginTop: 3,
  },

  authors: {
    fontSize: 12,
    color: "#9ca3af",
    marginTop: 2,
  },

  rowBetween: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginTop: 10,
  },

  availabilityRow: {
    flexDirection: "row",
    alignItems: "center",
  },

  badge: {
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },

  badgeAvailable: {
    backgroundColor: "#16a34a",
  },

  badgeUnavailable: {
    backgroundColor: "#dc2626",
  },

  badgeText: {
    color: "white",
    fontSize: 11,
    fontWeight: "600",
  },

  quantity: {
    color: "#6b7280",
    fontSize: 12,
    marginLeft: 10,
  },

  empty: {
    padding: 40,
    alignItems: "center",
  },

  emptyText: {
    color: "#9ca3af",
    fontSize: 16,
  },
});

