import React from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  Linking,
} from 'react-native';

export default function SupportScreen() {
  const faqs = [
    {
      id: 1,
      question: 'Como fazer um empréstimo?',
      answer: 'Navegue pelo catálogo, selecione um livro disponível e solicite o empréstimo. O bibliotecário irá aprovar sua solicitação.',
    },
    {
      id: 2,
      question: 'Quantos livros posso emprestar?',
      answer: 'Você pode ter até 3 empréstimos ativos simultaneamente.',
    },
    {
      id: 3,
      question: 'Por quanto tempo posso ficar com o livro?',
      answer: 'O prazo padrão de empréstimo é de 14 dias corridos.',
    },
    {
      id: 4,
      question: 'O que acontece se atrasar a devolução?',
      answer: 'Empréstimos atrasados podem resultar em suspensão temporária do seu acesso à biblioteca.',
    },
    {
      id: 5,
      question: 'Como renovar um empréstimo?',
      answer: 'Entre em contato com a biblioteca ou vá até o balcão de atendimento.',
    },
    {
      id: 6,
      question: 'Como atualizar meu perfil?',
      answer: 'Acesse a aba "Perfil", clique em "Editar Perfil" e faça as alterações desejadas.',
    },
  ];

  const handleEmailContact = () => {
    Linking.openURL('mailto:biblioteca@etec.com');
  };

  const handlePhoneContact = () => {
    Linking.openURL('tel:+5511999999999');
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerIcon}>📚</Text>
        <Text style={styles.headerTitle}>Central de Ajuda</Text>
        <Text style={styles.headerSubtitle}>
          Encontre respostas para suas dúvidas
        </Text>
      </View>

      <View style={styles.quickActionsContainer}>
        <Text style={styles.sectionTitle}>Contato Rápido</Text>
        
        <TouchableOpacity
          style={styles.actionCard}
          onPress={handleEmailContact}
        >
          <Text style={styles.actionIcon}>📧</Text>
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Email</Text>
            <Text style={styles.actionSubtitle}>biblioteca@etec.com</Text>
          </View>
          <Text style={styles.actionArrow}>›</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionCard}
          onPress={handlePhoneContact}
        >
          <Text style={styles.actionIcon}>📞</Text>
          <View style={styles.actionInfo}>
            <Text style={styles.actionTitle}>Telefone</Text>
            <Text style={styles.actionSubtitle}>(11) 99999-9999</Text>
          </View>
          <Text style={styles.actionArrow}>›</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.faqContainer}>
        <Text style={styles.sectionTitle}>Perguntas Frequentes</Text>
        
        {faqs.map((faq) => (
          <View key={faq.id} style={styles.faqCard}>
            <Text style={styles.faqQuestion}>❓ {faq.question}</Text>
            <Text style={styles.faqAnswer}>{faq.answer}</Text>
          </View>
        ))}
      </View>

      <View style={styles.instructionsContainer}>
        <Text style={styles.sectionTitle}>Como usar o app</Text>
        
        <View style={styles.instructionCard}>
          <View style={styles.stepNumber}>
            <Text style={styles.stepText}>1</Text>
          </View>
          <View style={styles.stepContent}>
            <Text style={styles.stepTitle}>Navegue pelo Catálogo</Text>
            <Text style={styles.stepDescription}>
              Explore todos os livros disponíveis na biblioteca
            </Text>
          </View>
        </View>

        <View style={styles.instructionCard}>
          <View style={styles.stepNumber}>
            <Text style={styles.stepText}>2</Text>
          </View>
          <View style={styles.stepContent}>
            <Text style={styles.stepTitle}>Use a Busca Avançada</Text>
            <Text style={styles.stepDescription}>
              Filtre por título, categoria ou autor
            </Text>
          </View>
        </View>

        <View style={styles.instructionCard}>
          <View style={styles.stepNumber}>
            <Text style={styles.stepText}>3</Text>
          </View>
          <View style={styles.stepContent}>
            <Text style={styles.stepTitle}>Acompanhe seus Empréstimos</Text>
            <Text style={styles.stepDescription}>
              Veja prazos de devolução e histórico
            </Text>
          </View>
        </View>

        <View style={styles.instructionCard}>
          <View style={styles.stepNumber}>
            <Text style={styles.stepText}>4</Text>
          </View>
          <View style={styles.stepContent}>
            <Text style={styles.stepTitle}>Mantenha seu Perfil Atualizado</Text>
            <Text style={styles.stepDescription}>
              Atualize suas informações quando necessário
            </Text>
          </View>
        </View>
      </View>

      <View style={styles.footer}>
        <Text style={styles.footerText}>
          Sistema de Biblioteca Escolar
        </Text>
        <Text style={styles.footerVersion}>Versão 1.0.0</Text>
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
  },
  headerIcon: {
    fontSize: 60,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 10,
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#666',
    marginTop: 5,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  quickActionsContainer: {
    padding: 20,
  },
  actionCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  actionIcon: {
    fontSize: 30,
    marginRight: 15,
  },
  actionInfo: {
    flex: 1,
  },
  actionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  actionSubtitle: {
    fontSize: 14,
    color: '#666',
    marginTop: 2,
  },
  actionArrow: {
    fontSize: 30,
    color: '#ccc',
  },
  faqContainer: {
    padding: 20,
  },
  faqCard: {
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 2,
  },
  faqQuestion: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 8,
  },
  faqAnswer: {
    fontSize: 14,
    color: '#666',
    lineHeight: 20,
  },
  instructionsContainer: {
    padding: 20,
  },
  instructionCard: {
    flexDirection: 'row',
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 2,
  },
  stepNumber: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#007AFF',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 15,
  },
  stepText: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
  },
  stepContent: {
    flex: 1,
  },
  stepTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 4,
  },
  stepDescription: {
    fontSize: 14,
    color: '#666',
  },
  footer: {
    alignItems: 'center',
    padding: 30,
  },
  footerText: {
    fontSize: 14,
    color: '#999',
  },
  footerVersion: {
    fontSize: 12,
    color: '#ccc',
    marginTop: 5,
  },
});