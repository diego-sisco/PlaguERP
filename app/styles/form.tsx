import {StyleSheet} from 'react-native';

export const styles = StyleSheet.create({
  label: {
    color: 'black',
    fontWeight: 'bold',
    fontSize: 16,
    marginBottom: 8,
  },

  inputContent: {
    display: 'flex',
    flexDirection: 'row',
    alignContent: 'center',
    borderBottomWidth: 1,
    borderColor: '#6c757d',
    margin: 5,
  },

  icon: {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    width: '10%',
  },

  inputLine: {
    flex: 1,
    color: 'black',
    fontSize: 16,
    padding: 8,
  },

  button: {
    margin: 10,
    marginTop: 20,
    borderRadius: 5,
    overflow: 'hidden',
    fontSize: 28,
  },
});
