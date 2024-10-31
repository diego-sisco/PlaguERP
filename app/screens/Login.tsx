import React, {useState, useEffect} from 'react';
import {Button, Image, ToastAndroid, View} from 'react-native';
import {Navigation} from 'react-native-navigation';
import {loginAuth} from '../api/Login/Post';
import {InputGroup} from '../components/InputGroup';
import {styles} from '../styles/app';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {Text} from 'react-native-elements';

type Authentication = {
  email: string;
  password: string;
};

export const Login = (props: any) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const showToast = (message: string) => {
    ToastAndroid.show(message, ToastAndroid.SHORT);
  };

  const handleAuth = async () => {
    if (!email || !password) {
      showToast('Por favor, ingresa el correo electrónico y la contraseña.');
      return;
    }

    const data: Authentication = {
      email: email,
      password: password,
    };

    try {
      const res = await loginAuth(data);
      const success = res?.success;

      if (success) {
        const userId = String(res.userID || '0');
        const {email, password} = data;

        await AsyncStorage.setItem('email', email);
        await AsyncStorage.setItem('password', password);
        await AsyncStorage.setItem('userID', userId);

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
                        name: 'Home',
                      },
                    },
                  ],
                },
              },
            },
          },
        });
      } else {
        showToast('Fallo en autenticación. Revisa email y contraseña.');
      }
    } catch (error) {
      if (
        error instanceof TypeError &&
        error.message.includes("Cannot read property 'toString' of undefined")
      ) {
        console.warn('Ignorando el error:', error.message);
      } else {
        console.error('Error:', error);
      }
    }
  };

  return (
    <View style={styles.rootCenter}>
      <View style={styles.imageContainer}>
        <Image
          source={require('../assets/images/logo.png')}
          style={styles.image}
        />
      </View>
      <View style={styles.welcome}>
        <Text style={styles.h1}>Iniciar Sesión</Text>
      </View>
      <View style={styles.section}>
        <InputGroup
          title="Correo Electrónico"
          setText={setEmail}
          password={false}
          iconName="person"
        />
        <InputGroup
          title="Contraseña"
          setText={setPassword}
          password={true}
          iconName="eye"
        />
        <View style={styles.button}>
          <Button
            title="Entrar"
            color="#0d6efd"
            onPress={() => {
              handleAuth();
            }}
          />
        </View>
      </View>
    </View>
  );
};
