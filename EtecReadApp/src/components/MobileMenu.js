// MENU LATERAL COM DRAWER NAVIGATOR 
import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Pressable,
  ScrollView,
} from "react-native";
import {
  Book,
  BookOpen,
  Bookmark,
  BarChart3,
  Settings,
  LogOut,
  Home,
  X,
} from "lucide-react-native";

const menuItems = [
  { icon: Home, label: "Dashboard", screen: "Dashboard" },
  { icon: BookOpen, label: "Empréstimos", screen: "Loans" },
  { icon: Bookmark, label: "Reservas", screen: "Reservations" },
  { icon: BarChart3, label: "Relatórios", screen: "Reports" },
  { icon: Settings, label: "Configurações", screen: "Settings" },
];

// ✅ Recebe a prop onLogout
export default function MobileMenu({ navigation, role, onLogout }) {
  const currentScreen =
    navigation.getState()?.routes[navigation.getState().index]?.name;

  return (
    <>
      {/* BACKDROP */}
      <Pressable
        style={styles.backdrop}
        onPress={() => navigation.closeDrawer()}
      />

      {/* MENU LATERAL */}
      <View style={styles.sidebar}>
        <View style={styles.header}>
          <View style={styles.logoContainer}>
            <View style={styles.logoBox}>
              <Book size={24} color="#fff" />
            </View>
            <View>
              <Text style={styles.logoTitle}>EtecRead</Text>
              <Text style={styles.logoSubtitle}>Sistema de Biblioteca</Text>
            </View>
          </View>

          <TouchableOpacity
            onPress={() => navigation.closeDrawer()}
            style={styles.closeBtn}
          >
            <X size={22} color="#fff" />
          </TouchableOpacity>
        </View>

        <View style={styles.userSection}>
          <View style={styles.userBox}>
            <View style={styles.userAvatar}>
              <Text style={styles.userAvatarText}>
                {role === "admin" ? "A" : "U"}
              </Text>
            </View>

            <View>
              <Text style={styles.userName}>
                {role === "admin" ? "Admin" : "Usuário"}
              </Text>
              <Text style={styles.userRole}>
                {role === "admin" ? "Administrador" : "Aluno"}
              </Text>
            </View>
          </View>

          <TouchableOpacity
            style={styles.logoutBtnBig}
            onPress={() => {
              // Fecha o menu e chama a função de logout do App.js
              navigation.closeDrawer();
              onLogout();
            }}
          >
            <LogOut size={22} color="#fff" />
            <Text style={styles.logoutTextBig}>Sair</Text>
          </TouchableOpacity>
        </View>

        {/* MENU */}
        <ScrollView style={styles.menuList}>
          {menuItems.map((item) => {
            const Icon = item.icon;
            const isActive = currentScreen === item.screen;

            return (
              <TouchableOpacity
                key={item.label}
                onPress={() => {
                  navigation.navigate(item.screen);
                  navigation.closeDrawer();
                }}
                style={[styles.menuItem, isActive && styles.activeItem]}
              >
                <Icon size={22} color={isActive ? "#fff" : "#e5e7eb"} />
                <Text style={[styles.menuLabel, isActive && styles.activeLabel]}>
                  {item.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </ScrollView>
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  backdrop: {
    position: "absolute",
    inset: 0,
    backgroundColor: "rgba(0,0,0,0.5)",
    zIndex: 40,
  },
  sidebar: {
    position: "absolute",
    left: 0,
    top: 0,
    width: 280,
    height: "100%",
    backgroundColor: "#1f2937",
    zIndex: 50,
    paddingVertical: 10,
  },
  header: {
    paddingHorizontal: 16,
    paddingBottom: 14,
    flexDirection: "row",
    justifyContent: "space-between",
    borderBottomWidth: 1,
    borderBottomColor: "#374151",
    alignItems: "center",
  },
  logoContainer: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
  },
  logoBox: {
    width: 40,
    height: 40,
    borderRadius: 8,
    backgroundColor: "#dc2626",
    justifyContent: "center",
    alignItems: "center",
  },
  logoTitle: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
  logoSubtitle: {
    color: "#d1d5db",
    fontSize: 12,
  },
  closeBtn: {
    padding: 8,
  },
  userSection: {
    paddingHorizontal: 16,
    marginBottom: 10,
  },
  userBox: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    backgroundColor: "#374151",
    padding: 10,
    borderRadius: 10,
    marginBottom: 12,
  },
  userAvatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "#dc2626",
    justifyContent: "center",
    alignItems: "center",
  },
  userAvatarText: {
    color: "#fff",
    fontSize: 18,
    fontWeight: "600",
  },
  userName: {
    color: "#fff",
    fontSize: 14,
  },
  userRole: {
    color: "#d1d5db",
    fontSize: 12,
  },
  logoutBtnBig: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    gap: 10,
    backgroundColor: "#dc2626",
    paddingVertical: 12,
    borderRadius: 10,
  },
  logoutTextBig: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
  menuList: {
    paddingHorizontal: 16,
    marginTop: 12,
  },
  menuItem: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 12,
    gap: 12,
  },
  menuLabel: {
    color: "#e5e7eb",
    fontSize: 16,
  },
  activeItem: {
    backgroundColor: "#dc2626",
    borderRadius: 6,
    paddingHorizontal: 10,
  },
  activeLabel: {
    color: "#fff",
    fontWeight: "600",
  },
});