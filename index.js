import {Navigation} from 'react-native-navigation';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {
  HomeScreen,
  LoginScreen,
  DetailsScreen,
  OrderScreen,
  QRScannerScreen,
  DeviceCheckScreen,
  ServiceScreen,
  DeviceScreen
} from './App';

import {Menu} from './app/screens/Menu';

Navigation.registerComponent('Home', () => HomeScreen);
Navigation.registerComponent('Order', () => OrderScreen);
Navigation.registerComponent('Device', () => DeviceScreen);
Navigation.registerComponent('Service', () => ServiceScreen);
Navigation.registerComponent('Details', () => DetailsScreen);
Navigation.registerComponent('Login', () => LoginScreen);
Navigation.registerComponent('Menu', () => Menu);
Navigation.registerComponent('QRScanner', () => QRScannerScreen);
Navigation.registerComponent('DeviceCheck', () => DeviceCheckScreen);

const checkStoredCredentials = async () => {
  let view = '';
  try {
    const storedEmail = await AsyncStorage.getItem('email');
    const storedPassword = await AsyncStorage.getItem('password');
    const storedId = await AsyncStorage.getItem('userID');

    view = storedEmail && storedPassword && storedId ? 'Home' : 'Login';

    Navigation.setRoot({
      root: {
        sideMenu: {
          left: {
            component: {
              name: 'Menu',
              passProps: {
                redirectName: 'Login',
                redirectHome: 'Home',
              },
            },
          },
          center: {
            stack: {
              children: [
                {
                  component: {
                    name: view,
                  },
                },
              ],
            },
          },
        },
      },
    });
  } catch (error) {
    console.error('Error al obtener datos de AsyncStorage:', error);
  }
};

Navigation.events().registerAppLaunchedListener(async () => {
  checkStoredCredentials();
});

Navigation.setDefaultOptions({
  statusBar: {
    backgroundColor: '#2471a3',
  },
  topBar: {
    title: {
      color: 'white',
      fontWeight: 'bold',
    },
    backButton: {
      color: 'white',
    },
    background: {
      color: '#2471a3',
    },
  },
});
