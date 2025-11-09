import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
  Dimensions,
} from 'react-native';
import api from '../../services/api';

const { width } = Dimensions.get('window');

export default function AdminReportsScreen() {
  const [loading, setLoading] = useState(true);
  const [selectedPeriod, setSelectedPeriod] = useState('monthly');
  const [selectedMonth, setSelectedMonth] = useState(new Date().getMonth() + 1);
  const [selectedYear, setSelectedYear] = useState(new Date().getFullYear());
  const [reportData, setReportData] = useState(null);
  const [overview, setOverview] = useState(null);

  useEffect(() => {
    loadData();
  }, [selectedPeriod, selectedMonth, selectedYear]);

  const loadData = async () => {
    setLoading(true);
    try {
      const [overviewRes, reportRes] = await Promise.all([
        api.adminReports.getOverview(),
        selectedPeriod === 'monthly'
          ? api.adminReports.getMonthly(selectedMonth, selectedYear)
          : api.adminReports.getOverview(),
      ]);

      if (overviewRes.success) setOverview(overviewRes.data);
      if (reportRes.success) setReportData(reportRes.data);
    } catch (error) {
      console.error('Erro ao carregar relatórios:', error);
      Alert.alert('Erro', 'Não foi possível carregar os relatórios');
    } finally {
      setLoading(false);
    }
  };

  const handleDownloadPDF = async () => {
    Alert.alert(
      'Exportar Relatório',
      `Deseja exportar o relatório de ${getMonthName(selectedMonth)}/${selectedYear} em PDF?`,
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Exportar',
          onPress: async () => {
            try {
              const response = await api.adminReports.downloadPDF(
                selectedMonth,
                selectedYear
              );
              if (response.success) {
                Alert.alert('Sucesso', 'Relatório exportado com sucesso!');
              }
            } catch (error) {
              Alert.alert('Erro', 'Não foi possível exportar o relatório');
            }
          },
        },
      ]
    );
  };

  const getMonthName = (month) => {
    const months = [
      'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    return months[month - 1];
  };

  const renderMonthSelector = () => (
    <View style={styles.periodSelector}>
      <ScrollView horizontal showsHorizontalScrollIndicator={false}>
        {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map((month) => (
          <TouchableOpacity
            key={month}
            style={[
              styles.monthChip,
              selectedMonth === month && styles.monthChipActive,
            ]}
            onPress={() => setSelectedMonth(month)}
          >
            <Text
              style={[
                styles.monthText,
                selectedMonth === month && styles.monthTextActive,
              ]}
            >
              {getMonthName(month)}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>
    </View>
  );

  const renderOverview = () => (
    <View style={styles.overviewContainer}>
      <Text style={styles.sectionTitle}>📊 Visão Geral</Text>
      
      <View style={styles.statsGrid}>
        <View style={[styles.statCard, { backgroundColor: '#2196F3' }]}>
          <Text style={styles.statNumber}>{overview?.total_books || 0}</Text>
          <Text style={styles.statLabel}>Livros Cadastrados</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#4CAF50' }]}>
          <Text style={styles.statNumber}>{overview?.total_students || 0}</Text>
          <Text style={styles.statLabel}>Alunos Ativos</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#FF9800' }]}>
          <Text style={styles.statNumber}>{overview?.total_loans || 0}</Text>
          <Text style={styles.statLabel}>Total Empréstimos</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#9C27B0' }]}>
          <Text style={styles.statNumber}>{overview?.total_categories || 0}</Text>
          <Text style={styles.statLabel}>Categorias</Text>
        </View>
      </View>
    </View>
  );

  const renderMonthlyReport = () => {
    if (!reportData) return null;

    return (
      <View style={styles.reportContainer}>
        <View style={styles.reportHeader}>
          <Text style={styles.reportTitle}>
            📈 Relatório de {getMonthName(selectedMonth)}/{selectedYear}
          </Text>
          <TouchableOpacity
            style={styles.exportButton}
            onPress={handleDownloadPDF}
          >
            <Text style={styles.exportButtonText}>📥 PDF</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.metricsContainer}>
          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>{reportData.loans_count || 0}</Text>
            <Text style={styles.metricLabel}>Empréstimos</Text>
            <Text style={styles.metricChange}>
              {reportData.loans_change >= 0 ? '↑' : '↓'}{' '}
              {Math.abs(reportData.loans_change || 0)}%
            </Text>
          </View>

          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>{reportData.returns_count || 0}</Text>
            <Text style={styles.metricLabel}>Devoluções</Text>
            <Text style={styles.metricChange}>
              {reportData.returns_change >= 0 ? '↑' : '↓'}{' '}
              {Math.abs(reportData.returns_change || 0)}%
            </Text>
          </View>

          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>{reportData.overdue_count || 0}</Text>
            <Text style={styles.metricLabel}>Atrasos</Text>
            <Text style={[styles.metricChange, styles.metricChangeNegative]}>
              {reportData.overdue_change >= 0 ? '↑' : '↓'}{' '}
              {Math.abs(reportData.overdue_change || 0)}%
            </Text>
          </View>

          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>{reportData.new_students || 0}</Text>
            <Text style={styles.metricLabel}>Novos Alunos</Text>
            <Text style={styles.metricChange}>
              {reportData.students_change >= 0 ? '↑' : '↓'}{' '}
              {Math.abs(reportData.students_change || 0)}%
            </Text>
          </View>
        </View>

        {/* Top Books */}
        <View style={styles.listSection}>
          <Text style={styles.listTitle}>📚 Livros Mais Emprestados</Text>
          {reportData.top_books?.slice(0, 5).map((book, index) => (
            <View key={book.id} style={styles.listItem}>
              <View style={styles.rankBadge}>
                <Text style={styles.rankText}>{index + 1}</Text>
              </View>
              <View style={styles.listInfo}>
                <Text style={styles.listItemTitle} numberOfLines={1}>
                  {book.title}
                </Text>
                <Text style={styles.listItemSubtitle}>
                  {book.loans_count} empréstimos
                </Text>
              </View>
            </View>
          ))}
        </View>

        {/* Top Categories */}
        <View style={styles.listSection}>
          <Text style={styles.listTitle}>📂 Categorias Mais Populares</Text>
          {reportData.top_categories?.slice(0, 5).map((category, index) => (
            <View key={category.id} style={styles.listItem}>
              <View style={styles.categoryBadge}>
                <Text style={styles.categoryText}>{category.name}</Text>
              </View>
              <Text style={styles.categoryCount}>
                {category.loans_count} empréstimos
              </Text>
            </View>
          ))}
        </View>

        {/* Most Active Students */}
        <View style={styles.listSection}>
          <Text style={styles.listTitle}>🎓 Alunos Mais Ativos</Text>
          {reportData.top_students?.slice(0, 5).map((student, index) => (
            <View key={student.id} style={styles.listItem}>
              <View style={styles.rankBadge}>
                <Text style={styles.rankText}>{index + 1}</Text>
              </View>
              <View style={styles.listInfo}>
                <Text style={styles.listItemTitle}>{student.name}</Text>
                <Text style={styles.listItemSubtitle}>
                  {student.loans_count} empréstimos • RM: {student.rm}
                </Text>
              </View>
            </View>
          ))}
        </View>

        {/* Performance Summary */}
        <View style={styles.summarySection}>
          <Text style={styles.summaryTitle}>💡 Resumo de Performance</Text>
          
          <View style={styles.summaryItem}>
            <Text style={styles.summaryLabel}>Taxa de Devolução no Prazo</Text>
            <View style={styles.progressBar}>
              <View
                style={[
                  styles.progressFill,
                  {
                    width: `${reportData.on_time_return_rate || 0}%`,
                    backgroundColor: '#4CAF50',
                  },
                ]}
              />
            </View>
            <Text style={styles.summaryValue}>
              {reportData.on_time_return_rate || 0}%
            </Text>
          </View>

          <View style={styles.summaryItem}>
            <Text style={styles.summaryLabel}>Taxa de Utilização do Acervo</Text>
            <View style={styles.progressBar}>
              <View
                style={[
                  styles.progressFill,
                  {
                    width: `${reportData.utilization_rate || 0}%`,
                    backgroundColor: '#2196F3',
                  },
                ]}
              />
            </View>
            <Text style={styles.summaryValue}>
              {reportData.utilization_rate || 0}%
            </Text>
          </View>

          <View style={styles.summaryItem}>
            <Text style={styles.summaryLabel}>Média de Empréstimos por Aluno</Text>
            <Text style={styles.summaryValueLarge}>
              {reportData.avg_loans_per_student?.toFixed(1) || '0.0'}
            </Text>
          </View>

          <View style={styles.summaryItem}>
            <Text style={styles.summaryLabel}>Tempo Médio de Empréstimo</Text>
            <Text style={styles.summaryValueLarge}>
              {reportData.avg_loan_duration || 0} dias
            </Text>
          </View>
        </View>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#007AFF" />
        <Text style={styles.loadingText}>Carregando relatórios...</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>📊 Relatórios</Text>
        <Text style={styles.headerSubtitle}>
          Análise detalhada e estatísticas do sistema
        </Text>
      </View>

      {renderMonthSelector()}
      {renderOverview()}
      {renderMonthlyReport()}

      <View style={styles.footer}>
        <Text style={styles.footerText}>
          Relatório gerado em {new Date().toLocaleDateString('pt-BR')}
        </Text>
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
    backgroundColor: '#f5f5f5',
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: '#666',
  },
  header: {
    backgroundColor: 'white',
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#666',
    marginTop: 5,
  },
  periodSelector: {
    backgroundColor: 'white',
    padding: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  monthChip: {
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 20,
    backgroundColor: '#f0f0f0',
    marginRight: 10,
  },
  monthChipActive: {
    backgroundColor: '#007AFF',
  },
  monthText: {
    fontSize: 14,
    color: '#666',
    fontWeight: '500',
  },
  monthTextActive: {
    color: 'white',
    fontWeight: 'bold',
  },
  overviewContainer: {
    padding: 15,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  statsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
  },
  statCard: {
    width: '48%',
    padding: 20,
    borderRadius: 15,
    margin: '1%',
    alignItems: 'center',
  },
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
  reportContainer: {
    padding: 15,
  },
  reportHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  reportTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    flex: 1,
  },
  exportButton: {
    backgroundColor: '#007AFF',
    paddingHorizontal: 15,
    paddingVertical: 8,
    borderRadius: 8,
  },
  exportButtonText: {
    color: 'white',
    fontSize: 14,
    fontWeight: 'bold',
  },
  metricsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginBottom: 20,
  },
  metricCard: {
    width: '48%',
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    margin: '1%',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  metricValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
  },
  metricLabel: {
    fontSize: 12,
    color: '#666',
    marginTop: 5,
  },
  metricChange: {
    fontSize: 12,
    color: '#4CAF50',
    marginTop: 5,
    fontWeight: 'bold',
  },
  metricChangeNegative: {
    color: '#F44336',
  },
  listSection: {
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 15,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  listTitle: {
    fontSize: 16,
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
    fontSize: 14,
  },
  listInfo: {
    flex: 1,
  },
  listItemTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
  },
  listItemSubtitle: {
    fontSize: 12,
    color: '#999',
    marginTop: 2,
  },
  categoryBadge: {
    flex: 1,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 15,
    backgroundColor: '#E3F2FD',
    marginRight: 10,
  },
  categoryText: {
    fontSize: 14,
    color: '#1976D2',
    fontWeight: 'bold',
  },
  categoryCount: {
    fontSize: 12,
    color: '#666',
    fontWeight: 'bold',
  },
  summarySection: {
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 15,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  summaryTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  summaryItem: {
    marginBottom: 15,
  },
  summaryLabel: {
    fontSize: 13,
    color: '#666',
    marginBottom: 8,
  },
  progressBar: {
    height: 8,
    backgroundColor: '#f0f0f0',
    borderRadius: 4,
    overflow: 'hidden',
    marginBottom: 5,
  },
  progressFill: {
    height: '100%',
    borderRadius: 4,
  },
  summaryValue: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
  },
  summaryValueLarge: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#007AFF',
    marginTop: 5,
  },
  footer: {
    alignItems: 'center',
    padding: 20,
    marginBottom: 20,
  },
  footerText: {
    fontSize: 12,
    color: '#999',
  },
});