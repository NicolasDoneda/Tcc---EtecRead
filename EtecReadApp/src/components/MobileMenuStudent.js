// MENU LATERAL COM DRAWER NAVIGATOR 
import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
} from "react-native";
import {
  Book,
  BookOpen,
  Bookmark,
  Settings,
  LogOut,
  Home,
  X,
  User,
} from "lucide-react-native";

const menuItems = [
  { icon: Home, label: "Catálogo", screen: "Catalog" },
  { icon: BookOpen, label: "Busca Avançada", screen: "Search" },
  { icon: Bookmark, label: "Meus Empréstimos", screen: "MyLoans" },
  { icon: User, label: "Perfil", screen: "Profile" },
  { icon: Settings, label: "Suporte", screen: "Support" },
];

export default function MobileMenuStudent({ navigation, state, role, onLogout }) {
  const currentScreen = state?.routes[state?.index]?.name;

  return (
    <View style={styles.container}>
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
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#1f2937",
  },
  header: {
    paddingHorizontal: 18,
    paddingVertical: 22,
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
    marginTop: 16,
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