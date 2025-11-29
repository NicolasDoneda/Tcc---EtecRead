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
import { BarChart3, Book, CheckCircle, Clock, AlertTriangle, Users, FolderOpen, Calendar } from 'lucide-react-native';
import api from '../../services/api';

export default function AdminReportsScreen() {
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [dashboardData, setDashboardData] = useState(null);
  const [loansData, setLoansData] = useState(null);
  const [catalogStats, setCatalogStats] = useState(null);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const [dashboardRes, loansRes, catalogRes] = await Promise.all([
        api.adminDashboard.get(),
        api.adminLoans.getStatistics(),
        api.catalog.getStatistics(),
      ]);

      if (dashboardRes?.success) setDashboardData(dashboardRes.data);
      if (loansRes?.success) setLoansData(loansRes.data);
      if (catalogRes?.success) setCatalogStats(catalogRes.data);
    } catch (error) {
      console.error('Erro ao carregar relatórios:', error);
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
        <ActivityIndicator size="large" color="#dc2626" />
        <Text style={styles.loadingText}>Carregando relatórios...</Text>
      </View>
    );
  }

  const stats = dashboardData?.stats || {};

  return (
    <ScrollView 
      style={styles.container} 
      contentContainerStyle={{ paddingBottom: 20 }}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      {/* Header */}
      <View style={styles.header}>
        <View style={styles.headerTitleRow}>
          <BarChart3 size={28} color="#dc2626" />
          <Text style={styles.headerTitle}>Relatórios</Text>
        </View>
        <Text style={styles.headerSubtitle}>Análise detalhada e estatísticas do sistema</Text>
      </View>

      {/* Overview Stats Grid */}
      <View style={styles.statsGrid}>
        <View style={[styles.statCard, { backgroundColor: '#dc2626' }]}>
          <Text style={styles.statNumber}>{stats.total_loans || 0}</Text>
          <Text style={styles.statLabel}>Total de Empréstimos</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#2563eb' }]}>
          <Text style={styles.statNumber}>{stats.active_loans || 0}</Text>
          <Text style={styles.statLabel}>Empréstimos Ativos</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#16a34a' }]}>
          <Text style={styles.statNumber}>{catalogStats?.total_books || 0}</Text>
          <Text style={styles.statLabel}>Total de Livros</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#9333ea' }]}>
          <Text style={styles.statNumber}>{catalogStats?.total_categories || 0}</Text>
          <Text style={styles.statLabel}>Categorias</Text>
        </View>
      </View>

      {/* Status dos Empréstimos */}
      <View style={styles.section}>
        <View style={styles.sectionTitleRow}>
          <Book size={20} color="#111827" />
          <Text style={styles.sectionTitle}>Status dos Empréstimos</Text>
        </View>
        
        <View style={styles.metricsContainer}>
          <View style={styles.metricCard}>
            <CheckCircle size={32} color="#16a34a" />
            <Text style={styles.metricValue}>{stats.returned_loans || 0}</Text>
            <Text style={styles.metricLabel}>Devolvidos</Text>
          </View>

          <View style={styles.metricCard}>
            <Clock size={32} color="#f59e0b" />
            <Text style={styles.metricValue}>{stats.pending_loans || 0}</Text>
            <Text style={styles.metricLabel}>Pendentes</Text>
          </View>

          <View style={styles.metricCard}>
            <AlertTriangle size={32} color="#dc2626" />
            <Text style={styles.metricValue}>{stats.overdue_loans || 0}</Text>
            <Text style={styles.metricLabel}>Atrasados</Text>
          </View>

          <View style={styles.metricCard}>
            <Calendar size={32} color="#2563eb" />
            <Text style={styles.metricValue}>{stats.active_loans || 0}</Text>
            <Text style={styles.metricLabel}>Em Andamento</Text>
          </View>
        </View>
      </View>

      {/* Estatísticas do Catálogo */}
      {catalogStats && (
        <View style={styles.section}>
          <View style={styles.sectionTitleRow}>
            <FolderOpen size={20} color="#111827" />
            <Text style={styles.sectionTitle}>Estatísticas do Catálogo</Text>
          </View>
          
          <View style={styles.catalogStatsContainer}>
            <View style={styles.catalogStatItem}>
              <View style={styles.catalogStatRow}>
                <Text style={styles.catalogStatLabel}>Livros Disponíveis</Text>
                <Text style={styles.catalogStatValue}>{catalogStats.available_books || 0}</Text>
              </View>
              <View style={styles.progressBarContainer}>
                <View 
                  style={[
                    styles.progressBar, 
                    { 
                      width: `${((catalogStats.available_books || 0) / (catalogStats.total_books || 1)) * 100}%`,
                      backgroundColor: '#16a34a'
                    }
                  ]} 
                />
              </View>
            </View>

            <View style={styles.catalogStatItem}>
              <View style={styles.catalogStatRow}>
                <Text style={styles.catalogStatLabel}>Livros Emprestados</Text>
                <Text style={styles.catalogStatValue}>
                  {(catalogStats.total_books || 0) - (catalogStats.available_books || 0)}
                </Text>
              </View>
              <View style={styles.progressBarContainer}>
                <View 
                  style={[
                    styles.progressBar, 
                    { 
                      width: `${(((catalogStats.total_books || 0) - (catalogStats.available_books || 0)) / (catalogStats.total_books || 1)) * 100}%`,
                      backgroundColor: '#dc2626'
                    }
                  ]} 
                />
              </View>
            </View>

            <View style={styles.catalogStatItem}>
              <View style={styles.catalogStatRow}>
                <Text style={styles.catalogStatLabel}>Total de Categorias</Text>
                <Text style={styles.catalogStatValue}>{catalogStats.total_categories || 0}</Text>
              </View>
            </View>
          </View>
        </View>
      )}

      {/* Empréstimos Recentes */}
      {dashboardData?.recent_loans && dashboardData.recent_loans.length > 0 && (
        <View style={styles.section}>
          <View style={styles.sectionTitleRow}>
            <Clock size={20} color="#111827" />
            <Text style={styles.sectionTitle}>Empréstimos Recentes</Text>
          </View>
          
          {dashboardData.recent_loans.slice(0, 5).map((loan, index) => (
            <View key={loan.id || index} style={styles.loanCard}>
              <View style={styles.loanHeader}>
                <Text style={styles.loanTitle} numberOfLines={1}>
                  {loan.book_title || 'Livro sem título'}
                </Text>
                <View style={[
                  styles.statusBadge,
                  loan.status === 'returned' && styles.statusReturned,
                  loan.status === 'active' && styles.statusActive,
                  loan.status === 'overdue' && styles.statusOverdue,
                ]}>
                  <Text style={styles.statusText}>
                    {loan.status === 'returned' ? 'Devolvido' : 
                     loan.status === 'active' ? 'Ativo' : 
                     loan.status === 'overdue' ? 'Atrasado' : loan.status}
                  </Text>
                </View>
              </View>
              
              <Text style={styles.loanStudent}>{loan.student_name || 'Aluno desconhecido'}</Text>
              
              <View style={styles.loanDates}>
                <Text style={styles.loanDate}>
                  Empréstimo: {loan.loan_date ? new Date(loan.loan_date).toLocaleDateString('pt-BR') : '-'}
                </Text>
                <Text style={styles.loanDate}>
                  Devolução: {loan.return_date ? new Date(loan.return_date).toLocaleDateString('pt-BR') : '-'}
                </Text>
              </View>
            </View>
          ))}
        </View>
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f7fb' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  loadingText: { marginTop: 10, color: '#6b7280', fontSize: 14 },
  
  header: { 
    padding: 20, 
    backgroundColor: 'white', 
    borderBottomWidth: 1, 
    borderBottomColor: '#e5e7eb' 
  },
  headerTitleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },
  headerTitle: { fontSize: 24, fontWeight: 'bold', color: '#111827' },
  headerSubtitle: { fontSize: 14, color: '#6b7280', marginTop: 5 },
  
  statsGrid: { 
    flexDirection: 'row', 
    flexWrap: 'wrap', 
    padding: 12, 
    justifyContent: 'space-between' 
  },
  statCard: { 
    width: '48%', 
    borderRadius: 12, 
    padding: 18, 
    marginBottom: 12, 
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  statNumber: { fontSize: 32, fontWeight: 'bold', color: 'white' },
  statLabel: { fontSize: 11, color: 'white', marginTop: 6, textAlign: 'center' },
  
  section: {
    margin: 12,
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
  },
  sectionTitleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    marginBottom: 16,
  },
  sectionTitle: { fontSize: 18, fontWeight: 'bold', color: '#111827' },
  
  metricsContainer: { 
    flexDirection: 'row', 
    flexWrap: 'wrap', 
    justifyContent: 'space-between',
  },
  metricCard: { 
    width: '48%', 
    backgroundColor: '#f9fafb', 
    padding: 16, 
    borderRadius: 10, 
    marginBottom: 10, 
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  metricValue: { fontSize: 28, fontWeight: 'bold', color: '#111827', marginTop: 8 },
  metricLabel: { fontSize: 12, color: '#6b7280', marginTop: 4, textAlign: 'center' },
  
  catalogStatsContainer: { gap: 12 },
  catalogStatItem: { marginBottom: 12 },
  catalogStatRow: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 8 },
  catalogStatLabel: { fontSize: 14, color: '#6b7280' },
  catalogStatValue: { fontSize: 16, fontWeight: 'bold', color: '#111827' },
  progressBarContainer: {
    height: 8,
    backgroundColor: '#e5e7eb',
    borderRadius: 4,
    overflow: 'hidden',
  },
  progressBar: { height: '100%', borderRadius: 4 },
  
  loanCard: {
    backgroundColor: '#f9fafb',
    padding: 14,
    borderRadius: 10,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  loanHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 },
  loanTitle: { fontSize: 15, fontWeight: '600', color: '#111827', flex: 1, marginRight: 10 },
  statusBadge: { paddingHorizontal: 8, paddingVertical: 4, borderRadius: 12 },
  statusReturned: { backgroundColor: '#16a34a' },
  statusActive: { backgroundColor: '#2563eb' },
  statusOverdue: { backgroundColor: '#dc2626' },
  statusText: { color: 'white', fontSize: 11, fontWeight: 'bold' },
  loanStudent: { fontSize: 13, color: '#6b7280', marginBottom: 6 },
  loanDates: { flexDirection: 'row', justifyContent: 'space-between' },
  loanDate: { fontSize: 11, color: '#9ca3af' },
});