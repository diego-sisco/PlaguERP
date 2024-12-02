import React, {useState, useEffect} from 'react';
import {
  RefreshControl,
  Text,
  ToastAndroid,
  TouchableOpacity,
  ScrollView,
  View,
} from 'react-native';
import NetInfo from '@react-native-community/netinfo';
import DatePicker from 'react-native-date-picker';
import {getOrders} from '../api/Order/Get';
import {readFile, writeFile, cleanFile} from '../functions/FileHandling';
import {file_paths} from '../functions/FilePath';
import {Card} from '../components/Card';
import {OrderType} from '../types/Order';
import {ResponseType} from '../types/Response';
import {styles} from '../styles/app';
import {AnswerType} from '../types/Answer';
import {ServiceType} from '../types/Service';
import {ProductType} from '../types/Product';
import {PestType} from '../types/Pest';
import {DeviceType} from '../types/Device';
import {setChemicalApplicationsReports, setDevicesReports} from '../api/Order/Post';
import {ApplicationMethodType} from '../types/ApplicationMethod';
import { DevicesReport } from '../types/DevicesReport';
import { ChemicalApplicationReport } from '../types/ChemicalApplicationReport';

function compareDates(fecha1: string, fecha2: string) {
  const date1 = new Date(fecha1).getTime();
  const date2 = new Date(fecha2).getTime();
  const currentDate = new Date().getTime();
  return Math.abs(date1 - currentDate) < Math.abs(date2 - currentDate);
}

const formatDate = (date: Date, type: string) => {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  return type == 'sql' ? `${year}-${month}-${day}` : `${day}-${month}-${year}`;
};

export const Home = (props: any) => {
  const [orders, setOrders] = useState<OrderType[]>([]);
  const [refreshIndicator, setRefreshIndicator] = useState(false);
  const [refreshing, setRefreshing] = React.useState(true);
  const [isConnected, setIsConnected] = useState(false);
  const [open, setOpen] = useState(false);
  const [date, setDate] = useState(new Date());
  const [changeDate, setChangeDate] = useState(false);

  const showToast = (message: string) => {
    ToastAndroid.show(message, ToastAndroid.SHORT);
  };

  const onRefresh: any = React.useCallback(() => {
    setRefreshing(true);
    setRefreshIndicator(prev => !prev);
    setTimeout(() => {
      setRefreshing(false);
    }, 2000);
  }, []);

  useEffect(() => {
    const fetchData = async () => {
      try {
        //await cleanFile(file_paths.orders);
        //await cleanFile(file_paths.catalog.services);
        //await cleanFile(file_paths.catalog.pests);
        //await cleanFile(file_paths.catalog.products);
        //await cleanFile(file_paths.data.chemicalApplications);
        //await cleanFile(file_paths.data.reviewDevices);
        //await cleanFile(file_paths.reports.chemicalApplications);
        //await cleanFile(file_paths.reports.devices);
        
        let reports: DevicesReport[] | ChemicalApplicationReport[];
        let orders: OrderType[] = await readFile(file_paths.orders);
        let reports_chemicalApplications: ChemicalApplicationReport[] = await readFile(
          file_paths.reports.chemicalApplications,
        );
        let reports_devices: DevicesReport[] = await readFile(
          file_paths.reports.devices,
        );

        //console.log(JSON.stringify(reports_devices, null, 2));

        if (isConnected) {
          showToast('Conectado');
          try {
            if (reports_devices.length > 0) {
              reports = [];
              reports = reports_devices.filter(
                (report: DevicesReport) => !report.is_sending,
              );
            const response = await setDevicesReports(reports);
              if (response == '200') {
                reports.forEach((report: DevicesReport) => {
                  report.is_sending = true;
                });
                await writeFile(
                  reports_devices,
                  file_paths.reports.devices
                );
                showToast('Reportes de dispositivos sinconizados');
              }
            }

            if (reports_chemicalApplications.length > 0) {
              reports = [];
              reports = reports_chemicalApplications.filter(
                (report: ChemicalApplicationReport) => !report.is_sending,
              );
              const response = await setChemicalApplicationsReports(reports);
              if (response == '200') {
                reports.forEach((report: ChemicalApplicationReport) => {
                  report.is_sending = true;
                });
                await writeFile(reports_chemicalApplications, file_paths.reports.chemicalApplications);
                showToast('Reportes de aplicación química sinconizados');
              }
            }

            let new_data: ResponseType | null = await getOrders(
              formatDate(date, 'sql'),
            );

            const programed_date = formatDate(date, 'sql');

            const updated_data = new_data
              ? await filterByLastUpdate(new_data)
              : {orders};

            const filtered_orders: OrderType[] = updated_data.orders.filter(
              (item: OrderType) => item.programmed_date == programed_date,
            );

            setOrders(filtered_orders);
          } catch (error) {
            showToast('Error al obtener los datos de las órdenes');
            console.error('Error fetching order data:', error);
          }
        } else {
          const programed_date = formatDate(date, 'sql');
          const filtered_orders: OrderType[] = orders.filter(
            (item: OrderType) => item.programmed_date == programed_date,
          );

          setOrders(filtered_orders);
        }
      } catch (error) {
        showToast('Error al obtener o agregar las órdenes');
      } finally {
        setChangeDate(false);
        setRefreshing(false);
        setRefreshIndicator(false);
      }
    };

    const unsubscribe = NetInfo.addEventListener(state => {
      if (state.isConnected != null) {
        setIsConnected(state.isConnected);
        if (refreshing) {
          fetchData();
        }
      }
    });

    return () => {
      unsubscribe();
    };
  }, [date, refreshing, isConnected]);

  const updateData = (current_data: any, new_data: any) => {
    const updated_data = new_data.map((data: any) => {
      const found_data = current_data.find((item: any) => item.id == data.id);
      /*if (found_data?.statusID == 3) {
        return found_data;
      }*/
      if (found_data && compareDates(found_data.updated_at, data.updated_at)) {
        return found_data;
      } else {
        return data;
      }
    });

    return updated_data;
  };

  const filterByLastUpdate = async (
    data: ResponseType,
  ): Promise<ResponseType> => {
    let orders: OrderType[] = await readFile(file_paths.orders);
    let services: ServiceType[] = await readFile(file_paths.catalog.services);
    let products: ProductType[] = await readFile(file_paths.catalog.products);
    let pests: PestType[] = await readFile(file_paths.catalog.pests);
    let appMethods: ApplicationMethodType[] = await readFile(
      file_paths.catalog.applicationMethods
    );
    let devices: DeviceType[] = await readFile(file_paths.catalog.devices);

    const updated_orders =
      orders.length > 0 ? updateData(orders, data.orders) : data.orders;
    const updated_services =
      services.length > 0 ? updateData(services, data.services) : data.services;
    const updated_products =
      products.length > 0 ? updateData(products, data.products) : data.products;
    const updated_pests =
      pests.length > 0 ? updateData(pests, data.pests) : data.pests;
    const updated_appMethods =
      appMethods.length > 0
        ? updateData(appMethods, data.applicationMethods)
        : data.applicationMethods;
    const updated_devices =
      devices.length > 0 ? updateData(devices, data.devices) : data.devices;

    // Escribir archivos actualizados
    await Promise.all([
      writeFile(updated_orders, file_paths.orders),
      writeFile(updated_services, file_paths.catalog.services),
      writeFile(updated_products, file_paths.catalog.products),
      writeFile(updated_pests, file_paths.catalog.pests),
      writeFile(updated_appMethods, file_paths.catalog.applicationMethods),
      writeFile(updated_devices, file_paths.catalog.devices),
    ]);

    // Devolver los datos actualizados
    const updated_data: ResponseType = {
      orders: updated_orders,
      services: updated_services,
      products: updated_products,
      pests: updated_pests,
      applicationMethods: updated_appMethods,
      devices: updated_devices,
    };

    return updated_data;
  };

  const datePickerInput = () => (
    <>
      <View
        style={{
          width: '100%',
          display: 'flex',
          flexDirection: 'row',
          justifyContent: 'center',
        }}>
        <TouchableOpacity
          style={styles.inputDate}
          onPress={() => setOpen(true)}>
          <View style={styles.icon}>
            <Text style={styles.textCenter}>{formatDate(date, 'simple')}</Text>
          </View>
        </TouchableOpacity>
      </View>
      <DatePicker
        modal
        mode="date"
        open={open}
        date={date}
        onConfirm={d => {
          setDate(d);
          setOpen(false);
          setChangeDate(true);
          setRefreshing(true);
          setRefreshIndicator(prev => !prev);
        }}
        onCancel={() => {
          setOpen(false);
        }}
      />
    </>
  );

  const renderOrders = () => (
    <ScrollView
      style={styles.root}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }>
      <View style={{padding: 5}}>{datePickerInput()}</View>

      {orders.map((order: OrderType) => (
        <Card
          key={order.id}
          data={order}
          viewId={props.viewId}
          viewName={props.viewName}
        />
      ))}
    </ScrollView>
  );

  const renderNoOrders = () => (
    <ScrollView
      style={styles.root}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }>
      <View style={{padding: 5}}>{datePickerInput()}</View>
      <View style={styles.centerContent}>
        <Text style={{color: 'red', fontSize: 18}}>
          Sin ordenes de servicio
        </Text>
      </View>
    </ScrollView>
  );

  return orders.length > 0 ? renderOrders() : renderNoOrders();
};
