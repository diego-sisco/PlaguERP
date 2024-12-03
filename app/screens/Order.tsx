import React, {useState, useEffect} from 'react';
import {Button, ScrollView, Text, ToastAndroid, View} from 'react-native';
import {Navigation} from 'react-native-navigation';

import {readFile, writeFile} from '../functions/FileHandling';
import {file_paths} from '../functions/FilePath';

import {OrderType} from '../types/Order';
import {CustomerType} from '../types/Customer';
import {styles} from '../styles/app';

const getIndex = (data: any, id: number) => {
  return data.findIndex((element: any) => element.id == id);
};

const isBeforeOrEqualToday = (inputDate: string) => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  // Convertir la fecha de entrada a un objeto Date
  const [year, month, day] = inputDate.split('-').map(Number);
  const dateToCompare = new Date(year, month - 1, day); // El mes en JavaScript es 0-indexado

  // Retornar true si la fecha es anterior o igual a hoy
  return dateToCompare <= today;
};

export const Order = (props: any) => {
  // Datos
  const orderID: number = props.orderID;
  const [order, setOrder] = useState<OrderType>();
  const [customer, setCustomer] = useState<CustomerType>();

  // Control de estados
  const [orderStatus, setOrderStatus] = useState(0);

  useEffect(() => {
    const getData = async () => {
      try {
        const orders: OrderType[] = await readFile(file_paths.orders);

        if (orders) {
          const index = getIndex(orders, orderID);
          if (index != -1) {
            setOrder(orders[index]);
            setCustomer(orders[index].customer);
            setOrderStatus(orders[index].statusID);
          }
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    getData();
  }, []);

  const updateStatus = async (id: number, status: number) => {
    try {
      const stored_orders = await readFile(file_paths.orders);
      const orderIndex = stored_orders.findIndex((item: any) => item.id == id);
      if (orderIndex != -1) {
        stored_orders[orderIndex].statusID = status;
        setOrderStatus(status);
        await writeFile(stored_orders, file_paths.orders);
      }
    } catch (error) {
      console.error('Error reading file or updating status:', error);
    }
  };

  const showToast = (message: string) => {
    ToastAndroid.show(message, ToastAndroid.SHORT);
  };

  // Funcion para renderizar los botones
  const renderButtons = () => {
    const handleActivateOrder = async () => {
      if (order /*&& isBeforeOrEqualToday(order.programmed_date)*/) {
        await updateStatus(order.id, 2);
        showToast('La orden está en curso');
      }
    };

    const handlePauseOrder = async () => {
      if (order) {
        await updateStatus(order.id, 1);
      }
      showToast('La orden está en pausa');
    };

    const handleServiceOrder = async () => {
      if (order) {
        Navigation.push(props.viewId, {
          component: {
            name: order.hasDevices ? props.viewDevice : props.viewService,
            passProps: {
              redirectID: props.viewId,
              orderID: orderID,
            },
            options: {
              topBar: {
                title: {
                  text: 'Servicios',
                },
              },
            },
          },
        });
      }
    };

    if (orderStatus == 1 || orderStatus == 3) {
      return (
        <View style={styles.section}>
          <View style={styles.button}>
            <Button
              title="Aceptar orden"
              color="#198754"
              onPress={handleActivateOrder}
            />
          </View>
        </View>
      );
    } else if (orderStatus != 3 && orderStatus != 6) {
      return (
        <View style={styles.section}>
        <View style={styles.buttons}>
          <View style={styles.modalButton}>
            <Button
              title="Pausar orden"
              color="#ffc107"
              onPress={handlePauseOrder}
            />
          </View>
          <View style={styles.modalButton}>
            <Button
              title="Servicios"
              color="#0d6efd"
              onPress={handleServiceOrder}
            />
          </View>
        </View>
        </View>
      );
    }
  };

  const renderDetails = (title: string, content: any) => {
    return (
      <View style={styles.section}>
        <View style={styles.sectionTitle}>
          <Text style={styles.title}>{title}</Text>
        </View>
        <View style={styles.section}>
          {content ? (
            <Text style={styles.text}>{content}</Text>
          ) : (
            <Text style={styles.textDanger}>Sin contenido adicional</Text>
          )}
        </View>
      </View>
    );
  };

  return (
    <ScrollView style={styles.root}>
      <View style={styles.section}>
        <View style={styles.sectionTitle}>
          <Text style={styles.title}>Información general</Text>
        </View>
        <View style={styles.section}>
          <Text style={styles.text}>Hora: {order?.start_time}</Text>
          <Text style={styles.text}>
            Precio:{' '}
            <Text style={{color: '#198754', fontWeight: 'bold'}}>
              ${order?.price ? order?.price : '0'}
            </Text>
          </Text>
          <Text style={styles.text}>Cliente: {customer?.name}</Text>
          <Text style={styles.text}>Teléfono: {customer?.phone}</Text>
          <Text style={styles.text}>Dirección: {customer?.address}</Text>
          <Text style={styles.text}>Código Postal: {customer?.zip_code}</Text>
          <Text style={styles.text}>Ciudad: {customer?.city}</Text>
          <Text style={styles.text}>Estado: {customer?.state}</Text>
        </View>
      </View>

      {renderDetails('Ejecución', order?.execution)}
      {renderDetails('Áreas de aplicación', order?.areas)}
      {renderDetails('Comentarios adicionales', order?.additional_comments)}
      {renderButtons()}
    </ScrollView>
  );
};
