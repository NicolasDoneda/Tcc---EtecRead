// HEADER DO ETEC READ (SOMENTE PARA ADMIN)
import React from "react";
import { View, Text, TouchableOpacity, StyleSheet } from "react-native";
import { Menu, Bell } from "lucide-react-native";

export default function MobileHeader({ onMenuClick, isMenuOpen }) {
  return (
    <View
      style={[
        styles.header,
        { backgroundColor: isMenuOpen ? "#1f2937" : "#dc2626" }
      ]}
    >
      <View style={styles.leftContainer}>
        <TouchableOpacity onPress={onMenuClick} style={styles.iconButton}>
          <Menu size={26} color="#fff" />
        </TouchableOpacity>

        <View>
          <Text style={styles.title}>EtecRead</Text>
          <Text style={styles.subtitle}>Dashboard Admin</Text>
        </View>
      </View>

      <TouchableOpacity style={styles.notificationBtn}>
        <Bell size={22} color="#fff" />
        <View style={styles.badge}>
          <Text style={styles.badgeText}>3</Text>
        </View>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  header: {
    paddingHorizontal: 16,
    paddingVertical: 14,
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    elevation: 4,
    zIndex: 50,
  },
  leftContainer: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
  },
  iconButton: {
    padding: 8,
  },
  title: {
    color: "#fff",
    fontSize: 18,
    fontWeight: "600",
  },
  subtitle: {
    color: "#e5e7eb",
    fontSize: 12,
  },
  notificationBtn: {
    padding: 6,
  },
  badge: {
    position: "absolute",
    top: -4,
    right: -4,
    backgroundColor: "#ef4444",
    minWidth: 16,
    height: 16,
    borderRadius: 8,
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1.5,
    borderColor: "#fff",
    paddingHorizontal: 3,
  },
  badgeText: {
    color: "#fff",
    fontSize: 10,
  },
});
