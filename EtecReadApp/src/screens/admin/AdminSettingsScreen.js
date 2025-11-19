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
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';

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
      if (response.success) setSystemInfo(response.data);
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
              if (response.success) Alert.alert('Sucesso', 'Backup criado com sucesso!');
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
              if (response.success) Alert.alert('Sucesso', 'Cache limpo com sucesso!');
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

  const updateSetting = (key, value) => setSettings(prev => ({ ...prev, [key]: value }));

  return (
    <ScrollView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Icon name="cog-outline" size={28} color="#dc2626" />
        <Text style={styles.headerTitle}>Configurações</Text>
        <Text style={styles.headerSubtitle}>Gerencie as configurações do sistema</Text>
      </View>

      {/* Seções */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="book-open-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Empréstimos</Text>
        </View>

        {['loan_duration_days', 'max_loans_per_student', 'max_renewals', 'fine_per_day'].map((key) => (
          <View key={key} style={styles.settingItem}>
            <View style={styles.settingInfo}>
              <Text style={styles.settingLabel}>{{
                loan_duration_days: 'Duração do empréstimo (dias)',
                max_loans_per_student: 'Empréstimos simultâneos',
                max_renewals: 'Renovações permitidas',
                fine_per_day: 'Multa por dia de atraso (R$)',
              }[key]}</Text>
              <Text style={styles.settingDescription}>{{
                loan_duration_days: 'Quantos dias um aluno pode ficar com o livro',
                max_loans_per_student: 'Máximo de livros por aluno ao mesmo tempo',
                max_renewals: 'Quantas vezes pode renovar o empréstimo',
                fine_per_day: 'Valor cobrado por dia de atraso',
              }[key]}</Text>
            </View>
            <TextInput
              style={styles.numberInput}
              value={String(settings[key])}
              onChangeText={(text) => updateSetting(key, text)}
              keyboardType={key === 'fine_per_day' ? 'decimal-pad' : 'numeric'}
              maxLength={key === 'fine_per_day' ? 5 : 3}
            />
          </View>
        ))}
      </View>

      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="clipboard-list-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Reservas</Text>
        </View>
        <View style={styles.settingItem}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingLabel}>Permitir reservas</Text>
            <Text style={styles.settingDescription}>Alunos podem reservar livros indisponíveis</Text>
          </View>
          <Switch
            value={settings.allow_reservations}
            onValueChange={(v) => updateSetting('allow_reservations', v)}
            trackColor={{ false: '#ddd', true: '#4CAF50' }}
            thumbColor={settings.allow_reservations ? '#fff' : '#f4f3f4'}
          />
        </View>
      </View>

      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="bell-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Notificações</Text>
        </View>
        {['notification_overdue', 'notification_due_soon'].map((key) => (
          <View key={key} style={styles.settingItem}>
            <View style={styles.settingInfo}>
              <Text style={styles.settingLabel}>{{
                notification_overdue: 'Notificar empréstimos atrasados',
                notification_due_soon: 'Notificar vencimento próximo',
              }[key]}</Text>
              <Text style={styles.settingDescription}>{{
                notification_overdue: 'Enviar alertas para livros em atraso',
                notification_due_soon: 'Avisar 2 dias antes do vencimento',
              }[key]}</Text>
            </View>
            <Switch
              value={settings[key]}
              onValueChange={(v) => updateSetting(key, v)}
              trackColor={{ false: '#ddd', true: '#4CAF50' }}
              thumbColor={settings[key] ? '#fff' : '#f4f3f4'}
            />
          </View>
        ))}
      </View>

      {systemInfo && (
        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Icon name="desktop-classic" size={22} color="#dc2626" />
            <Text style={styles.sectionTitle}>Sistema</Text>
          </View>
          {[
            { label: 'Versão do App', value: systemInfo.app_version },
            { label: 'Banco de Dados', value: systemInfo.database_size },
            { label: 'Último Backup', value: systemInfo.last_backup ? new Date(systemInfo.last_backup).toLocaleString('pt-BR') : 'Nunca' },
            { label: 'Total de Registros', value: systemInfo.total_records },
          ].map((item, idx) => (
            <View key={idx} style={styles.infoCard}>
              <Text style={styles.infoLabel}>{item.label}</Text>
              <Text style={styles.infoValue}>{item.value || 'N/A'}</Text>
            </View>
          ))}
        </View>
      )}

      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="wrench-outline" size={22} color="#dc2626" />
          <Text style={styles.sectionTitle}>Manutenção</Text>
        </View>
        <TouchableOpacity style={styles.actionButton} onPress={handleBackup} disabled={loading}>
          <Icon name="database-plus" size={28} color="#dc2626" style={{ marginRight: 15 }} />
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Criar Backup</Text>
            <Text style={styles.actionDescription}>Fazer backup completo do sistema</Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionButton} onPress={handleClearCache} disabled={loading}>
          <Icon name="trash-can-outline" size={28} color="#dc2626" style={{ marginRight: 15 }} />
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Limpar Cache</Text>
            <Text style={styles.actionDescription}>Remover dados temporários</Text>
          </View>
        </TouchableOpacity>
      </View>

      <View style={styles.saveContainer}>
        <TouchableOpacity
          style={[styles.saveButton, loading && styles.saveButtonDisabled]}
          onPress={handleSaveSettings}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="white" />
          ) : (
            <Icon name="content-save-outline" size={20} color="white" />
          )}
          {!loading && <Text style={styles.saveButtonText}> Salvar Configurações</Text>}
        </TouchableOpacity>
      </View>

      <View style={styles.footer}>
        <Text style={styles.footerText}>Administrador: {user?.name}</Text>
        <TouchableOpacity onPress={logout}>
          <Text style={styles.logoutText}>Sair</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  header: { padding: 20, backgroundColor: 'white', borderBottomWidth: 1, borderBottomColor: '#ddd', alignItems: 'flex-start' },
  headerTitle: { fontSize: 24, fontWeight: 'bold', color: '#333', marginTop: 5 },
  headerSubtitle: { fontSize: 14, color: '#666', marginTop: 3 },
  section: { backgroundColor: 'white', margin: 15, borderRadius: 12, padding: 15, shadowColor: '#000', shadowOffset: { width:0, height:2 }, shadowOpacity:0.1, shadowRadius:4, elevation:3 },
  sectionHeader: { flexDirection:'row', alignItems:'center', marginBottom:10 },
  sectionTitle: { fontSize:18, fontWeight:'bold', color:'#333', marginLeft:8 },
  settingItem: { flexDirection:'row', justifyContent:'space-between', alignItems:'center', paddingVertical:12, borderBottomWidth:1, borderBottomColor:'#f0f0f0' },
  settingInfo: { flex:1, marginRight:15 },
  settingLabel: { fontSize:16, fontWeight:'600', color:'#333', marginBottom:2 },
  settingDescription: { fontSize:12, color:'#999' },
  numberInput: { backgroundColor:'#f0f0f0', paddingHorizontal:15, paddingVertical:8, borderRadius:8, fontSize:16, fontWeight:'bold', textAlign:'center', minWidth:60 },
  infoCard: { flexDirection:'row', justifyContent:'space-between', alignItems:'center', paddingVertical:10, borderBottomWidth:1, borderBottomColor:'#f0f0f0' },
  infoLabel: { fontSize:14, color:'#666' },
  infoValue: { fontSize:14, fontWeight:'600', color:'#333' },
  actionButton: { flexDirection:'row', alignItems:'center', backgroundColor:'#f8f8f8', padding:15, borderRadius:10, marginBottom:10 },
  actionInfo: { flex:1 },
  actionTitle: { fontSize:16, fontWeight:'bold', color:'#333' },
  actionDescription: { fontSize:12, color:'#666', marginTop:2 },
  saveContainer: { padding:15, flexDirection:'row', justifyContent:'center', alignItems:'center' },
  saveButton: { flexDirection:'row', alignItems:'center', justifyContent:'center', backgroundColor:'#dc2626', padding:18, borderRadius:12 },
  saveButtonDisabled: { backgroundColor:'#ccc' },
  saveButtonText: { color:'white', fontSize:18, fontWeight:'bold' },
  footer: { alignItems:'center', padding:20, marginBottom:20 },
  footerText: { fontSize:14, color:'#999' },
  logoutText: { fontSize:14, color:'#F44336', marginTop:10, fontWeight:'bold' },
});
