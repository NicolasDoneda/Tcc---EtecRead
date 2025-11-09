import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import api from '../../services/api';

export default function AdminDashboardScreen() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const response = await api.adminDashboard.get();
      if (response.success) {
        setData(response.data);
      }
    } catch (error) {
      console.error('Erro ao carregar dashboard:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadData();
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#007AFF" />
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }
    >
      <View style={styles.statsGrid}>
        <View style={[styles.statCard, styles.blueCard]}>
          <Text style={styles.statNumber}>{data?.stats.total_students}</Text>
          <Text style={styles.statLabel}>Total Alunos</Text>
        </View>

        <View style={[styles.statCard, styles.greenCard]}>
          <Text style={styles.statNumber}>{data?.stats.total_books}</Text>
          <Text style={styles.statLabel}>Total Livros</Text>
        </View>

        <View style={[styles.statCard, styles.purpleCard]}>
          <Text style={styles.statNumber}>{data?.stats.available_books}</Text>
          <Text style={styles.statLabel}>Disponíveis</Text>
        </View>

        <View style={[styles.statCard, styles.orangeCard]}>
          <Text style={styles.statNumber}>{data?.stats.active_loans}</Text>
          <Text style={styles.statLabel}>Empréstimos Ativos</Text>
        </View>

        <View style={[styles.statCard, styles.yellowCard]}>
          <Text style={styles.statNumber}>{data?.stats.pending_reservations}</Text>
          <Text style={styles.statLabel}>Reservas Pendentes</Text>
        </View>

        <View style={[styles.statCard, styles.redCard]}>
          <Text style={styles.statNumber}>{data?.stats.overdue_loans}</Text>
          <Text style={styles.statLabel}>Atrasados</Text>
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>📚 Livros Mais Emprestados</Text>
        {data?.top_books.map((book, index) => (
          <View key={book.id} style={styles.listItem}>
            <View style={styles.rankBadge}>
              <Text style={styles.rankText}>{index + 1}</Text>
            </View>
            <View style={styles.listInfo}>
              <Text style={styles.listTitle} numberOfLines={1}>
                {book.title}
              </Text>
              <Text style={styles.listSubtitle}>
                {book.loans_count} empréstimos
              </Text>
            </View>
          </View>
        ))}
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>🎓 Alunos por Ano</Text>
        {data?.students_by_year.map((item) => (
          <View key={item.year} style={styles.listItem}>
            <View style={styles.yearBadge}>
              <Text style={styles.yearText}>{item.year}</Text>
            </View>
            <View style={styles.listInfo}>
              <Text style={styles.listTitle}>{item.count} alunos</Text>
            </View>
          </View>
        ))}
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>📖 Empréstimos Recentes</Text>
        {data?.recent_loans.slice(0, 5).map((loan) => (
          <View key={loan.id} style={styles.loanCard}>
            <View style={styles.loanInfo}>
              <Text style={styles.loanStudent} numberOfLines={1}>
                {loan.student_name}
              </Text>
              <Text style={styles.loanBook} numberOfLines={1}>
                {loan.book_title}
              </Text>
              <Text style={styles.loanDate}>
                {new Date(loan.loan_date).toLocaleDateString('pt-BR')}
              </Text>
            </View>
            <View style={[
              styles.statusBadge,
              loan.status === 'ativo' ? styles.activeStatus : styles.finishedStatus
            ]}>
              <Text style={styles.statusText}>
                {loan.status === 'ativo' ? 'Ativo' : 'Finalizado'}
              </Text>
            </View>
          </View>
        ))}
      </View>
    </ScrollView>
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
  statsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 10,
  },
  statCard: {
    width: '48%',
    padding: 20,
    borderRadius: 15,
    margin: '1%',
    alignItems: 'center',
  },
  blueCard: { backgroundColor: '#2196F3' },
  greenCard: { backgroundColor: '#4CAF50' },
  purpleCard: { backgroundColor: '#9C27B0' },
  orangeCard: { backgroundColor: '#FF9800' },
  yellowCard: { backgroundColor: '#FFC107' },
  redCard: { backgroundColor: '#F44336' },
  statNumber: {
    fontSize: 32,
    fontWeight: 'bold',
    color: 'white',
  },
  statLabel: {
    fontSize: 12,
    color: 'white',
    marginTop: 5,
    textAlign: 'center',
  },
  section: {
    backgroundColor: 'white',
    margin: 10,
    padding: 15,
    borderRadius: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  listItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  rankBadge: {
    width: 30,
    height: 30,
    borderRadius: 15,
    backgroundColor: '#007AFF',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 10,
  },
  rankText: {
    color: 'white',
    fontWeight: 'bold',
  },
  yearBadge: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 15,
    backgroundColor: '#E3F2FD',
    marginRight: 10,
  },
  yearText: {
    color: '#1976D2',
    fontWeight: 'bold',
  },
  listInfo: {
    flex: 1,
  },
  listTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
  },
  listSubtitle: {
    fontSize: 12,
    color: '#999',
    marginTop: 2,
  },
  loanCard: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  loanInfo: {
    flex: 1,
  },
  loanStudent: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
  },
  loanBook: {
    fontSize: 13,
    color: '#666',
    marginTop: 2,
  },
  loanDate: {
    fontSize: 11,
    color: '#999',
    marginTop: 2,
  },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 12,
  },
  activeStatus: {
    backgroundColor: '#E8F5E9',
  },
  finishedStatus: {
    backgroundColor: '#E0E0E0',
  },
  statusText: {
    fontSize: 11,
    fontWeight: 'bold',
  },
});