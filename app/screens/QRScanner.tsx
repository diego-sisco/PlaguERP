import React, { useState } from 'react';
import { LogBox, StyleSheet, Text } from 'react-native';
import { Navigation } from 'react-native-navigation';
import QRCodeScanner from 'react-native-qrcode-scanner';
import { RNCamera } from 'react-native-camera';
import {DeviceType} from '../types/Device';
import {file_paths} from '../functions/FilePath';
import {readFile} from '../functions/FileHandling';

LogBox.ignoreAllLogs();

export const QRScanner = (props: any) => {
  const [scanned, setScanned] = useState(false);
  
  const onSuccess = async (e: any) => {
    var deviceId = null;
    if (!scanned && e.data) { // Verificar si no se ha escaneado antes
      console.log('code: ', e.data);
      let reports_devices: DeviceType[] = await readFile(
        file_paths.catalog.devices,
      );

      deviceId = reports_devices.find((item: DeviceType) => item.code == e.data)?.id;

      if (props.viewId && props.viewRedirect) { // Verificar si props.viewId y props.viewQRScanner están definidos
        Navigation.push(props.viewId, {
          component: {
            name: props.viewRedirect,
            passProps: {
              redirectID: props.redirectID,
              orderID: props.orderID,
              deviceID: deviceId,
              isScanned: true
            },
            options: {
              topBar: {
                title: {
                  text: 'Revisión de dispositivo',
                },
              },
            },
          },
        });
      } 
    }
  };
  
  

  return (
    <QRCodeScanner
      onRead={onSuccess}
      flashMode={RNCamera.Constants.FlashMode.auto}
      topContent={
        <Text style={styles.centerText}>
          Apunta el escáner hacia un código QR.
        </Text>
      }
      bottomContent={
        <Text style={styles.centerText}>
          {scanned ? 'Escaneado completado' : 'Escaneando...'}
        </Text>
      }
    />
  );
};

const styles = StyleSheet.create({
  centerText: {
    flex: 1,
    fontSize: 18,
    padding: 32,
    color: '#FFF',
  },
});

