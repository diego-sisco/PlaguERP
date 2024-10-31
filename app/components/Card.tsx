import {Button, Text, View} from 'react-native';
import {Navigation} from 'react-native-navigation';
import Icon from 'react-native-vector-icons/Ionicons';
import {OrderType} from '../types/Order';
import {ServiceType} from '../types/Service';
import {CustomerType} from '../types/Customer';
import {styles} from '../styles/card';
import {file_paths} from '../functions/FilePath';
import {useEffect, useState} from 'react';
import {readFile} from '../functions/FileHandling';

const formatTime = (time: string) => {
  const [hour, minute] = time.split(':');
  return `${hour}:${minute}`;
};

export const Card = (props: any) => {
  const order: OrderType = props.data;
  const customer: CustomerType = props.data.customer;
  const [services, setServices] = useState<ServiceType[]>([]);

  useEffect(() => {
    const getServices = async () => {
      try {
        const all_services = await readFile(file_paths.catalog.services);
        setServices(
          all_services.filter((item: ServiceType) =>
            order.servicesID.includes(item.id),
          ),
        );
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    getServices();
  }, []);

  const getStatusStyle = (statusId: number) => {
    switch (statusId) {
      case 3:
        return {color: '#198754', fontSize: 18}; // Finalizada
      case 6:
        return {color: '#dc3545', fontSize: 18}; // Cancelada
      case 1:
        return {color: '#ffc107', fontSize: 18}; // Pendiente
      case 2:
        return {color: '#0d6efd', fontSize: 18}; // Aceptada
      default:
        return {};
    }
  };

  const getStatusText = (statusId: number) => {
    switch (statusId) {
      case 3:
        return 'Finalizada';
      case 6:
        return 'Cancelada';
      case 1:
        return 'Pendiente';
      case 2:
        return 'Aceptada';
      default:
        return '';
    }
  };

  return (
    <View style={styles.card}>
      <View style={styles.title}>
        <Text style={styles.textTitle}>Orden {order.id}</Text>
        <Text style={getStatusStyle(order.statusID)}>
          {getStatusText(order.statusID)}
        </Text>
      </View>
      <View style={styles.list}>
        <View style={styles.icon}>
          <Icon name="person" size={15} color="#000" />
        </View>
        <Text style={styles.text}>{order ? customer.name : 'S/N'}</Text>
      </View>
      <View style={styles.list}>
        <View style={styles.icon}>
          <Icon name="time" size={15} color="#000" />
        </View>
        <Text style={styles.text}>{formatTime(order.start_time)}</Text>
      </View>
      <View style={styles.list}>
        <View style={styles.icon}>
          <Icon name="calendar-clear" size={15} color="#000" />
        </View>
        <Text style={styles.text}>{order.programmed_date}</Text>
      </View>
      <View style={styles.list}>
        <View style={styles.icon}>
          <Icon name="call" size={15} color="#000" />
        </View>
        <Text style={styles.text}>{customer.phone}</Text>
      </View>
      <View style={styles.list}>
        <View style={styles.icon}>
          <Icon name="location" size={15} color="#000" />
        </View>
        <Text style={styles.text}>{order ? customer.address : 'S/N'}</Text>
      </View>

      {services.map((service: ServiceType, index: number) => (
        <View style={styles.list} key={index}>
          <View style={styles.icon}>
            <Icon name="build" size={15} color="#000" />
          </View>
          <Text style={styles.text}>{service.name}</Text>
        </View>
      ))}

      <View style={styles.button}>
        <Button
          title="Abrir"
          color="#0d6efd"
          onPress={() =>
            Navigation.push(props.viewId, {
              component: {
                name: props.viewName,
                passProps: {
                  orderID: order.id,
                },
                options: {
                  topBar: {
                    title: {
                      text: 'Información de la orden',
                    },
                  },
                },
              },
            })
          }
        />
      </View>
    </View>
  );
};
