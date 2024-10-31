import React, {useState, useEffect} from 'react';
import {
  ScrollView,
  View,
  Text,
  Button,
  ToastAndroid,
  TextInput,
} from 'react-native';
import {DeviceType} from '../types/Device';
import {cleanFile, readFile, writeFile} from '../functions/FileHandling';
import {styles} from '../styles/app';
import {QuestionType} from '../types/Question';
import answers from '../files/answers.json';
import {Navigation} from 'react-native-navigation';
import {file_paths} from '../functions/FilePath';
import SelectDropdown from 'react-native-select-dropdown';
import CheckBox from 'react-native-check-box';

import {ReviewDevice} from '../types/ReviewDevice';
import {TablePests} from '../components/tables/Device/TablePest';
import {PestType} from '../types/Pest';

import {ModalPest} from '../components/modals/Device/Pest';

import {ServiceType} from '../types/Service';
import {OrderType} from '../types/Order';
import {TextArea} from '../components/TextArea';
import {AnswerType} from '../types/Answer';

const getIndex = (data: any, id: number) => {
  return data.findIndex((element: any) => element.id == id);
};

export const DeviceCheck = (props: any) => {
  const orderID: number = props.orderID;
  const deviceID: number = props.deviceID;
  const serviceID: number = props.serviceID;
  const is_scanned = props.isScanned;

  console.log(serviceID);

  const [device, setDevice] = useState<DeviceType>();
  const [questions, setQuestions] = useState<QuestionType[]>([]);
  const [review, setReview] = useState<ReviewDevice>();
  const [observs, setObservs] = useState('');
  const [isChecked, setIsChecked] = useState<boolean>(false);

  const [pests, setPests] = useState<PestType[]>([]);
  const [pestModalVisible, setPestModalVisible] = useState<boolean>(false);
  const [selectedPests, setSelectedPests] = useState<AnswerType[]>([]);

  useEffect(() => {
    const getData = async () => {
      try {
        const orders = await readFile(file_paths.orders);
        const devices: DeviceType[] = await readFile(
          file_paths.catalog.devices,
        );
        const review_devices: ReviewDevice[] = await readFile(
          file_paths.data.reviewDevices,
        );
        const fetchedPests: PestType[] = await readFile(
          file_paths.catalog.pests,
        );
        
        const fetchedServices: ServiceType[] = await readFile(
          file_paths.catalog.services,
        );

        const order = orders.find((item: OrderType) => item.id == orderID);

        if (order) {
          //setPests(fetchedPests);
          const service = fetchedServices.find(item => item.id == serviceID);
          if(service) {
            setPests(fetchedPests.filter(pest => service.pestsID.includes(pest.id)));
          }
        }

        if (devices) {
          const index = getIndex(devices, deviceID);
          if (index != -1) {
            setQuestions(devices[index].questions);
            setDevice(devices[index]);

            const found_review: ReviewDevice | undefined = review_devices.find(
              review =>
                review.order_id == orderID && review.device_id == deviceID,
            );


            if (found_review) {
              setReview(found_review);
              setObservs(found_review.observs);
              setSelectedPests(found_review.pests || []);
              setIsChecked(found_review.product_change || false);
            }
          }
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    getData();
  }, [deviceID]);

  const showToast = (message: string) => {
    ToastAndroid.show(message, ToastAndroid.SHORT);
  };

  const handleComplete = async () => {
    try {
      if (review) {
        review.pests = selectedPests;
        review.observs = observs;

        let fetched_reviews: ReviewDevice[] = await readFile(
          file_paths.data.reviewDevices,
        );

        const founded_reviews: ReviewDevice[] = fetched_reviews.filter(
          review => review.order_id == orderID && review.device_id == deviceID,
        );

        if (founded_reviews.length > 0) {
          founded_reviews.forEach((found_review: ReviewDevice) => {
            const i = fetched_reviews.findIndex(
              item => item.device_id == found_review.device_id,
            );
            if (i != -1) {
              fetched_reviews[i] = review;
            }
          });
        } else {
          fetched_reviews.push(review);
        }

        await writeFile(fetched_reviews, file_paths.data.reviewDevices);
        /*console.log(
          'FileData: ',
          JSON.stringify(
            await readFile(file_paths.data.reviewDevices),
            null,
            2,
          ),
        );*/
      }

      Navigation.popTo(props.redirectID);
    } catch (error) {
      console.error('Error al completar la tarea:', error);
      showToast(
        'Ocurrió un error al completar la tarea. Por favor, inténtelo de nuevo.',
      );
    }
  };

  const setAnswers = (question_id: number, option: number | string) => {
    const new_question: AnswerType = {
      id: question_id,
      value: option,
    };

    if (review) {
      const found_question: AnswerType | undefined = review.questions.find(
        item => item.id == question_id,
      );
      if (found_question) {
        found_question.value = option;
      } else {
        review.questions.push(new_question);
      }
    } else {
      setReview({
        order_id: orderID,
        device_id: deviceID,
        questions: [new_question],
        pests: selectedPests,
        is_checked: true,
        is_scanned,
        product_change: isChecked,
        observs: observs,
      });
    }
  };

  const handleChecked = () => {
    if (review) {
      review.product_change = !isChecked;
    }
    setIsChecked(!isChecked);
  };

  const handleAddPest = (newPests: AnswerType[]) => {
    setSelectedPests(newPests);
    setPestModalVisible(false); // Cierra el modal después de agregar la plaga
  };

  return (
    <ScrollView style={styles.root}>
      <View style={styles.section}>
        <Text style={styles.h1}>
          [ {device?.name} {device?.number} ]
        </Text>
      </View>
      <View style={styles.section}>
        <View style={styles.sectionTitle}>
          <Text style={styles.title}>Preguntas</Text>
        </View>
      </View>
      <View style={styles.section}>
        {questions.map((question: QuestionType, index: number) => {
          const answer = answers.find(
            (item: any) => item.id == question.question_option_id,
          );
          const options: any = answer?.options;
          const found_review: AnswerType | undefined = review?.questions.find(
            item => item.id == question.id,
          );

          switch (answer?.type) {
            case 'select':
              return (
                <View key={index} style={styles.questionContainer}>
                  <Text style={styles.text}>
                    {index + 1}. {question.question}
                  </Text>
                  <SelectDropdown
                    data={options}
                    onSelect={selectedItem =>
                      setAnswers(question.id, selectedItem)
                    }
                    buttonTextAfterSelection={selectedItem => selectedItem}
                    rowTextForSelection={item => item}
                    defaultButtonText={String(
                      found_review
                        ? found_review.value
                        : 'Selecciona una opción',
                    )}
                    buttonStyle={styles.dropdownButton}
                    buttonTextStyle={styles.dropdownButtonText}
                    dropdownStyle={styles.dropdown}
                    rowStyle={styles.dropdownRow}
                    rowTextStyle={styles.dropdownRowText}
                  />
                </View>
              );
            case 'number':
              return (
                <View key={index} style={styles.questionContainer}>
                  <Text style={styles.text}>
                    {index + 1}. {question.question}
                  </Text>
                  <TextInput
                    style={styles.input}
                    keyboardType="numeric"
                    placeholder="Ingrese un número"
                    onChangeText={text => setAnswers(question.id, text)}
                    value={String(
                      review?.questions.find(item => item.id == question.id)
                        ?.value,
                    )}
                  />
                </View>
              );
            case 'text':
              return (
                <View key={index} style={styles.questionContainer}>
                  <Text style={styles.text}>
                    {index + 1}. {question.question}
                  </Text>
                  <TextInput
                    style={styles.input}
                    placeholder="Ingrese su respuesta"
                    onChangeText={text => setObservs(text)}
                    value={observs}
                  />
                </View>
              );
            default:
              return (
                <View key={index}>
                  <Text style={{color: 'red'}}>Sin preguntas asignadas</Text>
                </View>
              );
          }
        })}

        <View style={styles.checkbox}>
          <CheckBox isChecked={isChecked} onClick={handleChecked} />
          <Text style={styles.text}>Cambio de producto/cebo</Text>
        </View>

        <View style={styles.section}>
          <View style={styles.sectionTitle}>
            <Text style={styles.title}>Plagas</Text>
          </View>
        </View>
        <View style={styles.section}>
          <Button
            title="Agregar Plaga"
            color="#000000"
            onPress={() => setPestModalVisible(true)}
          />

          <TablePests
            selectedPests={selectedPests}
            pests={pests}
            setSelectedPests={setSelectedPests}
          />
        </View>

        <TextArea title="Observaciones" text={observs} setText={setObservs} />
        <View style={styles.section}>
          <View style={styles.button}>
            <Button title="Completar" onPress={handleComplete} />
          </View>
        </View>
      </View>
      <ModalPest
        pests={pests}
        selectedPests={selectedPests}
        modalVisible={pestModalVisible}
        setModalVisible={setPestModalVisible}
        setSelectedPests={handleAddPest}
      />
    </ScrollView>
  );
};
