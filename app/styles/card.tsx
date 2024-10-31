import {StyleSheet} from 'react-native';

export const styles = StyleSheet.create({
  card: {
    backgroundColor: 'white',
    borderColor: '#ced4da',
    borderRadius: 10,
    borderWidth: 1,
    padding: 20,
    margin: 10,
  },

  title: {
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'baseline',
    width: '100%',
    marginBottom: 10,
  },

  textTitle: {
    color: '#000',
    fontSize: 22,
    fontWeight: 'bold',
  },

  text: {
    color: 'black',
    fontSize: 15,
    maxWidth: 200,
  },

  button: {
    marginTop: 20,
    borderRadius: 5,
    overflow: 'hidden',
    fontSize: 28,
  },

  list: {
    display: 'flex',
    flexDirection: 'row',
    alignContent: 'center',
    marginTop: 2,
  },

  icon: {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    width: '8%',
  },
});
