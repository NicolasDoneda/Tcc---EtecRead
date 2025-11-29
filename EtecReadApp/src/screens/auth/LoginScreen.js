// src/screens/auth/LoginScreen.js
import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform
} from 'react-native';
import { User, Shield, Book } from 'lucide-react-native';
import { auth } from '../../services/api';

export default function LoginScreen({ setUser }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [rm, setRm] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert('Erro', 'Preencha email e senha');
      return;
    }

    setLoading(true);
    try {
      const result = await auth.login(email, password, rm || null);
      setLoading(false);

      if (result.success && result.data?.user) {
        // Atualiza o usuário no App.js
        setUser(result.data.user);
      } else {
        Alert.alert('Erro no login', result.message || 'Credenciais inválidas');
      }
    } catch (e) {
      setLoading(false);
      Alert.alert('Erro', e.message || 'Algo deu errado');
    }
  };

  return (
    <View style={styles.container}>
      <KeyboardAvoidingView
        style={styles.container}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
        <View style={styles.content}>
          {/* Logo */}
          <View style={styles.logoContainer}>
            <View style={styles.logoCircle}>
              <Book width={40} height={40} color="#dc2626" />
            </View>
            <Text style={styles.title}>EtecRead</Text>
            <Text style={styles.subtitle}>Sistema de Biblioteca Digital</Text>
          </View>

          {/* Card */}
          <View style={styles.card}>
            <Text style={styles.cardTitle}>Bem-vindo!</Text>
            <Text style={styles.cardSubtitle}>Faça login para continuar</Text>

            {/* Inputs */}
            <TextInput
              style={styles.input}
              placeholder="Email"
              value={email}
              onChangeText={setEmail}
              autoCapitalize="none"
              keyboardType="email-address"
              editable={!loading}
            />
            <TextInput
              style={styles.input}
              placeholder="RM (opcional)"
              value={rm}
              onChangeText={setRm}
              keyboardType="numeric"
              editable={!loading}
            />
            <TextInput
              style={styles.input}
              placeholder="Senha"
              value={password}
              onChangeText={setPassword}
              secureTextEntry
              editable={!loading}
            />

            {/* Botão de login */}
            <TouchableOpacity
              style={[styles.button, loading && styles.buttonDisabled]}
              onPress={handleLogin}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="white" />
              ) : (
                <Text style={styles.buttonText}>Entrar</Text>
              )}
            </TouchableOpacity>
            
          </View>

          <Text style={styles.versionText}>Versão 1.0.0 • ETEC Digital Library</Text>
        </View>
      </KeyboardAvoidingView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#dc2626' },
  content: { flex: 1, justifyContent: 'center', padding: 20 },
  logoContainer: { alignItems: 'center', marginBottom: 30 },
  logoCircle: {
    width: 80,
    height: 80,
    borderRadius: 20,
    backgroundColor: 'white',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10
  },
  title: { fontSize: 28, fontWeight: 'bold', color: 'white' },
  subtitle: { fontSize: 14, color: '#fee2e2' },
  card: {
    backgroundColor: 'white',
    borderRadius: 20,
    padding: 20,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 10,
    elevation: 5
  },
  cardTitle: { fontSize: 20, fontWeight: 'bold', marginBottom: 5, color: '#111' },
  cardSubtitle: { fontSize: 14, color: '#666', marginBottom: 15 },
  input: {
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#ddd',
    marginBottom: 15
  },
  button: {
    backgroundColor: '#dc2626',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10
  },
  buttonDisabled: { backgroundColor: '#b91c1c' },
  buttonText: { color: 'white', fontSize: 16, fontWeight: 'bold' },
  quickAccess: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 },
  quickButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#dc2626',
    padding: 12,
    borderRadius: 10,
    marginRight: 5
  },
  adminButton: { backgroundColor: 'white', borderWidth: 1, borderColor: '#dc2626', marginRight: 0 },
  quickText: { color: 'white', marginLeft: 5, fontWeight: 'bold' },
  versionText: { textAlign: 'center', color: '#fee2e2', marginTop: 20, fontSize: 12 }
});
