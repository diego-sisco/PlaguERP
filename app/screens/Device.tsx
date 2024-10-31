import React, {useState, useEffect} from 'react';
import {
  ScrollView,
  Text,
  View,
  Button,
  ToastAndroid,
  TouchableOpacity,
} from 'react-native';
import {styles} from '../styles/app';
import {OrderType} from '../types/Order';
import {file_paths} from '../functions/FilePath';
import {ServiceType} from '../types/Service';
import {readFile, writeFile} from '../functions/FileHandling';
import {DeviceType} from '../types/Device';
import Icon from 'react-native-vector-icons/Ionicons';
import {Navigation} from 'react-native-navigation';
import { ReviewDevice } from '../types/ReviewDevice';

const getIndex = (data: any, id: number) => {
  return data.findIndex((element: any) => element.id == id);
};

export const Device = (props: any) => {
  const orderID: number = props.orderID;
  const [services, setServices] = useState<ServiceType[]>([]);
  const [devices, setDevices] = useState<DeviceType[]>([]);
  const [reviews, setReviews] = useState<ReviewDevice[]>([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    const getData = async () => {
      try {
        const orders: OrderType[] = await readFile(file_paths.orders);
        const stored_services: ServiceType[] = await readFile(
          file_paths.catalog.services,
        );
        const stored_devices: DeviceType[] = await readFile(
          file_paths.catalog.devices,
        );

        const fetched_reviews: ReviewDevice[] = await readFile(
          file_paths.data.reviewDevices,
        );

        setDevices(stored_devices);
        setReviews(fetched_reviews.filter(review => review.order_id == orderID));

        if (orders) {
          const index = getIndex(orders, orderID);
          if (index != -1) {
            const serviceIds = orders[index].servicesID;

            if (stored_services) {
              const filtered_services: ServiceType[] = stored_services.filter(
                (service: ServiceType) => serviceIds.includes(service.id),
              );
              setServices(filtered_services);
            }
          }
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    getData();
  }, [orderID]);

  const handleQRScanner = () => {
    Navigation.push(props.viewId, {
      component: {
        name: props.viewQRScanner,
        passProps: {
          redirectID: props.viewId,
          orderID: orderID,
        },
        options: {
          topBar: {
            title: {
              text: 'Escaner QR',
            },
          },
        },
      },
    });
  };

  const refreshPage = async () => {
    setRefreshing(true);
    try {
      const fetched_reviews: ReviewDevice[] = await readFile(
        file_paths.data.reviewDevices,
      );

      setReviews(fetched_reviews.filter(review => review.order_id == orderID));
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setRefreshing(false);
    }
  };

  const handleDevice = (device_id: number, service_id: number) => {
    Navigation.push(props.viewId, {
      component: {
        name: props.viewDeviceCheck,
        passProps: {
          redirectID: props.viewId,
          orderID: orderID,
          deviceID: device_id,
          serviceID: service_id,
          isScanned: false,
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
  };

  const handleComplete = async () => {
    try {
      Navigation.push(props.viewId, {
        component: {
          name: props.viewDetails,
          passProps: {
            redirectID: props.viewId,
            orderID: orderID,
          },
          options: {
            topBar: {
              title: {
                text: 'Detalles',
              },
            },
          },
        },
      });
    } catch (error) {
      console.error('Error al completar la tarea:', error);
      showToast(
        'Ocurrió un error al completar la tarea. Por favor, inténtelo de nuevo.',
      );
    }
  };

  const showToast = (message: string) => {
    ToastAndroid.show(message, ToastAndroid.SHORT);
  };

  return (
    <ScrollView style={styles.root}>
      {services?.map((service: ServiceType) => {
        const devicesIds = service.devicesID;
        const filtered_devices: DeviceType[] | undefined = devices?.filter(
          (device: DeviceType) => devicesIds.includes(device.id),
        );
        const bg_btn1 = '#fd7e14';
        const bg_btn2 = '#212529'
        return (
          <View>
            <View style={styles.buttons}>
              <View style={styles.modalButton}>
                <TouchableOpacity
                  style={[styles.touchableButton, { backgroundColor: bg_btn1 }]}>
                  <Icon
                    name="refresh"
                    size={30}
                    color="#fff"
                    onPress={refreshPage}
                  />
                  <Text style={styles.touchableButtonText}>Refrescar</Text>
                </TouchableOpacity>
              </View>
              <View style={styles.modalButton}>
                <TouchableOpacity style={[styles.touchableButton, { backgroundColor: bg_btn2 }]} onPress={handleQRScanner}>
                  <Icon name="qr-code" size={30} color="#fff" />
                  <Text style={styles.touchableButtonText}>Escanear QR</Text>
                </TouchableOpacity>
              </View>
            </View>
            <View style={[styles.section]}>
              <View style={styles.sectionTitle}>
                <View
                  style={{
                    width: '100%',
                  }}>
                  <Text style={styles.title}>{service.name}</Text>
                </View>
              </View>
              <View style={styles.section}>
                <Text style={styles.subtitle}>[Dispositivos]</Text>
                {filtered_devices?.map((device: DeviceType, i) => (
                  <View
                    style={{
                      flex: 1,
                      flexDirection: 'row',
                      alignItems: 'center',
                      justifyContent: 'space-between',
                      padding: 5,
                    }}>
                    <TouchableOpacity onPress={() => handleDevice(device.id, service.id)}>
                      <Text style={styles.text}>
                        {`${device.name} ${device.number}`}
                      </Text>
                    </TouchableOpacity>
                    {reviews.some(r => r.device_id == device.id) ? (
                      <Icon name="checkmark-circle" size={20} color="#198754" />
                    ) : (
                      <Icon name="warning" size={20} color="#ffc107" />
                    )}
                  </View>
                ))}
              </View>
            </View>
          </View>
        );
      })}
      <Button title="Completar" color="#198754" onPress={handleComplete} />
    </ScrollView>
  );
};
