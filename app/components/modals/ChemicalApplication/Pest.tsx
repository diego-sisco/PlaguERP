import React, { useState, useEffect } from 'react';
import { View, Button, Modal, Text, TextInput } from 'react-native';
import DropDownPicker from 'react-native-dropdown-picker'; // Importar la librería
import { styles } from '../../../styles/app';
import { PestType } from '../../../types/Pest';
import { ServiceType } from '../../../types/Service';
import {ServicePest} from '../../../types/ServicePest';
import { AnswerType } from '../../../types/Answer';

interface ModalProps {
  services: ServiceType[];
  pests: PestType[];
  selectedPests: ServicePest[];
  modalVisible: boolean;
  setModalVisible: (visible: boolean) => void;
  setSelectedPests: (newPests: ServicePest[]) => void;
}

export const ModalPest: React.FC<ModalProps> = ({
  services,
  pests,
  selectedPests,
  modalVisible,
  setModalVisible,
  setSelectedPests,
}) => {
  const [selectedService, setSelectedService] = useState<number | null>(null); // Estado para el servicio seleccionado
  const [selectedPest, setSelectedPest] = useState<number | null>(null); // Estado para la plaga seleccionada

  const [serviceOpen, setServiceOpen] = useState(false); // Controlar el dropdown de servicios
  const [pestOpen, setPestOpen] = useState(false); // Controlar el dropdown de plagas

  const [pestQuantity, setPestQuantity] = useState(''); // Estado para la cantidad de plagas encontradas

  const storePest = () => {
    if (selectedPest && selectedService) {
      const newSelectedPests = [...selectedPests]; // Hacemos una copia del estado actual
      const foundServiceIndex = newSelectedPests.findIndex(
        (item: ServicePest) => item.key == selectedService
      );

      if (foundServiceIndex != -1) {
        const foundPestIndex = newSelectedPests[foundServiceIndex].values.findIndex(
          (item: AnswerType) => item.id === selectedPest
        );

        if (foundPestIndex != -1) {
          newSelectedPests[foundServiceIndex].values[foundPestIndex].value = parseInt(pestQuantity);
        } else {
          newSelectedPests[foundServiceIndex].values.push({
            id: selectedPest,
            value: parseInt(pestQuantity),
          });
        }
      } else {
        newSelectedPests.push({
          key: selectedService,
          values: [{ id: selectedPest, value: parseInt(pestQuantity) }],
        });
      }

      setSelectedPests(newSelectedPests); // Actualiza el estado con la nueva lista de plagas
      resetModal(); // Reinicia los campos del modal
    }
  };

  const resetModal = () => {
    setSelectedService(null);
    setSelectedPest(null);
    setPestQuantity('');
    setModalVisible(false); // Cierra el modal
  };

  return (
    <Modal
      animationType="fade"
      transparent={true}
      visible={modalVisible}
      onRequestClose={resetModal}>
      <View style={styles.modalBackground}>
        <View style={styles.modalContainer}>
          {/* Título */}
          <View style={styles.section}>
            <Text style={styles.title}>Seleccione servicio y plaga</Text>
          </View>

          {/* Dropdown para seleccionar servicio */}
          <View style={{ zIndex: 2000 }}>
            <DropDownPicker
              open={serviceOpen}
              value={selectedService}
              items={services.map(service => ({
                label: service.name,
                value: service.id,
              }))}
              setOpen={setServiceOpen}
              setValue={setSelectedService}
              placeholder="Seleccione un servicio"
              containerStyle={styles.dropdown}
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
            />
          </View>

          {/* Dropdown para seleccionar plaga */}
          <View style={{ zIndex: 1000 }}>
            <DropDownPicker
              open={pestOpen}
              value={selectedPest}
              items={pests.map(pest => ({
                label: pest.name,
                value: pest.id,
              }))}
              setOpen={setPestOpen}
              setValue={setSelectedPest}
              placeholder="Seleccione una plaga"
              containerStyle={styles.dropdown}
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
            />
          </View>

          {/* Input para cantidad de plagas encontradas */}
          <View style={styles.section}>
            <Text style={styles.label}>Cantidad de plagas encontradas:</Text>
            <TextInput
              style={styles.input}
              keyboardType="numeric"
              value={pestQuantity}
              onChangeText={setPestQuantity}
              placeholder="Ingrese la cantidad"
            />
          </View>

          {/* Botón para Agregar */}
          <View style={styles.buttons}>
            {/* Botón para agregar */}
            <View style={styles.modalButton}>
              <Button
                title="Agregar"
                color="#0d6efd"
                onPress={storePest}
              />
            </View>
            {/* Botón para cancelar */}
            <View style={styles.modalButton}>
              <Button
                title="Cancelar"
                color="#dc3545"
                onPress={resetModal}
              />
            </View>
          </View>
        </View>
      </View>
    </Modal>
  );
};