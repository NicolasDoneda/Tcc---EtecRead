import React, { useEffect, useState } from "react";
import { NavigationContainer } from "@react-navigation/native";
import { createDrawerNavigator } from "@react-navigation/drawer";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { ActivityIndicator, View } from "react-native";

// Screens
import LoginScreen from "./src/screens/auth/LoginScreen";

// Student Screens
import CatalogScreen from "./src/screens/student/CatalogScreen";
import MyLoansScreen from "./src/screens/student/MyLoansScreen";
import AdvancedSearchScreen from "./src/screens/student/AdvancedSearchScreen";
import ProfileScreen from "./src/screens/student/ProfileScreen";
import SupportScreen from "./src/screens/student/SupportScreen";

// Admin Screens
import AdminDashboardScreen from "./src/screens/admin/AdminDashboardScreen";
import AdminLoansScreen from "./src/screens/admin/AdminLoansScreen";
import AdminReservationsScreen from "./src/screens/admin/AdminReservationsScreen";
import AdminReportsScreen from "./src/screens/admin/AdminReportsScreen";
import AdminSettingsScreen from "./src/screens/admin/AdminSettingsScreen";

// Components
import MobileMenu from "./src/components/MobileMenu";
import MobileHeader from "./src/components/MobileHeader";

const Stack = createNativeStackNavigator();
const Drawer = createDrawerNavigator();

function AppDrawer({ user, onLogout }) {
  const isAdmin = user?.role === "admin";

  return (
    <Drawer.Navigator
      initialRouteName={isAdmin ? "Dashboard" : "Catalog"}
      drawerContent={(props) => (
        <MobileMenu
          {...props}
          role={user?.role}
          onLogout={onLogout}
        />
      )}
      screenOptions={({ navigation }) =>
        isAdmin
          ? {
              header: () => (
                <MobileHeader
                  onMenuClick={() => navigation.toggleDrawer()}
                  isMenuOpen={false}
                />
              ),
            }
          : { headerShown: false }
      }
    >
      {/* ROTAS ADMIN */}
      {isAdmin && (
        <>
          <Drawer.Screen 
            name="Dashboard" 
            component={AdminDashboardScreen}
            options={{ title: 'Dashboard' }}
          />
          <Drawer.Screen 
            name="Loans" 
            component={AdminLoansScreen}
            options={{ title: 'Empréstimos' }}
          />
          <Drawer.Screen
            name="Reservations"
            component={AdminReservationsScreen}
            options={{ title: 'Reservas' }}
          />
          <Drawer.Screen 
            name="Reports" 
            component={AdminReportsScreen}
            options={{ title: 'Relatórios' }}
          />
          <Drawer.Screen name="Settings">
            {(props) => <AdminSettingsScreen {...props} onLogout={onLogout} />}
          </Drawer.Screen>
        </>
      )}

      {/* ROTAS ALUNO*/}
      {!isAdmin && (
        <>
          <Drawer.Screen 
            name="Catalog" 
            component={CatalogScreen}
            options={{ title: 'Catálogo' }}
          />
          <Drawer.Screen 
            name="MyLoans" 
            component={MyLoansScreen}
            options={{ title: 'Meus Empréstimos' }}
          />
          <Drawer.Screen 
            name="Search" 
            component={AdvancedSearchScreen}
            options={{ title: 'Busca Avançada' }}
          />
          <Drawer.Screen 
            name="Profile" 
            component={ProfileScreen}
            options={{ title: 'Perfil' }}
          />
          <Drawer.Screen 
            name="Support" 
            component={SupportScreen}
            options={{ title: 'Suporte' }}
          />
        </>
      )}
    </Drawer.Navigator>
  );
}

export default function App() {
  const [loading, setLoading] = useState(true);
  const [user, setUser] = useState(null);

  useEffect(() => {
    async function loadUser() {
      try {
        const data = await AsyncStorage.getItem("user");
        if (data) {
          const parsedUser = JSON.parse(data);
          console.log('Usuário carregado:', parsedUser);
          setUser(parsedUser);
        }
      } catch (e) {
        console.log("Erro ao carregar user", e);
      } finally {
        setLoading(false);
      }
    }
    loadUser();
  }, []);

  async function handleLogout() {
    try {
      await AsyncStorage.removeItem("user");
      await AsyncStorage.removeItem("token");
      setUser(null);
    } catch (e) {
      console.log("Erro ao fazer logout", e);
    }
  }

  if (loading) {
    return (
      <View
        style={{
          flex: 1,
          justifyContent: "center",
          alignItems: "center",
          backgroundColor: "#fff",
        }}
      >
        <ActivityIndicator size="large" color="#dc2626" />
      </View>
    );
  }

  return (
    <NavigationContainer>
      <Stack.Navigator screenOptions={{ headerShown: false }}>
        {!user ? (
          <Stack.Screen name="Login">
            {(props) => <LoginScreen {...props} setUser={setUser} />}
          </Stack.Screen>
        ) : (
          <Stack.Screen name="MainDrawer">
            {(props) => <AppDrawer {...props} user={user} onLogout={handleLogout} />}
          </Stack.Screen>
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
}