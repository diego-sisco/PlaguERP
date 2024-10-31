import React from 'react';
import {View, Text, TouchableOpacity, StyleSheet} from 'react-native';
import {Navigation} from 'react-native-navigation';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {Item} from '../components/Item';

interface MenuProps {
  componentId: string;
  redirectName: string;
  redirectHome: string;
}
const clearAsyncStorage = async () => {
  try {
    await AsyncStorage.clear();
  } catch (error) {
    console.error('Error al limpiar AsyncStorage:', error);
  }
};

const handleLogout = async (componentId: string, redirectName: string) => {
  try {
    await clearAsyncStorage();
    Navigation.popToRoot(componentId);
    Navigation.setRoot({
      root: {
        stack: {
          children: [
            {
              component: {
                name: redirectName,
                options: {
                  topBar: {
                    title: { text: 'Iniciar Sesión' },
                  },
                },
              },
            },
          ],
        },
      },
    });
  } catch (error) {
    console.error('Error al realizar el cierre de sesión:', error);
  }
};

const handleHome = (componentId: string, redirectHome: string) => {
  Navigation.popToRoot(componentId);
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
                  name: redirectHome,
                },
              },
            ],
          },
        },
      },
    },
  });
};

export class Menu extends React.Component<MenuProps> {
  render() {
    return (
      <View style={styles.container}>
        <TouchableOpacity onPress={() => handleHome(this.props.componentId, this.props.redirectHome)}>
          <Item name="Inicio" color="black" iconName="home" iconSize={30} />
        </TouchableOpacity>
        <TouchableOpacity onPress={() => handleLogout(this.props.componentId, this.props.redirectName)}>
          <Item
            name="Cerrar Sesión"
            color="red"
            iconName="log-out-outline"
            iconSize={30}
          />
        </TouchableOpacity>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingTop: 50,
    paddingHorizontal: 20,
    backgroundColor: 'white',
  },
});
