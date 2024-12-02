import React, {useState, useEffect} from 'react';
import {Button, Image, Modal, ScrollView, View} from 'react-native';

import Orientation from 'react-native-orientation';
import {Navigation} from 'react-native-navigation';
import {TextArea} from '../components/TextArea';
import {SignatureArea} from '../components/SignatureArea';
import {readFile, writeFile} from '../functions/FileHandling';
import {file_paths} from '../functions/FilePath';
import AsyncStorage from '@react-native-async-storage/async-storage';

import {styles} from '../styles/app';
import {styles as signatureStyle} from '../components/styles/signatureArea';
import {Input} from '../components/Input';
import {OrderType} from '../types/Order';
import {ChemicalApplication} from '../types/ChemicalApplication';
import {ChemicalApplicationReport} from '../types/ChemicalApplicationReport';
import {ServicePest} from '../types/ServicePest';
import {Resource} from '../types/Resource';
import {ReviewDevice} from '../types/ReviewDevice';
import {DevicesReport} from '../types/DevicesReport';

export const Details = (props: any) => {
  const orderID: number = props.orderID;
  const [orders, setOrders] = useState<OrderType[]>([]);
  const [signature, setSignature] = useState<string | null>(null);
  const [modalVisible, setModalVisible] = useState(false);
  const [comments, setComments] = useState<string | null>('');
  const [techObservation, setTechObservation] = useState<string | null>('');
  const [recomments, setRecomments] = useState<string | null>('');
  const [signatureName, setSignatureName] = useState('');

  const formatDate = (date: Date): string => {
    return date.toISOString().split('T')[0];
  };

  const formatTime = (date: Date): string => {
    return date.toTimeString().split(' ')[0];
  };

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const ordersData = await readFile(file_paths.orders);
        setOrders(ordersData);
      } catch (error) {
        console.error('Error fetching orders:', error);
      }
    };
  
    fetchOrders();
  }, []);
  
  useEffect(() => {
    const fetchReports = async () => {
      if (orders.length > 0) {
        const order = orders.find((item: OrderType) => item.id == orderID);
        if (order) {
          try {
            const reportsFile = order.hasDevices
              ? file_paths.reports.devices
              : file_paths.data.reviewDevices;
  
            const fetchedReports = await readFile(reportsFile);
            const review = fetchedReports.find(
              (item: any) => item.orderID == orderID,
            );
  
            if (review) {
              setComments(review.comments);
              setRecomments(review.recs);
              setTechObservation(review.tech_obs);
              setSignatureName(review.signatureName ?? '');
              setSignature(review.signature);
            }
          } catch (error) {
            console.error('Error fetching reports:', error);
          }
        }
      }
    };
  
    fetchReports();
  }, [orders]);
  
  const completeReport = async (status: number) => {
    try {
      const currentDate = new Date();
      const formattedDate = formatDate(currentDate); // Verifica que estas funciones existan
      const formattedTime = formatTime(currentDate);

      const user_id = await AsyncStorage.getItem('userID');

      if (orders && orders.length > 0) {
        const order: OrderType | undefined = orders.find(
          (item: OrderType) => item.id == orderID,
        );

        if (order) {
          if (order.hasDevices) {
            const fetchedReviews: ReviewDevice[] = await readFile(
              file_paths.data.reviewDevices,
            );

            let fetchedReports: DevicesReport[] = await readFile(
              file_paths.reports.devices,
            );
            let index: number = fetchedReports.findIndex(
              (report: DevicesReport) => report.orderID == orderID,
            );

            let report: DevicesReport = {
              orderID: orderID,
              recs: recomments,
              tech_obs: techObservation,
              comments: comments,
              signature: signature,
              signatureName: signatureName,
              completed_date: formattedDate,
              end_time: formattedTime,
              incidents: fetchedReviews,
              is_sending: false,
              user_id: user_id,
            };

            if (index != -1) {
              fetchedReports[index] = report;
            } else {
              fetchedReports.push(report);
            }

            await writeFile(fetchedReports, file_paths.reports.devices);
          } else {
            let fetchedReports: ChemicalApplicationReport[] = await readFile(
              file_paths.reports.chemicalApplications,
            );

            // Leer aplicaciones químicas del archivo
            let fetchedChemicalApplications: ChemicalApplication[] =
              await readFile(file_paths.data.chemicalApplications);

            let foundedChemicalApplications: ChemicalApplication | undefined=
              fetchedChemicalApplications.find(
                (chemicalApp: ChemicalApplication) =>
                  chemicalApp.order_id == orderID,
              );

            let pests: ServicePest[] | undefined =
              foundedChemicalApplications?.pests;
            let resources: Resource[] | undefined =
              foundedChemicalApplications?.resources;

            let index: number = fetchedReports.findIndex(
              (report: ChemicalApplicationReport) => report.orderID == orderID,
            );

            let report: ChemicalApplicationReport = {
              orderID: orderID,
              recs: recomments,
              tech_obs: techObservation,
              comments: comments,
              signature: signature,
              signatureName: signatureName,
              completed_date: formattedDate,
              end_time: formattedTime,
              resources: resources,
              pests: pests,
              is_sending: false,
              user_id: user_id,
            };

            //console.log('Report: ', JSON.stringify(report, null, 2));

            if (index != -1) {
              fetchedReports[index] = report;
            } else {
              fetchedReports.push(report);
            }

            await writeFile(
              fetchedReports,
              file_paths.reports.chemicalApplications,
            );
          }

          // Actualizar el estado y guarda la orden
          order.statusID = status;
          await writeFile(orders, file_paths.orders);
        }
      }
    } catch (error) {
      console.error('Error al finalizar orden:', error);
    }
  };

  return (
    <View style={styles.root}>
      <ScrollView style={styles.scrollView}>
        <TextArea title="Comentarios" text={comments} setText={setComments} />
        <TextArea
          title="Observaciones"
          text={techObservation}
          setText={setTechObservation}
        />
        <TextArea
          title="Recomendaciones"
          text={recomments}
          setText={setRecomments}
        />
        <Input
          title="Nombre del firmante"
          text={signatureName}
          setText={setSignatureName}
        />
        {signature && (
          <View style={styles.content}>
            <Image
              source={{uri: `data:image/png;base64,${signature}`}}
              style={signatureStyle.image}
            />
          </View>
        )}
        <View style={styles.buttons}>
          <View style={styles.modalButton}>
            <Button
              title="Agregar Firma"
              color="#fd7e14"
              onPress={() => {
                setModalVisible(true);
                Orientation.lockToLandscape();
              }}
            />
          </View>
          <View style={styles.modalButton}>
            <Button
              title="Finalizar Orden"
              color="#0d6efd"
              onPress={() => {
                completeReport(3)
                  .then(() => {
                    Navigation.popToRoot(props.viewId);
                  })
                  .catch(error =>
                    console.error('Error al finalizar orden:', error),
                  );
              }}
            />
          </View>
        </View>
      </ScrollView>

      <Modal
        animationType="fade"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          setModalVisible(!modalVisible);
        }}>
        <SignatureArea
          setModalVisible={setModalVisible}
          setSignature={setSignature}
        />
      </Modal>
    </View>
  );
};
