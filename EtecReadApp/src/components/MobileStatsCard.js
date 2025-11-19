import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { MaterialIcons, Feather, Ionicons, FontAwesome5 } from '@expo/vector-icons';

const statsIcons = {
  books: () => <MaterialIcons name="menu-book" size={20} color="#fff" />,
  loan: () => <Feather name="book-open" size={20} color="#fff" />,
  users: () => <Ionicons name="people" size={20} color="#fff" />,
  trend: () => <FontAwesome5 name="chart-line" size={18} color="#fff" />,
};

export function MobileStatsCards({ stats }) {
  const data = [
    { key: 'total_books', title: 'Total de Livros', value: stats?.total_books ?? '-', icon: 'books', color: '#ef4444' },
    { key: 'active_loans', title: 'Empréstimos Ativos', value: stats?.active_loans ?? '-', icon: 'loan', color: '#059669' },
    { key: 'total_students', title: 'Usuários Ativos', value: stats?.total_students ?? '-', icon: 'users', color: '#2563eb' },
    { key: 'utilization_rate', title: 'Taxa de Utilização', value: stats?.utilization_rate ? `${stats.utilization_rate}%` : '-', icon: 'trend', color: '#f97316' },
  ];

  return (
    <View style={styles.grid}>
      {data.map((item) => {
        const Icon = statsIcons[item.icon];
        return (
          <View key={item.key} style={[styles.card, { backgroundColor: '#fff' }]}>
            <View style={[styles.iconBox, { backgroundColor: item.color }]}>
              <Icon />
            </View>
            <Text style={styles.smallLabel}>{item.title}</Text>
            <Text style={styles.value}>{item.value}</Text>
          </View>
        );
      })}
    </View>
  );
}

const styles = StyleSheet.create({
  grid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    gap: 10,
  },
  card: {
    width: '48%',
    borderRadius: 12,
    padding: 14,
    marginBottom: 10,
    elevation: 2,
  },
  iconBox: {
    width: 44,
    height: 44,
    borderRadius: 10,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  smallLabel: {
    fontSize: 12,
    color: '#6b7280',
    marginBottom: 4,
  },
  value: {
    fontSize: 20,
    fontWeight: '700',
    color: '#111827',
  },
});
