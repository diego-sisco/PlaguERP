import {StyleSheet} from 'react-native';

export const styles = StyleSheet.create({
  root: {
    width: '100%',
  },

  section: {
    margin: 15,
  },

  accordion: {
    color: 'black',
    borderWidth: 1,
    borderColor: 'black',
    borderRadius: 5,
    fontSize: 16,
    textAlign: 'justify',
    padding: 10,
    marginTop: 5,
  },

  collapse: {
    color: 'black',
    borderWidth: 1,
    borderColor: '#6c757d',
    borderRadius: 5,
    fontSize: 16,
    textAlign: 'justify',
    padding: 10,
    marginTop: 2,
    marginBottom: 5,
    backgroundColor: '#e9ecef',
  },

  title: {
    color: 'black',
    fontWeight: 'bold',
    fontSize: 20,
    borderBottomWidth: 1,
    borderBottomColor: 'black',
    paddingBottom: 5,
    marginBottom: 5,
  },

  text: {
    color: 'black',
    marginLeft: 10,
    marginRight: 10,
    fontSize: 16,
    textAlign: 'justify',
  },

  textCollapse: {
    color: 'black',
    fontSize: 16,
    textAlign: 'justify',
  },
  
  label: {
    color: 'black',
    fontWeight: 'bold',
    fontSize: 15,
    marginBottom: 8,
  },

  input: {
    color: 'black',
    borderWidth: 1,
    padding: 10,
    borderRadius: 5,
  },

  inputGroup: {
    margin: 5,
  },

  button: {
    margin: 5, 
  },

  buttonBR: {
    marginBottom: 10,
  },

  buttons: {
    margin: 0,
  },

  modalBG: {
    width: '100%',
    height: '100%',
    padding: 10,
  },

  centeredView: {
    width: '100%',
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.65)',
  },

  modalView: {
    width: '95%',
    backgroundColor: 'white',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    borderRadius: 10,
    },

  scrollView: {
    width: '100%',
    flexGrow: 1,
  },

  buttonsGroup: {
    margin: 15,
    marginBottom: 30,
  },

  

});

export default styles;
