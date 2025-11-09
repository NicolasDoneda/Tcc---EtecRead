// App.js
// ✅ VERSÃO COMPATÍVEL COM EXPO
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createStackNavigator } from '@react-navigation/stack';
import { ActivityIndicator, View, Text } from 'react-native';

import { AuthProvider, useAuth } from './src/contexts/AuthContext';

// Auth Screens
import LoginScreen from './src/screens/auth/LoginScreen';

// Student Screens
import CatalogScreen from './src/screens/student/CatalogScreen';
import MyLoansScreen from './src/screens/student/MyLoansScreen';
import AdvancedSearchScreen from './src/screens/student/AdvancedSearchScreen';
import ProfileScreen from './src/screens/student/ProfileScreen';
import SupportScreen from './src/screens/student/SupportScreen';

// Admin Screens
import AdminDashboardScreen from './src/screens/admin/AdminDashboardScreen';
import AdminLoansScreen from './src/screens/admin/AdminLoansScreen';
import AdminReservationsScreen from './src/screens/admin/AdminReservationsScreen';
import AdminReportsScreen from './src/screens/admin/AdminReportsScreen';
import AdminSettingsScreen from './src/screens/admin/AdminSettingsScreen';

const Tab = createBottomTabNavigator();
const Stack = createStackNavigator();

// ✅ CORRIGIDO: Componente de ícone simples para Expo
function TabIcon({ emoji }) {
  return <Text style={{ fontSize: 24 }}>{emoji}</Text>;
}

// Tab Navigator para ALUNOS
function StudentTabs() {
  return (
    <Tab.Navigator
      screenOptions={{
        tabBarActiveTintColor: '#007AFF',
        tabBarInactiveTintColor: '#999',
        headerShown: true,
        tabBarStyle: {
          paddingBottom: 5,
          paddingTop: 5,
          height: 60,
        },
      }}
    >
      <Tab.Screen
        name="Catalog"
        component={CatalogScreen}
        options={{
          title: 'Catálogo',
          tabBarIcon: () => <TabIcon emoji="📚" />,
        }}
      />
      <Tab.Screen
        name="MyLoans"
        component={MyLoansScreen}
        options={{
          title: 'Empréstimos',
          tabBarIcon: () => <TabIcon emoji="📖" />,
        }}
      />
      <Tab.Screen
        name="Search"
        component={AdvancedSearchScreen}
        options={{
          title: 'Busca',
          tabBarIcon: () => <TabIcon emoji="🔍" />,
        }}
      />
      <Tab.Screen
        name="Profile"
        component={ProfileScreen}
        options={{
          title: 'Perfil',
          tabBarIcon: () => <TabIcon emoji="👤" />,
        }}
      />
      <Tab.Screen
        name="Support"
        component={SupportScreen}
        options={{
          title: 'Suporte',
          tabBarIcon: () => <TabIcon emoji="❓" />,
        }}
      />
    </Tab.Navigator>
  );
}

// Tab Navigator para ADMINS
function AdminTabs() {
  return (
    <Tab.Navigator
      screenOptions={{
        tabBarActiveTintColor: '#007AFF',
        tabBarInactiveTintColor: '#999',
        headerShown: true,
        tabBarStyle: {
          paddingBottom: 5,
          paddingTop: 5,
          height: 60,
        },
      }}
    >
      <Tab.Screen
        name="Dashboard"
        component={AdminDashboardScreen}
        options={{
          title: 'Dashboard',
          tabBarIcon: () => <TabIcon emoji="📊" />,
        }}
      />
      <Tab.Screen
        name="Loans"
        component={AdminLoansScreen}
        options={{
          title: 'Empréstimos',
          tabBarIcon: () => <TabIcon emoji="📖" />,
        }}
      />
      <Tab.Screen
        name="Reservations"
        component={AdminReservationsScreen}
        options={{
          title: 'Reservas',
          tabBarIcon: () => <TabIcon emoji="📋" />,
        }}
      />
      <Tab.Screen
        name="Reports"
        component={AdminReportsScreen}
        options={{
          title: 'Relatórios',
          tabBarIcon: () => <TabIcon emoji="📈" />,
        }}
      />
      <Tab.Screen
        name="Settings"
        component={AdminSettingsScreen}
        options={{
          title: 'Configurações',
          tabBarIcon: () => <TabIcon emoji="⚙️" />,
        }}
      />
    </Tab.Navigator>
  );
}

// Navegação principal
function AppNavigator() {
  const { user, loading, isAdmin } = useAuth();

  if (loading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f5f5f5' }}>
        <ActivityIndicator size="large" color="#007AFF" />
        <Text style={{ marginTop: 10, fontSize: 16, color: '#666' }}>
          Carregando...
        </Text>
      </View>
    );
  }

  return (
    <Stack.Navigator screenOptions={{ headerShown: false }}>
      {!user ? (
        // Não autenticado - Tela de Login
        <Stack.Screen name="Login" component={LoginScreen} />
      ) : isAdmin() ? (
        // Admin autenticado - Tabs de Administrador
        <Stack.Screen name="AdminTabs" component={AdminTabs} />
      ) : (
        // Aluno autenticado - Tabs de Aluno
        <Stack.Screen name="StudentTabs" component={StudentTabs} />
      )}
    </Stack.Navigator>
  );
}

// App principal com Providers
export default function App() {
  return (
    <AuthProvider>
      <NavigationContainer>
        <AppNavigator />
      </NavigationContainer>
    </AuthProvider>
  );
}