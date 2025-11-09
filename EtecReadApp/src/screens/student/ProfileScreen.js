// src/screens/student/ProfileScreen.js
// ✅ VERSÃO ATUALIZADA - SEM WARNINGS
import React, { useState } from 'react';
import { View, Text, Image, TouchableOpacity, TextInput, StyleSheet, ScrollView, Alert, ActivityIndicator, Platform } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../services/api';

const shadow = (elevation = 3) => Platform.select({
  ios: { shadowColor: '#000', shadowOffset: { width: 0, height: elevation / 2 }, shadowOpacity: 0.1, shadowRadius: elevation },
  android: { elevation },
  web: { boxShadow: `0 ${elevation}px ${elevation * 2}px rgba(0,0,0,0.1)` },
});

export default function ProfileScreen() {
  const { user, logout, updateUserData } = useAuth();
  
  const [editing, setEditing] = useState(false);
  const [name, setName] = useState(user?.name || '');
  const [loading, setLoading] = useState(false);
  const [selectedImage, setSelectedImage] = useState(null);

  const pickImage = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    
    if (status !== 'granted') {
      Alert.alert('Permissão negada', 'Precisamos de acesso às suas fotos');
      return;
    }

    // ✅ CORRIGIDO: Usar novo formato sem MediaTypeOptions
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'], // ✅ Novo formato correto
      allowsEditing: true,
      aspect: [1, 1],
      quality: 0.8,
    });

    if (!result.canceled) {
      setSelectedImage(result.assets[0]);
    }
  };

  const handleSave = async () => {
    setLoading(true);

    try {
      const formData = new FormData();
      formData.append('name', name);

      if (selectedImage) {
        const uriParts = selectedImage.uri.split('.');
        const fileType = uriParts[uriParts.length - 1];

        formData.append('photo', {
          uri: Platform.OS === 'ios' ? selectedImage.uri.replace('file://', '') : selectedImage.uri,
          name: `photo.${fileType}`,
          type: `image/${fileType}`,
        });
      }

      const response = await api.auth.updateProfile(formData);
      
      if (response.success) {
        await updateUserData();
        setEditing(false);
        setSelectedImage(null);
        Alert.alert('Sucesso', 'Perfil atualizado com sucesso!');
      }
    } catch (error) {
      Alert.alert('Erro', 'Não foi possível atualizar o perfil');
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = () => {
    Alert.alert(
      'Sair',
      'Tem certeza que deseja sair?',
      [
        { text: 'Cancelar', style: 'cancel' },
        { text: 'Sair', onPress: logout, style: 'destructive' },
      ]
    );
  };

  // URL de avatar com fallback melhorado
  const getAvatarUri = () => {
    if (selectedImage?.uri) return selectedImage.uri;
    if (user?.photo_url) return user.photo_url;
    // Fallback com UI Avatars
    const userName = encodeURIComponent(user?.name || 'User');
    return `https://ui-avatars.com/api/?name=${userName}&size=150&background=007AFF&color=fff&bold=true`;
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity onPress={editing ? pickImage : null} activeOpacity={editing ? 0.7 : 1}>
          <Image
            source={{ uri: getAvatarUri() }}
            style={styles.avatar}
          />
          {editing && (
            <View style={styles.editIconContainer}>
              <Text style={styles.editIcon}>📷</Text>
            </View>
          )}
        </TouchableOpacity>

        {editing ? (
          <TextInput
            style={styles.nameInput}
            value={name}
            onChangeText={setName}
            placeholder="Seu nome"
          />
        ) : (
          <Text style={styles.name}>{user?.name}</Text>
        )}

        <View style={[
          styles.roleBadge,
          user?.role === 'admin' ? styles.adminBadge : styles.alunoBadge
        ]}>
          <Text style={styles.roleText}>
            {user?.role === 'admin' ? '🔐 Administrador' : '👨‍🎓 Aluno'}
          </Text>
        </View>
      </View>

      <View style={styles.infoContainer}>
        <View style={styles.infoCard}>
          <Text style={styles.infoLabel}>Email</Text>
          <Text style={styles.infoValue}>{user?.email}</Text>
        </View>

        {user?.rm && (
          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>RM</Text>
            <Text style={styles.infoValue}>{user.rm}</Text>
          </View>
        )}

        {user?.ano_escolar && (
          <View style={styles.infoCard}>
            <Text style={styles.infoLabel}>Ano Escolar</Text>
            <Text style={styles.infoValue}>{user.ano_escolar}º ano</Text>
          </View>
        )}
      </View>

      <View style={styles.buttonsContainer}>
        {editing ? (
          <>
            <TouchableOpacity
              style={[styles.button, styles.saveButton]}
              onPress={handleSave}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="white" />
              ) : (
                <Text style={styles.buttonText}>✓ Salvar</Text>
              )}
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.button, styles.cancelButton]}
              onPress={() => {
                setEditing(false);
                setName(user?.name || '');
                setSelectedImage(null);
              }}
              disabled={loading}
            >
              <Text style={styles.buttonText}>✗ Cancelar</Text>
            </TouchableOpacity>
          </>
        ) : (
          <>
            <TouchableOpacity
              style={[styles.button, styles.editButton]}
              onPress={() => setEditing(true)}
            >
              <Text style={styles.buttonText}>✏️ Editar Perfil</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.button, styles.logoutButton]}
              onPress={handleLogout}
            >
              <Text style={styles.buttonText}>🚪 Sair</Text>
            </TouchableOpacity>
          </>
        )}
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
    alignItems: 'center',
    padding: 30,
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
    ...shadow(5),
  },
  avatar: {
    width: 120,
    height: 120,
    borderRadius: 60,
    borderWidth: 4,
    borderColor: '#007AFF',
  },
  editIconContainer: {
    position: 'absolute',
    bottom: 0,
    right: 0,
    backgroundColor: '#007AFF',
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: 'center',
    alignItems: 'center',
  },
  editIcon: {
    fontSize: 18,
  },
  name: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 15,
  },
  nameInput: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 15,
    borderBottomWidth: 2,
    borderBottomColor: '#007AFF',
    paddingHorizontal: 20,
    textAlign: 'center',
  },
  roleBadge: {
    paddingHorizontal: 20,
    paddingVertical: 8,
    borderRadius: 20,
    marginTop: 10,
  },
  adminBadge: {
    backgroundColor: '#FF9800',
  },
  alunoBadge: {
    backgroundColor: '#4CAF50',
  },
  roleText: {
    color: 'white',
    fontWeight: 'bold',
    fontSize: 14,
  },
  infoContainer: {
    padding: 20,
  },
  infoCard: {
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    ...shadow(2),
  },
  infoLabel: {
    fontSize: 12,
    color: '#999',
    marginBottom: 5,
  },
  infoValue: {
    fontSize: 16,
    color: '#333',
    fontWeight: '500',
  },
  buttonsContainer: {
    padding: 20,
  },
  button: {
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10,
  },
  editButton: {
    backgroundColor: '#007AFF',
  },
  saveButton: {
    backgroundColor: '#4CAF50',
  },
  cancelButton: {
    backgroundColor: '#9E9E9E',
  },
  logoutButton: {
    backgroundColor: '#F44336',
  },
  buttonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
});