import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  Alert,
} from 'react-native';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api from '../../services/api';

export default function AdminSettingsScreen({ navigation, onLogout }) {
  const [user, setUser] = useState(null);

  useEffect(() => {
    loadUser();
  }, []);

  const loadUser = async () => {
    try {
      const userData = await AsyncStorage.getItem('user');
      if (userData) setUser(JSON.parse(userData));
    } catch (error) {
      console.error('Erro ao carregar usuário:', error);
    }
  };

  const handleLogout = async () => {
    Alert.alert(
      'Sair',
      'Deseja realmente sair da conta?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Sair',
          style: 'destructive',
          onPress: async () => {
            try {
              await api.auth.logout();
            } catch (error) {
              console.error('Erro ao fazer logout:', error);
            } finally {
              if (onLogout) {
                onLogout();
              }
            }
          },
        },
      ]
    );
  };

  const handleClearCache = async () => {
    Alert.alert(
      'Limpar Cache Local',
      'Isso irá limpar todos os dados em cache do aplicativo. Continuar?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Limpar',
          style: 'destructive',
          onPress: async () => {
            try {
              await AsyncStorage.clear();
              Alert.alert('Sucesso', 'Cache local limpo! Por favor, faça login novamente.');
              
              if (onLogout) {
                onLogout();
              }
            } catch (error) {
              Alert.alert('Erro', 'Não foi possível limpar o cache');
            }
          },
        },
      ]
    );
  };

  return (
    <ScrollView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Icon name="cog-outline" size={28} color="#dc2626" />
        <Text style={styles.headerTitle}>Configurações</Text>
        <Text style={styles.headerSubtitle}>Gerencie suas preferências</Text>
      </View>

      {/* Informações do Sistema */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="information-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Informações</Text>
        </View>

        <View style={styles.infoCard}>
          <Text style={styles.infoLabel}>Versão do App</Text>
          <Text style={styles.infoValue}>1.0.0</Text>
        </View>

        <View style={styles.infoCard}>
          <Text style={styles.infoLabel}>Usuário</Text>
          <Text style={styles.infoValue}>{user?.name || 'Carregando...'}</Text>
        </View>

        <View style={styles.infoCard}>
          <Text style={styles.infoLabel}>Email</Text>
          <Text style={styles.infoValue}>{user?.email || 'Carregando...'}</Text>
        </View>

        <View style={styles.infoCard}>
          <Text style={styles.infoLabel}>Tipo de Conta</Text>
          <Text style={styles.infoValue}>
            {user?.role === 'admin' ? 'Administrador' : 'Aluno'}
          </Text>
        </View>
      </View>

      {/* Ações */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="wrench-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Manutenção</Text>
        </View>

        <TouchableOpacity style={styles.actionButton} onPress={handleClearCache}>
          <Icon name="trash-can-outline" size={28} color="#dc2626" style={{ marginRight: 15 }} />
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Limpar Cache Local</Text>
            <Text style={styles.actionDescription}>Remover dados temporários do app</Text>
          </View>
        </TouchableOpacity>
      </View>

      {/* Sobre */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="book-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Sobre</Text>
        </View>

        <View style={styles.aboutCard}>
          <Text style={styles.aboutTitle}>EtecRead</Text>
          <Text style={styles.aboutText}>
            Sistema de Gerenciamento de Biblioteca
          </Text>
          <Text style={styles.aboutText}>
            Desenvolvido como TCC
          </Text>
          <Text style={styles.aboutVersion}>Versão 1.0.0</Text>
        </View>
      </View>

      {/* Footer com Logout */}
      <View style={styles.footer}>
        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <Icon name="logout" size={20} color="white" style={{ marginRight: 8 }} />
          <Text style={styles.logoutButtonText}>Sair da Conta</Text>
        </TouchableOpacity>

        <Text style={styles.footerText}>
          Logado como: {user?.name || 'Carregando...'}
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
  header: {
    padding: 20,
    backgroundColor: 'white',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
    alignItems: 'flex-start',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 5,
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#666',
    marginTop: 3,
  },
  section: {
    backgroundColor: 'white',
    margin: 15,
    borderRadius: 12,
    padding: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 15,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginLeft: 8,
  },
  infoCard: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  infoLabel: {
    fontSize: 14,
    color: '#666',
    fontWeight: '500',
  },
  infoValue: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
  },
  actionButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f8f8',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
  },
  actionInfo: {
    flex: 1,
  },
  actionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  actionDescription: {
    fontSize: 12,
    color: '#666',
    marginTop: 2,
  },
  aboutCard: {
    alignItems: 'center',
    paddingVertical: 20,
  },
  aboutTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#dc2626',
    marginBottom: 8,
  },
  aboutText: {
    fontSize: 14,
    color: '#666',
    textAlign: 'center',
    marginTop: 4,
  },
  aboutVersion: {
    fontSize: 12,
    color: '#999',
    marginTop: 12,
  },
  footer: {
    alignItems: 'center',
    padding: 20,
    marginBottom: 30,
  },
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#dc2626',
    paddingVertical: 15,
    paddingHorizontal: 30,
    borderRadius: 12,
    marginBottom: 15,
    width: '90%',
  },
  logoutButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
  footerText: {
    fontSize: 12,
    color: '#999',
    marginTop: 10,
  },
});