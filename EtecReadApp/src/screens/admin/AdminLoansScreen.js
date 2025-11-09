import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import api from '../../services/api';

export default function AdminLoansScreen() {
  const [loans, setLoans] = useState([]);
  const [statistics, setStatistics] = useState(null);
  const [filter, setFilter] = useState('all');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadData();
  }, [filter]);

  const loadData = async () => {
    try {
      const filters = {};
      
      // CORREÇÃO: Não passar string "true", passar apenas os filtros necessários
      if (filter === 'ativo') filters.status = 'ativo';
      if (filter === 'finalizado') filters.status = 'finalizado';
      if (filter === 'overdue') filters.overdue = true; // Boolean direto, não string

      const [loansRes, statsRes] = await Promise.all([
        api.adminLoans.getAll(filters),
        api.adminLoans.getStatistics(),
      ]);

      if (loansRes.success) setLoans(loansRes.data);
      if (statsRes.success) setStatistics(statsRes.data);
    } catch (error) {
      console.error('Erro ao carregar empréstimos:', error);
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
        <Text style={[styles.statNumber, { color: '#4CAF50' }]}>
          {statistics?.active || 0}
        </Text>
        <Text style={styles.statLabel}>Ativos</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={[styles.statNumber, { color: '#F44336' }]}>
          {statistics?.overdue || 0}
        </Text>
        <Text style={styles.statLabel}>Atrasados</Text>
      </View>
      <View style={styles.statCard}>
        <Text style={[styles.statNumber, { color: '#9E9E9E' }]}>
          {statistics?.finished || 0}
        </Text>
        <Text style={styles.statLabel}>Finalizados</Text>
      </View>
    </View>
  );

  const renderLoan = ({ item }) => (
    <View style={styles.loanCard}>
      <View style={styles.loanHeader}>
        <View>
          <Text style={styles.studentName}>{item.student.name}</Text>
          <Text style={styles.studentInfo}>
            RM: {item.student.rm || 'N/A'} • {item.student.ano_escolar}º ano
          </Text>
        </View>
        <View style={[
          styles.statusBadge,
          item.is_overdue
            ? styles.overdueBadge
            : item.status === 'ativo'
            ? styles.activeBadge
            : styles.finishedBadge
        ]}>
          <Text style={styles.statusText}>
            {item.is_overdue ? 'ATRASADO' : item.status.toUpperCase()}
          </Text>
        </View>
      </View>

      <View style={styles.loanBody}>
        <Text style={styles.bookTitle} numberOfLines={2}>
          {item.book.title}
        </Text>
        <Text style={styles.bookCategory}>{item.book.category}</Text>
      </View>

      <View style={styles.loanFooter}>
        <View style={styles.dateContainer}>
          <Text style={styles.dateLabel}>Empréstimo:</Text>
          <Text style={styles.dateValue}>
            {new Date(item.loan_date).toLocaleDateString('pt-BR')}
          </Text>
        </View>
        <View style={styles.dateContainer}>
          <Text style={styles.dateLabel}>Devolução:</Text>
          <Text style={[
            styles.dateValue,
            item.is_overdue && styles.overdueDate
          ]}>
            {new Date(item.due_date).toLocaleDateString('pt-BR')}
          </Text>
        </View>
      </View>

      {item.is_overdue && (
        <View style={styles.overdueWarning}>
          <Text style={styles.overdueText}>
            ⚠️ {item.days_overdue} dias de atraso
          </Text>
        </View>
      )}
    </View>
  );

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#007AFF" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.filtersContainer}>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'all' && styles.activeTab]}
          onPress={() => setFilter('all')}
        >
          <Text style={[styles.filterText, filter === 'all' && styles.activeFilterText]}>
            Todos
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'ativo' && styles.activeTab]}
          onPress={() => setFilter('ativo')}
        >
          <Text style={[styles.filterText, filter === 'ativo' && styles.activeFilterText]}>
            Ativos
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'overdue' && styles.activeTab]}
          onPress={() => setFilter('overdue')}
        >
          <Text style={[styles.filterText, filter === 'overdue' && styles.activeFilterText]}>
            Atrasados
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.filterTab, filter === 'finalizado' && styles.activeTab]}
          onPress={() => setFilter('finalizado')}
        >
          <Text style={[styles.filterText, filter === 'finalizado' && styles.activeFilterText]}>
            Finalizados
          </Text>
        </TouchableOpacity>
      </View>

      <FlatList
        data={loans}
        renderItem={renderLoan}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={statistics && renderStatistics()}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.empty}>
            <Text style={styles.emptyText}>Nenhum empréstimo encontrado</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  loading: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  filtersContainer: {
    flexDirection: 'row',
    backgroundColor: 'white',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  filterTab: {
    flex: 1,
    padding: 12,
    alignItems: 'center',
  },
  activeTab: {
    borderBottomWidth: 3,
    borderBottomColor: '#007AFF',
  },
  filterText: {
    fontSize: 14,
    color: '#666',
  },
  activeFilterText: {
    color: '#007AFF',
    fontWeight: 'bold',
  },
  listContent: {
    padding: 15,
  },
  statsContainer: {
    flexDirection: 'row',
    marginBottom: 15,
  },
  statCard: {
    flex: 1,
    backgroundColor: 'white',
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
  statNumber: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#007AFF',
  },
  statLabel: {
    fontSize: 11,
    color: '#666',
    marginTop: 2,
  },
  loanCard: {
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 15,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  loanHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 12,
  },
  studentName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  studentInfo: {
    fontSize: 12,
    color: '#999',
    marginTop: 2,
  },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 12,
  },
  activeBadge: {
    backgroundColor: '#E8F5E9',
  },
  finishedBadge: {
    backgroundColor: '#E0E0E0',
  },
  overdueBadge: {
    backgroundColor: '#FFEBEE',
  },
  statusText: {
    fontSize: 10,
    fontWeight: 'bold',
    color: '#333',
  },
  loanBody: {
    marginBottom: 12,
  },
  bookTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#333',
    marginBottom: 4,
  },
  bookCategory: {
    fontSize: 13,
    color: '#666',
  },
  loanFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  dateContainer: {
    flex: 1,
  },
  dateLabel: {
    fontSize: 11,
    color: '#999',
  },
  dateValue: {
    fontSize: 13,
    color: '#333',
    fontWeight: '500',
    marginTop: 2,
  },
  overdueDate: {
    color: '#F44336',
    fontWeight: 'bold',
  },
  overdueWarning: {
    marginTop: 10,
    padding: 8,
    backgroundColor: '#FFEBEE',
    borderRadius: 6,
  },
  overdueText: {
    fontSize: 12,
    color: '#D32F2F',
    fontWeight: 'bold',
  },
  empty: {
    padding: 50,
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 16,
    color: '#999',
  },
});