import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  FlatList,
  Image,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import api from '../../services/api';

export default function AdminReservationsScreen() {
  const [reservations, setReservations] = useState([]);
  const [statistics, setStatistics] = useState(null);
  const [filter, setFilter] = useState('all');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadData();
  }, [filter]);

  const loadData = async () => {
    try {
      const filters = filter !== 'all' ? { status: filter } : {};

      const [reservationsRes, statsRes] = await Promise.all([
        api.adminReservations.getAll(filters),
        api.adminReservations.getStatistics(),
      ]);

      if (reservationsRes.success) setReservations(reservationsRes.data);
      if (statsRes.success) setStatistics(statsRes.data);
    } catch (error) {
      console.error('Erro ao carregar reservas:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadData();
  };

  const renderStatistics = () => (
    <View style={styles.statsContainer}>
      <View style={styles.statCard}>
        <Text style={styles.statNumber}>{statistics?.total || 0}</Text>
        <Text style={styles.statLabel}>Total</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={[styles.statNumber, { color: '#F59E0B' }]}>
          {statistics?.pending || 0}
        </Text>
        <Text style={styles.statLabel}>Pendentes</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={[styles.statNumber, { color: '#10B981' }]}>
          {statistics?.confirmed || 0}
        </Text>
        <Text style={styles.statLabel}>Confirmadas</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={[styles.statNumber, { color: '#EF4444' }]}>
          {statistics?.cancelled || 0}
        </Text>
        <Text style={styles.statLabel}>Canceladas</Text>
      </View>
    </View>
  );

  const renderReservation = ({ item }) => (
    <View style={styles.reservationCard}>
      <View style={styles.cardHeader}>
        <View style={styles.studentSection}>
          {item.student.photo_url && (
            <Image
              source={{ uri: item.student.photo_url }}
              style={styles.studentPhoto}
            />
          )}
          <View style={styles.studentInfo}>
            <Text style={styles.studentName}>{item.student.name}</Text>
            <Text style={styles.studentDetails}>
              RM: {item.student.rm || 'N/A'} • {item.student.ano_escolar}º ano
            </Text>
          </View>
        </View>
        <View
          style={[
            styles.statusBadge,
            item.status === 'pendente' && styles.pendingBadge,
            item.status === 'confirmado' && styles.confirmedBadge,
            item.status === 'cancelado' && styles.cancelledBadge,
          ]}
        >
          <Text style={styles.statusText}>{item.status.toUpperCase()}</Text>
        </View>
      </View>

      <View style={styles.bookSection}>
        <Image
          source={{
            uri:
              item.book.cover_image || 'https://via.placeholder.com/50x75',
          }}
          style={styles.bookCover}
        />
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitle} numberOfLines={2}>
            {item.book.title}
          </Text>
          <Text style={styles.bookCategory}>{item.book.category}</Text>
          <Text
            style={[
              styles.availability,
              item.book.available_quantity > 0
                ? styles.available
                : styles.unavailable,
            ]}
          >
            {item.book.available_quantity > 0
              ? `✓ ${item.book.available_quantity} disponível(is)`
              : '✗ Indisponível'}
          </Text>
        </View>
      </View>

      <View style={styles.dateInfo}>
        <Text style={styles.dateText}>
          Reservado em: {new Date(item.reserved_at).toLocaleDateString('pt-BR')}
        </Text>
      </View>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#EF4444" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* --- Tabs / Filtros --- */}
      <View style={styles.filtersContainer}>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'all' && styles.activeTab]}
          onPress={() => setFilter('all')}
        >
          <Text
            style={[styles.filterText, filter === 'all' && styles.activeFilterText]}
          >
            Todas
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'pendente' && styles.activeTab]}
          onPress={() => setFilter('pendente')}
        >
          <Text
            style={[styles.filterText, filter === 'pendente' && styles.activeFilterText]}
          >
            Pendentes
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'confirmado' && styles.activeTab]}
          onPress={() => setFilter('confirmado')}
        >
          <Text
            style={[
              styles.filterText,
              filter === 'confirmado' && styles.activeFilterText,
            ]}
          >
            Confirmadas
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'cancelado' && styles.activeTab]}
          onPress={() => setFilter('cancelado')}
        >
          <Text
            style={[
              styles.filterText,
              filter === 'cancelado' && styles.activeFilterText,
            ]}
          >
            Canceladas
          </Text>
        </TouchableOpacity>
      </View>

      {/* --- Lista de reservas --- */}
      <FlatList
        data={reservations}
        renderItem={renderReservation}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={statistics && renderStatistics()}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.empty}>
            <Text style={styles.emptyText}>Nenhuma reserva encontrada</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  filtersContainer: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  filterTab: {
    flex: 1,
    padding: 12,
    alignItems: 'center',
  },
  activeTab: {
    backgroundColor: '#EF4444',
    borderRadius: 8,
  },
  filterText: {
    fontSize: 13,
    color: '#B91C1C',
  },
  activeFilterText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  listContent: { padding: 15 },
  statsContainer: { flexDirection: 'row', marginBottom: 15 },
  statCard: {
    flex: 1,
    backgroundColor: '#fff',
    padding: 12,
    borderRadius: 10,
    marginHorizontal: 3,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 2,
    elevation: 2,
  },
  statNumber: { fontSize: 24, fontWeight: 'bold', color: '#EF4444' },
  statLabel: { fontSize: 11, color: '#666', marginTop: 2 },
  reservationCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 15,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cardHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 15 },
  studentSection: { flexDirection: 'row', alignItems: 'center', flex: 1 },
  studentPhoto: { width: 40, height: 40, borderRadius: 20, marginRight: 10 },
  studentInfo: { flex: 1 },
  studentName: { fontSize: 15, fontWeight: 'bold', color: '#333' },
  studentDetails: { fontSize: 11, color: '#999', marginTop: 2 },
  statusBadge: { paddingHorizontal: 10, paddingVertical: 5, borderRadius: 12 },
  pendingBadge: { backgroundColor: '#FFF3E0' },
  confirmedBadge: { backgroundColor: '#E8F5E9' },
  cancelledBadge: { backgroundColor: '#FFEBEE' },
  statusText: { fontSize: 10, fontWeight: 'bold' },
  bookSection: { flexDirection: 'row', marginBottom: 12 },
  bookCover: { width: 50, height: 75, borderRadius: 5, marginRight: 12 },
  bookInfo: { flex: 1, justifyContent: 'center' },
  bookTitle: { fontSize: 14, fontWeight: '600', color: '#333', marginBottom: 4 },
  bookCategory: { fontSize: 12, color: '#666', marginBottom: 4 },
  availability: { fontSize: 11, fontWeight: 'bold' },
  available: { color: '#4CAF50' },
  unavailable: { color: '#F44336' },
  dateInfo: { paddingTop: 10, borderTopWidth: 1, borderTopColor: '#f0f0f0' },
  dateText: { fontSize: 11, color: '#999' },
  empty: { padding: 50, alignItems: 'center' },
  emptyText: { fontSize: 16, color: '#999' },
});
