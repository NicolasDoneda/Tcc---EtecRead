import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { MaterialCommunityIcons, Feather } from '@expo/vector-icons';

export function MobileRecentLoans({ loans = [] }) {
  // Função para formatar o status
  const getStatusDisplay = (status) => {
    const statusMap = {
      'ativo': 'Ativo',
      'finalizado': 'Finalizado',
      'atrasado': 'Atrasado',
    };
    return statusMap[status?.toLowerCase()] || status;
  };

  // Função para obter o estilo do badge baseado no status
  const getBadgeStyle = (status) => {
    switch(status?.toLowerCase()) {
      case 'ativo':
        return styles.badgeActive;
      case 'atrasado':
        return styles.badgeLate;
      case 'finalizado':
        return styles.badgeFinished;
      default:
        return styles.badgeActive;
    }
  };

  return (
    <View style={styles.card}>
      <View style={styles.header}>
        <MaterialCommunityIcons name="book-open-page-variant" size={18} color="#ef4444" />
        <Text style={styles.headerTitle}>  Empréstimos Recentes</Text>
      </View>

      {loans.slice(0, 6).map((loan) => (
        <View key={loan.id} style={styles.item}>
          <View style={styles.rowBetween}>
            <View style={{ flex: 1 }}>
              <Text style={styles.bookTitle} numberOfLines={1}>
                {loan.book_title ?? loan.book}
              </Text>
              <Text style={styles.loanId}>#{loan.id}</Text>
            </View>

            <View style={[styles.badge, getBadgeStyle(loan.status)]}>
              <Text style={styles.badgeText}>{getStatusDisplay(loan.status)}</Text>
            </View>
          </View>

          <View style={styles.infoRow}>
            <Feather name="user" size={14} color="#6b7280" />
            <Text style={styles.infoText}>{loan.student_name ?? loan.user}</Text>
          </View>

          <View style={styles.datesRow}>
            <View style={styles.dateItem}>
              <Feather name="calendar" size={14} color="#6b7280" />
              <Text style={styles.infoText}>
                Empréstimo: {loan.loan_date ? new Date(loan.loan_date).toLocaleDateString('pt-BR') : 'N/A'}
              </Text>
            </View>
            
            <View style={styles.dateItem}>
              <Feather name="calendar" size={14} color="#6b7280" />
              <Text style={styles.infoText}>
                Devolução: {loan.due_date ? new Date(loan.due_date).toLocaleDateString('pt-BR') : loan.returnDate}
              </Text>
            </View>
          </View>
        </View>
      ))}
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    marginTop: 14,
    backgroundColor: '#fff',
    padding: 14,
    borderRadius: 12,
    elevation: 2,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
  },
  headerTitle: {
    fontSize: 16,
    fontWeight: '700',
    color: '#111827',
  },
  item: {
    backgroundColor: '#f8fafc',
    padding: 12,
    borderRadius: 10,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: '#e6eef7',
  },
  rowBetween: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    justifyContent: 'space-between',
  },
  bookTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#111827',
  },
  loanId: {
    fontSize: 12,
    color: '#6b7280',
  },
  badge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  badgeActive: {
    backgroundColor: '#16a34a',
  },
  badgeLate: {
    backgroundColor: '#ef4444',
  },
  badgeFinished: {
    backgroundColor: '#6b7280',
  },
  badgeText: {
    color: '#fff',
    fontWeight: '700',
    fontSize: 12,
  },
  infoRow: {
    marginTop: 8,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  datesRow: {
    marginTop: 8,
    flexDirection: 'row',
    justifyContent: 'space-between',
    gap: 12,
  },
  dateItem: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  infoText: {
    marginLeft: 6,
    color: '#6b7280',
    fontSize: 12,
  },
});