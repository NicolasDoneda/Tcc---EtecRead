// src/screens/student/MyLoansScreen.js
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, Image, TouchableOpacity, StyleSheet, ActivityIndicator, RefreshControl } from 'react-native';
import api from '../../services/api';

export default function MyLoansScreen() {
  const [activeLoans, setActiveLoans] = useState([]);
  const [history, setHistory] = useState([]);
  const [summary, setSummary] = useState(null);
  const [tab, setTab] = useState('active');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const [activeRes, historyRes, summaryRes] = await Promise.all([
        api.myLoans.getActive(),
        api.myLoans.getHistory(),
        api.myLoans.getSummary(),
      ]);
      if (activeRes.success) setActiveLoans(activeRes.data);
      if (historyRes.success) setHistory(historyRes.data);
      if (summaryRes.success) setSummary(summaryRes.data);
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

  const renderSummary = () => (
    <View style={styles.summaryContainer}>
      <View style={styles.summaryCard}>
        <Text style={styles.summaryNumber}>{summary?.active_loans || 0}</Text>
        <Text style={styles.summaryLabel}>Ativos</Text>
      </View>
      <View style={styles.summaryCard}>
        <Text style={[styles.summaryNumber, styles.overdueNumber]}>{summary?.overdue_loans || 0}</Text>
        <Text style={styles.summaryLabel}>Atrasados</Text>
      </View>
      <View style={styles.summaryCard}>
        <Text style={styles.summaryNumber}>{summary?.available_slots || 0}</Text>
        <Text style={styles.summaryLabel}>Disponíveis</Text>
      </View>
    </View>
  );

  const renderLoan = ({ item }) => {
    const isOverdue = item.is_overdue;
    const daysText = item.days_remaining >= 0 ? `${item.days_remaining} dias restantes` : `${Math.abs(item.days_remaining)} dias de atraso`;
    return (
      <View style={styles.loanCard}>
        <Image source={{ uri: item.book.cover_image || 'https://via.placeholder.com/60x90' }} style={styles.bookCover} />
        <View style={styles.loanInfo}>
          <Text style={styles.bookTitle} numberOfLines={2}>{item.book.title}</Text>
          <Text style={styles.bookCategory}>{item.book.category}</Text>
          {tab === 'active' ? (
            <>
              <Text style={styles.date}>Empréstimo: {new Date(item.loan_date).toLocaleDateString('pt-BR')}</Text>
              <Text style={styles.date}>Devolução: {new Date(item.due_date).toLocaleDateString('pt-BR')}</Text>
              <View style={[styles.statusBadge, isOverdue ? styles.overdueBadge : styles.activeBadge]}>
                <Text style={styles.statusText}>{isOverdue ? '⚠️ Atrasado' : '✓ No prazo'}</Text>
                <Text style={styles.daysText}>{daysText}</Text>
              </View>
            </>
          ) : (
            <>
              <Text style={styles.date}>Empréstimo: {new Date(item.loan_date).toLocaleDateString('pt-BR')}</Text>
              <Text style={styles.date}>Devolução: {new Date(item.return_date).toLocaleDateString('pt-BR')}</Text>
              {item.was_late && (
                <View style={styles.lateWarning}>
                  <Text style={styles.lateText}>⚠️ Devolvido com atraso</Text>
                </View>
              )}
            </>
          )}
        </View>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#007AFF" />
      </View>
    );
  }

  const data = tab === 'active' ? activeLoans : history;

  return (
    <View style={styles.container}>
      <View style={styles.tabContainer}>
        <TouchableOpacity style={[styles.tab, tab === 'active' && styles.activeTab]} onPress={() => setTab('active')}>
          <Text style={[styles.tabText, tab === 'active' && styles.activeTabText]}>Ativos ({activeLoans.length})</Text>
        </TouchableOpacity>
        <TouchableOpacity style={[styles.tab, tab === 'history' && styles.activeTab]} onPress={() => setTab('history')}>
          <Text style={[styles.tabText, tab === 'history' && styles.activeTabText]}>Histórico ({history.length})</Text>
        </TouchableOpacity>
      </View>
      <FlatList data={data} renderItem={renderLoan} keyExtractor={(item) => item.id.toString()} ListHeaderComponent={summary && renderSummary()} contentContainerStyle={styles.listContent} refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />} ListEmptyComponent={<View style={styles.empty}><Text style={styles.emptyText}>{tab === 'active' ? 'Você não tem empréstimos ativos' : 'Seu histórico está vazio'}</Text></View>} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  tabContainer: { flexDirection: 'row', backgroundColor: 'white', borderBottomWidth: 1, borderBottomColor: '#ddd' },
  tab: { flex: 1, padding: 15, alignItems: 'center' },
  activeTab: { borderBottomWidth: 3, borderBottomColor: '#007AFF' },
  tabText: { fontSize: 16, color: '#666' },
  activeTabText: { color: '#007AFF', fontWeight: 'bold' },
  listContent: { padding: 15 },
  summaryContainer: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 15 },
  summaryCard: { flex: 1, backgroundColor: 'white', padding: 15, borderRadius: 10, marginHorizontal: 5, alignItems: 'center' },
  summaryNumber: { fontSize: 28, fontWeight: 'bold', color: '#007AFF' },
  overdueNumber: { color: '#F44336' },
  summaryLabel: { fontSize: 12, color: '#666', marginTop: 5 },
  loanCard: { flexDirection: 'row', backgroundColor: 'white', borderRadius: 10, padding: 12, marginBottom: 10, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.1, shadowRadius: 4, elevation: 3 },
  bookCover: { width: 60, height: 90, borderRadius: 5 },
  loanInfo: { flex: 1, marginLeft: 12 },
  bookTitle: { fontSize: 16, fontWeight: 'bold', color: '#333', marginBottom: 4 },
  bookCategory: { fontSize: 13, color: '#666', marginBottom: 8 },
  date: { fontSize: 12, color: '#999', marginBottom: 2 },
  statusBadge: { marginTop: 8, padding: 8, borderRadius: 5 },
  activeBadge: { backgroundColor: '#E8F5E9' },
  overdueBadge: { backgroundColor: '#FFEBEE' },
  statusText: { fontSize: 12, fontWeight: 'bold' },
  daysText: { fontSize: 11, marginTop: 2 },
  lateWarning: { marginTop: 8, padding: 6, backgroundColor: '#FFF3E0', borderRadius: 5 },
  lateText: { fontSize: 11, color: '#F57C00' },
  empty: { padding: 50, alignItems: 'center' },
  emptyText: { fontSize: 16, color: '#999', textAlign: 'center' },
});