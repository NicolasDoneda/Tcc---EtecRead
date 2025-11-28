import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { Calendar, Book, AlertCircle, CheckCircle } from 'lucide-react-native';
import api from '../../services/api';

export default function MyLoansScreen() {
  const [activeLoans, setActiveLoans] = useState([]);
  const [history, setHistory] = useState([]);
  const [summary, setSummary] = useState(null);
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

  const calculateDaysLeft = (dueDate) => {
    const today = new Date();
    const due = new Date(dueDate);
    const diffTime = due - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#dc2626" />
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
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>Meus Empréstimos</Text>
        <Text style={styles.subtitle}>Gerencie seus livros</Text>
      </View>

      {/* Summary Cards */}
      <View style={styles.summaryGrid}>
        <View style={styles.summaryCard}>
          <View style={[styles.iconBox, styles.iconBoxRed]}>
            <Book size={20} color="#fff" />
          </View>
          <Text style={styles.summaryLabel}>Empréstimos Ativos</Text>
          <Text style={styles.summaryValue}>{activeLoans.length}</Text>
        </View>

        <View style={styles.summaryCard}>
          <View style={[styles.iconBox, styles.iconBoxGray]}>
            <CheckCircle size={20} color="#fff" />
          </View>
          <Text style={styles.summaryLabel}>Total Devolvidos</Text>
          <Text style={styles.summaryValue}>{history.length}</Text>
        </View>
      </View>

      {/* Active Loans Section */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Ativos</Text>
        <View style={styles.loansContainer}>
          {activeLoans.length === 0 ? (
            <View style={styles.emptyState}>
              <Text style={styles.emptyText}>
                Você não tem empréstimos ativos
              </Text>
            </View>
          ) : (
            activeLoans.map((loan) => {
              const daysLeft = calculateDaysLeft(loan.due_date);
              const isUrgent = daysLeft <= 2;
              const progress = ((14 - daysLeft) / 14) * 100;

              return (
                <View key={loan.id} style={styles.loanCard}>
                  <View style={styles.loanHeader}>
                    <View style={styles.loanHeaderLeft}>
                      <Text style={styles.bookTitle} numberOfLines={2}>
                        {loan.book.title}
                      </Text>
                      <Text style={styles.bookAuthor} numberOfLines={1}>
                        {loan.book.author}
                      </Text>
                      <View style={styles.badge}>
                        <Text style={styles.badgeText}>#{loan.id}</Text>
                      </View>
                    </View>
                    {isUrgent ? (
                      <AlertCircle size={20} color="#dc2626" />
                    ) : (
                      <CheckCircle size={20} color="#16a34a" />
                    )}
                  </View>

                  <View style={styles.loanBody}>
                    {/* Progress Bar */}
                    <View style={styles.progressSection}>
                      <View style={styles.progressHeader}>
                        <Text style={styles.progressLabel}>Prazo restante</Text>
                        <Text
                          style={[
                            styles.progressValue,
                            isUrgent && styles.progressValueUrgent,
                          ]}
                        >
                          {daysLeft} {daysLeft === 1 ? 'dia' : 'dias'}
                        </Text>
                      </View>
                      <View style={styles.progressBar}>
                        <View
                          style={[
                            styles.progressFill,
                            { width: `${Math.min(progress, 100)}%` },
                            isUrgent && styles.progressFillUrgent,
                          ]}
                        />
                      </View>
                    </View>

                    {/* Dates */}
                    <View style={styles.datesRow}>
                      <Calendar size={12} color="#6b7280" />
                      <Text style={styles.datesText}>
                        Empréstimo: {new Date(loan.loan_date).toLocaleDateString('pt-BR')} • 
                        Devolução: {new Date(loan.due_date).toLocaleDateString('pt-BR')}
                      </Text>
                    </View>

            
                  </View>
                </View>
              );
            })
          )}
        </View>
      </View>

      {/* History Section */}
      <View style={styles.historyCard}>
        <Text style={styles.historyTitle}>Histórico</Text>
        <View style={styles.historyList}>
          {history.length === 0 ? (
            <View style={styles.emptyState}>
              <Text style={styles.emptyText}>Seu histórico está vazio</Text>
            </View>
          ) : (
            history.map((loan, index) => (
              <View key={index} style={styles.historyItem}>
                <View style={styles.historyItemLeft}>
                  <View style={styles.historyIconBox}>
                    <CheckCircle size={20} color="#16a34a" />
                  </View>
                  <View style={styles.historyInfo}>
                    <Text style={styles.historyBookTitle} numberOfLines={1}>
                      {loan.book.title}
                    </Text>
                    <Text style={styles.historyBookAuthor} numberOfLines={1}>
                      {loan.book.author}
                    </Text>
                  </View>
                </View>
                <Text style={styles.historyDate}>
                  Dev. {new Date(loan.return_date).toLocaleDateString('pt-BR')}
                </Text>
              </View>
            ))
          )}
        </View>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f3f4f6',
  },
  header: {
    padding: 24,
    paddingTop: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 4,
  },
  subtitle: {
    fontSize: 14,
    color: '#6b7280',
  },
  summaryGrid: {
    flexDirection: 'row',
    paddingHorizontal: 24,
    gap: 12,
    marginBottom: 24,
  },
  summaryCard: {
    flex: 1,
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  iconBox: {
    width: 40,
    height: 40,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 8,
  },
  iconBoxRed: {
    backgroundColor: '#dc2626',
  },
  iconBoxGray: {
    backgroundColor: '#374151',
  },
  summaryLabel: {
    fontSize: 12,
    color: '#6b7280',
    marginBottom: 4,
  },
  summaryValue: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#111827',
  },
  section: {
    paddingHorizontal: 24,
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 12,
  },
  loansContainer: {
    gap: 12,
  },
  loanCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  loanHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 12,
  },
  loanHeaderLeft: {
    flex: 1,
    marginRight: 12,
  },
  bookTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 4,
  },
  bookAuthor: {
    fontSize: 14,
    color: '#6b7280',
    marginBottom: 8,
  },
  badge: {
    alignSelf: 'flex-start',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: '#d1d5db',
    backgroundColor: '#fff',
  },
  badgeText: {
    fontSize: 12,
    color: '#374151',
  },
  loanBody: {
    gap: 12,
  },
  progressSection: {
    gap: 4,
  },
  progressHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  progressLabel: {
    fontSize: 12,
    color: '#6b7280',
  },
  progressValue: {
    fontSize: 12,
    fontWeight: '600',
    color: '#111827',
  },
  progressValueUrgent: {
    color: '#dc2626',
  },
  progressBar: {
    height: 8,
    backgroundColor: '#e5e7eb',
    borderRadius: 4,
    overflow: 'hidden',
  },
  progressFill: {
    height: '100%',
    backgroundColor: '#16a34a',
    borderRadius: 4,
  },
  progressFillUrgent: {
    backgroundColor: '#dc2626',
  },
  datesRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  datesText: {
    fontSize: 12,
    color: '#6b7280',
    flex: 1,
  },
  renewButton: {
    backgroundColor: '#374151',
    paddingVertical: 10,
    borderRadius: 8,
    alignItems: 'center',
  },
  renewButtonUrgent: {
    backgroundColor: '#dc2626',
  },
  renewButtonText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: '600',
  },
  historyCard: {
    backgroundColor: '#fff',
    marginHorizontal: 24,
    marginBottom: 24,
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  historyTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 12,
  },
  historyList: {
    gap: 8,
  },
  historyItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 12,
    backgroundColor: '#f9fafb',
    borderRadius: 8,
  },
  historyItemLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
    gap: 12,
  },
  historyIconBox: {
    width: 40,
    height: 40,
    backgroundColor: '#dcfce7',
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
  },
  historyInfo: {
    flex: 1,
  },
  historyBookTitle: {
    fontSize: 14,
    fontWeight: '500',
    color: '#111827',
    marginBottom: 2,
  },
  historyBookAuthor: {
    fontSize: 12,
    color: '#6b7280',
  },
  historyDate: {
    fontSize: 12,
    color: '#9ca3af',
  },
  emptyState: {
    padding: 32,
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 14,
    color: '#9ca3af',
    textAlign: 'center',
  },
});