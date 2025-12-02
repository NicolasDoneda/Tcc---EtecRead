import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
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

      {/* Status dos Empréstimos */}
      <View style={styles.section}>
        <View style={styles.sectionTitleRow}>
          <Book size={20} color="#111827" />
          <Text style={styles.sectionTitle}>Status dos Empréstimos</Text>
        </View>
        
        <View style={styles.metricsContainer}>
          <View style={styles.metricCard}>
            <CheckCircle size={32} color="#16a34a" />
            <Text style={styles.metricValue}>{loansData?.finished || 0}</Text>
            <Text style={styles.metricLabel}>Finalizados</Text>
          </View>

          <View style={styles.metricCard}>
            <Clock size={32} color="#2563eb" />
            <Text style={styles.metricValue}>{loansData?.active || 0}</Text>
            <Text style={styles.metricLabel}>Ativos</Text>
          </View>

          <View style={styles.metricCard}>
            <AlertTriangle size={32} color="#dc2626" />
            <Text style={styles.metricValue}>{loansData?.overdue || 0}</Text>
            <Text style={styles.metricLabel}>Atrasados</Text>
          </View>

          <View style={styles.metricCard}>
            <Calendar size={32} color="#9333ea" />
            <Text style={styles.metricValue}>{loansData?.total || 0}</Text>
            <Text style={styles.metricLabel}>Total</Text>
          </View>
        </View>
      </View>

      {/* Usuários Ativos */}
      <View style={styles.section}>
        <View style={styles.sectionTitleRow}>
          <Users size={20} color="#111827" />
          <Text style={styles.sectionTitle}>Usuários Ativos</Text>
        </View>
        
        <View style={styles.userCard}>
          <Users size={48} color="#2563eb" />
          <Text style={styles.userValue}>{stats.total_students || 0}</Text>
          <Text style={styles.userLabel}>Alunos Cadastrados</Text>
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
            </View>
            <View style={styles.catalogStatItem}>
              <View style={styles.catalogStatRow}>
                <Text style={styles.catalogStatLabel}>Total de Autores</Text>
                <Text style={styles.catalogStatValue}>{catalogStats.total_authors || 0}</Text>
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
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f7fb',
  },

  loading: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },

  loadingText: {
    marginTop: 10,
    color: '#6b7280',
    fontSize: 14,
  },

  header: {
    padding: 20,
    backgroundColor: 'white',
    borderBottomWidth: 1,
    borderBottomColor: '#e5e7eb',
  },

  headerTitleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },

  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#111827',
  },

  headerSubtitle: {
    fontSize: 14,
    color: '#6b7280',
    marginTop: 5,
  },

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

  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#111827',
  },

  userCard: {
    backgroundColor: '#eff6ff',
    padding: 24,
    borderRadius: 12,
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#bfdbfe',
  },

  userValue: {
    fontSize: 48,
    fontWeight: 'bold',
    color: '#1e40af',
    marginTop: 12,
  },

  userLabel: {
    fontSize: 16,
    color: '#3b82f6',
    marginTop: 6,
    fontWeight: '600',
  },

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

  metricValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#111827',
    marginTop: 8,
  },

  metricLabel: {
    fontSize: 12,
    color: '#6b7280',
    marginTop: 4,
    textAlign: 'center',
  },

  catalogStatsContainer: {
    gap: 12,
  },

  catalogStatItem: {
    marginBottom: 12,
  },

  catalogStatRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },

  catalogStatLabel: {
    fontSize: 14,
    color: '#6b7280',
  },

  catalogStatValue: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
  },
});