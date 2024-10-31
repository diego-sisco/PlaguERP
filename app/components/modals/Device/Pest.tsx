import React, { useState, useEffect } from 'react';
import { View, Button, Modal, Text, TextInput } from 'react-native';
import DropDownPicker from 'react-native-dropdown-picker'; // Importar la librería
import { styles } from '../../../styles/app';
import { PestType } from '../../../types/Pest';
import { AnswerType } from '../../../types/Answer';

interface ModalProps {
  pests: PestType[];
  selectedPests: AnswerType[];
  modalVisible: boolean;
  setModalVisible: (visible: boolean) => void;
  setSelectedPests: (newPests: AnswerType[]) => void;
}

export const ModalPest: React.FC<ModalProps> = ({
  pests,
  selectedPests,
  modalVisible,
  setModalVisible,
  setSelectedPests,
}) => {
  const [selectedService, setSelectedService] = useState<number | null>(null);
  const [selectedPest, setSelectedPest] = useState<number | null>(null);
  const [pestOpen, setPestOpen] = useState(false);
  const [pestQuantity, setPestQuantity] = useState('');

  const storePest = () => {
    if (selectedPest) {
      const newSelectedPests = [...selectedPests];
      const foundServiceIndex = newSelectedPests.findIndex(
        (item: AnswerType) => item.id == selectedService
      );

      if (foundServiceIndex != -1) {
        newSelectedPests[foundServiceIndex].value = parseInt(pestQuantity);
      } else {
        newSelectedPests.push({
          id: selectedPest,
          value: parseInt(pestQuantity),
        });
      }

      setSelectedPests(newSelectedPests);
      resetModal();
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
            <Text style={styles.title}>Selecciona plaga</Text>
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
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
              containerStyle={styles.dropdown} // Ajusta la altura máxima del dropdown
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