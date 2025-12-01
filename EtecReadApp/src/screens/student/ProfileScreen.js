// src/screens/student/ProfileScreen.js
import React, { useState, useEffect } from 'react';
import { View, Text, Image, TouchableOpacity, TextInput, StyleSheet, ScrollView, Alert, ActivityIndicator, Platform } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import Svg, { Path, Circle, Rect, G } from 'react-native-svg';
import { useAuth } from '../../contexts/AuthContext';
import api from '../../services/api';

const shadow = (elevation = 3) => Platform.select({
  ios: { shadowColor: '#000', shadowOffset: { width: 0, height: elevation / 2 }, shadowOpacity: 0.1, shadowRadius: elevation },
  android: { elevation },
  web: { boxShadow: `0 ${elevation}px ${elevation * 2}px rgba(0,0,0,0.1)` },
});

// Ícones SVG
const CameraIcon = ({ size = 20, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Circle cx="12" cy="13" r="4" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const ShieldIcon = ({ size = 16, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill={color}>
    <Path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM10 17L6 13L7.41 11.59L10 14.17L16.59 7.58L18 9L10 17Z"/>
  </Svg>
);

const StudentIcon = ({ size = 16, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill={color}>
    <Path d="M12 3L1 9L5 11.18V17.18L12 21L19 17.18V11.18L21 10.09V17H23V9L12 3ZM18.82 9L12 12.72L5.18 9L12 5.28L18.82 9ZM17 15.99L12 18.72L7 15.99V12.27L12 15L17 12.27V15.99Z"/>
  </Svg>
);

const CheckIcon = ({ size = 20, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M20 6L9 17L4 12" stroke={color} strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const CloseIcon = ({ size = 20, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M18 6L6 18M6 6L18 18" stroke={color} strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const EditIcon = ({ size = 20, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.43741 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

const LogoutIcon = ({ size = 20, color = "white" }) => (
  <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
    <Path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M16 17L21 12L16 7" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
    <Path d="M21 12H9" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
  </Svg>
);

export default function ProfileScreen() {
  const { user, logout, updateUserData } = useAuth();
  
  const [editing, setEditing] = useState(false);
  const [name, setName] = useState(user?.name || '');
  const [loading, setLoading] = useState(false);
  const [selectedImage, setSelectedImage] = useState(null);

  useEffect(() => {
    setName(user?.name || '');
  }, [user]);

  const pickImage = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Permissão negada', 'Precisamos de acesso às suas fotos');
      return;
    }

    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'],
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
          type: `image/${fileType.toLowerCase()}`,
        });
      }

      const response = await api.auth.updateProfile(formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });

      if (response?.success) {
        await updateUserData();
        setName(response.data.name);
        setSelectedImage({ uri: `${response.data.photo_url}?t=${new Date().getTime()}` });
        setEditing(false);
        Alert.alert('Sucesso', 'Perfil atualizado com sucesso!');
      } else {
        console.log('Resposta da API:', response);
        Alert.alert('Erro', 'Não foi possível atualizar o perfil');
      }
    } catch (error) {
      console.log('Erro ao atualizar perfil:', error);
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

  const getAvatarUri = () => {
    if (selectedImage?.uri) return selectedImage.uri;
    if (user?.photo_url) return `${user.photo_url}?t=${new Date().getTime()}`;
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
              <CameraIcon size={20} color="white" />
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
          <View style={styles.roleContent}>
            {user?.role === 'admin' ? (
              <ShieldIcon size={16} color="white" />
            ) : (
              <StudentIcon size={16} color="white" />
            )}
            <Text style={styles.roleText}>
              {user?.role === 'admin' ? 'Administrador' : 'Aluno'}
            </Text>
          </View>
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
                <View style={styles.buttonContent}>
                  <CheckIcon size={20} color="white" />
                  <Text style={styles.buttonText}>Salvar</Text>
                </View>
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
              <View style={styles.buttonContent}>
                <CloseIcon size={20} color="white" />
                <Text style={styles.buttonText}>Cancelar</Text>
              </View>
            </TouchableOpacity>
          </>
        ) : (
          <>
            <TouchableOpacity
              style={[styles.button, styles.editButton]}
              onPress={() => setEditing(true)}
            >
              <View style={styles.buttonContent}>
                <EditIcon size={20} color="white" />
                <Text style={styles.buttonText}>Editar Perfil</Text>
              </View>
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.button, styles.logoutButton]}
              onPress={handleLogout}
            >
              <View style={styles.buttonContent}>
                <LogoutIcon size={20} color="white" />
                <Text style={styles.buttonText}>Sair</Text>
              </View>
            </TouchableOpacity>
          </>
        )}
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  header: {
    backgroundColor: 'white',
    alignItems: 'center',
    padding: 30,
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
    ...shadow(5),
  },
  avatar: { width: 120, height: 120, borderRadius: 60, borderWidth: 4, borderColor: '#007AFF' },
  editIconContainer: {
    position: 'absolute', bottom: 0, right: 0,
    backgroundColor: '#007AFF', width: 36, height: 36, borderRadius: 18,
    justifyContent: 'center', alignItems: 'center',
  },
  name: { fontSize: 24, fontWeight: 'bold', color: '#333', marginTop: 15 },
  nameInput: {
    fontSize: 22, fontWeight: 'bold', color: '#333',
    marginTop: 15, borderBottomWidth: 2, borderBottomColor: '#007AFF',
    paddingHorizontal: 20, textAlign: 'center',
  },
  roleBadge: { paddingHorizontal: 20, paddingVertical: 8, borderRadius: 20, marginTop: 10 },
  adminBadge: { backgroundColor: '#FF9800' },
  alunoBadge: { backgroundColor: '#4CAF50' },
  roleContent: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  roleText: { color: 'white', fontWeight: 'bold', fontSize: 14 },
  infoContainer: { padding: 20 },
  infoCard: { backgroundColor: 'white', padding: 15, borderRadius: 10, marginBottom: 10, ...shadow(2) },
  infoLabel: { fontSize: 12, color: '#999', marginBottom: 5 },
  infoValue: { fontSize: 16, color: '#333', fontWeight: '500' },
  buttonsContainer: { padding: 20 },
  button: { padding: 15, borderRadius: 10, alignItems: 'center', marginBottom: 10 },
  buttonContent: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  editButton: { backgroundColor: '#007AFF' },
  saveButton: { backgroundColor: '#4CAF50' },
  cancelButton: { backgroundColor: '#9E9E9E' },
  logoutButton: { backgroundColor: '#F44336' },
  buttonText: { color: 'white', fontSize: 16, fontWeight: 'bold' },
});