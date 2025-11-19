import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { MaterialCommunityIcons, Feather } from '@expo/vector-icons';

const actions = [
  { key: 'loan', icon: () => <MaterialCommunityIcons name="book-plus" size={20} color="#fff" />, label: 'Novo Empréstimo', color: '#ef4444' },
  { key: 'addBook', icon: () => <Feather name="book" size={20} color="#fff" />, label: 'Adicionar Livro', color: '#374151' },
  { key: 'newUser', icon: () => <MaterialCommunityIcons name="account-plus" size={20} color="#fff" />, label: 'Novo Usuário', color: '#374151' },
  { key: 'search', icon: () => <Feather name="search" size={20} color="#fff" />, label: 'Buscar', color: '#374151' },
];

export function QuickActions({ onAction }) {
  return (
    <View style={styles.card}>
      <Text style={styles.subtitle}>Ações Rápidas</Text>
      <View style={styles.grid}>
        {actions.map((a) => (
          <TouchableOpacity
            key={a.key}
            style={[styles.btn, { backgroundColor: a.color }]}
            activeOpacity={0.7}
            onPress={() => onAction?.(a.key)}
          >
            {a.icon()}
            <Text style={styles.btnLabel}>{a.label}</Text>
          </TouchableOpacity>
        ))}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    padding: 14,
    borderRadius: 12,
    elevation: 2,
  },
  subtitle: {
    fontSize: 14,
    color: '#6b7280',
    marginBottom: 10,
  },
  grid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    gap: 10,
  },
  btn: {
    width: '48%',
    paddingVertical: 14,
    borderRadius: 10,
    alignItems: 'center',
    justifyContent: 'center',
  },
  btnLabel: {
    marginTop: 8,
    color: '#fff',
    fontSize: 12,
    textAlign: 'center',
  },
});
