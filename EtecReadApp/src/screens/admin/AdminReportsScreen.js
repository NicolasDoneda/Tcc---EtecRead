import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
} from 'react-native';
import api from '../../services/api';

export default function AdminReportsScreen() {
  const [loading, setLoading] = useState(true);
  const [selectedMonth, setSelectedMonth] = useState(new Date().getMonth() + 1);
  const [selectedYear, setSelectedYear] = useState(new Date().getFullYear());
  const [overview, setOverview] = useState(null);
  const [reportData, setReportData] = useState(null);

  useEffect(() => {
    loadData();
  }, [selectedMonth, selectedYear]);

  const loadData = async () => {
    setLoading(true);
    try {
      const [overviewRes, reportRes] = await Promise.all([
        api.adminReports.getOverview(),
        api.adminReports.getMonthly(selectedMonth, selectedYear),
      ]);
      if (overviewRes.success) setOverview(overviewRes.data);
      if (reportRes.success) setReportData(reportRes.data);
    } catch (error) {
      Alert.alert('Erro', 'Não foi possível carregar os relatórios');
    } finally {
      setLoading(false);
    }
  };

  const getMonthName = (month) => {
    const months = [
      'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
      'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'
    ];
    return months[month - 1];
  };

  const handleDownloadPDF = () => {
    Alert.alert('Exportar PDF', `Deseja baixar o relatório de ${getMonthName(selectedMonth)}/${selectedYear}?`);
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#dc2626" />
        <Text style={styles.loadingText}>Carregando relatórios...</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 20 }}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>📊 Relatórios</Text>
        <Text style={styles.headerSubtitle}>Análise detalhada e estatísticas do sistema</Text>
      </View>

      {/* Month Tabs */}
      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.monthTabs}>
        {Array.from({ length: 12 }, (_, i) => i + 1).map((month) => (
          <TouchableOpacity
            key={month}
            style={[styles.monthTab, selectedMonth === month && styles.monthTabActive]}
            onPress={() => setSelectedMonth(month)}
          >
            <Text style={[styles.monthTabText, selectedMonth === month && styles.monthTabTextActive]}>
              {getMonthName(month)}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Overview */}
      {overview && (
        <View style={styles.statsGrid}>
          {[
            { key: 'total-loans', value: overview.total_loans || 0, label: 'Empréstimos' },
            { key: 'total-students', value: overview.total_students || 0, label: 'Alunos Ativos' },
            { key: 'total-books', value: overview.total_books || 0, label: 'Livros Cadastrados' },
            { key: 'total-categories', value: overview.total_categories || 0, label: 'Categorias' },
          ].map((stat) => (
            <View key={stat.key} style={[styles.statCard, { backgroundColor: '#dc2626' }]}>
              <Text style={styles.statNumber}>{stat.value}</Text>
              <Text style={styles.statLabel}>{stat.label}</Text>
            </View>
          ))}
        </View>
      )}

      {/* Monthly Metrics & All Sections */}
      {reportData && (
        <View style={styles.reportContainer}>
          <View style={styles.metricsContainer}>
            {[
              { id: 'loans', label: 'Empréstimos', value: reportData.loans_count, change: reportData.loans_change },
              { id: 'returns', label: 'Devoluções', value: reportData.returns_count, change: reportData.returns_change },
              { id: 'overdue', label: 'Atrasos', value: reportData.overdue_count, change: reportData.overdue_change },
              { id: 'students', label: 'Novos Alunos', value: reportData.new_students, change: reportData.students_change },
            ].map((metric) => (
              <View key={metric.id} style={styles.metricCard}>
                <Text style={styles.metricValue}>{metric.value || 0}</Text>
                <Text style={styles.metricLabel}>{metric.label}</Text>
                <Text style={[styles.metricChange, metric.change >= 0 ? styles.metricChangeUp : styles.metricChangeDown]}>
                  {metric.change >= 0 ? '↑' : '↓'} {Math.abs(metric.change || 0)}%
                </Text>
              </View>
            ))}
          </View>

          {/* Livros Mais Emprestados */}
          <View style={styles.listSection}>
            <Text style={styles.listTitle}>📚 Livros Mais Emprestados</Text>
            {reportData.top_books?.slice(0, 5).map((book, i) => (
              <View key={book.id} style={styles.listItem}>
                <View style={[styles.rankBadge, { backgroundColor: '#dc2626' }]}><Text style={styles.rankText}>{i+1}</Text></View>
                <Text style={styles.listItemTitle}>{book.title}</Text>
                <Text style={styles.listItemSubtitle}>{book.loans_count} empréstimos</Text>
              </View>
            ))}
          </View>

          {/* Categorias Mais Populares */}
          <View style={styles.listSection}>
            <Text style={styles.listTitle}>📂 Categorias Mais Populares</Text>
            {reportData.top_categories?.slice(0,5).map((cat) => (
              <View key={cat.id} style={styles.listItem}>
                <View style={[styles.categoryBadge, { backgroundColor: '#fcdcdc' }]}>
                  <Text style={styles.categoryText}>{cat.name}</Text>
                </View>
                <Text style={styles.listItemSubtitle}>{cat.loans_count} empréstimos</Text>
              </View>
            ))}
          </View>

          {/* Alunos Mais Ativos */}
          <View style={styles.listSection}>
            <Text style={styles.listTitle}>🎓 Alunos Mais Ativos</Text>
            {reportData.top_students?.slice(0,5).map((student, i) => (
              <View key={student.id} style={styles.listItem}>
                <View style={[styles.rankBadge, { backgroundColor: '#dc2626' }]}><Text style={styles.rankText}>{i+1}</Text></View>
                <Text style={styles.listItemTitle}>{student.name}</Text>
                <Text style={styles.listItemSubtitle}>{student.loans_count} empréstimos • RM: {student.rm}</Text>
              </View>
            ))}
          </View>

          {/* Resumo de Performance */}
          <View style={styles.summarySection}>
            <Text style={styles.summaryTitle}>💡 Resumo de Performance</Text>

            {[
              {
                key: 'on-time-rate',
                label: 'Taxa de Devolução no Prazo',
                type: 'progress',
                value: reportData.on_time_return_rate || 0,
                color: '#4CAF50'
              },
              {
                key: 'utilization-rate',
                label: 'Taxa de Utilização do Acervo',
                type: 'progress',
                value: reportData.utilization_rate || 0,
                color: '#2196F3'
              },
              {
                key: 'avg-loans',
                label: 'Média de Empréstimos por Aluno',
                type: 'large',
                value: reportData.avg_loans_per_student?.toFixed(1) || '0.0'
              },
              {
                key: 'avg-duration',
                label: 'Tempo Médio de Empréstimo',
                type: 'large',
                value: `${reportData.avg_loan_duration || 0} dias`
              }
            ].map((item) => (
              <View key={item.key} style={styles.summaryItem}>
                <Text style={styles.summaryLabel}>{item.label}</Text>
                {item.type === 'progress' ? (
                  <>
                    <View style={styles.progressBar}>
                      <View style={[styles.progressFill, { width: `${item.value}%`, backgroundColor: item.color }]} />
                    </View>
                    <Text style={styles.summaryValue}>{item.value}%</Text>
                  </>
                ) : (
                  <Text style={styles.summaryValueLarge}>{item.value}</Text>
                )}
              </View>
            ))}
          </View>

          {/* Botão Download */}
          <TouchableOpacity style={styles.exportButton} onPress={handleDownloadPDF}>
            <Text style={styles.exportButtonText}>📥 Baixar PDF</Text>
          </TouchableOpacity>
        </View>
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  loadingText: { marginTop: 10, color: '#666' },
  header: { padding: 20, backgroundColor: 'white', borderBottomWidth: 1, borderBottomColor: '#ddd' },
  headerTitle: { fontSize: 24, fontWeight: 'bold', color: '#333' },
  headerSubtitle: { fontSize: 14, color: '#666', marginTop: 5 },
  monthTabs: { paddingVertical: 10, paddingLeft: 15, marginBottom: 10 },
  monthTab: { paddingHorizontal: 15, paddingVertical: 8, borderRadius: 20, backgroundColor: '#f0f0f0', marginRight: 10 },
  monthTabActive: { backgroundColor: '#dc2626' },
  monthTabText: { color: '#666', fontWeight: '500' },
  monthTabTextActive: { color: 'white', fontWeight: 'bold' },
  statsGrid: { flexDirection: 'row', flexWrap: 'wrap', padding: 10, justifyContent: 'space-between' },
  statCard: { width: '48%', borderRadius: 12, padding: 20, marginBottom: 10, alignItems: 'center' },
  statNumber: { fontSize: 28, fontWeight: 'bold', color: 'white' },
  statLabel: { fontSize: 12, color: 'white', marginTop: 5, textAlign: 'center' },
  reportContainer: { padding: 15 },
  metricsContainer: { flexDirection: 'row', flexWrap: 'wrap', justifyContent: 'space-between', marginBottom: 20 },
  metricCard: { width: '48%', backgroundColor: 'white', padding: 15, borderRadius: 12, marginBottom: 10, alignItems: 'center' },
  metricValue: { fontSize: 28, fontWeight: 'bold', color: '#333' },
  metricLabel: { fontSize: 12, color: '#666', marginTop: 5 },
  metricChange: { fontSize: 12, fontWeight: 'bold', marginTop: 5 },
  metricChangeUp: { color: '#4CAF50' },
  metricChangeDown: { color: '#F44336' },
  listSection: { backgroundColor: 'white', borderRadius: 12, padding: 15, marginBottom: 15 },
  listTitle: { fontSize: 16, fontWeight: 'bold', color: '#333', marginBottom: 10 },
  listItem: { flexDirection: 'row', alignItems: 'center', marginBottom: 10 },
  rankBadge: { width: 30, height: 30, borderRadius: 15, justifyContent: 'center', alignItems: 'center', marginRight: 10 },
  rankText: { color: 'white', fontWeight: 'bold' },
  listItemTitle: { flex: 1, fontSize: 14, color: '#333', fontWeight: '600' },
  listItemSubtitle: { fontSize: 12, color: '#666', marginLeft: 10 },
  categoryBadge: { flex: 1, paddingHorizontal: 12, paddingVertical: 6, borderRadius: 15, marginRight: 10, justifyContent: 'center', alignItems: 'center' },
  categoryText: { fontSize: 14, color: '#dc2626', fontWeight: 'bold' },
  summarySection: { backgroundColor: 'white', borderRadius: 12, padding: 15, marginBottom: 15 },
  summaryTitle: { fontSize: 16, fontWeight: 'bold', color: '#333', marginBottom: 15 },
  summaryItem: { marginBottom: 15 },
  summaryLabel: { fontSize: 13, color: '#666', marginBottom: 8 },
  progressBar: { height: 8, backgroundColor: '#f0f0f0', borderRadius: 4, overflow: 'hidden', marginBottom: 5 },
  progressFill: { height: '100%', borderRadius: 4 },
  summaryValue: { fontSize: 14, fontWeight: 'bold', color: '#333' },
  summaryValueLarge: { fontSize: 24, fontWeight: 'bold', color: '#dc2626', marginTop: 5 },
  exportButton: { backgroundColor: '#dc2626', paddingVertical: 12, borderRadius: 12, alignItems: 'center', marginTop: 10 },
  exportButtonText: { color: 'white', fontWeight: 'bold', fontSize: 16 },
});