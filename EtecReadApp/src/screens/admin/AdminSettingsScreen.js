import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  TextInput,
  StyleSheet,
  Alert,
  ActivityIndicator,
  Switch,
} from 'react-native';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../services/api';

export default function AdminSettingsScreen() {
  const { user, logout } = useAuth();
  
  const [loading, setLoading] = useState(false);
  const [settings, setSettings] = useState({
    loan_duration_days: 14,
    max_loans_per_student: 3,
    max_renewals: 2,
    fine_per_day: 1.00,
    allow_reservations: true,
    notification_overdue: true,
    notification_due_soon: true,
  });

  const [systemInfo, setSystemInfo] = useState(null);

  useEffect(() => {
    loadSettings();
    loadSystemInfo();
  }, []);

  const loadSettings = async () => {
    try {
      const response = await api.adminSettings.get();
      if (response.success) {
        // Garantir que valores booleanos sejam convertidos corretamente
        const loadedSettings = {
          ...response.data,
          allow_reservations: response.data.allow_reservations === true || response.data.allow_reservations === 'true',
          notification_overdue: response.data.notification_overdue === true || response.data.notification_overdue === 'true',
          notification_due_soon: response.data.notification_due_soon === true || response.data.notification_due_soon === 'true',
        };
        setSettings(loadedSettings);
      }
    } catch (error) {
      console.error('Erro ao carregar configurações:', error);
    }
  };

  const loadSystemInfo = async () => {
    try {
      const response = await api.adminSettings.getSystemInfo();
      if (response.success) {
        setSystemInfo(response.data);
      }
    } catch (error) {
      console.error('Erro ao carregar informações do sistema:', error);
    }
  };

  const handleSaveSettings = async () => {
    Alert.alert(
      'Confirmar alterações',
      'Deseja salvar as configurações do sistema?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Salvar',
          onPress: async () => {
            setLoading(true);
            try {
              // Garantir que os valores sejam do tipo correto
              const settingsToSave = {
                loan_duration_days: parseInt(settings.loan_duration_days) || 14,
                max_loans_per_student: parseInt(settings.max_loans_per_student) || 3,
                max_renewals: parseInt(settings.max_renewals) || 2,
                fine_per_day: parseFloat(settings.fine_per_day) || 1.00,
                allow_reservations: Boolean(settings.allow_reservations),
                notification_overdue: Boolean(settings.notification_overdue),
                notification_due_soon: Boolean(settings.notification_due_soon),
              };

              const response = await api.adminSettings.update(settingsToSave);
              if (response.success) {
                Alert.alert('Sucesso', 'Configurações atualizadas com sucesso!');
              } else {
                Alert.alert('Erro', response.message || 'Não foi possível salvar');
              }
            } catch (error) {
              Alert.alert('Erro', 'Erro ao salvar configurações');
              console.error('Erro ao salvar:', error);
            } finally {
              setLoading(false);
            }
          },
        },
      ]
    );
  };

  const handleBackup = async () => {
    Alert.alert(
      'Backup do Sistema',
      'Deseja criar um backup completo do banco de dados?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Criar Backup',
          onPress: async () => {
            setLoading(true);
            try {
              const response = await api.adminSettings.createBackup();
              if (response.success) {
                Alert.alert('Sucesso', 'Backup criado com sucesso!');
              }
            } catch (error) {
              Alert.alert('Erro', 'Não foi possível criar o backup');
            } finally {
              setLoading(false);
            }
          },
        },
      ]
    );
  };

  const handleClearCache = async () => {
    Alert.alert(
      'Limpar Cache',
      'Isso irá limpar todos os dados em cache. Continuar?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Limpar',
          style: 'destructive',
          onPress: async () => {
            setLoading(true);
            try {
              const response = await api.adminSettings.clearCache();
              if (response.success) {
                Alert.alert('Sucesso', 'Cache limpo com sucesso!');
              }
            } catch (error) {
              Alert.alert('Erro', 'Não foi possível limpar o cache');
            } finally {
              setLoading(false);
            }
          },
        },
      ]
    );
  };

  const updateSetting = (key, value) => {
    setSettings(prev => ({
      ...prev,
      [key]: value
    }));
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>⚙️ Configurações</Text>
        <Text style={styles.headerSubtitle}>Gerencie as configurações do sistema</Text>
      </View>

      {/* CONFIGURAÇÕES DE EMPRÉSTIMO */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>📖 Empréstimos</Text>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Duração do empréstimo (dias)</Text>
            <Text style={styles.settingDescription}>
              Quantos dias um aluno pode ficar com o livro
            </Text>
          </View>
          <TextInput
            style={styles.numberInput}
            value={String(settings.loan_duration_days)}
            onChangeText={(text) => updateSetting('loan_duration_days', text)}
            keyboardType="numeric"
            maxLength={3}
          />
        </View>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Empréstimos simultâneos</Text>
            <Text style={styles.settingDescription}>
              Máximo de livros por aluno ao mesmo tempo
            </Text>
          </View>
          <TextInput
            style={styles.numberInput}
            value={String(settings.max_loans_per_student)}
            onChangeText={(text) => updateSetting('max_loans_per_student', text)}
            keyboardType="numeric"
            maxLength={2}
          />
        </View>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Renovações permitidas</Text>
            <Text style={styles.settingDescription}>
              Quantas vezes pode renovar o empréstimo
            </Text>
          </View>
          <TextInput
            style={styles.numberInput}
            value={String(settings.max_renewals)}
            onChangeText={(text) => updateSetting('max_renewals', text)}
            keyboardType="numeric"
            maxLength={2}
          />
        </View>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Multa por dia de atraso (R$)</Text>
            <Text style={styles.settingDescription}>
              Valor cobrado por dia de atraso
            </Text>
          </View>
          <TextInput
            style={styles.numberInput}
            value={String(settings.fine_per_day)}
            onChangeText={(text) => updateSetting('fine_per_day', text)}
            keyboardType="decimal-pad"
            maxLength={5}
          />
        </View>
      </View>

      {/* CONFIGURAÇÕES DE RESERVAS */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>📋 Reservas</Text>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Permitir reservas</Text>
            <Text style={styles.settingDescription}>
              Alunos podem reservar livros indisponíveis
            </Text>
          </View>
          <Switch
            value={Boolean(settings.allow_reservations)}
            onValueChange={(value) => updateSetting('allow_reservations', value)}
            trackColor={{ false: '#ddd', true: '#4CAF50' }}
            thumbColor={settings.allow_reservations ? '#fff' : '#f4f3f4'}
          />
        </View>
      </View>

      {/* NOTIFICAÇÕES */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>🔔 Notificações</Text>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Notificar empréstimos atrasados</Text>
            <Text style={styles.settingDescription}>
              Enviar alertas para livros em atraso
            </Text>
          </View>
          <Switch
            value={Boolean(settings.notification_overdue)}
            onValueChange={(value) => updateSetting('notification_overdue', value)}
            trackColor={{ false: '#ddd', true: '#4CAF50' }}
            thumbColor={settings.notification_overdue ? '#fff' : '#f4f3f4'}
          />
        </View>

        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Notificar vencimento próximo</Text>
            <Text style={styles.settingDescription}>
              Avisar 2 dias antes do vencimento
            </Text>
          </View>
          <Switch
            value={Boolean(settings.notification_due_soon)}
            onValueChange={(value) => updateSetting('notification_due_soon', value)}
            trackColor={{ false: '#ddd', true: '#4CAF50' }}
            thumbColor={settings.notification_due_soon ? '#fff' : '#f4f3f4'}
          />
        </View>
      </View>

      {/* INFORMAÇÕES DO SISTEMA */}
      {systemInfo && (
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>💻 Sistema</Text>

          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>Versão do App</Text>
            <Text style={styles.infoValue}>{systemInfo.app_version || '1.0.0'}</Text>
          </View>

          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>Banco de Dados</Text>
            <Text style={styles.infoValue}>{systemInfo.database_size || 'N/A'}</Text>
          </View>

          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>Último Backup</Text>
            <Text style={styles.infoValue}>
              {systemInfo.last_backup
                ? new Date(systemInfo.last_backup).toLocaleString('pt-BR')
                : 'Nunca'}
            </Text>
          </View>

          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>Total de Registros</Text>
            <Text style={styles.infoValue}>{systemInfo.total_records || 0}</Text>
          </View>
        </View>
      )}

      {/* AÇÕES DO SISTEMA */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>🔧 Manutenção</Text>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={handleBackup}
          disabled={loading}
        >
          <Text style={styles.actionIcon}>💾</Text>
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Criar Backup</Text>
            <Text style={styles.actionDescription}>
              Fazer backup completo do sistema
            </Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={handleClearCache}
          disabled={loading}
        >
          <Text style={styles.actionIcon}>🗑️</Text>
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Limpar Cache</Text>
            <Text style={styles.actionDescription}>
              Remover dados temporários
            </Text>
          </View>
        </TouchableOpacity>
      </View>

      {/* BOTÃO SALVAR */}
      <View style={styles.saveContainer}>
        <TouchableOpacity
          style={[styles.saveButton, loading && styles.saveButtonDisabled]}
          onPress={handleSaveSettings}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="white" />
          ) : (
            <Text style={styles.saveButtonText}>💾 Salvar Configurações</Text>
          )}
        </TouchableOpacity>
      </View>

      <View style={styles.footer}>
        <Text style={styles.footerText}>
          Administrador: {user?.name}
        </Text>
        <TouchableOpacity onPress={logout}>
          <Text style={styles.logoutText}>Sair</Text>
        </TouchableOpacity>
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
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  settingItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  settingInfo: {
    flex: 1,
    marginRight: 15,
  },
  settingLabel: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    marginBottom: 2,
  },
  settingDescription: {
    fontSize: 12,
    color: '#999',
  },
  numberInput: {
    backgroundColor: '#f0f0f0',
    paddingHorizontal: 15,
    paddingVertical: 8,
    borderRadius: 8,
    fontSize: 16,
    fontWeight: 'bold',
    textAlign: 'center',
    minWidth: 60,
  },
  infoCard: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  infoLabel: {
    fontSize: 14,
    color: '#666',
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
  actionIcon: {
    fontSize: 30,
    marginRight: 15,
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
  saveContainer: {
    padding: 15,
  },
  saveButton: {
    backgroundColor: '#007AFF',
    padding: 18,
    borderRadius: 12,
    alignItems: 'center',
  },
  saveButtonDisabled: {
    backgroundColor: '#ccc',
  },
  saveButtonText: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
  },
  footer: {
    alignItems: 'center',
    padding: 20,
    marginBottom: 20,
  },
  footerText: {
    fontSize: 14,
    color: '#999',
  },
  logoutText: {
    fontSize: 14,
    color: '#F44336',
    marginTop: 10,
    fontWeight: 'bold',
  },
});