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

// Recebe onLogout como prop 
function AppDrawer({ user, onLogout }) {
  const isAdmin = user.role === "admin";

  return (
    <Drawer.Navigator
      initialRouteName={isAdmin ? "Dashboard" : "Catalog"}
      drawerContent={(props) => (
        <MobileMenu
          {...props}
          role={user.role}
          onLogout={onLogout} // Passando a function de logOut
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
          <Drawer.Screen name="Dashboard" component={AdminDashboardScreen} />
          <Drawer.Screen name="Loans" component={AdminLoansScreen} />
          <Drawer.Screen
            name="Reservations"
            component={AdminReservationsScreen}
          />
          <Drawer.Screen name="Reports" component={AdminReportsScreen} />
          <Drawer.Screen name="Settings" component={AdminSettingsScreen} />
        </>
      )}

      {/* ROTAS ALUNO*/}
      {!isAdmin && (
        <>
          <Drawer.Screen name="Catalog" component={CatalogScreen} />
          <Drawer.Screen name="MyLoans" component={MyLoansScreen} />
          <Drawer.Screen name="Search" component={AdvancedSearchScreen} />
          <Drawer.Screen name="Profile" component={ProfileScreen} />
          <Drawer.Screen name="Support" component={SupportScreen} />
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
        if (data) setUser(JSON.parse(data));
      } catch (e) {
        console.log("Erro ao carregar user", e);
      } finally {
        setLoading(false);
      }
    }
    loadUser();
  }, []);

  // FUNÇÃO QUE LIMPA O ESTADO DO USUÁRIO
  async function handleLogout() {
    await AsyncStorage.removeItem("user");
    setUser(null);
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
            {/* ✅ PASSANDO handleLogout para AppDrawer */}
            {(props) => <AppDrawer {...props} user={user} onLogout={handleLogout} />}
          </Stack.Screen>
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
}