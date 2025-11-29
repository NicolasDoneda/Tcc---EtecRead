import React from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, Linking } from 'react-native';
import { AlertCircle, Book, HelpCircle, CheckCircle, MapPin, Clock, Phone, Mail } from 'lucide-react-native';

export default function SupportScreen() {
  const faqs = [
    { id: 1, question: 'Como fazer um empréstimo?', answer: 'Navegue pelo catálogo, selecione um livro disponível e solicite o empréstimo. O bibliotecário irá aprovar sua solicitação.' },
    { id: 2, question: 'Quantos livros posso emprestar?', answer: 'Você pode ter até 3 empréstimos ativos simultaneamente.' },
    { id: 3, question: 'Por quanto tempo posso ficar com o livro?', answer: 'O prazo padrão de empréstimo é de 14 dias corridos.' },
    { id: 4, question: 'O que acontece se atrasar a devolução?', answer: 'Empréstimos atrasados podem resultar em suspensão temporária do seu acesso à biblioteca.' },
    { id: 5, question: 'Como renovar um empréstimo?', answer: 'Entre em contato com a biblioteca ou vá até o balcão de atendimento.' },
    { id: 6, question: 'Como atualizar meu perfil?', answer: 'Acesse a aba "Perfil", clique em "Editar Perfil" e faça as alterações desejadas.' },
  ];

  const handleEmailContact = () => Linking.openURL('mailto:biblioteca@etecguarulhos.sp.gov.br');
  const handlePhoneContact = () => Linking.openURL('tel:+551120870100');

  return (
    <ScrollView style={styles.container}>
      {/* Cabeçalho */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Central de Ajuda</Text>
        <Text style={styles.headerSubtitle}>Tudo o que você precisa saber sobre a biblioteca</Text>
      </View>

      {/* Alerta de cadastro */}
      <View style={styles.alertCard}>
        <AlertCircle size={20} color="#ef4444" />
        <View style={{ marginLeft: 10, flex: 1 }}>
          <Text style={styles.alertTitle}>Como fazer seu cadastro:</Text>
          <Text style={styles.alertText}>
            Para se cadastrar e ter acesso ao aplicativo, é necessário comparecer pessoalmente
            à biblioteca da <Text style={{ fontWeight: 'bold' }}>Etec de Guarulhos</Text> com documento de identificação válido.
          </Text>
        </View>
      </View>

      {/* Contato rápido */}
      <View style={styles.card}>
        <Text style={styles.cardTitle}><MapPin size={18} color="#ef4444" /> Biblioteca Etec de Guarulhos</Text>

        <View style={styles.infoItem}>
          <MapPin size={16} color="#6b7280" />
          <View style={{ marginLeft: 10 }}>
            <Text style={styles.infoLabel}>Endereço:</Text>
            <Text style={styles.infoText}>R. Cristóbal Cláudio Elilo, 88 - Parque Cecap, Guarulhos - SP, 07190-065</Text>
          </View>
        </View>

        <View style={styles.infoItem}>
          <Clock size={16} color="#6b7280" />
          <View style={{ marginLeft: 10 }}>
            <Text style={styles.infoLabel}>Horário de Funcionamento:</Text>
            <Text style={styles.infoText}>Segunda a Sexta: 8h às 19h</Text>
          </View>
        </View>

        <TouchableOpacity style={styles.infoItem} onPress={handlePhoneContact}>
          <Phone size={16} color="#6b7280" />
          <View style={{ marginLeft: 10 }}>
            <Text style={styles.infoLabel}>Telefone:</Text>
            <Text style={styles.infoText}>(11) 2087-0100</Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity style={styles.infoItem} onPress={handleEmailContact}>
          <Mail size={16} color="#6b7280" />
          <View style={{ marginLeft: 10 }}>
            <Text style={styles.infoLabel}>E-mail:</Text>
            <Text style={styles.infoText}>biblioteca@etecguarulhos.sp.gov.br</Text>
          </View>
        </TouchableOpacity>
      </View>


      {/* FAQ */}
      <View style={styles.card}>
        <Text style={styles.cardTitle}><HelpCircle size={18} color="#ef4444" /> Perguntas Frequentes</Text>
        {faqs.map((faq) => (
          <View key={faq.id} style={styles.faqItem}>
            <Text style={styles.faqQuestion}>❓ {faq.question}</Text>
            <Text style={styles.faqAnswer}>{faq.answer}</Text>
          </View>
        ))}
      </View>

    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f5f5f5' },
  header: { padding: 20, backgroundColor: 'white', borderBottomLeftRadius: 20, borderBottomRightRadius: 20, alignItems: 'center' },
  headerTitle: { fontSize: 24, fontWeight: 'bold', color: '#374151', marginBottom: 5 },
  headerSubtitle: { fontSize: 14, color: '#6b7280', textAlign: 'center' },

  alertCard: { flexDirection: 'row', alignItems: 'flex-start', backgroundColor: '#fee2e2', padding: 15, borderRadius: 10, margin: 15 },
  alertTitle: { fontWeight: 'bold', color: '#374151', marginBottom: 5 },
  alertText: { color: '#374151', fontSize: 14 },

  card: { backgroundColor: 'white', padding: 15, borderRadius: 10, marginHorizontal: 15, marginVertical: 10 },
  cardTitle: { fontSize: 16, fontWeight: 'bold', color: '#374151', marginBottom: 10, flexDirection: 'row', alignItems: 'center' },

  infoItem: { flexDirection: 'row', alignItems: 'flex-start', marginBottom: 10 },
  infoLabel: { fontSize: 14, fontWeight: 'bold', color: '#374151' },
  infoText: { fontSize: 14, color: '#6b7280' },

  instructionsContainer: { marginTop: 10 },
  instructionCard: { flexDirection: 'row', alignItems: 'flex-start', marginBottom: 10 },
  stepContent: { marginLeft: 10, flex: 1 },
  stepTitle: { fontSize: 14, fontWeight: 'bold', color: '#374151', marginBottom: 3 },
  stepDescription: { fontSize: 14, color: '#6b7280' },

  faqItem: { marginBottom: 10 },
  faqQuestion: { fontWeight: 'bold', color: '#374151', marginBottom: 5 },
  faqAnswer: { color: '#6b7280', fontSize: 14 },
});
