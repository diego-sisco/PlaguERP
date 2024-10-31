import {StyleSheet, Touchable} from 'react-native';

export const styles = StyleSheet.create({
  root: {
    backgroundColor: 'white',
    flex: 1,
    padding: 10,
  },

  section: {
    marginBottom: 10,
    color: '#000',
  },

  sectionTitle: {
    display: 'flex',
    flexDirection: 'row',
    width: '100%',
    marginTop: 10,
    paddingBottom: 2,
    borderBottomWidth: 1,
  },

  title: {
    color: '#000',
    fontWeight: '600',
    fontSize: 20,
  },

  subtitle: {
    color: '#000',
    fontWeight: '500',
    fontSize: 16,
    marginVertical: 5,
  },

  modalButton: {
    paddingHorizontal: 5,
    width: '50%',
  },

  button: {
    padding: 10,
    width: '100%',
  },

  touchableButton: {
    display: 'flex',
    flexDirection: 'row',
    alignItems: 'center', // Alinea verticalmente los elementos en el centro
    justifyContent: 'space-evenly',
    borderWidth: 1,
    borderRadius: 5,
    borderColor: 'transparent',
    backgroundColor: '#fd7e14',
    color: '#fff',
    padding: 3, // Espaciado interno para mayor comodidad
    elevation: 2, // Sombra en Android
    shadowColor: '#000', // Sombra en iOS
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 2,
  },

  touchableButtonText: {
    fontWeight: '500',
    color: '#fff',
    fontSize: 15,
  },

  buttons: {
    width: '100%',
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginVertical: 10,
  },

  table: {
    borderWidth: 0.5,
    borderColor: '#6c757d',
    marginBottom: 5,
  },

  row: {
    flexDirection: 'row',
  },

  headerCell: {
    flex: 1,
    borderWidth: 0.5,
    borderColor: '#6c757d',
    padding: 8,
    textAlign: 'center',
    color: '#000',
    backgroundColor: '#e9ecef',
  },

  cell: {
    flex: 1,
    borderWidth: 0.5,
    borderColor: '#6c757d',
    padding: 8,
    textAlign: 'center',
    color: '#000',
  },

  dropdown: {
    borderColor: '#6c757d',
    marginBottom: 15,
    width: '100%',
    maxHeight: 300,
  },

  label: {
    color: '#000',
    marginBottom: 10,
    fontSize: 16,
  },

  buttonCollapse: {
    width: '100%',
  },

  content: {
    display: 'flex',
    flexDirection: 'row',
    justifyContent:'center',
    alignItems: 'center',
    width: '100%',
  },

  accordion: {
    color: '#000',
    backgroundColor: '#6c757d',
    borderWidth: 1,
    fontSize: 20,
    padding: 10,
  },

  collapse: {
    borderWidth: 1,
    borderTopWidth: 0,
    padding: 10,
  },

  middleCollapse: {
    borderWidth: 1,
    borderTopWidth: 0,
    borderBottomWidth: 0,
    padding: 10,
  },

  text: {
    color: '#000',
    fontSize: 16,
    marginBottom: 2,
    flex: 1,
    flexWrap: 'wrap',
  },

  checkbox: {
    display: 'flex',
    flexDirection: 'row',
    justifyContent:'center',
    alignItems: 'center',
    width: '100%',
    marginBottom: 10,
  },

  centerContent: {
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: 5,
    padding: 10,
    width: '100%',
  },

  centerText: {
    flex: 1,
    fontSize: 18,
    padding: 32,
    color: '#777',
  },

  checkboxGroup: {
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    borderRadius: 5,
    display: 'flex',
    flexDirection: 'row',
    flexWrap: 'wrap',
    margin: 10,
  },

  checkboxText: {
    color: '#000',
    fontSize: 16,
  },

  collapseHeader: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#fff',
  },

  h1: {
    fontSize: 26,
    fontWeight: '900',
    textAlign: 'left',
    color: '#000'
  },

  h2: {
    fontSize: 25,
    fontWeight: 'bold',
    textAlign: 'left',
  },

  h3: {
    color: '#000',
    fontSize: 18,
    fontWeight: '500',
    textAlign: 'left',
  },

  h4: {
    color: '#000',
    fontSize: 15,
    fontWeight: '500',
    textAlign: 'left',
  },

  icon: {
    alignItems: 'center',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
  },

  image: {
    height: 100,
    resizeMode: 'contain',
    width: 100,
  },

  imageContainer: {
    marginBottom: 30,
  },

  input: {
    backgroundColor: 'white',
    borderColor: '#6c757d',
    borderRadius: 5,
    borderWidth: 1,
    color: '#000',
    fontSize: 16,
    padding: 4,
    width: 'auto',
  },

  inputContent: {
    alignItems: 'center',
    borderBottomWidth: 1,
    borderColor: '#000',
    display: 'flex',
    flexDirection: 'row',
    margin: 5,
    width: '90%',
  },

  inputDate: {
    backgroundColor: '#e9ecef',
    borderRadius: 5,
    borderWidth: 1,
    borderColor: '#6c757d',
    padding: 10,
    width: '50%',
  },

  inputLine: {
    color: 'black',
    flex: 1,
    fontSize: 16,
  },

  itemAccordion: {
    borderRadius: 5,
    borderWidth: 1,
    margin: 5,
    padding: 10,
  },

  list: {
    alignItems: 'center',
    display: 'flex',
    flexDirection: 'row',
    marginTop: 2,
  },

  modalBG: {
    height: '100%',
    padding: 10,
    width: '100%',
  },

  modalView: {
    backgroundColor: 'white',
    borderRadius: 5,
    height: '80%',
    shadowColor: '#000',
    shadowOffset: {
      height: 2,
      width: 0,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    width: '80%',
  },

  navButton: {
    alignItems: 'center',
    backgroundColor: 'white',
    borderColor: '#6c757d',
    borderRadius: 5,
    borderWidth: 1,
    elevation: 5,
    justifyContent: 'center',
    padding: 8,
    shadowColor: '#000',
    shadowOffset: {height: 2, width: 0},
    shadowOpacity: 0.3,
    shadowRadius: 5,
    width: '20%',
  },

  rootCenter: {
    alignItems: 'center',
    backgroundColor: 'white',
    flex: 1,
    justifyContent: 'center',
  },

  scrollView: {
    flexGrow: 1,
    padding: 5,
    width: '100%',
  },

  signatureContainer: {
    backgroundColor: '#fff', // Color de fondo opcional
    width: '80%',
    padding: 5,
    borderWidth: 1,
    borderRadius: 5,
    borderColor: '#fff',
  },

  signature: {
    width: '100%',
    height: '100%',
  },

  textCenter: {
    color: '#000',
    fontSize: 16,
    fontWeight: 'bold',
    textAlign: 'center',
  },

  textDanger: {
    color: '#dc3545',
    fontSize: 15,
  },

  welcome: {
    margin: 15,
  },

  dropdownButton: {
    width: '100%',
    backgroundColor: '#f1f1f1',
    borderRadius: 5,
    padding: 10,
  },

  dropdownButtonText: {
    fontSize: 16,
    textAlign: 'left',
  },

  dropdownRow: {
    backgroundColor: '#f9f9f9',
  },

  dropdownRowText: {
    fontSize: 16,
    textAlign: 'left',
  },

  questionContainer: {
    marginBottom: 16,
  },

  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },

  modalBackground: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.7)', // Fondo oscuro
  },

  modalContainer: {
    width: '90%',
    padding: 20,
    backgroundColor: 'white',
    borderRadius: 5,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
});
